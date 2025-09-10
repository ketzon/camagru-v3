<?php
require __DIR__.'/DB.php';
$db = DB::pdo();
$db->exec("
CREATE TABLE IF NOT EXISTS users(
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  username TEXT UNIQUE NOT NULL,
  email TEXT UNIQUE NOT NULL,
  pass_hash TEXT NOT NULL,
  email_confirmed INTEGER DEFAULT 0,
  notify_on_comment INTEGER DEFAULT 1,
  created_at TEXT DEFAULT CURRENT_TIMESTAMP
);
");
echo "OK\n";
