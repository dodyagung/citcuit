<?php

/*
  |--------------------------------------------------------------------------
  | Application Routes
  |--------------------------------------------------------------------------
  |
  | Here is where you can register all of the routes for an application.
  | It is a breeze. Simply tell Lumen the URIs it should respond to
  | and give it the Closure to call when that URI is requested.
  |
 */

$app->get('signin', 'AuthController@signIn');
$app->get('about', 'NonAPIController@about');

$app->group(['middleware' => 'MustBeAuthenticated', 'namespace' => 'App\Http\Controllers'], function ($app) {
    $app->get('', 'APIController@home');
    $app->get('older/{max_id:[0-9]+}', 'APIController@home');
    $app->get('detail/{tweet_id:[0-9]+}', 'APIController@detail');
    $app->get('like/{tweet_id:[0-9]+}', 'APIController@like');
    $app->get('unlike/{tweet_id:[0-9]+}', 'APIController@unlike');
    $app->post('tweet', 'APIController@postTweet');
    
    $app->get('delete/{tweet_id:[0-9]+}', 'APIController@delete');
    $app->post('delete', 'APIController@postDelete');

    $app->get('mentions', 'APIController@mentions');
    $app->get('mentions/older/{max_id:[0-9]+}', 'APIController@mentions');

    $app->get('profile/{screen_name:[a-zA-Z0-9_]{1,15}}', 'APIController@profile');
    $app->get('profile/{screen_name:[a-zA-Z0-9_]{1,15}}/older/{max_id:[0-9]+}', 'APIController@profile');
    
    $app->get('reply/{tweet_id:[0-9]+}', 'APIController@reply');
    $app->post('reply', 'APIController@postReply');

    $app->get('signout', 'AuthController@signOut');
});