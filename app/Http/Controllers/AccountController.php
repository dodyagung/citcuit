<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Twitter;

class AccountController extends Controller
{
    public function login()
    {
        // your SIGN IN WITH TWITTER  button should point to this route
        $sign_in_twitter = true;
        $force_login = false;

        // Make sure we make this request w/o tokens, overwrite the default values in case of login.
        Twitter::reconfig(["token" => "", "secret" => ""]);
        $token = Twitter::getRequestToken(route("account.callback"));

        if (isset($token["oauth_token_secret"])) {
            $url = Twitter::getAuthorizeURL(
                $token,
                $sign_in_twitter,
                $force_login
            );

            session()->put("oauth_state", "start");
            session()->put("oauth_request_token", $token["oauth_token"]);
            session()->put(
                "oauth_request_token_secret",
                $token["oauth_token_secret"]
            );

            return redirect()->to($url);
        } else {
            abort(401);
        }
    }

    public function callback()
    {
        // You should set this route on your Twitter Application settings as the callback
        // https://apps.twitter.com/app/YOUR-APP-ID/settings
        if (session()->has("oauth_request_token")) {
            $request_token = [
                "token" => session()->get("oauth_request_token"),
                "secret" => session()->get("oauth_request_token_secret"),
            ];

            Twitter::reconfig($request_token);

            $oauth_verifier = false;

            if (request()->has("oauth_verifier")) {
                $oauth_verifier = request()->get("oauth_verifier");
                // getAccessToken() will reset the token for you
                $token = Twitter::getAccessToken($oauth_verifier);
            }

            if (!isset($token["oauth_token_secret"])) {
                abort(401);
            }

            $credentials = Twitter::getCredentials();

            if (is_object($credentials) && !isset($credentials->error)) {
                // $credentials contains the Twitter user object with all the info about the user.
                // Add here your own user logic, store profiles, create new users on your tables...you name it!
                // Typically you'll want to store at least, user id, name and access tokens
                // if you want to be able to call the API on behalf of your users.

                // This is also the moment to log in your users if you're using Laravel's Auth class
                // Auth::login($user) should do the trick.

                session()->put("access_token", $token);

                return redirect()
                    ->route("home")
                    ->with(
                        "status",
                        "Congrats! You've successfully signed in."
                    );
            } else {
                abort(401);
            }
        } else {
            abort(401);
        }
    }

    public function logout()
    {
        session()->forget("access_token");
        return redirect()
            ->route("home")
            ->with("status", "You've successfully logged out!");
    }
}
