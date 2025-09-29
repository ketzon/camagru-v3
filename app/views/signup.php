<?php
require_once __DIR__ . '/../csrf.php';
$csrf = Csrf::getToken(); ?>

<h1>Form Signup</h1>
<ul>
<li><strong>Username: </strong>Username must be between 4 and 12 characters</li>
<li><strong>Email: </strong> Email must be valid&mdash;<i style="color:0366d6">example@mail.com</i></li>
<li><strong>Password: </strong>Password should be at least 8 characters in length, should include at least one upper case letter, one number and one special character&mdash; <i style="color:0366d6">DummyPassword88@</li>
</ul>
<form method="post">
  <input type="hidden" name="_csrf" value="<?php htmlspecialchars($csrf) ?>">
  <input type="text" name="username" placeholder="Username">
  <input type="text" name="email" placeholder="Email">
  <input type="password" name="password" placeholder="Password">
  <button type="submit">Signup</button>
</form>
