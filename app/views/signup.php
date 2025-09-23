<?php
require_once __DIR__ . '/../csrf.php';
$csrf = Csrf::getToken(); ?>

<h1>Form Signup</h1>
<form method="post">
  <input type="hidden" name="_csrf" value="<?php htmlspecialchars($csrf) ?>">
  <input type="text" name="username" placeholder="Username">
  <input type="text" name="email" placeholder="Email">
  <input type="password" name="password" placeholder="Password">
  <button type="submit">Signup</button>
</form>
