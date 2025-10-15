<?php
require_once __DIR__ . '/../DB.php';
require_once __DIR__ . '/../csrf.php';
require_once __DIR__ . '/../utils.php';

class CommentController {
    public function add(): void {
        Csrf::checkToken();
        $uid = auth_id();
        $image_id = (int)($_POST['image_id'] ?? 0);
        $body = trim($_POST['body'] ?? '');
        if ($image_id <= 0 || $body === '') { 
            http_response_code(400); 
            exit('bad input'); 
        }
        if (mb_strlen($body) > 500) { 
            $body = mb_substr($body, 0, 500); 
        }
        $pdo = DB::pdo();
        $st = $pdo->prepare("INSERT INTO comments(user_id, image_id, body) VALUES (?,?,?)");
        $st->execute([$uid, $image_id, $body]);
        //test mail when comment
        $st = $pdo->prepare("
            SELECT u.id AS owner_id, u.username AS owner_name, u.email, u.notify_on_comment, u.email_confirmed
            FROM images i
            JOIN users u ON u.id = i.user_id
            WHERE i.id = ?
            ");
        $st->execute([$image_id]);
        $owner = $st->fetch();
        if ($owner
            && (int)$owner['notify_on_comment'] === 1
            && (int)$owner['email_confirmed'] === 1
            && (int)$owner['owner_id'] !== $uid
            && filter_var($owner['email'], FILTER_VALIDATE_EMAIL)) {
            $preview = mb_substr(trim(preg_replace('/\s+/', ' ', strip_tags($body))), 0, 120);
            $link = "http://localhost:8080/image/{$image_id}";
            $st = $pdo->prepare("SELECT username FROM users WHERE id=?");
            $st->execute([$uid]);
            $author = $st->fetchColumn() ?: ('User #'.$uid);
            $subject = "New comment on your photo";
            $message = "Hi {$owner['owner_name']},\n\n"
                . "{$author} commented on your photo:\n"
                . "\"{$preview}\"\n\n"
                . "Open: {$link}\n\n"
                . "You can disable these emails in settings.";
            $headers = "From: Camagru <ketzon.contact@gmail.com>\r\n";
            @mail($owner['email'], $subject, $message, $headers);
        }
        header("Location: /image/$image_id");
    }
}
