<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = ['name'];

    // Relasi ke Dokumen (Satu kategori punya banyak dokumen)
    public function documents()
    {
        return $this->hasMany(Document::class);
    }

    // Relasi ke Role untuk melihat pengaturan izin
    public function roles()
    {
        return $this->belongsToMany(Role::class, 'category_role_permissions')
                    ->withPivot('can_view', 'can_download')
                    ->withTimestamps();
    }
}