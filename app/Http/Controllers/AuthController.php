<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Codebird\Codebird;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    private $api;
    private $citcuit;

    public function __construct()
    {
        Codebird::setConsumerKey(env('TWITTER_CONSUMER_KEY'), env('TWITTER_CONSUMER_SECRET'));

        $this->citcuit = new CitcuitController();
        $this->api = Codebird::getInstance();
    }

    public function getSignIn(Request $request)
    {
        if (!session('auth')) {
            $reply = $this->api->oauth_requestToken([
                'oauth_callback' => url('signin'),
            ]);

            $error = $this->citcuit->parseError($reply, 'Authentication');
            if ($error) {
                return view('error', $error);
            }

            session(['auth.oauth_token' => $reply->oauth_token]);
            session(['auth.oauth_token_secret' => $reply->oauth_token_secret]);

            $this->api->setToken($reply->oauth_token, $reply->oauth_token_secret);
            $auth_url = $this->api->oauth_authorize();

            return redirect($auth_url);
        } elseif ($request->input('oauth_verifier')) {
            $this->api->setToken(session('auth.oauth_token'), session('auth.oauth_token_secret'));

            $reply = $this->api->oauth_accessToken([
                'oauth_verifier' => $request->input('oauth_verifier'),
            ]);

            $error = $this->citcuit->parseError($reply, 'Authentication');
            if ($error) {
                return view('error', $error);
            }

            session(['auth.oauth_token' => $reply->oauth_token]);
            session(['auth.oauth_token_secret' => $reply->oauth_token_secret]);
            session(['auth.screen_name' => $reply->screen_name]);

            return redirect('/');
        } else {
            $this->api->logout();
            $request->session()->flush();

            return redirect('/');
        }
    }

    public function getSignOut(Request $request)
    {
        $this->api->logout();
        $request->session()->flush();

        return redirect('/');
    }
}
