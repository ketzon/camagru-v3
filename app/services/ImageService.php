<?php 

class ImageService {
    public static function compose (string $srcPath, ?string $overlayPath, string $outPath): string{
        [$w, $h, $type]  = getimagesize($srcPath);
        switch($type){
            case IMAGETYPE_JPEG:
                $src = imagecreatefromjpeg($srcPath);
                break;
            case IMAGETYPE_PNG:
                $src = imagecreatefrompng($srcPath);
                break;
            default:
                throw new Exception("format not supported");
        }
        imagealphablending($src, true); //blend color
        imagesavealpha($src, true); //keep transparency

        if ($overlayPath && is_file($overlayPath)) {
            $ov = imagecreatefrompng($overlayPath);
            if (!$ov) {
                echo ("invalid png file");
                exit;
            }
            imagesavealpha($ov, true);
            $ovRes = imagecreatetruecolor($w, $h);
            imagesavealpha($ovRes, true);
            $transparent = imagecolorallocatealpha($ovRes, 0,0,0,127);//add rgb+tran
            imagefill($ovRes,0,0,$transparent);
            $ow = imagesx($ov);
            $oh = imagesy($ov);
            imagecopyresampled($ovRes, $ov,0,0,0,0, $w, $h, $ow,$oh);
            imagecopy($src, $ovRes,0,0,0,0,$w,$h);
            imagedestroy($ov);//keep uniquement le final $src et delete overlay
            imagedestroy($ovRes);
        }
        imagesavealpha($src, true);
        if(!imagepng($src, $outPath)){
            throw new Exception("can't write");
        }
        imagedestroy($src);
        return $outPath;
    }
}
