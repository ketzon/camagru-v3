<?php
require_once __DIR__ . '/../DB.php';
require_once __DIR__ . '/../csrf.php';
require_once __DIR__ . '/../utils.php';

class LikeController {
    public function like(): void {
        Csrf::checkToken();
        $uid = auth_id();
        $image_id = (int)($_POST['image_id'] ?? 0);
        if ($image_id <= 0) { 
            http_response_code(400); 
            exit('bad image'); 
        }
        $pdo = DB::pdo();
        try {
            $st = $pdo->prepare("INSERT INTO likes(user_id, image_id) VALUES (?, ?)");
            $st->execute([$uid, $image_id]);
        } catch (Exception $e) {
            echo ($e->getMessage());

        }
        header("Location: /image/$image_id");
    }
}
