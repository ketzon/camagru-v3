<?php
require_once __DIR__ . '/../DB.php';
require_once __DIR__ . '/../utils.php';

class GalleryController {
    public function list(): void {
        $pdo = DB::pdo();
        $size = max(5, (int)($_GET['size'] ?? 12)); 
        $page = max(1, (int)($_GET['page'] ?? 1));
        $offset = ($page - 1) * $size;

        $total = (int)$pdo->query("SELECT COUNT(*) FROM images")->fetchColumn();
        $stmt = $pdo->prepare("SELECT id, user_id, created_at FROM images ORDER BY id DESC LIMIT ? OFFSET ?");
        $stmt->bindValue(1, $size, PDO::PARAM_INT);
        $stmt->bindValue(2, $offset, PDO::PARAM_INT);
        $stmt->execute();
        $rows = $stmt->fetchAll();
        $pages = max(1, (int)ceil($total / $size));
        require __DIR__ . '/../views/gallery.php';//use variable in gallery
    }

    public function show(): void {
        $id = (int)($_GET['id'] ?? 0);
        if ($id <= 0) { 
            http_response_code(404); 
            exit('Not found'); 
        }
        $pdo = DB::pdo();
        // image
        $img = $pdo->prepare("SELECT * FROM images WHERE id = ?");
        $img->execute([$id]);
        $image = $img->fetch();
        if (!$image) { 
            http_response_code(404); 
            exit('not found'); 
        }
        // likes (correct)
        $st = $pdo->prepare("SELECT COUNT(*) FROM likes WHERE image_id = ?");
        $st->execute([$id]);
        $likes = (int)$st->fetchColumn();
        $liked = false;
        if ($uid = auth_id()) {
            $st = $pdo->prepare("SELECT 1 FROM likes WHERE image_id = ? AND user_id = ?");
            $st->execute([$id, $uid]);
            $liked = (bool)$st->fetchColumn();
        }
        // comments
        $stc = $pdo->prepare("
            SELECT c.id, c.body, c.created_at, u.username
            FROM comments c
            JOIN users u ON u.id = c.user_id
            WHERE c.image_id = ?
            ORDER BY c.id DESC
            ");
        $stc->execute([$id]);
        $comments = $stc->fetchAll();
        $uid = $_SESSION['uid'] ?? null;
        $canInteract = $uid !== null;
        require __DIR__ . '/../views/image_show.php';
    }
}

