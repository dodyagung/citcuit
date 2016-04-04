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

$app->get('signin', 'AuthController@getSignIn');
$app->get('about', 'NonAPIController@getAbout');

$app->group(['middleware' => 'MustBeAuthenticated', 'namespace' => 'App\Http\Controllers'], function ($app) {
    $app->get('', 'APIController@getHome');
    $app->get('older/{max_id:[0-9]+}', 'APIController@getHome');
    $app->post('tweet', 'APIController@postTweet');

    $app->get('mentions', 'APIController@getMentions');
    $app->get('mentions/older/{max_id:[0-9]+}', 'APIController@getMentions');

    $app->get('profile/{screen_name:[a-zA-Z0-9_]{1,15}}', 'APIController@getProfile');
    $app->get('profile/{screen_name:[a-zA-Z0-9_]{1,15}}/older/{max_id:[0-9]+}', 'APIController@getProfile');

    $app->get('detail/{tweet_id:[0-9]+}', 'APIController@getDetail');

    $app->get('like/{tweet_id:[0-9]+}', 'APIController@getLike');
    $app->get('unlike/{tweet_id:[0-9]+}', 'APIController@GetUnlike');

    $app->get('follow/{screen_name:[a-zA-Z0-9_]{1,15}}', 'APIController@getFollow');
    $app->get('unfollow/{screen_name:[a-zA-Z0-9_]{1,15}}', 'APIController@getUnfollow');

    $app->get('delete/{tweet_id:[0-9]+}', 'APIController@getDelete');
    $app->post('delete', 'APIController@postDelete');

    $app->get('reply/{tweet_id:[0-9]+}', 'APIController@getReply');
    $app->post('reply', 'APIController@postReply');

    $app->get('retweet/{tweet_id:[0-9]+}', 'APIController@getRetweet');
    $app->post('unretweet', 'APIController@postUnretweet');
    $app->post('retweet', 'APIController@postRetweet');
    $app->post('retweet_with_comment', 'APIController@postRetweetWithComment');

    $app->get('signout', 'AuthController@getSignOut');
});
