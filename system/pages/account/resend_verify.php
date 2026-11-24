<?php
global $account_logged, $twig, $config;
/**
 * Resend Verify Account Email
 *
 * @package   MyAAC
 * @author    OpenTibiaBR
 * @copyright 2024 MyAAC
 * @link      https://github.com/opentibiabr/myaac
 */
defined('MYAAC') or die('Direct access not allowed!');

if (!$config['mail_enabled'])
  echo "You can't resend email to verify your account";
else {
  $show_form = true;
  $csrf = getSession('email_verify_csrf');
  if (!is_string($csrf) || strlen($csrf) !== 64) {
    $csrf = bin2hex(random_bytes(32));
    setSession('email_verify_csrf', $csrf);
  }
  $accName = $account_logged->getName();
  $accEmail = $account_logged->getEMail();

  if ($account_logged->getCustomField('email_verified') == '1') {
    echo "You account is already verified!";
    return;
  }

  if (isset($_POST['confirmresend']) && $_POST['confirmresend'] == '1') {
    $submitted = $_POST['csrf'] ?? '';
    if (!is_string($submitted) || !hash_equals($csrf, $submitted)) {
      error('Your session expired. Please refresh and try again.');
      return;
    }
    $lastSend = (int)getSession('email_verify_last_send');
    if ($lastSend > time() - 60) {
      error('Please wait one minute before requesting another email.');
      return;
    }
    $hash = bin2hex(random_bytes(16));
    $account_logged->setCustomField('email_hash', $hash);

    $verify_url = getLink('account/confirm_email/' . $hash);
    $body_html = $twig->render('mail.account.verify.html.twig', array(
      'account' => $accName,
      'verify_url' => generateLink($verify_url, $verify_url, true)
    ));

    if (_mail($accEmail, configLua('serverName') . ' - Verify Account', $body_html)) {
      $message = "<br />Your request was sent on email address <b>{$accEmail}</b>";
      $show_form = false;
      setSession('email_verify_last_send', time());
    } else {
      $message = "<br /><p class='error'>An error occurred while sending email ( <b>{$accEmail}</b> )! Try again later. For Admin: More info can be found in system/logs/mailer-error.log</p>";
    }

    $twig->display('success.html.twig', array(
      'title' => $show_form ? 'Email Not Sent' : 'Verify Email Sent',
      'description' => "<ul>{$message}</ul>"
    ));
  }

  //show errors if not empty
  if (!empty($errors)) {
    $twig->display('error_box.html.twig', array('errors' => $errors));
  }

  if ($show_form) {
    $twig->display('account.resend_verify_email.html.twig', array(
      'csrf' => $csrf,
      'name' => $accName,
      'email' => $accEmail,
    ));
  }
}
