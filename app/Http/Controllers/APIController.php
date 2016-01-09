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

    private function _parseError($response, $location) {
        $limited = CitcuitController::parseError($response, $location);
        if ($limited != FALSE) {
            return view('error', $limited);
        }
    }

    public function home(Request $request, $max_id = false) {
        $param = [
            'count' => 10,
        ];
        if ($max_id) {
            $param['max_id'] = $max_id;
        }

        $result = $this->api->statuses_homeTimeline($param);
        if (CitCuit::parseError($result, 'Home') == TRUE) {
            return view('error', CitCuit::parseError($result, 'Home'));
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
        ];
        $result = $this->api->statuses_show_ID($param);
        if (CitCuit::parseError($result, 'Tweet Detail') == TRUE) {
            return view('error', CitCuit::parseError($result, 'Tweet Detail'));
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
        if (CitCuit::parseError($result, 'Mentions') == TRUE) {
            return view('error', CitCuit::parseError($result, 'Mentions'));
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
        if (CitCuit::parseError($result, 'Profile') == TRUE) {
            return view('error', CitCuit::parseError($result, 'Profile'));
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
        if (CitCuit::parseError($result, 'User Tweet') == TRUE) {
            return view('error', CitCuit::parseError($result, 'User Tweet'));
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
        if (CitCuit::parseError($result, 'Post Tweet') == TRUE) {
            return view('error', CitCuit::parseError($result, 'Post Tweet'));
        }

        return redirect('');
    }

    public function like(Request $request, $tweet_id) {
        $param = [
            'id' => $tweet_id,
        ];
        $result = $this->api->favorites_create($param);
        if (CitCuit::parseError($result, 'Like') == TRUE) {
            return view('error', CitCuit::parseError($result, 'Like'));
        }

        return redirect()->back();
    }

    public function unlike(Request $request, $tweet_id) {
        $param = [
            'id' => $tweet_id,
        ];
        $result = $this->api->favorites_destroy($param);
        if (CitCuit::parseError($result, 'Unlike') == TRUE) {
            return view('error', CitCuit::parseError($result, 'Unlike'));
        }

        return redirect()->back();
    }

    public function reply(Request $request, $tweet_id) {
        $param = [
            'id' => $tweet_id,
        ];
        $result = $this->api->statuses_show_ID($param);
        if (CitCuit::parseError($result, 'Reply') == TRUE) {
            return view('error', CitCuit::parseError($result, 'Reply'));
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
        if (CitCuit::parseError($result, 'Post Reply') == TRUE) {
            return view('error', CitCuit::parseError($result, 'Post Reply'));
        }

        return redirect('');
    }

    public function delete(Request $request, $tweet_id) {
        $param = [
            'id' => $tweet_id,
        ];
        $result = $this->api->statuses_show_ID($param);
        if (CitCuit::parseError($result, 'Delete') == TRUE) {
            return view('error', CitCuit::parseError($result, 'Delete'));
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
        if (CitCuit::parseError($result, 'Post Delete') == TRUE) {
            return view('error', CitCuit::parseError($result, 'Post Delete'));
        }

        return redirect('');
    }
    
    public function retweet(Request $request, $tweet_id) {
        $param = [
            'id' => $tweet_id,
        ];
        $result = $this->api->statuses_show_ID($param);
        if (CitCuit::parseError($result, 'Retweet') == TRUE) {
            return view('error', CitCuit::parseError($result, 'Retweet'));
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
        if (CitCuit::parseError($result, 'Post Retweet with Comment') == TRUE) {
            return view('error', CitCuit::parseError($result, 'Post Retweet with Comment'));
        }

        return redirect('');
    }

    public function postRetweet(Request $request) {
        $id = $request->id;

        $param = [
            'id' => $id,
        ];

        $result = $this->api->statuses_retweet_ID($param);
        if (CitCuit::parseError($result, 'Post Retweet') == TRUE) {
            return view('error', CitCuit::parseError($result, 'Post Retweet'));
        }

        return redirect('');
    }

}
