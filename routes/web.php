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
    // return view('welcome');
    return redirect('/threads');
});

Auth::routes();
Route::get('/register/confirm', 'Auth\RegisterConfirmationController@index')->name('register.confirm');

Route::get('/home', 'HomeController@index')->name('home');

Route::get('/threads','ThreadsController@index')->name('threads');
Route::get('/threads/create','ThreadsController@create');

Route::get('/threads/{channel}/{thread}','ThreadsController@show');
Route::delete('/threads/{channel}/{thread}','ThreadsController@destroy');
Route::post('/threads','ThreadsController@store');

Route::post('/locked-threads/{thread}', 'LockedThreadsController@store')->name('locked-threads.store');
Route::delete('/locked-threads/{thread}', 'LockedThreadsController@destroy')->name('locked-threads.destroy');

Route::get('/threads/{channel}','ThreadsController@index');

Route::get('/threads/{channel}/{thread}/replies','RepliesController@index');
Route::post('/threads/{channel}/{thread}/replies','RepliesController@store');

Route::patch('/replies/{reply}','RepliesController@update');
Route::delete('/replies/{reply}','RepliesController@destroy')->name('replies.destroy');

Route::post('/replies/{reply}/favorites','FavoritesController@store');
Route::post('/replies/{reply}/best', 'BestRepliesController@store')->name('best-replies.store');
Route::delete('/replies/{reply}/favorites','FavoritesController@destroy');

Route::post('/threads/{channel}/{thread}/subscriptions', 'SubscriptionsController@store');
Route::delete('/threads/{channel}/{thread}/subscriptions', 'SubscriptionsController@destroy');

Route::get('/profiles/{user}','ProfilesController@show')->name('profile');
Route::get('/profiles/{user}/notifications','UserNotificationsController@index');
Route::delete('/profiles/{user}/notifications/{notification}','UserNotificationsController@destroy');

Route::get('/api/users', 'APi\UsersController@index');
Route::post('/api/users/{user}/avatar', 'Api\UserAvatarController@store')->name('avatar');
