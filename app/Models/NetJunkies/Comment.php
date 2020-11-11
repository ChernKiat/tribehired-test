<?php
namespace App\Models\NetJunkies;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    // protected $connection = 'mysql';
    protected $table = 'netjunkies_comments';
    // protected $table = 'comments';

    protected $fillable = [
    ];

    public function post()
    {
        return $this->belongsTo(Post::class, 'post_id', 'id');
    }
}
