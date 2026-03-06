<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Menambahkan kolom active_role_id setelah password
            // constrained('roles') otomatis merujuk ke tabel roles kolom id
            $table->foreignId('active_role_id')
                  ->after('password')
                  ->nullable()
                  ->constrained('roles')
                  ->onDelete('set null'); // Jika role dihapus, user tetap ada tapi active_role jadi null
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // 1. Hapus foreign key constraint-nya dulu
            $table->dropForeign(['active_role_id']); 
            
            // 2. Baru hapus kolomnya
            $table->dropColumn('active_role_id');
        });
    }
};