<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;


class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */

    //Can be over written with protected function redirectTo(){ logic..; return $path;}

                /*
                protected redirectTo()
                {  
                    //need the user instance

                    //if user registered less than one hour ago, he can still hit the link

                    //Still need to protect this link somehow, it should only be accessible from here


                }
                */
    protected $redirectTo = '/registration-message';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    //this is only to validate user submitted data!
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|min:6|confirmed',
            //'accepted' => 'required|boolean',
            //'admin' => 'required|boolean'
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return User
     */
    

    /*
    The create method of the RegisterController is responsible for creating new App\User records in your database using the Eloquent ORM. You are free to modify this method according to the needs of your database.
    */

    protected function create(array $data)
    {
        //mail user and admin here?

        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
            'accepted' => false,
            'admin' => false,
        ]);
    }
}
