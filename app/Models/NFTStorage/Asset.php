<?php
namespace App\Models\NFTStorage;

use App\Tools\ImageTool;
use Illuminate\Database\Eloquent\Model;

class Asset extends Model
{
    // protected $connection = 'mysql';
    protected $table = 'nftstorage_assets';
    // protected $table = 'assets';

    const WALLET_DEFAULT_DEPLOY_ADDRESS  = '0xaa43e38158d656e2B366f4D25274606962c09D72';

    const TYPE_BLACK   = 1;
    const TYPE_CUSTOM  = 2;

    protected $guarded = [];

    public function multiverse()
    {
        return $this->belongsTo(Multiverse::class, 'nftstorage_multiverse_id', 'id');
    }

    public function getImageAttribute()
    {
        switch ($this->type) {
            case self::TYPE_BLACK:
                $first = $this->generateARandomImage(4);
                $second = $this->generateARandomImage(4);
                return public_path(Multiverse::PATH_RINKEBY_SERVER_FOLDER . "{$this->multiverse->id}/{$this->index}_b_{$first}_{$second}.{$this->extension}");
                break;
            case self::TYPE_CUSTOM:
                $randomCharacter = $this->generateARandomImage($this->states_total);
                return public_path(Multiverse::PATH_RINKEBY_SERVER_FOLDER . "{$this->multiverse->id}/{$this->index}_c_{$randomCharacter}.{$this->extension}");
                break;
            default:
                break;
        }
        return route('nftstorage.multiverse.image', ['sha256' => $this->sha256, 'multiverse' => $this->multiverse->name]);
    }

    public function getOriginalImageAttribute()
    {
        return public_path(Multiverse::PATH_RINKEBY_SERVER_FOLDER . "{$this->multiverse->name}_{$this->index}.{$this->extension}");
    }

    public function getMetaNameAttribute()
    {
        return "{$this->multiverse->name} #{$this->index}";
    }

    public function getMetaImageAttribute()
    {
        return route('nftstorage.multiverse.image', ['sha256' => $this->sha256, 'multiverse' => $this->multiverse->name]);
    }

    public function getMetaFilenameAttribute()
    {
        $filename = "0000000000000000000000000000000000000000000000000000000000000000{$this->hex}"; // 64x0
        $filename = substr($filename, strlen($this->hex));
        return public_path(Multiverse::PATH_RINKEBY_SERVER_FOLDER . "{$filename}.json");
    }

    public function getMetaAttribute()
    {
        return array(
            'name'          => $this->meta_name,
            'description'   => '', // self::PROJECT_DESCRIPTION,
            'image'         => $this->meta_image,
            // 'image_data'    => $this->meta_image,
            'external_url'  => route('nftstorage.multiverse.external', ['sha256' => $this->sha256, 'multiverse' => $this->multiverse->name]), // the static image
        );
    }

    public function generateARandomImage($length)
    {
        $characters = range(0, $length - 1);
        return $characters[rand(0, strlen($characters) - 1)];
    }

    public function generateBlackImages($image, $directory, $destination, $x = 2, $y = 2)
    {
        list($width, $height) = getimagesize("{$directory}{$image}");

        $min_x = floor($width / $x);
        $min_y = floor($height / $y);

        $array_x = array_fill(0, $x, $min_x);
        $balance = $width - ($min_x * $x);
        $temp = ceil($balance / 2);
        for ($i = 0; $i < $temp; $i++) {
            $array_x[$i]++;
        }
        $temp = floor($balance / 2);
        for ($i = $x - 1; $i > $x - $temp - 1; $i--) {
            $array_x[$i]++;
        }
        $balance = 0;
        $temp = array(0);
        foreach ($array_x as $value) {
            $balance += $value;
            $temp[] = $balance;
        }
        $array_x = $temp;
        $array_y = array_fill(0, $y, $min_y);
        $balance = $height - ($min_y * $y);
        $temp = ceil($balance / 2);
        for ($i = 0; $i < $temp; $i++) {
            $array_y[$i]++;
        }
        $temp = floor($balance / 2);
        for ($i = $y - 1; $i > $y - $temp - 1; $i--) {
            $array_y[$i]++;
        }
        $balance = 0;
        $temp = array(0);
        foreach ($array_y as $value) {
            $balance += $value;
            $temp[] = $balance;
        }
        $array_y = $temp;

        // $x = rand(0, $x - 1);
        // $y = rand(0, $y - 1);

        for ($i = 1; $i < $x + 1; $i++) {
            for ($j = 1; $j < $y + 1; $j++) {
                ImageTool::maskARectangle($image, $directory, $destination, $array_x, $array_y, $i, $j);
            }
        }
    }
}
