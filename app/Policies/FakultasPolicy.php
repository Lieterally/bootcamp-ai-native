<?php

namespace App\Policies;

use App\Models\Fakultas;
use App\Models\User;

class FakultasPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->isSuperadmin();
    }

    public function view(User $user, Fakultas $fakultas): bool
    {
        return $user->isSuperadmin();
    }

    public function create(User $user): bool
    {
        return $user->isSuperadmin();
    }

    public function update(User $user, Fakultas $fakultas): bool
    {
        return $user->isSuperadmin();
    }

    public function delete(User $user, Fakultas $fakultas): bool
    {
        return $user->isSuperadmin();
    }
}
