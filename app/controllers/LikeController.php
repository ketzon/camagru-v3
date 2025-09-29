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
        //protection for relike (toggle mode)
        $pdo = DB::pdo();
        $st = $pdo->prepare("SELECT 1 FROM likes WHERE user_id=? AND image_id=?");
        $st->execute([$uid, $image_id]);
        $already = (bool)$st->fetchColumn();
        if($already){
            $st = $pdo->prepare("DELETE FROM likes WHERE user_id=? AND image_id=?");
            $st->execute([$uid, $image_id]);
        } else {
            $st = $pdo->prepare("INSERT INTO likes(user_id,image_id) VALUES(?,?)");
            $st->execute([$uid, $image_id]);
        }
        header("Location: /image/$image_id");
        exit;
    }
}
