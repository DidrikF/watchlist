<?php

namespace App\Http\Controllers;

//use Illuminate\Http\Request;
use App\Http\Requests\Request;

use App\Models\User;

use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\Mail; 

use App\Mail\{UserRegistered, RegistrationResult};

class AdminController extends Controller
{
    public function showPanel(User $user)
    {

    	$this->authorize('showPanel', $user);
        /*
    	$notAcceptedUsers = (new User)->where('accepted', false)->get();

    	$acceptedUsers = (new User)->where('accepted', true)->get();

    	$admins = (new User)->where('admin', true)->get();
        */

        $users = (new User)->all();

    	return response()->view('admin.panel', [
    			'users' => $users
    		]);
    }


    public function acceptUser(User $user)
    {
    	$admin = Auth::user();

    	$this->authorize('acceptUser', $admin);

    	$user->accepted = true;

    	if($user->save()){

            Mail::to($user)->send(new RegistrationResult($user));

            $users = (new User)->all();

    		return response()->json($users, 200);
    	}

    	return response()->json(null, 400);
    }

    public function denyUser(User $user)
    {
        $admin = Auth::user();

        $this->authorize('denyUser', $admin);
        if($user->email === 'didrik@watchlist.com') return response()->json(null, 403);

        if($user->delete()){

            $users = (new User)->all();

            return response()->json($users, 200);
        }

        return response()->json(null, 400);
    }

    public function banUser(User $user)
    {
    	$admin = Auth::user();

    	$this->authorize('banUser', $admin);
        if($user->email === 'didrik@watchlist.com') return response()->json(null, 403);

    	$user->accepted = false;
        $user->admin = false;

    	if($user->save()){

            $users = (new User)->all();

    		return response()->json($users, 200);
    	}

    	return response()->json(null, 400);
    }


    //Only if is prime boss
    public function makeAdmin(User $user)
    {
    	$admin = Auth::user();

    	$this->authorize('makeAdmin', $admin);

    	$user->admin = true;
        $user->accepted = true;

    	if($user->save()){
            $users = (new User)->all();

    		return response()->json($users, 200);
    	}

    	return response()->json(null, 400);

    }

    public function removeAdmin(User $user)
    {
    	$admin = Auth::user();

    	$this->authorize('removeAdmin', $admin);
        if($user->email === 'didrik@watchlist.com') return response()->json(null, 403);

    	$user->admin = false;

    	if($user->save()){

            $users = (new User)->all();

    		return response()->json($users, 200);
    	}

    	return resopnse()->json(null, 400);

    }


}
