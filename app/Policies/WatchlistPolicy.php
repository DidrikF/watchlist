<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class WatchlistPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can perform operations on the watchlist.
     *
     * @param  \App\User  $user
     * @param  \App\Watchlist  $watchlist
     * @return mixed
     */
    //the authenticated user should be inserted automatically
    public function read(User $user, Watchlist $watchlist)
    {
        return $user->id === $watchlist->user_id;
    }

    public function update(User $user, Watchlist $watchlist)
    {
        return $user->id === $watchlist->user_id;
    }

    public function delete(User $user, Watchlist $watchlist)
    {
        return $user->id === $watchlist->user_id;
    }
}
