<?php require_once __DIR__.'/../../utils.php'; $uid = auth_id(); ?>
<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title><?= htmlspecialchars($title ?? 'Camagru') ?></title>
</head>
<body>
<div class="nav">
    <div class="container">
        <a href="/">Home</a>
        <?php if ($uid): ?>
        <a href="/gallery">Gallery</a>
        <a href="/editor">Editor</a>
        <a href="/logout">Logout</a>
        <?php else: ?>
        <a href="/login">Login</a>
        <a href="/signup">Signup</a>
        <?php endif; ?>
    </div>
</div>
<main class="container">
