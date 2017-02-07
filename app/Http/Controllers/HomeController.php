<?php

namespace App\Http\Controllers;

use App\Models\{Watchlist, Notification};

use Illuminate\Support\Facades\Auth;

//use Illuminate\Http\Request;
use App\Http\Requests\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //We know the user is authenticated.
        $watchlists = (new Watchlist)->where('user_id', Auth::user()->id)->get();
        $triggeredNotifications = (new Notification)->where('user_id', Auth::user()->id)->where('triggered', true)->get();
        return view('home', [
            'watchlists' => $watchlists,
            'triggeredNotifications' => $triggeredNotifications,
        ]);
    }
}
