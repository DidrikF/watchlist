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
}); //->middleware('auth.basic'); //FOr HTTP basic authentication

Auth::routes(); //needs to be here! Registers the authentication related routes.

Route::get('/about', function(){
	return view('about');
});

/*
//only acceable from the registration controller after registration??? NOT IMPORTANT though
Route::get('/registration-message', function(){
	return view('auth.registration-message');
}); //->middleware() //can middleware be user for this
*/

//auth middleware
Route::group(['middleware' => ['auth']], function() {

	Route::get('/throwerror', 'ExceptionController@index');

	//Homepage only avaliable to authenticated users
	Route::get('/home', 'HomeController@index');

	Route::get('/search', 'SearchController@index');

	Route::get('/jsonsearch/{searchWord}', 'SearchController@jsonSearch');

	Route::get('/company/{ticker}', 'CompanyController@index');
	

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

	//NOTIFACATIONS
	Route::get('/notification/{ticker}', 'NotificationController@read'); //get all notificaitons associated with ticker && user
	Route::get('/notification', 'NotificationController@readAll');
	Route::post('/notification/{ticker}', 'NotificationController@create');
	Route::put('/notification/{notification}/{ticker}', 'NotificationController@update');
	Route::delete('/notification/{notification}', 'NotificationController@delete');

});


Route::group(['middleware' => ['auth']], function() { //isAdmin middleware
	Route::get('/admin/panel', 'AdminController@showPanel');

	Route::put('/admin/accept/{user}', 'AdminController@acceptUser');
	Route::delete('/admin/deny/{user}', 'AdminController@denyUser');
	Route::put('/admin/ban/{user}', 'AdminController@banUser');

	Route::put('/admin/makeadmin/{user}', 'AdminController@makeAdmin');
	Route::put('/admin/removeadmin/{user}', 'AdminController@removeAdmin');

});
