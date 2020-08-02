<?php
namespace App\Http\Models\NetJunkies;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    // protected $connection = 'mysql';
    protected $table = 'netjunkies_posts';
    // protected $table = 'posts';

    protected $fillable = [
    ];

    public function comments()
    {
        return $this->hasMany(Comment::class, 'post_id', 'id');
    }
}
