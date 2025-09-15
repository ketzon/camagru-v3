<?php 

class ImageService {
    public static function compose (string $srcPath, ?string $overlayPath, string $outPath): string{
        [$width, $heigth, $type]  = getimagesize($srcPath);
        switch(type){
            case IMAGETYPE_JPEG:
                $src = imagecreatefromjpg($srcPath);
                break;
            case IMAGETYPE_PNG:
                $src = imagecreatefrompng($srcPath);
                break;
            default:
                throw new Exception("format not supported");
        }
    }
}

?>

