<?php
namespace App\Models\NFTStorage;

use App\Tools\ImageTool;
use Illuminate\Database\Eloquent\Model;
use Log;

class Maneki extends Model
{
    // protected $connection = 'mysql';
    protected $table = 'nftstorage_manekis';
    // protected $table = 'manekis';

    // protected $guarded = [];

    public static $project_name  = 'Maneki Zodiac';
    public static $project_description  = '';

    const TYPE_ADAM     = 0;
    const TYPE_EVE      = 1;
    const TYPE_SERPENT  = 2;
    const TYPE_ZODIAC   = 3;
    const TYPE_COUPLE   = 4;
    const TYPE_GENESIS  = 5;

    const TYPE_TOTAL_LIST = array(
        self::TYPE_ADAM     => 1,
        self::TYPE_EVE      => 1,
        self::TYPE_SERPENT  => 1,
        self::TYPE_ZODIAC   => 12,
        self::TYPE_COUPLE   => 66,
        self::TYPE_GENESIS  => 924,
    );

    const ZODIAC_RAT      = '0';
    const ZODIAC_OX       = '1';
    const ZODIAC_TIGER    = '2';
    const ZODIAC_RABBIT   = '3';
    const ZODIAC_DRAGON   = '4';
    const ZODIAC_SNAKE    = '5';
    const ZODIAC_HORSE    = '6';
    const ZODIAC_GOAT     = '7';
    const ZODIAC_MONKEY   = '8';
    const ZODIAC_ROOSTER  = '9';
    const ZODIAC_DOG      = 'a';
    const ZODIAC_PIG      = 'b';

    const ZODIAC_ORDER_LIST = array(
        self::ZODIAC_RAT,
        self::ZODIAC_OX,
        self::ZODIAC_TIGER,
        self::ZODIAC_RABBIT,
        self::ZODIAC_DRAGON,
        self::ZODIAC_SNAKE,
        self::ZODIAC_HORSE,
        self::ZODIAC_GOAT,
        self::ZODIAC_MONKEY,
        self::ZODIAC_ROOSTER,
        self::ZODIAC_DOG,
        self::ZODIAC_PIG,
    );

    const ZODIAC_ALL  = self::ZODIAC_RAT . self::ZODIAC_OX . self::ZODIAC_TIGER . self::ZODIAC_RABBIT . self::ZODIAC_DRAGON . self::ZODIAC_SNAKE . self::ZODIAC_HORSE . self::ZODIAC_GOAT . self::ZODIAC_MONKEY . self::ZODIAC_ROOSTER . self::ZODIAC_DOG . self::ZODIAC_PIG;

    public function pending()
    {
        // the static image route

        // the zodiac image
    }

    public function getMetaNameAttribute()
    {
        return self::$project_name . " #{$this->index}";
    }

    public function getMetaImageAttribute()
    {
        return route('nftstorage.maneki.image', ['sha256' => $this->sha256, 'index' => $this->index]);
    }

    public function getMetaFilenameAttribute()
    {
        $filename = "0000000000000000000000000000000000000000000000000000000000000000{$this->index}"; // 64x0
        $filename = substr($filename, strlen($this->index));
        return public_path("/myNFTStorage/Rinkeby Server (Ultimate NFT)/{$filename}.json");
    }

    public function getMetaAttribute()
    {
        return array(
            'name'          => $this->meta_name,
            'image'         => $this->meta_image,
            // 'image_data'    => $this->meta_image,
            'description'   => self::$project_description,
            'external_url'  => 'https://sealkingdom.xyz/nft-storage/alpha-static', // the static image
        );
    }

    public function getImageAttribute()
    {
        switch ($this->type) {
            case self::TYPE_ADAM:
                return response()->file(public_path('/myNFTStorage/input/adam.png'));
                break;
            case self::TYPE_EVE:
                $randomCharacter = $this->generateARandomCharacter();
                return response()->file(public_path("/myNFTStorage/input/eve_{$randomCharacter}.png"));
                break;
            case self::TYPE_SERPENT:
                $randomCharacter = $this->generateARandomCharacter();
                return response()->file(public_path("/myNFTStorage/input/serpent_{$randomCharacter}.png"));
                break;
            case self::TYPE_ZODIAC:
                // ?
                break;
            case self::TYPE_COUPLE:
                $rightCharacter = $this->maneki[0];
                $leftCharacter = $this->maneki[1];
                $topRandomCharacter = $this->generateARandomCharacter();
                return response()->file(public_path("/myNFTStorage/input/couple_{$rightCharacter}_{$leftCharacter}_{$topRandomCharacter}.png"));
                break;
            case self::TYPE_GENESIS:
                $rightRandomCharacter = $this->generateARandomCharacter($this->maneki);
                $leftRandomCharacter = $this->generateARandomCharacter($this->maneki . $rightRandomCharacter);
                return response()->file(public_path("/myNFTStorage/input/genesis_{$rightRandomCharacter}_{$leftRandomCharacter}.png"));
                break;
            default:
                break;
        }
        return route('nftstorage.maneki.image', ['sha256' => $this->sha256, 'index' => $this->index]);
    }

    public function generateARandomCharacter($offset = '') {
        $characters = preg_replace("/[{$offset}]/", '', self::ZODIAC_ALL);
        $charactersLength = strlen($characters);
        return $characters[rand(0, $charactersLength - 1)];
    }

    public static function generateBaseImages()
    {
        for ($i = 0; $i < self::ZODIAC_ORDER_LIST; $i++) {
            // TYPE_EVE

            // TYPE_SERPENT

            for ($j = 0; $j < self::ZODIAC_ORDER_LIST; $j++) {
                ImageTool::combine2Images(public_path("myNFTStorage\input\right_{$i}.png"), public_path("myNFTStorage\input\left_{$j}.png"), public_path("myNFTStorage\output\genesis_{$i}_{$j}.png"));

                for ($k = 0; $k < self::ZODIAC_ORDER_LIST; $k++) {
                    ImageTool::combine2Images(public_path("myNFTStorage\input\genesis_{$i}_{$j}.png"), public_path("myNFTStorage\input\top_{$k}.png"), public_path("myNFTStorage\output\couple_{$i}_{$j}_{$k}.png"));
                }
            }
        }
    }

    public static function seed()
    {
        $index = 0;
        $coupleIndexList = array();
        foreach (self::ZODIAC_ORDER_LIST as $i => $right) {
            foreach (self::ZODIAC_ORDER_LIST as $j => $left) {
                if ($i < $j) {
                    $coupleIndexList[] = $right . $left;
                }
            }
        }
        $genesisIndexList = array();
        foreach (self::ZODIAC_ORDER_LIST as $i => $first) {
            foreach (self::ZODIAC_ORDER_LIST as $j => $second) {
                foreach (self::ZODIAC_ORDER_LIST as $k => $third) {
                    foreach (self::ZODIAC_ORDER_LIST as $l => $fourth) {
                        foreach (self::ZODIAC_ORDER_LIST as $m => $fifth) {
                            foreach (self::ZODIAC_ORDER_LIST as $n => $sixth) {
                                if ($i < $j && $j < $k && $k < $l && $l < $m && $m < $n) {
                                    $genesisIndexList[] = $first . $second . $third . $fourth . $fifth . $sixth;
                                }
                            }
                        }
                    }
                }
            }
        }

        foreach (self::TYPE_TOTAL_LIST as $key => $value) {
            for ($i = 0; $i < $value; $i++) {
                switch ($key) {
                    case self::TYPE_ADAM:
                    case self::TYPE_EVE:
                    case self::TYPE_SERPENT:
                        $maneki = null;
                        $sha256 = null;
                        break;
                    case self::TYPE_ZODIAC:
                        $maneki = self::ZODIAC_ORDER_LIST[$i];
                        $sha256 = hash('sha256', $maneki);
                        break;
                    case self::TYPE_COUPLE:
                        $maneki = $coupleIndexList[$i];
                        $sha256 = hash('sha256', $maneki);
                        break;
                    case self::TYPE_GENESIS:
                        $maneki = $genesisIndexList[$i];
                        $sha256 = hash('sha256', $maneki);
                        break;
                    default:
                        break;
                }

                $maneki = self::updateOrCreate([
                    'index'  => $index,
                ], [
                    'index'   => $index++,
                    'sha256'  => $sha256,
                    'type'    => $key,
                    'unit'    => $i,
                    'maneki'  => $maneki,
                ]);
            }
        }
    }

    public static function generateBaseMetas()
    {
        $manekis = self::get();
        foreach ($manekis as $maneki) {
            FileTool::createAFile($maneki->meta_filename, json_encode($maneki->meta, JSON_UNESCAPED_SLASHES));
        }
    }
}
