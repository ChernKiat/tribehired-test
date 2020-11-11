<?php
namespace App\Models\NetJunkies;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    // protected $connection = 'mysql';
    protected $table = 'netjunkies_posts';
    // protected $table = 'posts';

    const SOURCE_OTHERS    = 0;
    const SOURCE_FACEBOOK  = 1;
    const SOURCE_REDDIT    = 3;

    const CRAWLER_STATUS_ERROR    = 0;
    const CRAWLER_STATUS_SUCCESS  = 1;
    const CRAWLER_STATUS_RUN      = 3;

    const MAIN_TYPE_TEXT   = 1;
    const MAIN_TYPE_IMAGE  = 2;

    protected $fillable = [
    ];

    public function comments()
    {
        return $this->hasMany(Comment::class, 'post_id', 'id');
    }

    public function setPostSource()
    {
        if (!empty($this->url)) {
            if (strpos($this->url, 'facebook') !== false) {
                $this->source = self::SOURCE_FACEBOOK;
            } elseif (strpos($this->url, 'reddit') !== false) {
                $this->source = self::SOURCE_REDDIT;
            } else {
                $this->source = self::SOURCE_OTHERS;
            }
        } else {
            $this->source = null;
        }
    }
}
