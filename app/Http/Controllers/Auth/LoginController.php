<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    protected $remember;
    /**
     * Handle an authentication attempt.
     *
     * @return Response
     */
    public function authenticate()
    {
        $this->remember = true;


        if (Auth::attempt(['email' => $email, 'password' => $password, 'accepted' => 1]), $this->remember) {
            // Authentication passed...
            return redirect()->intended('/');
        }
        //return redirect('/login'); //Maybe I dont need this
    }
}