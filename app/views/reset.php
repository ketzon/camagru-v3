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
<li><strong>Password Policies: </strong>Password should be at least 8 characters in length, should include at least one upper case letter, one number and one special character&mdash; <i class="blue">DummyPassword88@</i></li>
</ul>

<h1>Reset password</h1>
<form method="post" action="/reset" style="margin-top:20px;">
    <input type="hidden" name="_csrf" value="<?= htmlspecialchars($csrf) ?>">
    <input type="hidden" name="t" value="<?= htmlspecialchars($token) ?>">
    <input type="hidden" name="u" value="<?= (int)$uid ?>">
    <label for="newPassword">New password (One try, please double read password Policies before validating)</label><br>
    <input type="password" id="newPassword" name="newPassword" placeholder="********" required minlength="8" style="margin:8px 0;"><br>
    <button type="submit">Change</button>
</form>

<?php require __DIR__ . '/partials/footer.php'; ?>
