<?php
require_once __DIR__ . '/../DB.php';

class AuthController {
  public function signup(): void {
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if (!$username || !$email || !$password) {
      echo "tous les champs sont obligatoires";
      return;
    }

    //PASSWORD_DEFAULT = algo bcrypt
    $hash = password_hash($password, PASSWORD_DEFAULT);
    /* echo $hash; */
    /* echo "\r"; */
    /* echo $username; */
    /* echo "\r"; */
    /* echo $email; */
    /* echo "\r"; */

    try {
      $pdo = DB::pdo();
      $stmt = $pdo->prepare("INSERT INTO users(username, email, pass_hash) VALUES (?, ?, ?)");
      $stmt->execute([$username, $email, $hash]);
      header("Location: /login");
    } catch (PDOException $e) {
      echo "Erreur: " . htmlspecialchars($e->getMessage());
    }
  }

  public function login(): void {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    $pdo = DB::pdo();
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['pass_hash'])) {
      setcookie("session_user", $user['id'], time()+3600, "/");
      header("Location: /"); //redirige home si bien co
    } else {
      echo "Identifiants invalides";
    }
  }
}
