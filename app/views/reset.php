<?php require __DIR__.'/partials/header.php'; 
$token = $_GET['t'] ?? ''; $uid = (int)($_GET['u'] ?? 0); ?>
<h1>Reset password</h1>
<form method="post" action="/reset">
    <input type="hidden" name="_csrf" value="<?php require __DIR__.'/../csrf.php'; echo htmlspecialchars(Csrf::getToken()); ?>">
    <input type="hidden" name="t" value="<?= htmlspecialchars($token) ?>">
    <input type="hidden" name="u" value="<?= (int)$uid ?>">
    <input type="password" name="newPassword" placeholder="New password (min 8)" required>
    <button type="submit">Change</button>
</form>
<?php require __DIR__.'/partials/footer.php'; ?>
