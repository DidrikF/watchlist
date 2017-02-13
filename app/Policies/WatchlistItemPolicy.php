<?php

namespace App\Policies;

use App\Models\{User, Watchlsit};

use Illuminate\Auth\Access\HandlesAuthorization;

class WatchlistItemPolicy
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

    //In WatchlistPolicy
}
