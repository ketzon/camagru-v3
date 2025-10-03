<?php
require_once __DIR__ . '/../DB.php';
require_once __DIR__ . '/../csrf.php';

class AuthController {
  public function signup(): void {
    Csrf::checkToken();
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $uppercase   = preg_match('@[A-Z]@', $password);
    $lowercase   = preg_match('@[a-z]@', $password);
    $number      = preg_match('@[0-9]@', $password);
    $specialChars = preg_match('@[^\w]@', $password);
    if (!$username || !$email || !$password) {
      echo "please fill all value";
      return;
    }
    if (strlen($username) < 4 || strlen($username) > 12){
        echo "Username must be between 4 and 12 characters";
        return;
    }
    //check if email format is valid
    if (!filter_var($email, FILTER_VALIDATE_EMAIL )){
        echo "please enter a valid email";
        return;
    }
    if(!$uppercase || !$lowercase || !$number || !$specialChars || strlen($password) < 8) {
            echo "Password should be at least 8 characters in length, should include at least one upper case letter, one number and one special character.";
            return;
   }
    if (!$username || !$email || !$password) {
      echo "please enter a value";
      return;
    }
    //PASSWORD_DEFAULT = algo bcrypt
    $hash = password_hash($password, PASSWORD_DEFAULT);
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
    Csrf::checkToken();
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    $pdo = DB::pdo();
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['pass_hash'])) {
        $_SESSION['uid'] = (int)$user['id'];
        $_SESSION['user'] = $user['username'];
        flash('ok', '[auth] welcome !');
        header('Location: /');
        exit;
    } else {
      echo "Identifiants invalides";
    }
  }
}
