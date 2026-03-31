<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('document_access_logs', function (Blueprint $table) {
            $table->id();

            // Relasi
            $table->foreignId('document_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');

            // Jenis aksi
            $table->enum('action', ['preview', 'download', 'batch_download', 'download_denied', 'batch_download_denied']);

            // Jaringan
            $table->string('ip_address', 45)->nullable();

            // Device & Browser (parsed dari User-Agent)
            $table->string('user_agent')->nullable();           // raw UA string
            $table->string('browser_name', 80)->nullable();     // Chrome, Firefox, Edge, …
            $table->string('browser_version', 30)->nullable();  // 124.0.0
            $table->string('os_name', 80)->nullable();          // Windows, Android, iOS, …
            $table->string('os_version', 30)->nullable();       // 11, 14.6, …
            $table->string('device_type', 30)->nullable();      // desktop | mobile | tablet | bot
            $table->string('device_brand', 60)->nullable();     // Samsung, Apple, …
            $table->string('device_model', 60)->nullable();     // iPhone 14, Galaxy S23, …

            // Geolokasi (opsional — diisi bila GeoIP tersedia)
            $table->string('country_code', 5)->nullable();      // ID, US, SG, …
            $table->string('country_name', 80)->nullable();     // Indonesia
            $table->string('region_name', 80)->nullable();      // Jawa Timur
            $table->string('city_name', 80)->nullable();        // Surabaya
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();

            $table->timestamps();

            // Index untuk filter & laporan
            $table->index(['document_id', 'action']);
            $table->index(['user_id', 'created_at']);
            $table->index('ip_address');
            $table->index('country_code');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('document_access_logs');
    }
};
