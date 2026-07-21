<?php

namespace App\Policies;

use App\Models\PeriodeAkademik;
use App\Models\User;

class PeriodeAkademikPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->isSuperadmin();
    }

    public function view(User $user, PeriodeAkademik $periodeAkademik): bool
    {
        return $user->isSuperadmin();
    }

    public function create(User $user): bool
    {
        return $user->isSuperadmin();
    }

    public function update(User $user, PeriodeAkademik $periodeAkademik): bool
    {
        return $user->isSuperadmin();
    }

    public function delete(User $user, PeriodeAkademik $periodeAkademik): bool
    {
        return $user->isSuperadmin();
    }
}
