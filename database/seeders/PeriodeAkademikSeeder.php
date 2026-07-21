<?php

namespace Database\Seeders;

use App\Models\PeriodeAkademik;
use Illuminate\Database\Seeder;

class PeriodeAkademikSeeder extends Seeder
{
    public function run(): void
    {
        // Periode lalu (non-aktif)
        PeriodeAkademik::firstOrCreate(
            ['tahun_akademik' => '2024/2025', 'semester' => 'Ganjil'],
            [
                'is_active' => false,
                'tanggal_buka_cuti' => '2024-08-01',
                'tanggal_tutup_cuti' => '2024-08-31',
                'tanggal_buka_aktif_studi' => '2024-08-01',
                'tanggal_tutup_aktif_studi' => '2024-08-31',
            ]
        );

        // Periode aktif saat ini
        PeriodeAkademik::firstOrCreate(
            ['tahun_akademik' => '2024/2025', 'semester' => 'Genap'],
            [
                'is_active' => true,
                'tanggal_buka_cuti' => '2025-01-10',
                'tanggal_tutup_cuti' => '2025-02-10',
                'tanggal_buka_aktif_studi' => '2025-01-10',
                'tanggal_tutup_aktif_studi' => '2025-02-10',
            ]
        );

        // Periode mendatang
        PeriodeAkademik::firstOrCreate(
            ['tahun_akademik' => '2025/2026', 'semester' => 'Ganjil'],
            [
                'is_active' => false,
                'tanggal_buka_cuti' => null,
                'tanggal_tutup_cuti' => null,
                'tanggal_buka_aktif_studi' => null,
                'tanggal_tutup_aktif_studi' => null,
            ]
        );
    }
}
