<?php

namespace App\Models\Tesseract;

use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    // protected $table = 'pages';

    public function document()
    {
        return $this->belongsTo(Document::class, 'document_id', 'id');
    }
}
