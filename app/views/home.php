<?php
$title = 'Home';
require __DIR__ . '/partials/header.php';
?>
<h1>[home] welcome on my camagru</h1>

<?php if ($m = flash('ok')): ?>
<p><?= htmlspecialchars($m) ?></p>
<?php endif; ?>

<?php if (auth_id()): ?>
<p>Logged as a user #<?= auth_id() ?></p>
<?php endif; ?>

<?php require __DIR__ . '/partials/footer.php'; ?>
