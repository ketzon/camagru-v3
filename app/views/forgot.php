<?php 
require __DIR__ . '/partials/header.php'; 
require_once __DIR__ . '/../csrf.php'; 
$csrf = Csrf::getToken();
?>

<h1>Forgot password</h1>
<form method="post" action="/forgot">
    <input type="hidden" name="_csrf" value="<?= htmlspecialchars($csrf) ?>">
    <input type="email" name="email" placeholder="you@example.com" required>
    <button type="submit">Send reset link</button>
</form>

<?php require __DIR__ . '/partials/footer.php'; ?>
