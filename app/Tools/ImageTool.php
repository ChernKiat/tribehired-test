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


    public static function pasteAnImageOnAnotherImage($image_1, $image_2, $source, $destination, $is_single = true, $is_first = null, $is_last = null)
    {
        // transparent source to not transparent destination = destination
        // not transparent source to transparent destination = source
        // small source to big destination = black color for extra
        // big source to small destination = crop

        if ($is_single || (!$is_single && $is_first)) {
            $image_1 = "{$source}{$image_1}";
        }
        $image_2 = "{$source}{$image_2}";
        $destination .= pathinfo($image_1, PATHINFO_FILENAME) . '_' . pathinfo($image_2, PATHINFO_FILENAME) . '.png';
        list($width, $height) = getimagesize($image_2);

        foreach (['image_1', 'image_2'] as $value) {
            if (!$is_single && !$is_first && $value == 'image_1') { continue; }
            switch (pathinfo(${$value}, PATHINFO_EXTENSION)) {
                case 'png':
                    ${$value} = imagecreatefrompng(${$value});
                    break;
                case 'jpg':
                case 'jpeg':
                default:
                    ${$value} = imagecreatefromjpeg(${$value});
                    break;
            }
            imagealphablending(${$value}, true);
            imagesavealpha(${$value}, true);
        }

        imagecopy($image_1, $image_2, 0, 0, 0, 0, $width, $height);

        if ($is_single || (!$is_single && $is_last)) {
            header('Content-Type: image/png');
            imagepng($image_1, $destination);
            imagedestroy($image_1);
        } else {
            return $image_1;
        }
        // dd($image);
    }

    public static function pasteMultipleImagesOnAnImage($images, $source, $destination)
    {
        $temp = $images[0];
        foreach ($images as $key => $image) {
            if ($key == 0) { continue; }
            if ($key == 1) {
                $temp = self::pasteAnImageOnAnotherImage($temp, $image, $source, $destination, false, true, false);
            } elseif ($key == (count($images) - 1)) {
                $temp = self::pasteAnImageOnAnotherImage($temp, $image, $source, $destination, false, false, true);
            } else {
                $temp = self::pasteAnImageOnAnotherImage($temp, $image, $source, $destination, false, false, false);
            }
        }
    }

    public static function maskARectangle($image, $directory, $destination, $array_x, $array_y, $x, $y, $red = 0, $green = 0, $blue = 0)
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

        imagefilledrectangle($image, $array_x[$y - 1], $array_y[$x - 1], $array_x[$y], $array_y[$x], $black);

        header('Content-Type: image/png');
        imagepng($image, "{$destination}_b_{$x}_{$y}.png");
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
