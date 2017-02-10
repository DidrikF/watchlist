<?php

namespace App\Http\Controllers;

//use Illuminate\Http\Request;
use App\Http\Requests\Request;

use App\Models\User;

use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\Mail; 

use App\Http\Mail\{UserRegistered, RegistrationResult};

class AdminController extends Controller
{
    public function showPanel(User $user)
    {

    	$this->authorize('showPanel', $user);

    	$notAcceptedUsers = (new User)->where('accepted', false)->get();

    	$acceptedUsers = (new User)->where('accepted', true)->get();

    	$admins = (new User)->where('admin', true)->get();

    	return response()->view('admin.panel', [
    			'notAcceptedUsers' => $notAcceptedUsers,
    			'acceptedUsers' => $acceptedUsers,
    			'admins' => $admins,
    		]);
    }


    public function acceptUser(User $user)
    {
    	$admin = Auth::user();

    	$this->authorize('acceptUser', $admin);

    	$user->accepted = true;
    	if($user->save()){

            Mail::to($user)->send(new RegistrationResult($user));

    		return response->json(null, 200);
    	}

    	return response()->json(null, 400);
    }


    public function banUser(User $user)
    {
    	$admin = Auth::user();

    	$this->authorize('banUser', $admin);

    	$user->accepted = false;

    	if($user->save()){
    		return response()->json(null, 200);
    	}

    	return response()->json(null, 400);
    }


    public function makeAdmin(User $user)
    {
    	$admin = Auth::user();

    	$this->authorize('makeAdmin', $admin);

    	$user->admin = true;

    	if($user->save()){
    		return response()->json(null, 200);
    	}

    	return response()->json(null, 400);

    }

    public function removeAdmin(User $user)
    {
    	$admin = Auth::user();

    	$this->authorize('removeAdmin', $admin);

    	$user->admin = false;

    	if($user->save()){
    		return response()->json(null, 200);
    	}

    	return resopnse()->json(null, 400);

    }


}
