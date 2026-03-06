<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class CategoryRolePermission extends Pivot
{
    protected $table = 'category_role_permissions';
    
    // Pastikan casting ke boolean agar tidak terbaca sebagai integer 0/1
    protected $casts = [
        'can_view' => 'boolean',
        'can_download' => 'boolean',
    ];
}