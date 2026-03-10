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
        Schema::create('rekap_registers', function (Blueprint $table) {
            $table->id();
            $table->string('nama_rekap');
            $table->year('tahun');
            $table->string('link_dokumen');
            $table->boolean('is_visible')->default(true); // Status: Munculkan atau Tidak
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rekap_registers');
    }
};
