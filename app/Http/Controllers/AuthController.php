<?php

namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller;
use Illuminate\Http\Request;
use Codebird\Codebird;

class AuthController extends Controller {

    private $api;

    public function __construct() {
        Codebird::setConsumerKey(env('TWITTER_CONSUMER_KEY'), env('TWITTER_CONSUMER_SECRET'));
        $this->api = Codebird::getInstance();
    }

    public function getSignIn(Request $request) {
        if (!session('citcuit.oauth')) {
            $reply = $this->api->oauth_requestToken([
                'oauth_callback' => url('signin')
            ]);
            $this->api->setToken($reply->oauth_token, $reply->oauth_token_secret);

            session(['citcuit.oauth' => (array) $reply]);
            $auth_url = $this->api->oauth_authorize();

            return redirect($auth_url);
        } elseif (isset($_GET['oauth_verifier'])) {
            $this->api->setToken(session('citcuit.oauth.oauth_token'), session('citcuit.oauth.oauth_token_secret'));

            $reply = $this->api->oauth_accessToken([
                'oauth_verifier' => $_GET['oauth_verifier']
            ]);

            session(['citcuit.oauth' => (array) $reply]);

            return redirect(url());
        } else {
            $this->api->logout();
            $request->session()->flush();

            return redirect('/');
        }
    }

    public function getSignOut(Request $request) {
        $this->api->logout();
        $request->session()->flush();

        return redirect(url());
    }

}
