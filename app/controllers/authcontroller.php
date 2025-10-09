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

    //mail management
    $subject = "Camagru account registration";
    $message = "Please valid account registration on camagru website";
    mail($email, $subject, $message);
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
        $_SESSION['mail'] = $user['email'];
        flash('ok', '[auth] welcome !');
        header('Location: /');
        exit;
    } else {
      echo "Identifiants invalides";
    }
  }
    public function settings(): void {
        Csrf::checkToken();
        $uid = auth_id();
        $pdo = DB::pdo();
        //get data
        $stmt = $pdo->prepare("SELECT * FROM users WHERE id=?");
        $stmt->execute([$uid]);
        $data = $stmt->fetch();
        $username = $_POST['newUsername'] ?? '';
        $email = $_POST['newEmail'] ?? '';
        $currPw = $_POST['currentPassword'] ?? '';
        $newPw = $_POST['newPassword'] ?? '';
        if ($currPw !== '' && $newPw !== ''){
            $uppercase   = preg_match('@[A-Z]@', $newPw);
            $lowercase   = preg_match('@[a-z]@', $newPw);
            $number      = preg_match('@[0-9]@', $newPw);
            $specialChars = preg_match('@[^\w]@', $newPw);
            if (!password_verify($currPw, $data['pass_hash'])){
                flash("setWARN","Original password doesn't match with the one entered");
            }
            else if(!$uppercase || !$lowercase || !$number || !$specialChars || strlen($newPw) < 8) {
                flash("setWARN", "New Password should respect the password policy.");
            } else {
                $hash = password_hash($newPw, PASSWORD_DEFAULT);
                $stmt = $pdo->prepare("UPDATE users SET pass_hash=? WHERE id=?");
                $stmt->execute([$hash, $uid]);
                flash("setVALID", "Password updated... OK!");
            }
        }
        if ($email !== ''){
            if (!filter_var($email, FILTER_VALIDATE_EMAIL )){
                flash("setWARN", "Please enter a valid email.");
            }else if ($data['email'] === $email){
                flash("setWARN", "Same mail as before... choose something else please.");
            } else {
                $stmt = $pdo->prepare("SELECT id FROM users WHERE email=?");
                $stmt->execute([$email]);
                $exists = $stmt->fetch();
                if ($exists){
                    flash("setWARN", "Mail already taken... choose something else please.");
                } else {
                    $stmt = $pdo->prepare("UPDATE users SET email=? WHERE id=?");
                    $stmt->execute([$email, $uid]);
                    $_SESSION['mail'] = $email;
                    flash("setVALID", "Mail updated... OK!");
                }
            }
        }
        if ($username !== ''){
            if (strlen($username) < 4 || strlen($username) > 12){
                flash("setWARN", "Username must be between 4 and 12 characters");
            }
            else if ($data['username'] === $username){
                flash("setWARN", "Same name as before... choose something else please.");
            } else {
                $stmt = $pdo->prepare("SELECT id FROM users WHERE username=?");
                $stmt->execute([$username]);
                $exists = $stmt->fetch();
                if ($exists){
                    flash("setWARN", "Username already taken... choose something else please.");
                } else {
                    $stmt = $pdo->prepare("UPDATE users SET username=? WHERE id=?");
                    $stmt->execute([$username, $uid]);
                    $_SESSION['user'] = $username;
                    flash("setVALID", "Username updated... OK!");
                }
            }
        }
        header('Location: /settings');
    }
}
