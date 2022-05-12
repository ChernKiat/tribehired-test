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
        imagedestroy($image1);
        dd($image1);
    }


    public static function pasteAnImageOnAnotherImage($image, $directory)
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

    public static function maskARectangle($image, $directory, $x1, $y1, $x2, $y2, $red = 0, $green = 0, $blue = 0)
    {
        list($width, $height) = getimagesize("{$directory}\\{$image}");

        $mask = imagecreatetruecolor($width, $height);
        $black = imagecolorallocate($mask, $red, $green, $blue);

        $image = imagecreatefromjpeg("{$directory}\\{$image}");
        // $image = imagecreatefrompng("{$directory}\\{$image}");

        imagefilledrectangle($image, $x1, $y1, $x2, $y2, $black);

        header('Content-Type: image/png');
        imagepng($image, "{$directory}\\input\\nice_{$x1}_{$y1}.png");
        imagedestroy($image);
        // dd($image);
    }

    public static function changeAColorInBulk($image, $directory, $red1, $green1, $blue1, $red2, $green2, $blue2)
    {
        // $image = imagecreatefromjpeg("{$directory}\\{$image}");
        $image = imagecreatefrompng("{$directory}\\{$image}");

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
        imagepng($image, "{$directory}\\nice.png");
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

    public static function demo($image, $directory, $x = 2, $y = 2)
    {
        list($width, $height) = getimagesize("{$directory}\\{$image}");

        $min_x = floor($width / $x);
        $min_y = floor($height / $y);

        $array_x = array(0);
        $temp = 0;
        $balance = $width - ($min_x * $x);
        for ($i = 0; $i < $x; $i++) {
            $temp += $min_x;
            if ($i < $balance) {
                $temp++;
            }
            $array_x[] = $temp;
        }
        $array_y = array(0);
        $temp = 0;
        $balance = $height - ($min_y * $y);
        for ($i = 0; $i < $y; $i++) {
            $temp += $min_y;
            if ($i < $balance) {
                $temp++;
            }
            $array_y[] = $temp;
        }

        // $x = rand(0, $x - 1);
        // $y = rand(0, $y - 1);

        for ($i = 0; $i < $x; $i++) {
            for ($j = 0; $j < $y; $j++) {
                self::maskARectangle($image, $directory, $array_x[$i], $array_y[$j], $array_x[$i + 1], $array_y[$j + 1]);
            }
        }
    }
}
