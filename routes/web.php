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

Auth::routes(); //needs to be here to register the authentication related routes.

Route::get('/home', 'HomeController@index');

Route::get('/search', 'SearchController@index');

Route::get('/company/{ticker}', 'CompanyController@index');

//auth middleware

Route::group(['middleware' => ['auth']], function() {

	Route::get('/{user}/test/{ticker}', 'AnalysisController@test'); 

	Route::get('/{user}/analysis/{ticker}', 'AnalysisController@read'); //analysis = ticker

	Route::post('/{user}/analysis/{ticker}', 'AnalysisController@create'); //what about put ??

	Route::put('/{user}/analysis/{ticker}', 'AnalysisController@update');

	Route::delete('/{user}/analysis/{ticker}', 'AnalysisController@delete');

});
