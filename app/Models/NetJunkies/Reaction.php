<?php
namespace App\Http\Models\NetJunkies;

use Illuminate\Database\Eloquent\Model;

class Reaction extends Model
{
    // protected $connection = 'mysql';
    protected $table = 'netjunkies_reactions';
    // protected $table = 'reactions';

    protected $fillable = [
    ];

    public function comment()
    {
        return $this->belongsTo(Comment::class, 'comment_id', 'id');
    }
}
