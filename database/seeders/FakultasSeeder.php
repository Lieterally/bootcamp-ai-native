<?php

namespace Database\Seeders;

use App\Models\Fakultas;
use Illuminate\Database\Seeder;

class FakultasSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            [
                'kode' => 'FT',
                'nama' => ['id' => 'Fakultas Teknik', 'en' => 'Faculty of Engineering'],
            ],
            [
                'kode' => 'FS',
                'nama' => ['id' => 'Fakultas Sains', 'en' => 'Faculty of Science'],
            ],
            [
                'kode' => 'FITB',
                'nama' => ['id' => 'Fakultas Ilmu dan Teknologi Bumi', 'en' => 'Faculty of Earth Science and Technology'],
            ],
        ];

        foreach ($data as $item) {
            Fakultas::firstOrCreate(['kode' => $item['kode']], $item);
        }
    }
}
