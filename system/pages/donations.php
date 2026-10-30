<?php
defined('MYAAC') or die('Direct access not allowed!');

$title = t('donations.title');
$wallets = require SYSTEM . 'donation-wallets.php';
?>

<section class="zealot-donations" aria-labelledby="zealot-donations-title">
  <header class="zealot-donations__hero">
    <span><?= t('donations.eyebrow') ?></span>
    <h1 id="zealot-donations-title"><?= t('donations.heading') ?></h1>
    <p><?= t('donations.intro') ?></p>
  </header>

  <div class="zealot-donations__notice" role="note">
    <strong><?= t('donations.warning_title') ?></strong>
    <p><?= t('donations.warning_copy') ?></p>
  </div>

  <div class="zealot-donations__wallets">
    <?php foreach ($wallets as $wallet): ?>
      <article class="zealot-donations__wallet">
        <header>
          <span><?= htmlspecialchars($wallet['asset'], ENT_QUOTES, 'UTF-8') ?></span>
          <strong><?= htmlspecialchars($wallet['network'], ENT_QUOTES, 'UTF-8') ?></strong>
        </header>
        <p class="zealot-donations__address-label"><?= t('donations.address') ?></p>
        <code><?= htmlspecialchars($wallet['address'], ENT_QUOTES, 'UTF-8') ?></code>
        <button type="button" class="zealot-donations__copy" data-address="<?= htmlspecialchars(
          $wallet['address'],
          ENT_QUOTES,
          'UTF-8'
        ) ?>">
          <?= t('donations.copy') ?>
        </button>
      </article>
    <?php endforeach; ?>
  </div>

  <p class="zealot-donations__footer">
    <?= t(
      'donations.footer'
    ) ?> <a href="mailto:server.zealot@gmail.com">server.zealot@gmail.com</a>
  </p>
</section>

<script>
document.querySelectorAll('.zealot-donations__copy').forEach((button) => {
  button.addEventListener('click', async () => {
    try {
      await navigator.clipboard.writeText(button.dataset.address);
      button.textContent = <?= json_encode(t('donations.copied')) ?>;
      window.setTimeout(() => { button.textContent = <?= json_encode(
        t('donations.copy')
      ) ?>; }, 1800);
    } catch (error) {
      window.prompt(<?= json_encode(t('donations.copy_prompt')) ?>, button.dataset.address);
    }
  });
});
</script>
