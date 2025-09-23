<?php
require_once __DIR__ . '/../DB.php';

class MediaController {
    public function render(): void {
        $id = (int)($_GET['id'] ?? 0);
        if ($id <= 0) { 
            http_response_code(404); 
            exit; 
        }
        $pdo = DB::pdo();
        $st = $pdo->prepare("SELECT path_final FROM images WHERE id = ?");
        $st->execute([$id]);
        $path = $st->fetchColumn();
        if (!$path || !is_file($path)) { 
            http_response_code(404); 
            exit; 
        }
        header('Content-Type: image/png');
        header('Content-Length: ' . filesize($path));
        readfile($path);
    }
}
