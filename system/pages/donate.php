<?php
global $config, $twig, $logged, $account_logged, $db, $title, $action;

defined('MYAAC') or die('Direct access not allowed!');

$title = t('payment.title', 'Payment Center');

require_once PLUGINS . 'pagseguro/config.php';

$coinCatalog = require SYSTEM . 'payment-catalog.php';

$defaults = [
  'currency' => $coinCatalog['currency'],
  'support_contact' => 'server.zealot@gmail.com',
  'stripe_checkout_url' => '',
  'coins_packages' => $coinCatalog['coins_packages'],
  'premium_packages' => $coinCatalog['premium_packages'],
];
$paymentCenter = array_replace_recursive($defaults, $config['payment_center'] ?? []);
// The public catalog and its currency must be the same values shown at
// checkout; local payment settings must not be able to create a mismatch.
$paymentCenter['currency'] = $coinCatalog['currency'];
$paymentCenter['coins_packages'] = $coinCatalog['coins_packages'];
$paymentCenter['premium_packages'] = $coinCatalog['premium_packages'];
$twig->addGlobal('config', $config);

function paymentCenterTableExists($table)
{
  global $db;

  return (bool) $db->query('SHOW TABLES LIKE ' . $db->quote($table))->fetch();
}

function paymentCenterEnsureStripeTable()
{
  global $db;

  if (paymentCenterTableExists('myaac_stripe_transactions')) {
    paymentCenterEnsureStripeColumns();
    return;
  }

  $db->query("CREATE TABLE `myaac_stripe_transactions` (
        `id` INT(11) NOT NULL AUTO_INCREMENT,
        `checkout_session_id` VARCHAR(255) NOT NULL,
        `payment_intent_id` VARCHAR(255) DEFAULT NULL,
        `account_id` INT(11) UNSIGNED NOT NULL,
        `product_type` VARCHAR(20) NOT NULL DEFAULT 'coins',
        `package_key` VARCHAR(80) NOT NULL,
        `coins` INT(11) NOT NULL,
        `premium_days` INT(11) NOT NULL DEFAULT 0,
        `amount_total` INT(11) NOT NULL,
        `currency` VARCHAR(10) NOT NULL,
        `coin_field` VARCHAR(40) NOT NULL,
        `status` VARCHAR(40) NOT NULL DEFAULT 'created',
        `created_at` DATETIME NOT NULL,
        `delivered_at` DATETIME DEFAULT NULL,
        PRIMARY KEY (`id`),
        UNIQUE KEY `checkout_session_id` (`checkout_session_id`),
        KEY `account_id` (`account_id`),
        KEY `status` (`status`)
    ) ENGINE=InnoDB DEFAULT CHARACTER SET=utf8;");
}

function paymentCenterColumnExists($table, $column)
{
  global $db;

  return (bool) $db
    ->query('SHOW COLUMNS FROM `' . $table . '` LIKE ' . $db->quote($column))
    ->fetch();
}

function paymentCenterEnsureStripeColumns()
{
  global $db;

  if (!paymentCenterColumnExists('myaac_stripe_transactions', 'product_type')) {
    $db->query(
      "ALTER TABLE `myaac_stripe_transactions` ADD COLUMN `product_type` VARCHAR(20) NOT NULL DEFAULT 'coins' AFTER `account_id`"
    );
  }

  if (!paymentCenterColumnExists('myaac_stripe_transactions', 'premium_days')) {
    $db->query(
      'ALTER TABLE `myaac_stripe_transactions` ADD COLUMN `premium_days` INT(11) NOT NULL DEFAULT 0 AFTER `coins`'
    );
  }
}

function paymentCenterFindPackage($packages, $key)
{
  if (!isset($packages[$key])) {
    return null;
  }

  $package = $packages[$key];
  $package['key'] = $key;
  return $package;
}

function paymentCenterNormalizeCoinPackage($key, $package)
{
  $coins = (int) ($package['coins'] ?? 0);
  $bonus = (int) ($package['bonus'] ?? ($package['extra'] ?? 0));
  $price = (float) ($package['price'] ?? ($package['value'] ?? 0));

  return [
    'key' => $key,
    'type' => 'coins',
    'label' =>
      $package['label'] ??
      t('payment.coin_amount', '{amount} Coins', ['amount' => $coins + $bonus]),
    'reward' =>
      $bonus > 0
        ? t('payment.coins_with_bonus', '{coins} Coins +{bonus} bonus', [
          'coins' => $coins,
          'bonus' => $bonus,
        ])
        : t('payment.coin_amount', '{amount} Coins', ['amount' => $coins]),
    'coins' => $coins,
    'bonus' => $bonus,
    'price' => $price,
  ];
}

function paymentCenterNormalizePremiumPackage($key, $package)
{
  $days = (int) ($package['days'] ?? 0);

  return [
    'key' => $key,
    'type' => 'premium',
    'label' =>
      $package['label'] ?? t('payment.premium_days', '{days} Days Premium', ['days' => $days]),
    'reward' => t('payment.premium_days_reward', '{days} Premium days', ['days' => $days]),
    'days' => $days,
    'price' => (float) ($package['price'] ?? 0),
  ];
}

function paymentCenterIsValidPackage($package, $productType)
{
  if (!is_array($package) || (float) ($package['price'] ?? 0) <= 0) {
    return false;
  }

  if ($productType === 'coins') {
    return (int) ($package['coins'] ?? 0) + (int) ($package['bonus'] ?? 0) > 0;
  }

  return (int) ($package['days'] ?? 0) > 0;
}

function paymentCenterCheckoutCurrency($paymentCenter)
{
  $currency = strtoupper((string) ($paymentCenter['currency'] ?? ''));
  if (!preg_match('/^[A-Z]{3}$/', $currency)) {
    throw new RuntimeException('Invalid checkout currency.');
  }

  return $currency;
}

function paymentCenterMinimumAmountCents($currency)
{
  // Stripe's card-payment floor is currency-specific. Keep this validation
  // server-side so a catalog edit cannot result in an opaque Stripe error.
  $minimums = [
    'USD' => 50,
  ];

  return $minimums[$currency] ?? 1;
}

function paymentCenterStripeEnabled($stripeConfig)
{
  return !empty($stripeConfig['enabled']) &&
    !empty($stripeConfig['secret_key']) &&
    strpos($stripeConfig['secret_key'], 'PASTE_YOUR') === false;
}

function paymentCenterStripeRequest($endpoint, $params)
{
  global $config;

  $secretKey = $config['stripe']['secret_key'] ?? '';
  $ch = curl_init('https://api.stripe.com/v1/' . ltrim($endpoint, '/'));
  curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POST => true,
    CURLOPT_POSTFIELDS => http_build_query($params),
    CURLOPT_HTTPHEADER => [
      'Authorization: Bearer ' . $secretKey,
      'Content-Type: application/x-www-form-urlencoded',
    ],
    CURLOPT_TIMEOUT => 20,
  ]);

  $response = curl_exec($ch);
  $error = curl_error($ch);
  $status = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
  curl_close($ch);

  if ($response === false) {
    throw new RuntimeException('Stripe connection failed: ' . $error);
  }

  $decoded = json_decode($response, true);
  if ($status < 200 || $status >= 300) {
    $message = $decoded['error']['message'] ?? 'Stripe returned HTTP ' . $status;
    throw new RuntimeException($message);
  }

  return $decoded;
}

$coinsPackages = [];
foreach ($paymentCenter['coins_packages'] as $key => $package) {
  $coinsPackages[$key] = paymentCenterNormalizeCoinPackage($key, $package);
}

$premiumPackages = [];
foreach ($paymentCenter['premium_packages'] as $key => $package) {
  $premiumPackages[$key] = paymentCenterNormalizePremiumPackage($key, $package);
}

$stripeEnabled = paymentCenterStripeEnabled($config['stripe'] ?? []);

if ($action === 'final') {
  echo $twig->render('donate-final.html.twig');
  return;
}

if (!$logged || !$account_logged || !$account_logged->isLoaded()) {
  echo 'To buy coins or premium time you need to be logged in. ' .
    generateLink(
      '?subtopic=accountmanagement&redirect=' . urlencode(BASE_URL . '?subtopic=donate'),
      'Login'
    ) .
    ' first.';
  return;
}

$message = null;

if (
  $_SERVER['REQUEST_METHOD'] === 'POST' &&
  ($_POST['payment_action'] ?? '') === 'stripe_checkout'
) {
  paymentCenterEnsureStripeTable();

  $productType = ($_POST['product_type'] ?? '') === 'premium' ? 'premium' : 'coins';
  $packageKey = trim((string) ($_POST['package_key'] ?? ''));
  $packages = $productType === 'premium' ? $premiumPackages : $coinsPackages;
  $package = paymentCenterFindPackage($packages, $packageKey);
  $stripeConfig = $config['stripe'] ?? [];

  if (!paymentCenterIsValidPackage($package, $productType)) {
    $message = ['type' => 'error', 'text' => 'Please select a valid package.'];
  } elseif (!$stripeEnabled) {
    $message = [
      'type' => 'error',
      'text' => t(
        'payment.card_unavailable',
        'Card checkout is temporarily unavailable. Please try again later.'
      ),
    ];
  } else {
    try {
      $coinsAmount =
        $productType === 'coins' ? (int) ($package['coins'] + ($package['bonus'] ?? 0)) : 0;
      $premiumDays = $productType === 'premium' ? (int) ($package['days'] ?? 0) : 0;
      $amountCents = (int) round(((float) $package['price']) * 100);
      if ($amountCents <= 0) {
        throw new RuntimeException('Invalid package amount.');
      }
      $checkoutCurrency = paymentCenterCheckoutCurrency($paymentCenter);
      $minimumAmountCents = paymentCenterMinimumAmountCents($checkoutCurrency);
      if ($amountCents < $minimumAmountCents) {
        throw new RuntimeException(
          sprintf(
            'This card payment is below Stripe\'s minimum of %s %.2f.',
            $checkoutCurrency,
            $minimumAmountCents / 100
          )
        );
      }
      $coinField = $stripeConfig['coin_field'] ?? 'coins_transferable';
      if (!in_array($coinField, ['coins', 'coins_transferable'], true)) {
        $coinField = 'coins_transferable';
      }

      $session = paymentCenterStripeRequest('checkout/sessions', [
        'mode' => 'payment',
        'success_url' =>
          BASE_URL .
          '?subtopic=donate&action=final&provider=stripe&session_id={CHECKOUT_SESSION_ID}',
        'cancel_url' => BASE_URL . '?subtopic=donate&type=' . $productType . '&payment=cancelled',
        'integration_identifier' => 'zealot_checkout_' . bin2hex(random_bytes(4)),
        'client_reference_id' => (string) $account_logged->getId(),
        'line_items[0][price_data][currency]' => strtolower($checkoutCurrency),
        'line_items[0][price_data][unit_amount]' => $amountCents,
        'line_items[0][price_data][product_data][name]' => $package['label'],
        'line_items[0][quantity]' => 1,
        'metadata[account_id]' => (string) $account_logged->getId(),
        'metadata[product_type]' => $productType,
        'metadata[package_key]' => $package['key'],
        'metadata[coins]' => (string) $coinsAmount,
        'metadata[premium_days]' => (string) $premiumDays,
        'metadata[coin_field]' => $coinField,
      ]);

      $db->query(
        'INSERT INTO `myaac_stripe_transactions` ' .
          '(`checkout_session_id`, `payment_intent_id`, `account_id`, `product_type`, `package_key`, `coins`, `premium_days`, `amount_total`, `currency`, `coin_field`, `status`, `created_at`) VALUES (' .
          $db->quote($session['id']) .
          ', NULL, ' .
          (int) $account_logged->getId() .
          ', ' .
          $db->quote($productType) .
          ', ' .
          $db->quote($package['key']) .
          ', ' .
          $coinsAmount .
          ', ' .
          $premiumDays .
          ', ' .
          $amountCents .
          ', ' .
          $db->quote(strtolower($checkoutCurrency)) .
          ', ' .
          $db->quote($coinField) .
          ', ' .
          $db->quote('created') .
          ', NOW())'
      );

      header('Location: ' . $session['url']);
      exit();
    } catch (Exception $e) {
      $message = [
        'type' => 'error',
        'text' => 'Stripe checkout could not be created: ' . $e->getMessage(),
      ];
    }
  }
}

$selectedType =
  ($_GET['type'] ?? ($_POST['product_type'] ?? 'coins')) === 'premium' ? 'premium' : 'coins';
$selectedPackages = $selectedType === 'premium' ? $premiumPackages : $coinsPackages;
$requestedPackageKey = trim((string) ($_GET['package'] ?? ($_POST['package_key'] ?? '')));
$selectedPackageKey = array_key_first($selectedPackages);
if (isset($selectedPackages[$requestedPackageKey])) {
  $selectedPackageKey = $requestedPackageKey;
}

echo $twig->render('donate.html.twig', [
  'payment' => $paymentCenter,
  'coins_packages' => $coinsPackages,
  'premium_packages' => $premiumPackages,
  'selected_type' => $selectedType,
  'message' => $message,
  'account_name' => $account_logged->getName(),
  'stripe_enabled' => $stripeEnabled,
  'selected_package_key' => $selectedPackageKey,
]);
