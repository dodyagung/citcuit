<?php

namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller;
use Illuminate\Http\Request;
use Codebird\Codebird;
use Cookie;

class AuthController extends Controller {

    private $api;

    public function __construct() {
        Codebird::setConsumerKey(env('TWITTER_CONSUMER_KEY'), env('TWITTER_CONSUMER_SECRET'));
        $this->api = Codebird::getInstance();
    }

    public function getSignIn(Request $request) {
        if (!Cookie::get('citcuit_session1')) {
            $reply = $this->api->oauth_requestToken([
                'oauth_callback' => url('signin')
            ]);

            Cookie::queue('citcuit_session1', $reply->oauth_token, env('SESSION_LIFETIME'));
            Cookie::queue('citcuit_session2', $reply->oauth_token_secret, env('SESSION_LIFETIME'));

            $this->api->setToken($reply->oauth_token, $reply->oauth_token_secret);
            $auth_url = $this->api->oauth_authorize();

            return redirect($auth_url);
        } elseif ($request->input('oauth_verifier')) {
            $this->api->setToken(Cookie::get('citcuit_session1'), Cookie::get('citcuit_session2'));

            $reply = $this->api->oauth_accessToken([
                'oauth_verifier' => $request->input('oauth_verifier')
            ]);

            Cookie::queue('citcuit_session1', $reply->oauth_token, env('SESSION_LIFETIME'));
            Cookie::queue('citcuit_session2', $reply->oauth_token_secret, env('SESSION_LIFETIME'));
            Cookie::queue('citcuit_session3', $reply->screen_name, env('SESSION_LIFETIME'));

            return redirect('/');
        } else {
            $this->api->logout();
            $request->session()->flush();
            Cookie::queue(Cookie::forget('citcuit_session1'));
            Cookie::queue(Cookie::forget('citcuit_session2'));
            Cookie::queue(Cookie::forget('citcuit_session3'));
            Cookie::queue(Cookie::forget('citcuit_session4'));

            return redirect('/');
        }
    }

    public function getSignOut(Request $request) {
        $this->api->logout();
        $request->session()->flush();
        Cookie::queue(Cookie::forget('citcuit_session1'));
        Cookie::queue(Cookie::forget('citcuit_session2'));
        Cookie::queue(Cookie::forget('citcuit_session3'));
        Cookie::queue(Cookie::forget('citcuit_session4'));

        return redirect('/');
    }

}
