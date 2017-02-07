<?php

namespace App\Policies;

use App\Models\User;

use App\Models\Notification;

use Illuminate\Auth\Access\HandlesAuthorization;

class NotificationPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }


    public function update(User $user, Notification $notification)
    {
        return $user->id === $notification->user_id;
    }

    public function delete(User $user, Notification $notification)
    {
        return $user->id === $notification->user_id;
    }
}
