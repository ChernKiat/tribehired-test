<?php
namespace App\Models\NFTStorage;

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
                $randomCharacter = $this->generateARandomImage(16);
                return public_path(Multiverse::PATH_RINKEBY_SERVER_FOLDER . "{$this->multiverse->name}_{$this->index}_b{$randomCharacter}.{$this->extension}");
                break;
            case self::TYPE_CUSTOM:
                $randomCharacter = $this->generateARandomImage($this->states_total);
                return public_path(Multiverse::PATH_RINKEBY_SERVER_FOLDER . "{$this->multiverse->name}_{$this->index}_c{$randomCharacter}.{$this->extension}");
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

    public function generateARandomImage($length)
    {
        $characters = range(0, $length - 1);
        return $characters[rand(0, strlen($characters) - 1)];
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

}
