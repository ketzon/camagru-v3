/* tuto pdo: */
/* https://www.youtube.com/watch?v=Rh7mXaZl1oc&ab_channel=Grafikart.fr */
<?php

/*
  Classe DB = utilitaire pour gérer la connexion à la base avec PDO.

  - final class : on ne peut pas hériter (classe figée).
  - static $pdo : variable partagée par toute la classe (1 seule connexion).
  - self::pdo() : méthode statique qui retourne la connexion (la crée si besoin).
  - DSN (Data Source Name) : "sqlite:/chemin/vers/fichier.sqlite" = indique type de base + emplacement.
  - \PDO : classe native de PHP (dans l’espace global).
  - Options utiles :
      * ERRMODE_EXCEPTION : erreurs SQL lèvent une exception (plus sûr).
      * FETCH_ASSOC : fetch() retourne uniquement des tableaux associatifs.

  Usage :
    $pdo = DB::pdo();
    $rows = $pdo->query("SELECT * FROM users")->fetchAll();
*/



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
