<?php require_once __DIR__ . '/../../utils.php'; 
$uid = auth_id();
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title> camagru </title>
    <style>
    html, body { 
        height:100%; 
        margin:0; 
    }
    body { display:flex; flex-direction:column; font-family:monospace; font-size:20px; min-height:100vh; }
    main { flex:1; padding:12px; }
    .blue { color: #0366d6; }
    footer { text-align:center; padding:8px; }
    .nav { padding:8px 12px; border-bottom:1px solid #ddd; }
    a { color: #0366d6; text-decoration:none; font-weight: 600; transition: color 0.2s ease;}
    a:hover { color: #0056b3; text-decoration: underline;}
    .logout { color:crimson}
    .logout:hover {color:crimson}
    </style>
</head>
<body>
<div class="nav">
    <a href="/">Home</a> |
    <?php if($uid): ?>
    <a href="/gallery">Gallery</a> |
    <a href="/editor">Editor</a> |
    <a href="/settings">Settings</a> |
    <a class="logout" href="/logout">Logout</a> 
    <?php else: ?>
    <a href="/login">Login</a> |
    <a href="/signup">Signup</a> 
    <?php endif; ?>
</div>
<main>
