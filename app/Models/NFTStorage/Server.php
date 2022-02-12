<?php
namespace App\Models\NFTStorage;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Http;
use GuzzleHttp\Client;
use GuzzleHttp\RedirectMiddleware;
use Log;

class Server extends Model
{
    // protected $connection = 'mysql';
    // protected $table = 'cryptobot_strategies';
    // protected $table = 'strategies';

    // protected $guarded = [];

    public static function getNFT($nfd_id)
    {
        $client = new Client(['allow_redirects' => ['track_redirects' => true]]);
        $response = $client->request('GET', env('MORALIS_SERVER_URL__ULTIMATE_NFT') . "functions/getNFT", [
            'ApplicationId'  => env('MORALIS_APPLICATION_ID__ULTIMATE_NFT'),
            'nfd_id'         => $nfd_id,
        ]);

        dd($response->getHeader(RedirectMiddleware::HISTORY_HEADER));

        $response = Http::get(env('MORALIS_SERVER_URL__ULTIMATE_NFT') . "functions/getNFT", [
            'ApplicationId'  => env('MORALIS_APPLICATION_ID__ULTIMATE_NFT'),
            'nfd_id'         => $nfd_id,
        ]);

        return $response->body();
    }
}
