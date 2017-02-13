<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

use Illuminate\Foundation\Auth\RegistrersUsers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\Events\Registered;

use Illuminate\Support\Facades\Mail; 

use App\Mail\{UserRegistered, RegistrationAwaitingAcceptance};


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

    /*-----------------------------------------------------------------------------
    /* The RegistrersUsers trait code I have moved into the controller for more controll
    /**
     * Show the application registration form.
     *
     * @return \Illuminate\Http\Response
     */
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    /**
     * Handle a registration request for the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {
        $this->validator($request->all())->validate();

        event(new Registered($user = $this->create($request->all())));

        //$this->guard()->login($user);

        return $this->registered($request, $user)
            ?: view('auth.registration-message');//redirect($this->redirectPath());
    }

    /**
     * Get the guard to be used during registration.
     *
     * @return \Illuminate\Contracts\Auth\StatefulGuard
     */
    protected function guard()
    {
        return Auth::guard();
    }

    /**
     * The user has been registered.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  mixed  $user
     * @return mixed
     */
    protected function registered(Request $request, $user)
    {
        //THIS IS WHERE I MAIL PEOPLE
        //dd($user);
        $this->emailAdmin($user);
        $this->emailRegisteredUser($user);

        //return view('auth.registration-message');
    }
    
    protected function emailAdmin(User $user)
    {
        $admins = (new User)->where('admin', true)->get();

        Mail::to($admins)->send(new UserRegistered($user));
    }

    protected function emailRegisteredUser(User $user)
    {
        //need to get a hold of the user that just registered...

        Mail::to($user)->send(new RegistrationAwaitingAcceptance($user));
    }
    /*-----------------------------------------------------------------------------

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
    protected $redirectTo = '/'; //not registrered-message

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
