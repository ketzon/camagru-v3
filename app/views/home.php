<?php
$title = 'Home';
require __DIR__ . '/partials/header.php';
?>
<h1>[home] welcome on my camagru</h1>

<?php if ($m = flash('ok')): ?>
<p><?= htmlspecialchars($m) ?></p>
<?php endif; ?>
<?php if (auth_id()): ?>
<p>Logged as: <strong style="color:#0366d6;"><?= getUserName() ?></strong> #<?= auth_id()?></p>
<p>Mail: <strong style="color:#0366d6;"><?= getMail() ?></strong> </p>
<?php endif; ?>

<?php require __DIR__ . '/partials/footer.php'; ?>
