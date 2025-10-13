<?php
require_once __DIR__ . '/../app/DB.php';
$token = $_GET['token'] ?? '';
if ($token === '') {
    exit('wrong link');
}
$pdo = DB::pdo();
$stmt = $pdo->prepare('SELECT id FROM users WHERE confirm_token=?');
$stmt->execute([$token]);
$user = $stmt->fetch();
if (!$user) {
    exit('Invalid or expired token');
}
$pdo->prepare('UPDATE users SET email_confirmed=1, confirm_token=NULL WHERE id=?')->execute([$user['id']]);
flash ("ok",  "your account has been confirmed! you can now log in.");
