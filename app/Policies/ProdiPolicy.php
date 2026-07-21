<?php

namespace App\Policies;

use App\Models\Prodi;
use App\Models\User;

class ProdiPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->isSuperadmin();
    }

    public function view(User $user, Prodi $prodi): bool
    {
        return $user->isSuperadmin();
    }

    public function create(User $user): bool
    {
        return $user->isSuperadmin();
    }

    public function update(User $user, Prodi $prodi): bool
    {
        return $user->isSuperadmin();
    }

    public function delete(User $user, Prodi $prodi): bool
    {
        return $user->isSuperadmin();
    }
}
