<?php

namespace App\Policies;

use App\Models\PengajuanAktifStudi;
use App\Models\User;

class PengajuanAktifStudiPolicy
{
    public function viewAny(User $user): bool
    {
        return in_array($user->role, ['superadmin', 'admin_akademik', 'admin_fakultas']);
    }

    public function view(User $user, PengajuanAktifStudi $pengajuanAktifStudi): bool
    {
        if ($user->isSuperadmin() || $user->isAdminAkademik()) {
            return true;
        }

        if ($user->isAdminFakultas()) {
            return $pengajuanAktifStudi->mahasiswa->prodi->fakultas_id === $user->fakultas_id;
        }

        return false;
    }

    public function create(User $user): bool
    {
        return false; // Mahasiswa submits via frontend, not admin panel
    }

    public function update(User $user, PengajuanAktifStudi $pengajuanAktifStudi): bool
    {
        return false; // Status changes via approve/reject actions
    }

    public function delete(User $user, PengajuanAktifStudi $pengajuanAktifStudi): bool
    {
        return $user->isSuperadmin();
    }
}
