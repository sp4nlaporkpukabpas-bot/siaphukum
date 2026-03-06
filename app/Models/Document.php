<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    protected $fillable = [
        'category_id', 'parent_id', 'name', 
        'document_number', 'document_date', 
        'upload_date', 'file_path'
    ];

    protected $casts = [
        'document_date' => 'date', // Ini akan otomatis mengubah string menjadi objek Carbon
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    // Jika dokumen ini adalah sub-dokumen (lampiran)
    public function parent()
    {
        return $this->belongsTo(Document::class, 'parent_id');
    }

    // Jika dokumen ini punya banyak lampiran
    public function children()
    {
        return $this->hasMany(Document::class, 'parent_id');
    }
}