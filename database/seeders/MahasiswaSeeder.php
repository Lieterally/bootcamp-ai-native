<?php

namespace Database\Seeders;

use App\Models\Mahasiswa;
use App\Models\Prodi;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class MahasiswaSeeder extends Seeder
{
    public function run(): void
    {
        $prodiIF = Prodi::where('kode', 'IF')->first();
        $prodiSI = Prodi::where('kode', 'SI')->first();
        $prodiMA = Prodi::where('kode', 'MA')->first();

        $mahasiswaData = [
            [
                'nim' => '10221001',
                'name' => 'Ahmad Fauzi',
                'email' => 'ahmad.fauzi@student.itk.ac.id',
                'prodi_id' => $prodiIF->id,
                'semester_tempuh' => 5,
                'sks_tempuh' => 80,
                'sks_lulus' => 72,
                'dosen_wali' => 'Dr. Budi Santoso, M.Kom.',
                'status_akademik' => 'Aktif',
            ],
            [
                'nim' => '10221002',
                'name' => 'Siti Nurhaliza',
                'email' => 'siti.nurhaliza@student.itk.ac.id',
                'prodi_id' => $prodiIF->id,
                'semester_tempuh' => 3,
                'sks_tempuh' => 54,
                'sks_lulus' => 48,
                'dosen_wali' => 'Dr. Budi Santoso, M.Kom.',
                'status_akademik' => 'Aktif',
            ],
            [
                'nim' => '10221003',
                'name' => 'Rizky Pratama',
                'email' => 'rizky.pratama@student.itk.ac.id',
                'prodi_id' => $prodiSI->id,
                'semester_tempuh' => 4,
                'sks_tempuh' => 64,
                'sks_lulus' => 60,
                'dosen_wali' => 'Prof. Ir. Andi Wijaya, M.T.',
                'status_akademik' => 'Cuti',
            ],
            [
                'nim' => '10221004',
                'name' => 'Dewi Lestari',
                'email' => 'dewi.lestari@student.itk.ac.id',
                'prodi_id' => $prodiMA->id,
                'semester_tempuh' => 6,
                'sks_tempuh' => 100,
                'sks_lulus' => 94,
                'dosen_wali' => 'Dr. Ratna Sari, M.Si.',
                'status_akademik' => 'Aktif',
            ],
            [
                'nim' => '10221005',
                'name' => 'Bayu Aditya',
                'email' => 'bayu.aditya@student.itk.ac.id',
                'prodi_id' => $prodiIF->id,
                'semester_tempuh' => 1,
                'sks_tempuh' => 20,
                'sks_lulus' => 18,
                'dosen_wali' => 'Dr. Budi Santoso, M.Kom.',
                'status_akademik' => 'Aktif',
            ],
        ];

        foreach ($mahasiswaData as $data) {
            $user = User::firstOrCreate(['email' => $data['email']], [
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => Hash::make('password'),
                'role' => 'mahasiswa',
            ]);

            Mahasiswa::firstOrCreate(['nim' => $data['nim']], [
                'user_id' => $user->id,
                'prodi_id' => $data['prodi_id'],
                'nim' => $data['nim'],
                'name' => $data['name'],
                'email' => $data['email'],
                'semester_tempuh' => $data['semester_tempuh'],
                'sks_tempuh' => $data['sks_tempuh'],
                'sks_lulus' => $data['sks_lulus'],
                'dosen_wali' => $data['dosen_wali'],
                'status_akademik' => $data['status_akademik'],
            ]);
        }
    }
}
