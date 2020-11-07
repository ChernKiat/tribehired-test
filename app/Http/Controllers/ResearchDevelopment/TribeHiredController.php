<?php
namespace App\Http\Controllers\Test;

use App\Http\Controllers\Controller;
use App\Tools\Test\TribeHiredAPITool;
use Illuminate\Http\Request;

class TribeHiredController extends Controller
{
    public function one()
    {
        $posts = TribeHiredAPITool::getPostsIndexWithComments();

        $output = array();
        foreach ($posts as $post) {
            $output[] = array(
                'post_id'                   => $post['id'],
                'post_title'                => $post['title'],
                'post_body'                 => $post['body'],
                'total_number_of_comments'  => $post['total_comments'],
            );
        }

        return json_encode($output);
    }

    public function two(Request $request)
    {
        $posts = TribeHiredAPITool::getPostsIndexWithComments($request->input('search'));

        $output = array();
        foreach ($posts as $post) {
            $output[] = array(
                'post_id'                   => $post['id'],
                'post_title'                => $post['title'],
                'post_body'                 => $post['body'],
                'total_number_of_comments'  => $post['total_comments'],
            );
        }

        return json_encode($output);
    }
}
