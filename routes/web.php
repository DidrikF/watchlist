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

	//ANALYSIS (remove user id from the route)
	Route::get('/analysis/{ticker}', 'AnalysisController@read'); //analysis = ticker

	Route::post('/analysis/{ticker}', 'AnalysisController@create'); //what about put ??

	Route::put('/analysis/{ticker}', 'AnalysisController@update');

	Route::delete('/analysis/{ticker}', 'AnalysisController@delete');

	//WATCHLIST
	Route::get('/watchlist', 'WathclistController@getAll'); //all I need is the authenticatied user, NOT USED ATM

	Route::get('/watchlist/{watchlist}', 'WatchlistController@read'); //title, desc, items/companies

	Route::post('/watchlist', 'WatchlistController@create'); //create new watchlist, no watchlist id needed

	Route::put('/watchlist/{watchlist}', 'WatchlistController@update');

	Route::delete('/watchlist/{watchlist}', 'WatchlistController@delete');

	//WATCHLIST ITEM
	Route::post('/watchlist/{watchlist}', 'WatchlistController@createItem');

	Route::delete('watchlist/{watchlist}/{ticker}', 'WatchlistController@deleteItem');

	

});
