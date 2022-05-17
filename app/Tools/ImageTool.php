<?php

namespace App\Tools;

class ImageTool
{
    public static function combine2Images($image_1, $image_2)
    {
        $image1 = imagecreatefrompng($image_1);
        $image2 = imagecreatefrompng($image_2);
        imagealphablending($image1, true);
        imagesavealpha($image1, true);
        imagecopy($image1, $image2, 0, 0, 0, 0, 100, 100);
        imagepng($image1, $destination_path);
        imagedestroy($image1);
        dd($image1);
    }


    public static function pasteAnImageOnAnotherImage($image, $directory, $destination_path)
    {
        // transparent source to not transparent destination = destination
        // not transparent source to transparent destination = source
        // small source to big destination = black color for extra
        // big source to small destination = crop

        // $destination_image = imagecreatefromjpeg("{$directory}\\sweet.png");
        $source_image = imagecreatefrompng("{$directory}\\sweet.png");

        $destination_image = imagecreatefromjpeg("{$directory}\\{$image}");
        // $source_image = imagecreatefrompng("{$directory}\\{$image}");

        list($width, $height) = getimagesize("{$directory}\\{$image}");
        imagecopy($destination_image, $source_image, 0, 0, 0, 0, $width, $height);

        header('Content-Type: image/png');
        imagepng($destination_image, "{$directory}\\nice.png");
        imagedestroy($destination_image);
        dd($image);
    }

    public static function maskARectangle($image, $directory, $destination, $x1, $y1, $x2, $y2, $red = 0, $green = 0, $blue = 0)
    {
        $path = "{$directory}{$image}";
        $destination = $destination . pathinfo($image, PATHINFO_FILENAME);
        list($width, $height) = getimagesize($path);

        $mask = imagecreatetruecolor($width, $height);
        $black = imagecolorallocate($mask, $red, $green, $blue);

        switch (pathinfo($path, PATHINFO_EXTENSION)) {
            case 'png':
                $image = imagecreatefrompng($path);
                break;
            case 'jpg':
            case 'jpeg':
            default:
                $image = imagecreatefromjpeg($path);
                break;
        }

        imagefilledrectangle($image, $x1, $y1, $x2, $y2, $black);

        header('Content-Type: image/png');
        imagepng($image, "{$destination}_{$x1}_{$y1}.png");
        imagedestroy($image);
        // dd($image);
    }

    public static function changeAColorInBulk($image, $directory, $red1, $green1, $blue1, $red2, $green2, $blue2)
    {
        // $image = imagecreatefromjpeg("{$directory}{$image}");
        $image = imagecreatefrompng("{$directory}{$image}");

        if (!imageistruecolor($image))
            imagepalettetotruecolor($image);

        $column1 = (($red1 & 0xFF) << 16) + (($green1 & 0xFF) << 8) + ($blue1 & 0xFF);
        $column2 = (($red2 & 0xFF) << 16) + (($green2 & 0xFF) << 8) + ($blue2 & 0xFF);

        $width = imagesx($image);
        $height = imagesy($image);
        for ($x = 0; $x < $width; $x++)
            for ($y = 0; $y < $height; $y++)
            {
                $colrgb = imagecolorat($image, $x, $y);
                if ($column1 !== $colrgb)
                    continue;
                imagesetpixel($image, $x, $y, $column2);
            }

        header('Content-Type: image/png');
        imagepng($image, "{$directory}nice.png");
        imagedestroy($image);
        dd($image);
    }

    // public static function changeAColorInBulk($image, $directory)
    // {
    //     $image = imagecreatefromjpeg("{$directory}\\{$image}");
    //     // $image = imagecreatefrompng("{$directory}\\{$image}");

    //     $target = imagecolorexact($image, 231, 231, 231);
    //     imagecolorset($image, $target, 0, 0, 0);

    //     header('Content-Type: image/png');
    //     imagepng($image, "{$directory}\\nice.png");
    //     imagedestroy($image);
    //     dd($image);
    // }

    // public static function changeAColorInBulk($image, $directory)
    // {
    //     $image = imagecreatefromjpeg("{$directory}\\{$image}");
    //     // $image = imagecreatefrompng("{$directory}\\{$image}");

    //     $target = imagecolorexact($image, 231, 231, 231);
    //     imagecolorset($image, $target, 0, 0, 0);

    //     header('Content-Type: image/png');
    //     imagealphablending($image, false);
    //     imagesavealpha($image, true);
    //     imagepng($image, "{$directory}\\nice.png");
    //     imagedestroy($image);
    //     dd($image);

    //     header('Content-Type: image/png');

    //     /* RGB of your inside color */
    //     $rgb = array(0,0,255);
    //     /* Your file */
    //     $file="../test.png";

    //     /* Negative values, don't edit */
    //     $rgb = array(255-$rgb[0],255-$rgb[1],255-$rgb[2]);

    //     $im = imagecreatefrompng($file);

    //     imagefilter($im, IMG_FILTER_NEGATE);
    //     imagefilter($im, IMG_FILTER_COLORIZE, $rgb[0], $rgb[1], $rgb[2]);
    //     imagefilter($im, IMG_FILTER_NEGATE);
    // }
}
