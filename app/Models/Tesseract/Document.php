<?php

namespace App\Models\Tesseract;

use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    // protected $table = 'documents';

    public function pages()
    {
        return $this->hasMany(Page::class, 'document_id', 'id');
    }
}
