<?php

final class DB {
  private static ?\PDO $pdo = null;
  static function pdo(): \PDO {
    if (!self::$pdo) {
      self::$pdo = new \PDO('sqlite:' . __DIR__ . '/../storage/db.sqlite', '', '', [
        \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
        \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
      ]);
    }
    return self::$pdo;
  }
}
