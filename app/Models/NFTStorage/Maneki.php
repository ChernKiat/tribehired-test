<?php
namespace App\Models\NFTStorage;

use App\Tools\FileTool;
use App\Tools\ImageTool;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Http;
use Config;
use Log;

class Maneki extends Model
{
    // protected $connection = 'mysql';
    protected $table = 'nftstorage_manekis';
    // protected $table = 'manekis';

    protected $guarded = [];

    const PROJECT_NAME             = 'Maneki Zodiac';
    const PROJECT_DESCRIPTION      = '12 maneki chinese zodiac spirits who come from another dimension which destined for bringing good fortune to the correct wallet!';
    const PROJECT_TRANSACTION_FEE  = 0; // 100 = a 1% seller fee

    // const NETWORK_ETHEREUM_MAINNET  = 0;
    // const NETWORK_RINKEBY_TESTNET   = 1;

    // const WALLET_DEPLOY_ADDRESS_LIST = array(
    //     self::NETWORK_ETHEREUM_MAINNET  => '0xaa43e38158d656e2B366f4D25274606962c09D72',
    //     self::NETWORK_RINKEBY_TESTNET   => '0xaa43e38158d656e2B366f4D25274606962c09D72',
    // );

    const WALLET_DEPLOY_ADDRESS  = '0xaa43e38158d656e2B366f4D25274606962c09D72';

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

    public function getImageDemoAttribute()
    {
        switch ($this->index) {
            case self::TYPE_ADAM:
                return public_path('/myNFTStorage/Rinkeby Server (Ultimate NFT)/0.png');
                break;
            case self::TYPE_EVE:
                return public_path('/myNFTStorage/Rinkeby Server (Ultimate NFT)/2.png');
                break;
            case self::TYPE_SERPENT:
                return public_path('/myNFTStorage/Rinkeby Server (Ultimate NFT)/3.png');
                break;
            case $this->index > 2:
                return public_path('/myNFTStorage/Rinkeby Server (Ultimate NFT)/b.png');
                break;
            default:
                break;
        }
    }

    public function getImageAttribute()
    {
        switch ($this->type) {
            case self::TYPE_ADAM:
                return public_path('/myNFTStorage/input/adam.png');
                break;
            case self::TYPE_EVE:
                $randomCharacter = $this->generateARandomCharacter();
                return public_path("/myNFTStorage/input/eve_{$randomCharacter}.png");
                break;
            case self::TYPE_SERPENT:
                $randomCharacter = $this->generateARandomCharacter();
                return public_path("/myNFTStorage/input/serpent_{$randomCharacter}.png");
                break;
            case self::TYPE_ZODIAC:
                // ?
                break;
            case self::TYPE_COUPLE:
                $rightCharacter = $this->maneki[0];
                $leftCharacter = $this->maneki[1];
                $topRandomCharacter = $this->generateARandomCharacter();
                return public_path("/myNFTStorage/input/couple_{$rightCharacter}_{$leftCharacter}_{$topRandomCharacter}.png");
                break;
            case self::TYPE_GENESIS:
                $rightRandomCharacter = $this->generateARandomCharacter($this->maneki);
                $leftRandomCharacter = $this->generateARandomCharacter($this->maneki . $rightRandomCharacter);
                return public_path("/myNFTStorage/input/genesis_{$rightRandomCharacter}_{$leftRandomCharacter}.png");
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

    public function getMetaNameAttribute()
    {
        return self::PROJECT_NAME . " #{$this->index}";
    }

    public function getMetaImageAttribute()
    {
        return route('nftstorage.maneki.image', ['sha256' => $this->sha256, 'index' => $this->index]);
    }

    public function getMetaFilenameAttribute()
    {
        $filename = "0000000000000000000000000000000000000000000000000000000000000000{$this->hex}"; // 64x0
        $filename = substr($filename, strlen($this->index));
        return public_path("/myNFTStorage/Rinkeby Server (Ultimate NFT)/{$filename}.json");
    }

    public function getMetaAttribute()
    {
        return array(
            'name'          => $this->meta_name,
            'description'   => '', // self::PROJECT_DESCRIPTION,
            'image'         => $this->meta_image,
            // 'image_data'    => $this->meta_image,
            'external_url'  => route('nftstorage.maneki.static', ['maneki' => $this->maneki]), // the static image
        );
    }

    public static function generateContractMeta()
    {
        FileTool::createAFile(public_path('/myNFTStorage/Rinkeby Server (Ultimate NFT)/contract.json'), json_encode(array(
            'name'                     => self::PROJECT_NAME,
            'description'              => self::PROJECT_DESCRIPTION,
            'image'                    => route('nftstorage.maneki.main') . '/myNFTStorage/Rinkeby Server (Ultimate NFT)/contract.png',
            'external_url'             => route('nftstorage.maneki.main'),
            'seller_fee_basis_points'  => self::PROJECT_TRANSACTION_FEE,
            'fee_recipient'            => self::WALLET_DEPLOY_ADDRESS,
        ), JSON_UNESCAPED_SLASHES));
    }

    public static function generateMetas()
    {
        $manekis = self::get();
        foreach ($manekis as $maneki) {
            FileTool::createAFile($maneki->meta_filename, json_encode($maneki->meta, JSON_UNESCAPED_SLASHES));
        }
    }

    public static function refreshMetas()
    {
        $manekis = self::get();
        foreach ($manekis as $maneki) {
            $response = Http::get("https://testnets-api.opensea.io/api/v1/asset/0x15e1a50b319864144d92ce281c68f4a176ae69a9/{$maneki->index}", [
            // $response = Http::get("https://api.opensea.io/api/v1/asset/0x15e1a50b319864144d92ce281c68f4a176ae69a9/{$maneki->index}", [
                'force_update' => 'true',
            ]);
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
                        $maneki = 'adam';
                        $sha256 = hash('sha256', 'adam');
                        break;
                    case self::TYPE_EVE:
                        $maneki = 'eve';
                        $sha256 = hash('sha256', 'eve');
                        break;
                    case self::TYPE_SERPENT:
                        $maneki = 'serpent';
                        $sha256 = hash('sha256', 'serpent');
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
                    'index'   => $index,
                    'hex'     => dechex($index++),
                    'unit'    => $i,
                    'maneki'  => $maneki,
                    'sha256'  => $sha256,
                    'type'    => $key,
                ]);
            }
        }
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
}

