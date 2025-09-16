<?php if ($m = flash('ok')): ?>
    <p><?= htmlspecialchars($m) ?></p>
<?php endif; ?>

<?php if (auth_id()): ?>
    <p><i>log en tant que user #<?= auth_id() ?></i></p>
    <a href="/logout">Logout</a>
<?php else: ?>
    <a href="/signup">Signup</a> | <a href="/login">Login</a>

<?php endif; ?>
