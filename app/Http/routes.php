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
    $app->get('older/{max_id}', 'APIController@getHome');
    $app->post('tweet', 'APIController@postTweet');

    $app->get('mentions', 'APIController@getMentions');
    $app->get('mentions/older/{max_id}', 'APIController@getMentions');

    $app->get('messages', 'APIController@getMessages');
    $app->get('messages/older/{max_id}', 'APIController@getMessages');
    $app->get('messages/sent', 'APIController@getMessagesSent');
    $app->get('messages/sent/older/{max_id}', 'APIController@getMessagesSent');
    $app->get('messages/detail/{max_id}', 'APIController@getMessagesDetail');
    $app->get('messages/create', 'APIController@getMessagesCreate');
    $app->get('messages/create/{screen_name}', 'APIController@getMessagesCreate');
    $app->get('messages/delete/{max_id}', 'APIController@getMessagesDelete');
    $app->post('messages/create', 'APIController@postMessagesCreate');
    $app->post('messages/delete', 'APIController@postMessagesDelete');

    $app->get('likes/{screen_name}', 'APIController@getLikes');
    $app->get('likes/{screen_name}/older/{max_id}', 'APIController@getLikes');

    $app->get('user/{screen_name}', 'APIController@getUser');
    $app->get('user/{screen_name}/older/{max_id}', 'APIController@getUser');

    $app->get('detail/{tweet_id}', 'APIController@getDetail');

    $app->get('like/{tweet_id}', 'APIController@getLike');
    $app->get('unlike/{tweet_id}', 'APIController@getUnlike');

    $app->get('follow/{screen_name}', 'APIController@getFollow');
    $app->get('unfollow/{screen_name}', 'APIController@getUnfollow');

    $app->get('delete/{tweet_id}', 'APIController@getDelete');
    $app->post('delete', 'APIController@postDelete');

    $app->get('reply/{tweet_id}', 'APIController@getReply');
    $app->post('reply', 'APIController@postReply');

    $app->get('retweet/{tweet_id}', 'APIController@getRetweet');
    $app->post('unretweet', 'APIController@postUnretweet');
    $app->post('retweet', 'APIController@postRetweet');
    $app->post('retweet_with_comment', 'APIController@postRetweetWithComment');

    $app->get('search', 'APIController@getSearch');
    $app->get('search/user', 'APIController@getSearchUser');

    $app->get('settings', 'APIController@getSettings');
    $app->get('settings/profile', 'APIController@getSettingsProfile');
    $app->post('settings/profile', 'APIController@postSettingsProfile');
    $app->get('settings/profile_image', 'APIController@getSettingsProfileImage');
    $app->post('settings/profile_image', 'APIController@postSettingsProfileImage');
    $app->get('settings/facebook', 'APIController@getSettingsFacebook');
    $app->get('settings/facebook/login', 'APIController@getSettingsFacebookLogin');
    $app->get('settings/facebook/logout', 'APIController@getSettingsFacebookLogout');

    $app->get('trends', 'APIController@getTrends');

    $app->get('upload', 'APIController@getUpload');
    $app->post('upload', 'APIController@postUpload');

    $app->get('following/{screen_name}', 'APIController@getFollowing');
    $app->get('following/{screen_name}/cursor/{cursor}', 'APIController@getFollowing');
    $app->get('followers/{screen_name}', 'APIController@getFollowers');
    $app->get('followers/{screen_name}/cursor/{cursor}', 'APIController@getFollowers');

    $app->get('signout', 'AuthController@getSignOut');
});
