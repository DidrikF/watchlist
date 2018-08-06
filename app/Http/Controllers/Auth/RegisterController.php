<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

use Illuminate\Foundation\Auth\RegistrersUsers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\Events\Registered;

//REMOVE THESE
//use Illuminate\Support\Facades\Mail; 

//use App\Mail\{UserRegistered, RegistrationAwaitingAcceptance};
//
use App\Jobs\{SendUserRegistered, SendRegistrationAwaitingAcceptance};

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

        //DO NOT LOG PEOPLE IN AFTER REGISTRATION
        //$this->guard()->login($user); 



        // this would dispatch email jobs
        //return $this->registered($request, $user)
        //    ?: view('auth.registration-message');
            
        return redirect($this->redirectPath());
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

        $this->dispatch(new SendUserRegistered($user));
        $this->dispatch(new SendRegistrationAwaitingAcceptance($user));


        //$this->emailAdmin($user);
        //$this->emailRegisteredUser($user);
    }
    /*
    protected function emailAdmin(User $user)
    {
        $this->dispatch(new SendUserRegistered($user));

        //Mail::to($admins)->send(new UserRegistered($user));
    }

    protected function emailRegisteredUser(User $user)
    {
        $this->dispatch(new SendRegistrationAwaitingAcceptance($user));

        //Mail::to($user)->send(new RegistrationAwaitingAcceptance($user));
    }
    */
    /*-----------------------------------------------------------------------------

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/livedemo/companywatchlist/login'; //not registrered-message

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
            'accepted' => true, // Used to be false, to make it so that an admin would have to approve new users.
            'admin' => false,
        ]);
    }
}
