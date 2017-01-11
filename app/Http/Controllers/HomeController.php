<?php

namespace App\Http\Controllers;

use App\Models\Watchlist;

use Illuminate\Support\Facades\Auth;

use Illuminate\Http\Request;

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
        $watchlists = (new Watchlist)->where('user_id', Auth::user()->id)->get();
        return view('home', [
            'watchlists' => $watchlists,
        ]);
    }
}
