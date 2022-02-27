<?php

namespace App\Tools;

class ImageTool
{
    public static function combine2Images($image_1, $image_2, $destination_path)
    {
        $image1 = imagecreatefrompng($image_1);
        $image2 = imagecreatefrompng($image_2);
        imagealphablending($image1, true);
        imagesavealpha($image1, true);
        imagecopy($image1, $image2, 0, 0, 0, 0, 100, 100);
        imagepng($image1, $destination_path);
    }
}
