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

Route::get('/test', 'CompanyController@test');

//auth middleware

Route::group(['middleware' => ['auth']], function() {

	Route::get('/analysis/{ticker}/show', 'AnalysisController@show'); //I want route model binding

	Route::get('/analysis/{ticker}/save', 'AnalysisController@save');

	Route::get('/analysis/{ticker}/delete', 'AnalysisController@delete');

});
