<?php
global $config, $db, $logged, $account_logged, $title, $action;

defined('MYAAC') or die('Direct access not allowed!');

function shopTableExists($table)
{
    global $db;

    $query = $db->query('SHOW TABLES LIKE ' . $db->quote($table));
    return (bool)$query->fetch();
}

$isHistory = $action === 'show_history' || $action === 'history';
$title = $isHistory ? t('shop.history.title', 'Shop History') : t('shop.offer.title', 'Shop Offer');

// The legacy Shop Offer was coupled to PagSeguro, which only settles in BRL.
// The live storefront uses the USD Payment Center instead, so never expose a
// BRL-priced offer that could disagree with the checkout currency.
if (!$isHistory) {
    header('Location: ' . BASE_URL . '?subtopic=donate&type=coins', true, 302);
    exit;
}

require_once PLUGINS . 'pagseguro/config.php';

if ($isHistory) {
    if (!$logged || !$account_logged || !$account_logged->isLoaded()) {
        echo '<p>' . t('shop.history.login_required', 'To view your shop history you need to be logged in.') . ' ' .
            generateLink(getLink('account/manage'), t('shop.login', 'Login')) . '.</p>';
        return;
    }

    $accountId = (int)$account_logged->getId();
    $transactions = [];
    $items = [];

    if (shopTableExists('pagseguro_transactions')) {
        $query = $db->query(
            'SELECT `transaction_code`, `payment_method`, `payment_status`, `coins_amount`, `delivered`, `created_at`, `updated_at` ' .
            'FROM `pagseguro_transactions` WHERE `account_id` = ' . $accountId . ' ORDER BY `created_at` DESC LIMIT 30'
        );
        $transactions = $query->fetchAll();
    }

    if (shopTableExists('myaac_send_items')) {
        $query = $db->query(
            'SELECT `transaction_code`, `item_name`, `item_count`, `payment_method`, `payment_status`, `status`, `created_at`, `updated_at` ' .
            'FROM `myaac_send_items` WHERE `account_id` = ' . $accountId . ' ORDER BY `created_at` DESC LIMIT 30'
        );
        $items = $query->fetchAll();
    }

    echo '<table width="100%" border="0" cellpadding="4" cellspacing="1">';
    echo '<tr bgcolor="' . $config['vdarkborder'] . '" class="white"><td colspan="6"><b>' . t('shop.history.point_purchases', 'Coin Purchases') . '</b></td></tr>';
    echo '<tr bgcolor="' . $config['darkborder'] . '"><td><b>' . t('shop.history.date', 'Date') . '</b></td><td><b>' . t('shop.history.transaction', 'Transaction') . '</b></td><td><b>' . t('shop.history.coins', 'Coins') . '</b></td><td><b>' . t('shop.history.status', 'Status') . '</b></td><td><b>' . t('shop.history.delivered', 'Delivered') . '</b></td><td><b>' . t('shop.history.method', 'Method') . '</b></td></tr>';
    if (count($transactions) === 0) {
        echo '<tr bgcolor="' . $config['lightborder'] . '"><td colspan="6">' . t('shop.history.no_points', 'No coin purchases were found for this account.') . '</td></tr>';
    } else {
        $i = 0;
        foreach ($transactions as $transaction) {
            $bg = getStyle($i++);
            echo '<tr bgcolor="' . $bg . '">';
            echo '<td>' . htmlspecialchars($transaction['created_at']) . '</td>';
            echo '<td>' . htmlspecialchars($transaction['transaction_code']) . '</td>';
            echo '<td>' . (int)$transaction['coins_amount'] . '</td>';
            echo '<td>' . htmlspecialchars($transaction['payment_status']) . '</td>';
            echo '<td>' . ($transaction['delivered'] === '1' ? t('shop.history.yes', 'Yes') : t('shop.history.no', 'No')) . '</td>';
            echo '<td>' . htmlspecialchars($transaction['payment_method']) . '</td>';
            echo '</tr>';
        }
    }
    echo '</table><br />';

    echo '<table width="100%" border="0" cellpadding="4" cellspacing="1">';
    echo '<tr bgcolor="' . $config['vdarkborder'] . '" class="white"><td colspan="7"><b>' . t('shop.history.item_purchases', 'Item Purchases') . '</b></td></tr>';
    echo '<tr bgcolor="' . $config['darkborder'] . '"><td><b>' . t('shop.history.date', 'Date') . '</b></td><td><b>' . t('shop.history.transaction', 'Transaction') . '</b></td><td><b>' . t('shop.history.item', 'Item') . '</b></td><td><b>' . t('shop.history.count', 'Count') . '</b></td><td><b>' . t('shop.history.status', 'Status') . '</b></td><td><b>' . t('shop.history.delivery', 'Delivery') . '</b></td><td><b>' . t('shop.history.method', 'Method') . '</b></td></tr>';
    if (count($items) === 0) {
        echo '<tr bgcolor="' . $config['lightborder'] . '"><td colspan="7">' . t('shop.history.no_items', 'No item purchases were found for this account.') . '</td></tr>';
    } else {
        $statusMap = ['0' => t('shop.history.pending', 'Pending'), '1' => t('shop.history.approved', 'Approved'), '2' => t('shop.history.delivered', 'Delivered'), '3' => t('shop.history.canceled', 'Canceled')];
        $i = 0;
        foreach ($items as $item) {
            $bg = getStyle($i++);
            echo '<tr bgcolor="' . $bg . '">';
            echo '<td>' . htmlspecialchars($item['created_at']) . '</td>';
            echo '<td>' . htmlspecialchars($item['transaction_code']) . '</td>';
            echo '<td>' . htmlspecialchars($item['item_name']) . '</td>';
            echo '<td>' . (int)$item['item_count'] . '</td>';
            echo '<td>' . htmlspecialchars($item['payment_status']) . '</td>';
            echo '<td>' . ($statusMap[$item['status']] ?? htmlspecialchars($item['status'])) . '</td>';
            echo '<td>' . htmlspecialchars($item['payment_method']) . '</td>';
            echo '</tr>';
        }
    }
    echo '</table>';
    return;
}
