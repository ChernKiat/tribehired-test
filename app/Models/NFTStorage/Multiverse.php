<?php
namespace App\Models\NFTStorage;

use App\Tools\FileTool;
use App\Tools\ImageTool;
use App\Tools\StringTool;
use Illuminate\Database\Eloquent\Model;

class Multiverse extends Model
{
    // protected $connection = 'mysql';
    protected $table = 'nftstorage_multiverses';
    // protected $table = 'multiverses';

    protected $guarded = [];

    const PROJECT_MULTIVERSE  = 1;

    const WALLET_DEFAULT_DEPLOY_ADDRESS  = '0xaa43e38158d656e2B366f4D25274606962c09D72';

    const PATH_IMAGE_FOLDER  = '/myNFTStorage/image/';
    const PATH_INPUT_FOLDER  = '/myNFTStorage/input/';
    const PATH_RINKEBY_SERVER_FOLDER  = '/myNFTStorage/Rinkeby Server/';


    public function assets()
    {
        return $this->hasMany(Asset::class, 'nftstorage_multiverse_id', 'id');
    }

    public function asset()
    {
        return $this->hasOne(Asset::class, 'nftstorage_multiverse_id', 'id')->latest();
    }

    public function generateContractMeta()
    {
        FileTool::createAFile(public_path(self::PATH_RINKEBY_SERVER_FOLDER . 'contract.json'), json_encode(array(
            'name'                     => $this->name,
            'description'              => $this->description,
            'image'                    => route('nftstorage.multiverse.main') . self::PATH_RINKEBY_SERVER_FOLDER . 'contract.png',
            'external_url'             => route('nftstorage.multiverse.main'),
            'seller_fee_basis_points'  => $this->transaction_fee,
            'fee_recipient'            => $this->wallet_address ?? self::WALLET_DEFAULT_DEPLOY_ADDRESS,
        ), JSON_UNESCAPED_SLASHES));
    }

    public function generateMetas()
    {
        foreach ($this->assets as $asset) {
            FileTool::createAFile($asset->meta_filename, json_encode($asset->meta, JSON_UNESCAPED_SLASHES));
        }
    }

    public function refreshMetas()
    {
        foreach (self::get() as $asset) {
            Http::get("https://testnets-api.opensea.io/api/v1/asset/0x15e1a50b319864144d92ce281c68f4a176ae69a9/{$asset->index}", [
            // Http::get("https://api.opensea.io/api/v1/asset/0x15e1a50b319864144d92ce281c68f4a176ae69a9/{$asset->index}", [
                'force_update' => 'true',
            ]);
        }
    }

    public static function seed()
    {
        $multiverse = self::updateOrCreate([
            'name'             => 'Maneki Zodiac',
        ], [
            'name'             => 'Maneki Zodiac',
            'description'      => '',
            'transaction_fee'  => 0,
            'wallet_address'   => self::WALLET_DEFAULT_DEPLOY_ADDRESS,
        ]);

        $totalList = array(
            'adam'     => 1,
            'eve'      => 1,
            'serpent'  => 1,
            'zodiac'   => 12,
            'couple'   => 66,
            'genesis'  => 924,
        );
        $index = 0;
        foreach ($totalList as $key => $value) {
            for ($i = 0; $i < $value; $i++) {
                $random = StringTool::randomGenerator('letter');
                switch ($key) {
                    case 'adam':
                        $sha256 = hash('sha256', "{$index}_{$random}");
                        $total = 1;
                        break;
                    case 'eve':
                    case 'serpent':
                        $sha256 = hash('sha256', "{$index}_{$random}");
                        $total = 12;
                        break;
                    case 'zodiac':
                        $sha256 = hash('sha256', "{$index}_{$random}");
                        $total = 22;
                        break;
                    case 'couple':
                        $sha256 = hash('sha256', "{$index}_{$random}");
                        $total = 10;
                        break;
                    case 'genesis':
                        $sha256 = hash('sha256', "{$index}_{$random}");
                        $total = 20;
                        break;
                    default:
                        break;
                }

                $asset = Asset::updateOrCreate([
                    'nftstorage_multiverse_id'  => $multiverse->id,
                    'index'                     => $index,
                ], [
                    'nftstorage_multiverse_id'  => $multiverse->id,
                    'index'                     => $index,
                    'hex'                       => dechex($index++),
                    'unit'                      => $i,
                    'states_total'              => $total,
                    'random'                    => $random,
                    'extension'                 => 'png',
                    'sha256'                    => $sha256,
                    'type'                      => Asset::TYPE_CUSTOM,
                ]);
            }
        }
    }

    public function generateBlackImages()
    {
        // foreach ($this->assets as $asset) {
            $this->asset->generateBlackImages('8.png', public_path(self::PATH_INPUT_FOLDER), public_path(self::PATH_IMAGE_FOLDER . "{$this->id}/"), 4, 4);
            // $asset->generateBlackImages($this->asset->original_image "{$this->name}_{$this->index}_b", public_path(self::PATH_INPUT_FOLDER), public_path(self::PATH_IMAGE_FOLDER . "{$this->id}/"), 4, 4);...........
        // }
    }

    public function generateBaseCustomImages()
    {
        // foreach (['0', '1', '2', '3', '4', '5', '6', '7', '8', '9', 'a', 'b'] as $key => $i) {
        //     foreach (['0', '1', '2', '3', '4', '5', '6', '7', '8', '9', 'a', 'b'] as $index => $j) {
        //         if ($key < $index) {
        //             // ImageTool::pasteAnImageOnAnotherImage("{$i}.png", "{$j}.png", public_path(self::PATH_INPUT_FOLDER), public_path(self::PATH_IMAGE_FOLDER . Multiverse::PROJECT_MULTIVERSE . '/'));
        //             ImageTool::pasteMultipleImagesOnAnImage(["{$i}.png", "{$j}.png", 'z.png'], public_path(self::PATH_INPUT_FOLDER), public_path(self::PATH_IMAGE_FOLDER . Multiverse::PROJECT_MULTIVERSE . '/'), ['z']);
        //         }
        //         foreach (['0', '1', '2', '3', '4', '5', '6', '7', '8', '9', 'a', 'b'] as $token => $k) {
        //             if ($key < $index && $index < $token) {
        //                 ImageTool::pasteMultipleImagesOnAnImage(["{$i}_{$j}.png", "{$k}.png", 'z.png'], public_path(self::PATH_INPUT_FOLDER), public_path(self::PATH_IMAGE_FOLDER . Multiverse::PROJECT_MULTIVERSE . '/'), ['z']);
        //             }
        //         }
        //     }
        // }

        // foreach ([
        //     ['1_2.png', '3_4_5.png', '6_7_8.png', '9_a_b.png', 'z.png'],
        //     ['0_2.png', '3_4_5.png', '6_7_8.png', '9_a_b.png', 'z.png'],
        //     ['0_1.png', '3_4_5.png', '6_7_8.png', '9_a_b.png', 'z.png'],
        //     ['0_1_2.png', '4_5.png', '6_7_8.png', '9_a_b.png', 'z.png'],
        //     ['0_1_2.png', '3_5.png', '6_7_8.png', '9_a_b.png', 'z.png'],
        //     ['0_1_2.png', '3_4.png', '6_7_8.png', '9_a_b.png', 'z.png'],
        //     ['0_1_2.png', '3_4_5.png', '7_8.png', '9_a_b.png', 'z.png'],
        //     ['0_1_2.png', '3_4_5.png', '6_8.png', '9_a_b.png', 'z.png'],
        //     ['0_1_2.png', '3_4_5.png', '6_7.png', '9_a_b.png', 'z.png'],
        //     ['0_1_2.png', '3_4_5.png', '6_7_8.png', 'a_b.png', 'z.png'],
        //     ['0_1_2.png', '3_4_5.png', '6_7_8.png', '9_b.png', 'z.png'],
        //     ['0_1_2.png', '3_4_5.png', '6_7_8.png', '9_a.png', 'z.png'],
        // ] as $value) {
        //     ImageTool::pasteMultipleImagesOnAnImage($value, public_path(self::PATH_INPUT_FOLDER), public_path(self::PATH_IMAGE_FOLDER . Multiverse::PROJECT_MULTIVERSE . '/'), ['z']);
        // }
    }

    public function generateCustomImages()
    {
        foreach ($this->assets as $asset) {
            switch ($asset->index) {
                case 0:
                    $sha256 = hash('sha256', "{$index}_{$random}");
                    break;
                case 'eve':
                case 'serpent':
                    $sha256 = hash('sha256', "{$index}_{$random}");
                    break;
                case 'zodiac':
                    $sha256 = hash('sha256', "{$index}_{$random}");
                    break;
                case 'couple':
                    $sha256 = hash('sha256', "{$index}_{$random}");
                    break;
                case 'genesis':
                    $sha256 = hash('sha256', "{$index}_{$random}");
                    break;
                default:
                    break;
            }
        }
        $totalList = array(
            'adam'     => 1,
            'eve'      => 12,
            'serpent'  => 12,
            'zodiac'   => 22,
            'couple'   => 10,
            'genesis'  => 20,
        );
        $index = 0;
        foreach ($totalList as $key => $value) {

        }
    }
}
