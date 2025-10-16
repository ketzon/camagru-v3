<?php
require __DIR__ . '/partials/header.php';
require_once __DIR__ . '/../csrf.php';
$csrf = Csrf::getToken();
$token = $_GET['t'] ?? '';
$uid = (int)($_GET['u'] ?? 0);

if ($token === '' || $uid <= 0) {
    echo "<p style='color:red;'>Invalid or missing reset link.</p>";
    require __DIR__ . '/partials/footer.php';
    exit;
}
?>

<h1>Reset password</h1>
<form method="post" action="/reset" style="margin-top:20px;">
    <input type="hidden" name="_csrf" value="<?= htmlspecialchars($csrf) ?>">
    <input type="hidden" name="t" value="<?= htmlspecialchars($token) ?>">
    <input type="hidden" name="u" value="<?= (int)$uid ?>">
    <label for="newPassword">New password (min 8 characters)</label><br>
    <input type="password" id="newPassword" name="newPassword" placeholder="********" required minlength="8" style="margin:8px 0;"><br>
    <button type="submit">Change</button>
</form>

<?php require __DIR__ . '/partials/footer.php'; ?>
