<?php
require_once __DIR__.'/../DB.php';
require_once __DIR__.'/../csrf.php';
require_once __DIR__.'/../services/ImageService.php';
require_once __DIR__.'/../utils.php';

class ImageController {
    public function compose(): void {
        Csrf::checkToken();
        $uid = auth_id();
        if (!$uid){
            http_response_code(400);
            exit;
        }
        $overlayRel = trim($_POST['overlay_path'] ?? ''); // ex: /overlays/moustache.png
        if ($overlayRel && str_starts_with($overlayRel, '/overlays/')){
            $path = __DIR__ . '/../../public' . $overlayRel;
            $overlayAbs = realpath($path);
        }else{
            $overlayAbs = null;
        }
        // 1) reception image soit data_url (canvas), soit upload direct 
        $srcAbs = null;
        if (!empty($_POST['data_url'])) {
            $data = $_POST['data_url'];
            if (!preg_match('#^data:image/(png|jpeg);base64,#', $data)) { 
                exit('wrong data_url format'); 
            }
            [$meta, $b64] = explode(',', $data, 2);
            $bin = base64_decode($b64, true);
            if ($bin === false){ 
                exit('wrong base64');
            }
            $ext = str_contains($meta,'png') ? 'png' : 'jpg';
            $srcAbs = __DIR__ . '/../../storage/uploads/'.uniqid('u_', true).'.'.$ext;
            file_put_contents($srcAbs, $bin);
        } else if (!empty($_FILES['file']['tmp_name'])) {
            $f = $_FILES['file'];
            $mime = mime_content_type($f['tmp_name']);
            if (!in_array($mime, ['image/png','image/jpeg'], true)) exit('MIME invalide');
            $ext = $mime==='image/png'?'png':'jpg';
            $srcAbs = __DIR__ . '/../../storage/uploads/'.uniqid('u_', true).'.'.$ext;
            move_uploaded_file($f['tmp_name'], $srcAbs);
        } else {
            exit('error no image receive');
        }
        // 2) composer
        $outAbs = __DIR__ . '/../../storage/renders/'.uniqid('r_', true).'.png';
        try {
            ImageService::compose($srcAbs, $overlayAbs ?: null, $outAbs);
        } catch (Exception $e) {
            http_response_code(500); 
            exit('compose error: '.$e->getMessage());
        }
        // 3) db
        $pdo = DB::pdo();
        $stmt = $pdo->prepare("INSERT INTO images(user_id, path_raw, path_final) VALUES (?,?,?)");
        $stmt->execute([$uid, $srcAbs, $outAbs]);
        $id = (int)$pdo->lastInsertId();
        // 4) redirect sur futur page
        header('Location: /image/'.$id);
    }
    public function delete(): void {
        Csrf::checkToken();
        $uid = auth_id();
        if (!$uid){
            http_response_code(400);
            exit;
        }
        $pdo = DB::pdo();
        $image_id = (int)$_POST['image_id'] ?? 0; 

        $owner = $pdo->prepare("SELECT user_id path_raw path_final FROM images WHERE id=?");
        $owner->execute([$image_id]);
        $img = $owner->fetch();
        if ((int)$img['user_id'] != $uid){
            exit ("can't delete image you don't own");
        }
        /* echo ("image deleted from db: " . $image_id); */
        $stmt = $pdo->prepare("DELETE FROM images WHERE id=?");
        $stmt->execute([$image_id]);
        foreach (['path_raw','path_final'] as $elem) {
            $path = $img[$elem] ?? null;
            if ($path && is_file($path)) { 
                @unlink($path); 
            }
        }
        header('Location: /gallery');

    }
}
