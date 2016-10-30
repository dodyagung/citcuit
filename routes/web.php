<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are handled
| by your application. Just tell Laravel the URIs it should respond
| to using a Closure or controller method. Build something great!
|
*/

Route::get('about', 'NonAPIController@getAbout');
Route::get('signin', 'AuthController@getSignIn');

Route::group(['middleware' => 'auth.citcuit'], function () {
    Route::get('', 'APIController@getHome');

    Route::get('', 'APIController@getHome');
    Route::get('older/{max_id}', 'APIController@getHome');
    Route::post('tweet', 'APIController@postTweet');

    Route::get('mentions', 'APIController@getMentions');
    Route::get('mentions/older/{max_id}', 'APIController@getMentions');

    Route::get('messages', 'APIController@getMessages');
    Route::get('messages/older/{max_id}', 'APIController@getMessages');
    Route::get('messages/sent', 'APIController@getMessagesSent');
    Route::get('messages/sent/older/{max_id}', 'APIController@getMessagesSent');
    Route::get('messages/detail/{max_id}', 'APIController@getMessagesDetail');
    Route::get('messages/create', 'APIController@getMessagesCreate');
    Route::get('messages/create/{screen_name}', 'APIController@getMessagesCreate');
    Route::get('messages/delete/{max_id}', 'APIController@getMessagesDelete');
    Route::post('messages/create', 'APIController@postMessagesCreate');
    Route::post('messages/delete', 'APIController@postMessagesDelete');

    Route::get('likes/{screen_name}', 'APIController@getLikes');
    Route::get('likes/{screen_name}/older/{max_id}', 'APIController@getLikes');

    Route::get('user/{screen_name}', 'APIController@getUser');
    Route::get('user/{screen_name}/older/{max_id}', 'APIController@getUser');

    Route::get('detail/{tweet_id}', 'APIController@getDetail');

    Route::get('like/{tweet_id}', 'APIController@getLike');
    Route::get('unlike/{tweet_id}', 'APIController@getUnlike');

    Route::get('follow/{screen_name}', 'APIController@getFollow');
    Route::get('unfollow/{screen_name}', 'APIController@getUnfollow');

    Route::get('delete/{tweet_id}', 'APIController@getDelete');
    Route::post('delete', 'APIController@postDelete');

    Route::get('reply/{tweet_id}', 'APIController@getReply');
    Route::post('reply', 'APIController@postReply');

    Route::get('retweet/{tweet_id}', 'APIController@getRetweet');
    Route::post('unretweet', 'APIController@postUnretweet');
    Route::post('retweet', 'APIController@postRetweet');
    Route::post('retweet_with_comment', 'APIController@postRetweetWithComment');

    Route::get('search', 'APIController@getSearch');
    Route::get('search/user', 'APIController@getSearchUser');

    Route::get('settings', 'APIController@getSettings');
    Route::get('settings/general', 'APIController@getSettingsGeneral');
    Route::get('settings/general/reset', 'APIController@getSettingsGeneralReset');
    Route::post('settings/general', 'APIController@postSettingsGeneral');
    Route::get('settings/profile', 'APIController@getSettingsProfile');
    Route::post('settings/profile', 'APIController@postSettingsProfile');
    Route::get('settings/profile_image', 'APIController@getSettingsProfileImage');
    Route::post('settings/profile_image', 'APIController@postSettingsProfileImage');
    Route::get('settings/profile_header', 'APIController@getSettingsProfileHeader');
    Route::post('settings/profile_header', 'APIController@postSettingsProfileHeader');
    Route::get('settings/profile_header/remove', 'APIController@getSettingsProfileHeaderRemove');
    Route::get('settings/facebook', 'APIController@getSettingsFacebook');
    Route::get('settings/facebook/login', 'APIController@getSettingsFacebookLogin');
    Route::get('settings/facebook/logout', 'APIController@getSettingsFacebookLogout');

    Route::get('trends', 'APIController@getTrends');

    Route::get('upload', 'APIController@getUpload');
    Route::post('upload', 'APIController@postUpload');

    Route::get('following/{screen_name}', 'APIController@getFollowing');
    Route::get('following/{screen_name}/cursor/{cursor}', 'APIController@getFollowing');
    Route::get('followers/{screen_name}', 'APIController@getFollowers');
    Route::get('followers/{screen_name}/cursor/{cursor}', 'APIController@getFollowers');

    Route::get('signout', 'AuthController@getSignOut');
});
