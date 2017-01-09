<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Analysis; //this might not be needed as we are insering the Analsis class from the controller
use Illuminate\Auth\Access\HandlesAuthorization;

class AnalysisPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the analysis.
     *
     * @param  \App\User  $user
     * @param  \App\Analysis  $analysis
     * @return mixed
     */
    public function read(User $user, Analysis $analysis)
    {
        return $user->id === $analysis->user_id;
    }

    /**
     * Determine whether the user can create and update analyses.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(Analysis $analysis)
    {
        //making sure there is now analysis associated to that user with given ticker
        return true; //has to be authenticated to hit this method and that is all the authorization neeeded
    }

    public function update(User $user, Analysis $analysis)
    {
        return $user->id === $analysis->user_id;
    }

    /**
     * Determine whether the user can delete the analysis.
     *
     * @param  \App\User  $user
     * @param  \App\Analysis  $analysis
     * @return mixed
     */
    public function delete(User $user, Analysis $analysis)
    {
        return $user->id === $analysis->user_id;
    }
}
