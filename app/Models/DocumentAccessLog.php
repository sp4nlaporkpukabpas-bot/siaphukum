<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DocumentAccessLog extends Model
{
    protected $fillable = [
        'document_id',
        'user_id',
        'action',

        // Jaringan
        'ip_address',
        'user_agent',

        // Browser & OS
        'browser_name',
        'browser_version',
        'os_name',
        'os_version',

        // Perangkat
        'device_type',
        'device_brand',
        'device_model',

        // Geolokasi
        'country_code',
        'country_name',
        'region_name',
        'city_name',
        'latitude',
        'longitude',
    ];

    protected $casts = [
        'latitude'  => 'float',
        'longitude' => 'float',
    ];

    // Semua nilai action yang valid
    const ACTIONS = [
        'preview',
        'download',
        'batch_download',
        'download_denied',
        'batch_download_denied',
    ];

    // ----- Relasi -----

    public function document()
    {
        return $this->belongsTo(Document::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // ----- Scope filter -----

    public function scopeForDocument($query, $documentId)
    {
        return $query->where('document_id', $documentId);
    }

    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeAction($query, string $action)
    {
        return $query->where('action', $action);
    }

    public function scopeDeviceType($query, string $type)
    {
        return $query->where('device_type', $type);
    }

    public function scopeCountry($query, string $code)
    {
        return $query->where('country_code', $code);
    }

    // ----- Helper -----

    public function isDenied(): bool
    {
        return str_ends_with($this->action, '_denied');
    }
}
