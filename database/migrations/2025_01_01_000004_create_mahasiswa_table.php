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
        Schema::create('mahasiswa', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('prodi_id')->constrained('prodi')->restrictOnDelete();
            $table->string('nim', 20)->unique();
            $table->string('name', 100);
            $table->string('email', 100);
            $table->integer('semester_tempuh')->default(1);
            $table->integer('sks_tempuh')->default(0);
            $table->integer('sks_lulus')->default(0);
            $table->string('dosen_wali', 100)->nullable();
            $table->enum('status_akademik', ['Aktif', 'Cuti', 'Mengundurkan Diri'])->default('Aktif');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mahasiswa');
    }
};
