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
        Schema::create('periode_akademik', function (Blueprint $table) {
            $table->id();
            $table->string('tahun_akademik', 9); // format: YYYY/YYYY
            $table->enum('semester', ['Ganjil', 'Genap']);
            $table->boolean('is_active')->default(false);
            $table->date('tanggal_buka_cuti')->nullable();
            $table->date('tanggal_tutup_cuti')->nullable();
            $table->date('tanggal_buka_aktif_studi')->nullable();
            $table->date('tanggal_tutup_aktif_studi')->nullable();
            $table->timestamps();

            $table->unique(['tahun_akademik', 'semester']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('periode_akademik');
    }
};
