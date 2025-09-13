<?php 
require_once __DIR__ . '/../csrf.php';
$csrf = csrf::getToken();?>

<h1> Login Form</h1>
<form method="post">
    <input type="hidden" name="_csrf" value="<?php htmlspecialchars($csrf) ?>">
    <input type="text" name="email" placeholder="Email">
    <input type="password" name="password" placeholder="Password">
    <button type="submit">Login</button>
</form>
