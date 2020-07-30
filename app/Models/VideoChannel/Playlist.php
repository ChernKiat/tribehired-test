<?php

namespace App\Models\VideoChannel;

use Illuminate\Database\Eloquent\Model;

class Playlist extends Model
{
    protected $table = 'playlists';

    protected $fillable = [
        'name',
        'platform',
    ];

    public function videos()
    {
        return $this->hasMany(Video::class, 'playlist_id', 'id');
    }
}
