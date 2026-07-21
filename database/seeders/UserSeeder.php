<?php

namespace Database\Seeders;

use App\Models\Fakultas;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $ft = Fakultas::where('kode', 'FT')->first();
        $fs = Fakultas::where('kode', 'FS')->first();

        // Superadmin
        User::firstOrCreate(['email' => 'superadmin@itk.ac.id'], [
            'name' => 'Super Admin',
            'email' => 'superadmin@itk.ac.id',
            'password' => Hash::make('password'),
            'role' => 'superadmin',
        ]);

        // Admin Akademik
        User::firstOrCreate(['email' => 'akademik@itk.ac.id'], [
            'name' => 'Admin Akademik',
            'email' => 'akademik@itk.ac.id',
            'password' => Hash::make('password'),
            'role' => 'admin_akademik',
        ]);

        // Admin Fakultas Teknik
        User::firstOrCreate(['email' => 'admin.ft@itk.ac.id'], [
            'name' => 'Admin Fakultas Teknik',
            'email' => 'admin.ft@itk.ac.id',
            'password' => Hash::make('password'),
            'role' => 'admin_fakultas',
            'fakultas_id' => $ft->id,
        ]);

        // Admin Fakultas Sains
        User::firstOrCreate(['email' => 'admin.fs@itk.ac.id'], [
            'name' => 'Admin Fakultas Sains',
            'email' => 'admin.fs@itk.ac.id',
            'password' => Hash::make('password'),
            'role' => 'admin_fakultas',
            'fakultas_id' => $fs->id,
        ]);
    }
}
