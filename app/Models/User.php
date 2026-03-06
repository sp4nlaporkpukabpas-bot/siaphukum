<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = ['name', 'nip', 'username', 'password', 'active_role_id'];

    // Relasi ke semua role yang dimiliki
    public function roles()
    {
        return $this->belongsToMany(Role::class, 'role_user');
    }

    // Relasi ke role yang sedang aktif
    public function activeRole()
    {
        return $this->belongsTo(Role::class, 'active_role_id');
    }

    /**
     * Helper untuk cek izin akses berdasarkan role yang sedang AKTIF
     * Contoh penggunaan: if(auth()->user()->canAccessCategory($catId, 'download'))
     */
    public function canAccessCategory($categoryId, $permissionType = 'view')
    {
        if (!$this->active_role_id) return false;

        $permission = \DB::table('category_role_permissions')
            ->where('role_id', $this->active_role_id)
            ->where('category_id', $categoryId)
            ->first();

        if (!$permission) return false;

        return $permissionType === 'download' ? $permission->can_download : $permission->can_view;
    }
}