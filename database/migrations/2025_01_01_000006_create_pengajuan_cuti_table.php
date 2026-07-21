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
        Schema::create('pengajuan_cuti', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mahasiswa_id')->constrained('mahasiswa')->cascadeOnDelete();
            $table->foreignId('periode_akademik_id')->constrained('periode_akademik')->restrictOnDelete();
            $table->string('nim', 20);
            $table->string('name', 100);
            $table->string('prodi', 100);
            $table->integer('semester_tempuh');
            $table->integer('sks_tempuh');
            $table->integer('sks_lulus');
            $table->string('dosen_wali', 100)->nullable();
            $table->text('alasan_cuti');
            $table->enum('status', ['Menunggu Persetujuan', 'Disetujui', 'Ditolak'])->default('Menunggu Persetujuan');
            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->string('catatan', 500)->nullable();
            $table->timestamp('submitted_at')->nullable();
            $table->timestamp('processed_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengajuan_cuti');
    }
};
