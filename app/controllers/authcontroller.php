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
            $uid = (int)$pdo->lastInsertId();
            $token = bin2hex(random_bytes(16));
            $pdo->prepare("UPDATE users SET confirm_token=? WHERE id=?")->execute([$token, $uid]);

            $link = "http://localhost:8080/confirm?t={$token}";
            $subject = "Account registration confirmation";
            $message = "Welcome {$username}!\n\nClick here to confirm your account:\n{$link}\n";
            $headers = "From: Camagru <ketzon.contact@gmail.com>\r\n";
            if (mail($email, $subject, $message, $headers)){
                flash('ok', 'we sent you a confirmation email. check your inbox');
            }else{
                flash('ok', 'account created, verification could not be sent now');
            }
            header("Location: /");
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

        if (!$user){
            flash('ok', 'wrong user');
            header('location: /login');
            exit;
        }
        if ((int)$user['email_confirmed'] !== 1){
            flash('ok', "please confirm your mail before login");
            header('Location: /');
            exit;
        }

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
        $stmt = $pdo->prepare("SELECT * FROM users WHERE id=?");
        $stmt->execute([$uid]);
        $data = $stmt->fetch();
        if (isset($_POST['comment'])){
            $choice =  ($_POST['comment']);
            if ($choice === 'yes' && (int)$data['notify_on_comment'] === 0){
                $pdo->prepare("UPDATE users SET notify_on_comment=? WHERE id=?")->execute(['1', $uid]);
                flash("setVALID","notification on comment actived");
            }else if ($choice === 'no' && (int)$data['notify_on_comment'] === 1){
                $pdo->prepare("UPDATE users SET notify_on_comment=? WHERE id=?")->execute(['0', $uid]);
                flash("setVALID","notification on comment desactived");
            }else{
            }
        }
        //get data
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

    public function requestReset(): void {
        Csrf::checkToken();
        $email = trim($_POST['email'] ?? '');
        $okMsg = 'If that email exists, a reset link was sent.';
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            flash('ok', $okMsg); header('Location: /forgot'); return;
        }
        $pdo = DB::pdo();
        $st = $pdo->prepare('SELECT id, email_confirmed FROM users WHERE email=?');
        $st->execute([$email]);
        $u = $st->fetch();
        if (!$u || (int)$u['email_confirmed'] !== 1) {
            flash('ok', $okMsg); header('Location: /forgot'); return;
        }
        $token = bin2hex(random_bytes(16));
        $exp   = time() + 1800; // 30mn
        $pdo->prepare('UPDATE users SET reset_token=?, reset_expires=? WHERE id=?')->execute([$token, $exp, (int)$u['id']]);
        $link = "http://localhost:8080/reset?t={$token}&u={$u['id']}";
        $subject = "Reset your password";
        $message = "Click here to reset your password (valid 30 min):\n{$link}\n";
        $headers = "From: Camagru <ketzon.contact@gmail.com>\r\n";
        @mail($email, $subject, $message, $headers);
        flash('ok', $okMsg);
        header('Location: /forgot');
    }

    public function performReset(): void {
        Csrf::checkToken();
        $uid = (int)($_POST['u'] ?? 0);
        $t   = $_POST['t'] ?? '';
        $pw  = $_POST['newPassword'] ?? '';
        $uppercase   = preg_match('@[A-Z]@', $pw);
        $lowercase   = preg_match('@[a-z]@', $pw);
        $number      = preg_match('@[0-9]@', $pw);
        $specialChars = preg_match('@[^\w]@', $pw);
        if(!$uppercase || !$lowercase || !$number || !$specialChars) {
            flash('ok', "please respect the strict password policy, if you want a new password");
            header('Location: /'); 
            return;
        }
        if ($uid <= 0 || $t === '' || strlen($pw) < 8) {
            flash('ok', 'Invalid data.'); 
            header('Location: /forgot'); 
            return;
        }
        $pdo = DB::pdo();
        $st = $pdo->prepare('SELECT reset_token, reset_expires FROM users WHERE id=?');
        $st->execute([$uid]);
        $row = $st->fetch();
        if (!$row || $row['reset_token'] !== $t || (int)$row['reset_expires'] < time()) {
            flash('ok', 'reset link invalid or expired.'); 
            header('Location: /forgot'); 
            return;
        }
        $hash = password_hash($pw, PASSWORD_DEFAULT);
        $pdo->prepare('UPDATE users SET pass_hash=?, reset_token=NULL, reset_expires=NULL WHERE id=?')
            ->execute([$hash, $uid]);
        flash('ok', 'password changed. you can login now.');
        header('Location: /login');
    }
}
