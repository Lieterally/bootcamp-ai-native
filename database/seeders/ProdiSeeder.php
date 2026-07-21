<?php

namespace Database\Seeders;

use App\Models\Fakultas;
use App\Models\Prodi;
use Illuminate\Database\Seeder;

class ProdiSeeder extends Seeder
{
    public function run(): void
    {
        $ft = Fakultas::where('kode', 'FT')->first();
        $fs = Fakultas::where('kode', 'FS')->first();
        $fitb = Fakultas::where('kode', 'FITB')->first();

        $data = [
            // Fakultas Teknik
            ['fakultas_id' => $ft->id, 'kode' => 'IF', 'nama' => ['id' => 'Informatika', 'en' => 'Informatics'], 'jenjang' => 'S1'],
            ['fakultas_id' => $ft->id, 'kode' => 'SI', 'nama' => ['id' => 'Sistem Informasi', 'en' => 'Information Systems'], 'jenjang' => 'S1'],
            ['fakultas_id' => $ft->id, 'kode' => 'TI', 'nama' => ['id' => 'Teknik Industri', 'en' => 'Industrial Engineering'], 'jenjang' => 'S1'],
            // Fakultas Sains
            ['fakultas_id' => $fs->id, 'kode' => 'MA', 'nama' => ['id' => 'Matematika', 'en' => 'Mathematics'], 'jenjang' => 'S1'],
            ['fakultas_id' => $fs->id, 'kode' => 'FIS', 'nama' => ['id' => 'Fisika', 'en' => 'Physics'], 'jenjang' => 'S1'],
            // Fakultas Ilmu dan Teknologi Bumi
            ['fakultas_id' => $fitb->id, 'kode' => 'TG', 'nama' => ['id' => 'Teknik Geologi', 'en' => 'Geological Engineering'], 'jenjang' => 'S1'],
            ['fakultas_id' => $fitb->id, 'kode' => 'TL', 'nama' => ['id' => 'Teknik Lingkungan', 'en' => 'Environmental Engineering'], 'jenjang' => 'S1'],
        ];

        foreach ($data as $item) {
            Prodi::firstOrCreate(['kode' => $item['kode']], $item);
        }
    }
}
