<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $fillable = ['name', 'display_name'];

    // Relasi ke User (Banyak role bisa dimiliki banyak user)
    public function users()
    {
        return $this->belongsToMany(User::class, 'role_user');
    }

    // Relasi ke Kategori melalui tabel permission
    public function categories()
    {
        return $this->belongsToMany(Category::class, 'category_role_permissions')
                    ->withPivot('can_view', 'can_download')
                    ->withTimestamps();
    }
}