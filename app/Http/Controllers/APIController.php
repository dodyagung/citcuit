<?php

namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller;
use Illuminate\Http\Request;
use Codebird\Codebird;
use App\Http\Controllers\CitcuitController as CitCuit;

class APIController extends Controller {

    private $api;
    private $view_prefix = 'api.';

    public function __construct() {
        Codebird::setConsumerKey(env('TWITTER_CONSUMER_KEY'), env('TWITTER_CONSUMER_SECRET'));
        $this->api = Codebird::getInstance();
        $this->api->setToken(session('citcuit.oauth.oauth_token'), session('citcuit.oauth.oauth_token_secret'));
//        $this->api->setReturnFormat(CODEBIRD_RETURNFORMAT_ARRAY);
//        $reply = $this->api->oauth2_token();
//        Codebird::setBearerToken($reply->access_token);
        $this->api->setConnectionTimeout(6000);
        $this->api->setTimeout(15000);
    }

    public function home(Request $request, $max_id = false) {
        $param = [
            'count' => 10,
        ];
        if ($max_id) {
            $param['max_id'] = $max_id;
        }

        $result = $this->api->statuses_homeTimeline($param);
        if (($error = CitCuit::parseError($result, 'Home')) != FALSE) {
            return view('error', $error);
        }

        $render = [
            'rate' => [
                'Home' => CitCuit::parseRateLimit($result),
            ],
            'timeline' => CitCuit::parseTweets($result),
        ];

        return view($this->view_prefix . 'home', $render);
    }

    public function detail(Request $request, $tweet_id) {
        $param = [
            'id' => $tweet_id,
            'include_my_retweet' => 'true'
        ];

        $result = $this->api->statuses_show_ID($param);
        if (($error = CitCuit::parseError($result, 'Tweet Detail')) != FALSE) {
            return view('error', $error);
        }

        $render = [
            'rate' => [
                'Tweet Detail' => CitCuit::parseRateLimit($result),
            ],
            'tweet' => CitCuit::parseTweet($result),
        ];

        return view($this->view_prefix . 'detail', $render);
    }

    public function mentions(Request $request, $max_id = false) {
        $param = [
            'count' => 10,
        ];
        if ($max_id) {
            $param['max_id'] = $max_id;
        }

        $result = $this->api->statuses_mentionsTimeline($param);
        if (($error = CitCuit::parseError($result, 'Mentions')) != FALSE) {
            return view('error', $error);
        }

        $render = [
            'rate' => [
                'Mentions' => CitCuit::parseRateLimit($result),
            ],
            'timeline' => CitCuit::parseTweets($result),
        ];

        return view($this->view_prefix . 'mentions', $render);
    }

    public function profile(Request $request, $screen_name, $max_id = false) {
        //user
        $param = [
            'screen_name' => $screen_name
        ];

        $result = $this->api->users_show($param);
        if (($error = CitCuit::parseError($result, 'Profile')) != FALSE) {
            return view('error', $error);
        }
        $rate_profile = CitCuit::parseRateLimit($result);

        $render = [
            'profile' => CitCuit::parseProfile($result),
        ];

        //tweet
        $param = [
            'screen_name' => $screen_name,
            'count' => 10,
        ];
        if ($max_id) {
            $param['max_id'] = $max_id;
        }

        $result = $this->api->statuses_userTimeline($param);
        if (($error = CitCuit::parseError($result, 'User Tweet')) != FALSE) {
            return view('error', $error);
        }

        $rate_tweets = CitCuit::parseRateLimit($result);

        $render['screen_name'] = $screen_name;
        $render['timeline'] = CitCuit::parseTweets($result);
        $render['rate'] = [
            'Profile' => $rate_profile,
            'User Tweet' => $rate_tweets,
        ];

        return view($this->view_prefix . 'profile', $render);
    }

    public function postTweet(Request $request) {
        $tweet = $request->tweet;

        $param = [
            'status' => $tweet,
        ];
        $result = $this->api->statuses_update($param);
        if (($error = CitCuit::parseError($result)) != FALSE) {
            return view('error', $error);
        }

        return redirect('');
    }

    public function like(Request $request, $tweet_id) {
        $param = [
            'id' => $tweet_id,
        ];
        $result = $this->api->favorites_create($param);
        if (($error = CitCuit::parseError($result, 'Like')) != FALSE) {
            return view('error', $error);
        }

        return redirect()->back();
    }

    public function unlike(Request $request, $tweet_id) {
        $param = [
            'id' => $tweet_id,
        ];
        $result = $this->api->favorites_destroy($param);
        if (($error = CitCuit::parseError($result, 'Unlike')) != FALSE) {
            return view('error', $error);
        }

        return redirect()->back();
    }

    public function reply(Request $request, $tweet_id) {
        $param = [
            'id' => $tweet_id,
        ];
        $result = $this->api->statuses_show_ID($param);
        if (($error = CitCuit::parseError($result, 'Reply')) != FALSE) {
            return view('error', $error);
        }

        $render = [
            'rate' => [
                'Reply' => CitCuit::parseRateLimit($result),
            ],
            'tweet' => CitCuit::parseTweet($result),
        ];

        return view($this->view_prefix . 'reply', $render);
    }

    public function postReply(Request $request) {
        $tweet = $request->tweet;
        $in_reply_to_status_id = $request->in_reply_to_status_id;

        $param = [
            'status' => $tweet,
            'in_reply_to_status_id' => $in_reply_to_status_id,
        ];
        $result = $this->api->statuses_update($param);
        if (($error = CitCuit::parseError($result)) != FALSE) {
            return view('error', $error);
        }

        return redirect('');
    }

    public function delete(Request $request, $tweet_id) {
        $param = [
            'id' => $tweet_id,
        ];
        $result = $this->api->statuses_show_ID($param);
        if (($error = CitCuit::parseError($result, 'Delete')) != FALSE) {
            return view('error', $error);
        }

        $render = [
            'rate' => [
                'Delete' => CitCuit::parseRateLimit($result),
            ],
            'tweet' => CitCuit::parseTweet($result),
        ];

        return view($this->view_prefix . 'delete', $render);
    }

    public function postDelete(Request $request) {
        $id = $request->id;

        $param = [
            'id' => $id,
        ];
        $result = $this->api->statuses_destroy_ID($param);
        if (($error = CitCuit::parseError($result)) != FALSE) {
            return view('error', $error);
        }

        return redirect('');
    }

    public function retweet(Request $request, $tweet_id) {
        $param = [
            'id' => $tweet_id,
        ];
        $result = $this->api->statuses_show_ID($param);
        if (($error = CitCuit::parseError($result, 'Retweet')) != FALSE) {
            return view('error', $error);
        }

        $render = [
            'rate' => [
                'Retweet' => CitCuit::parseRateLimit($result),
            ],
            'tweet' => CitCuit::parseTweet($result),
        ];

        return view($this->view_prefix . 'retweet', $render);
    }

    public function postRetweetWithComment(Request $request) {
        $tweet = $request->tweet;
        $retweet_link = $request->retweet_link;

        $param = [
            'status' => $tweet . ' ' . $retweet_link,
        ];

        $result = $this->api->statuses_update($param);
        if (($error = CitCuit::parseError($result)) != FALSE) {
            return view('error', $error);
        }

        return redirect('');
    }

    public function postRetweet(Request $request) {
        $id = $request->id;

        $param = [
            'id' => $id,
        ];

        $result = $this->api->statuses_retweet_ID($param);
        if (($error = CitCuit::parseError($result)) != FALSE) {
            return view('error', $error);
        }

        return redirect('');
    }

    public function unretweet(Request $request, $tweet_id) {
        $param = [
            'id' => $tweet_id,
        ];
        $result = $this->api->statuses_destroy_ID($param);
        if (($error = CitCuit::parseError($result, 'Unretweet')) != FALSE) {
            return view('error', $error);
        }

        return redirect('');
    }

}
