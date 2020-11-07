<?php

namespace App\Links\Test;

use App\Links\BaseLink;

class TribeHiredLink extends BaseLink
{
    const BASE_URL  = "https://jsonplaceholder.typicode.com";

    const POSTS_ENDPOINT  = "posts";
    const COMMENTS_ENDPOINT  = "comments";

    public static function getPostsIndex()
    {
        $response = self::accessEndpoint()->request('GET', self::POSTS_ENDPOINT);

        // return json_decode($response->getBody(), true);
        return array_column(json_decode($response->getBody(), true), null, 'id');
    }

    public static function getPostShow($id)
    {
        $response = self::accessEndpoint()->request('GET', self::POSTS_ENDPOINT . "/{$id}");

        return json_decode($response->getBody(), true);
    }

    public static function getCommentsIndex($filter = '')
    {
        $response = self::accessEndpoint()->request('GET', self::COMMENTS_ENDPOINT);

        $comments = json_decode($response->getBody(), true);
        if (!empty($filter)) {
            foreach ($comments as $key => $comment) {
                if (strpos($comment['name'], $filter) === false &&
                    strpos($comment['email'], $filter) === false &&
                    strpos($comment['body'], $filter) === false) {
                    unset($comments[$key]);
                }
            }
        }

        return $comments;
    }

    public static function getPostsIndexWithComments($filter = '')
    {
        $posts = self::getPostsIndex();
        $comments = self::getCommentsIndex($filter);

        // bind comments
        foreach ($comments as $comment) {
            $posts[$comment['postId']]['comments'][] = $comment;
        }
        // count comments
        foreach ($posts as $postsKey => $post) {
            if (!isset($post['comments'])) {
                $posts[$postsKey]['comments'] = array();
            }
            $posts[$postsKey]['total_comments'] = count($posts[$postsKey]['comments']);
        }
        // sort posts
        usort($posts, function($a, $b) { return $b['total_comments'] - $a['total_comments']; });

        return $posts;
    }
}
