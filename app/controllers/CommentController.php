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
        header("Location: /image/$image_id");
    }
}
