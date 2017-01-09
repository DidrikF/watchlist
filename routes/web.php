<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes(); //needs to be here! Registers the authentication related routes.

Route::get('/home', 'HomeController@index');

Route::get('/search', 'SearchController@index');

Route::get('/company/{ticker}', 'CompanyController@index');

//auth middleware

Route::group(['middleware' => ['auth']], function() {

	//Route::get('/{user}/test/{ticker}', 'AnalysisController@test'); 

	//ANALYSIS (user is not nessesary, but I leave it for now)
	Route::get('/{user}/analysis/{ticker}', 'AnalysisController@read'); //analysis = ticker

	Route::post('/{user}/analysis/{ticker}', 'AnalysisController@create'); //what about put ??

	Route::put('/{user}/analysis/{ticker}', 'AnalysisController@update');

	Route::delete('/{user}/analysis/{ticker}', 'AnalysisController@delete');

	//WATCHLIST
	Route::get('/watchlist', 'WathclistController@getAll'); //all I need is the authenticatied user

	Route::post('/watchlist', 'WatchlistController@create'); //create new watchlist, no watchlist id needed

	Route::put('/watchlist/{watchlist}', 'WatchlistController@update');

	Route::delete('/watchlist/{watchlist}', 'WatchlistController@delete');

});
