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

Route::group(['middleware' => 'auth'], function() {
    Route::get('/', ['uses' => 'PreLogonFirstPageCallback@index']);
    Route::get('/groups/create', ['uses' => 'GroupController@create'])->name('groupCreate');
    Route::post('/groups/create', ['uses' => 'GroupController@store']);
    Route::get('/groups/{group}', ['uses' => 'GroupController@index'])->name('group');
    Route::get('/groups/{group}/share', ['uses' => 'GroupShareController@index'])->name('groupShare');
    Route::post('/groups/{group}/share', ['uses' => 'GroupShareController@store']);
    Route::post('/groups/{group}/unshare/{user}', ['uses' => 'GroupUnshareController@store']);
    Route::get('/groups/{group}/delete', ['uses' => 'GroupDeleteController@index']);
    Route::post('/groups/{group}/changename', ['uses' => 'GroupChangeNameController@store']);
    Route::post('/groups/{group}/delete', ['uses' => 'GroupDeleteController@delete']);
    Route::get('/pwdfor/{credential}', ['uses' => 'PasswordForController@index']);
    Route::post('logout', ['uses' => 'LogoutController@store'])->name('logout');
    Route::get('/search/{search}', ['uses' => 'SearchController@index'])->name('search');
    Route::get('/changepwd', ['uses' => 'ChangePasswordController@index'])->name('changepassword');
    Route::post('/changepwd', ['uses' => 'ChangePasswordController@store']);

    Route::post('/cred/add', ['uses' => 'CredentialsController@store']);
    Route::post('/cred/{credential}', ['uses' => 'CredentialsController@update']);
    Route::get('/cred/{credential}/remove', ['uses' => 'CredentialsController@delete']);
    Route::get('/securitycheck', ['uses' => 'SecurityCheckController@index'])->name('securitycheck');

    Route::post('/import', ['uses' => 'ImportController@store']);
});

Route::get('/login', ['uses' => 'LoginController@index'])->name('login');
Route::post('/login', ['uses' => 'LoginController@post']);
Route::post('/reg', ['uses' => 'RegistrationController@post'])->name('registration');
