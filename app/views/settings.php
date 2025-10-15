<?php
require_once __DIR__ . '/../csrf.php';
require __DIR__ . '/partials/header.php';
$csrf = Csrf::getToken(); ?>

<h1>Change account settings</h1>
<p style="color:crimson;"><strong>YOU MUST RESPECT ACCOUNT POLICY</strong></p>

<ul>
<li><strong>Username: </strong>Username must be between 4 and 12 characters</li>
<li><strong>Email: </strong> Email must be valid&mdash;<i class="blue">example@mail.com</i></li>
<li><strong>Password: </strong>Password should be at least 8 characters in length, should include at least one upper case letter, one number and one special character&mdash; <i class="blue">DummyPassword88@</i></li>
</ul>

<p><?php getUserName()?></p>

<form method="post">
  <input type="hidden" name="_csrf" value="<?php htmlspecialchars($csrf) ?>">
  <input type="text" name="newUsername" placeholder="New Username">
  <button type="submit">Change Username</button>
</form>
<form method="post">
  <input type="hidden" name="_csrf" value="<?php htmlspecialchars($csrf) ?>">
  <input type="text" name="newEmail" placeholder="New Email">
  <button type="submit">Change Email</button>
</form>
<form method="post">
  <input type="hidden" name="_csrf" value="<?php htmlspecialchars($csrf) ?>">
  <input type="password" name="currentPassword" placeholder="Password">
  <input type="password" name="newPassword" placeholder="New Password">
  <button type="submit">Change Password</button>
</form>

<p> Please choose if you want <strong>notification</strong> when someone <strong>comment</strong> your picture</p>

<form method="post">
<input type="radio" name="comment" id="yes" value="yes" />
<label for="yes">yes</label>
<input type="radio" name="comment" id="no" value="no"/>
<label for="no">no</label>
<button type="submit">valider</button>
</form>



<?php if ($m = flash('setVALID')): ?>
<p style="color:green;"><?= htmlspecialchars($m) ?></p>
<?php endif; ?>
<?php if ($m = flash('setWARN')): ?>
<p style="color:crimson;"><?= htmlspecialchars($m) ?></p>
<?php endif; ?>
<?php require __DIR__ . '/partials/footer.php'; ?>
