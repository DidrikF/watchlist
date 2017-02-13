<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class AdminPolicy
{
    use HandlesAuthorization;

    public function showPanel(User $admin)
    {
        return $admin->isAdmin();
    }


    public function acceptUser(User $admin)
    {
        return $admin->isAdmin();
    }

    public function denyUser(User $admin)
    {

        return $admin->isAdmin();
    }

    public function banUser(User $admin)
    {
        return $admin->isAdmin();
    }

    public function makeAdmin(User $admin)
    {
        return $admin->email === 'didrik@watchlist.com';
    }

    public function removeAdmin(User $admin)
    {
        return $admin->email === 'didrik@watchlist.com';
    }

}
