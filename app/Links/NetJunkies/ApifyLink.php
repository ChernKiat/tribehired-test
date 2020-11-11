<?php

namespace App\Links\NetJunkies;

use App\Links\BaseLink;

class ApifyLink extends BaseLink
{
    const BASE_URL  = "https://api.apify.com";

    const FACEBOOK_ACTOR  = "pocesar~facebook-pages-scraper";

    const ACTOR_PATH  = "v2/acts";
    const DATASET_PATH  = "v2/datasets";
    const TASK_PATH  = "v2/actor-tasks";

    const ADD_RUN_PATH  = "/runs";
    const ADD_LAST_PATH  = "/last";

    public static function getActors()
    {
        $response = self::accessEndpoint()->request('GET', self::ACTOR_PATH . '/' . self::FACEBOOK_ACTOR, [
                        'query' => ['token' => env('APIFY_TOKEN')]
                    ]);
        dd(json_decode($response->getBody(), true));

        // return json_decode($response->getBody(), true);
        return array_column(json_decode($response->getBody(), true), null, 'id');
    }

    public static function getTasks()
    {
        $response = self::accessEndpoint()->request('GET', self::TASK_PATH, [
                        'query' => ['token' => env('APIFY_TOKEN')]
                    ]);
        $output = json_decode($response->getBody(), true);

        return array_column($output['data']['items'], null, 'id');
    }

    public static function getTask($actorTaskId)
    {
        $response = self::accessEndpoint()->request('GET', self::TASK_PATH . "/{$actorTaskId}" . self::ADD_RUN_PATH, [
                        'query' => ['token' => env('APIFY_TOKEN'), 'waitForFinish' => 60]
                    ]);
        $output = json_decode($response->getBody(), true);

        return $output['data']['items'];
    }

    public static function getLastDatasets()
    {
        $response = self::accessEndpoint()->request('GET', self::ACTOR_PATH . '/' . self::FACEBOOK_ACTOR . self::ADD_RUN_PATH . self::ADD_LAST_PATH . '/dataset', [
                        'query' => ['token' => env('APIFY_TOKEN')]
                    ]);
        dd(json_decode($response->getBody(), true));
    }

    public static function getDatasets()
    {
        $response = self::accessEndpoint()->request('GET', self::DATASET_PATH, [
                        'query' => ['token' => env('APIFY_TOKEN')]
                    ]);
        dd(json_decode($response->getBody(), true));
    // 'https://api.apify.com/v2/datasets/zbg3vVF3NnXGZfdsX/items?format=json&clean=1&unwind=posts&fields=posts,title,pageUrl';
    }

    public static function getDataset($datasetId)
    {
        $response = self::accessEndpoint()->request('GET', self::DATASET_PATH . "/{$datasetId}", [
                        'query' => ['token' => env('APIFY_TOKEN')]
                    ]);
        dd(json_decode($response->getBody(), true));
        $output = json_decode($response->getBody(), true);

        return $output['data'];
    }
}
