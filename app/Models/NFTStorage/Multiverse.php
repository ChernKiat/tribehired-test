<?php
namespace App\Models\NFTStorage;

use App\Tools\FileTool;
use App\Tools\StringTool;
use Illuminate\Database\Eloquent\Model;

class Multiverse extends Model
{
    // protected $connection = 'mysql';
    protected $table = 'nftstorage_multiverses';
    // protected $table = 'multiverses';

    protected $guarded = [];

    const WALLET_DEFAULT_DEPLOY_ADDRESS  = '0xaa43e38158d656e2B366f4D25274606962c09D72';

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

    public function generateBaseImages()
    {

    }
}
