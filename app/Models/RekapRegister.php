<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RekapRegister extends Model
{
    protected $fillable = [
        'nama_rekap',
        'tahun',
        'link_dokumen',
        'is_visible',
    ];

    protected $casts = [
        'is_visible' => 'boolean',
    ];
}