<?php

namespace App\Policies;

use App\Models\Mahasiswa;
use App\Models\User;

class MahasiswaPolicy
{
    public function viewAny(User $user): bool
    {
        return in_array($user->role, ['superadmin', 'admin_akademik', 'admin_fakultas']);
    }

    public function view(User $user, Mahasiswa $mahasiswa): bool
    {
        if ($user->isSuperadmin() || $user->isAdminAkademik()) {
            return true;
        }

        if ($user->isAdminFakultas()) {
            return $mahasiswa->prodi->fakultas_id === $user->fakultas_id;
        }

        return false;
    }

    public function create(User $user): bool
    {
        return $user->isSuperadmin() || $user->isAdminAkademik();
    }

    public function update(User $user, Mahasiswa $mahasiswa): bool
    {
        return $user->isSuperadmin() || $user->isAdminAkademik();
    }

    public function delete(User $user, Mahasiswa $mahasiswa): bool
    {
        return $user->isSuperadmin();
    }
}
