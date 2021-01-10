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

Route::get("/", "HomeController@index")->name("home");
Route::get("about", "AboutController@index")->name("about");

Route::prefix("oauth")
    ->name("oauth.")
    ->group(function () {
        Route::get("login", "AuthController@login")->name("login");
        Route::get("callback", "AuthController@callback")->name("callback");
        Route::get("logout", "AuthController@logout")->name("logout");
Route::name("account.")->group(function () {
    Route::get("login", "AccountController@login")->name("login");
    Route::get("callback", "AccountController@callback")->name("callback");
    Route::get("logout", "AccountController@logout")->name("logout");
});

