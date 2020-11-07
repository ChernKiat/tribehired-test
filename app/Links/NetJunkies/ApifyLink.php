<?php

namespace App\Links\NetJunkies;

use App\Links\BaseLink;

class ApifyLink extends BaseLink
{
    const BASE_URL  = "https://api.apify.com";

    const FACEBOOK_ACTOR_PATH  = "v2/acts/pocesar~facebook-pages-scraper";

    public static function getActor()
    {
        $response = self::accessEndpoint()->request('GET', self::FACEBOOK_ACTOR_PATH, [
                        'query' => ['token' => env('APIFY_TOKEN')]
                    ]);
        dd(json_decode($response->getBody(), true));

        // return json_decode($response->getBody(), true);
        return array_column(json_decode($response->getBody(), true), null, 'id');
    }
}
