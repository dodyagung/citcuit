<?php

namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller;
use Illuminate\Http\Request;
use Codebird\Codebird;

class APIController extends Controller {

    private $api;
    private $citcuit;
    private $view_prefix = 'api.';

    public function __construct() {
        $this->citcuit = new CitcuitController();

        Codebird::setConsumerKey(env('TWITTER_CONSUMER_KEY'), env('TWITTER_CONSUMER_SECRET'));
        $this->api = Codebird::getInstance();
        $this->api->setToken(session('citcuit.oauth.oauth_token'), session('citcuit.oauth.oauth_token_secret'));
//        $this->api->setReturnFormat(CODEBIRD_RETURNFORMAT_ARRAY);
//        $reply = $this->api->oauth2_token();
//        Codebird::setBearerToken($reply->access_token);
        $this->api->setConnectionTimeout(6000);
        $this->api->setTimeout(15000);
    }

    public function getHome(Request $request, $max_id = false) {
        $param = [
            'count' => 10,
        ];
        if ($max_id) {
            $param['max_id'] = $max_id;
        }
        $result = $this->api->statuses_homeTimeline($param);

        $error = $this->citcuit->parseError($result, 'Home');
        if ($error) {
            return view('error', $error);
        }

        $render = [
            'rate' => [
                'Home' => $this->citcuit->parseRateLimit($result),
            ],
            'timeline' => $this->citcuit->parseResult($result, 'tweet'),
        ];

        return view($this->view_prefix . 'home', $render);
    }

    public function getDetail(Request $request, $tweet_id) {
        $param = [
            'id' => $tweet_id,
            'include_my_retweet' => 'true'
        ];
        $result = $this->api->statuses_show_ID($param);

        $error = $this->citcuit->parseError($result, 'Tweet Detail');
        if ($error) {
            return view('error', $error);
        }

        $render = [
            'rate' => [
                'Tweet Detail' => $this->citcuit->parseRateLimit($result),
            ],
            'tweet' => $this->citcuit->parseTweet($result),
        ];

        return view($this->view_prefix . 'detail', $render);
    }

    public function getMentions(Request $request, $max_id = false) {
        $param = [
            'count' => 10,
        ];
        if ($max_id) {
            $param['max_id'] = $max_id;
        }
        $result = $this->api->statuses_mentionsTimeline($param);

        $error = $this->citcuit->parseError($result, 'Mentions');
        if ($error) {
            return view('error', $error);
        }

        $render = [
            'rate' => [
                'Mentions' => $this->citcuit->parseRateLimit($result),
            ],
            'timeline' => $this->citcuit->parseResult($result, 'tweet'),
        ];

        return view($this->view_prefix . 'mentions', $render);
    }

    public function getProfile(Request $request, $screen_name, $max_id = false) {
        $render = [
            'screen_name' => $screen_name,
        ];
        $render['rate'] = [];

        //user
        $param = [
            'screen_name' => $screen_name
        ];
        $result = $this->api->users_show($param);

        $error = $this->citcuit->parseError($result, 'Profile');
        if ($error) {
            return view('error', $error);
        }

        $render['rate']['Profile'] = $this->citcuit->parseRateLimit($result);
        $render['profile'] = $this->citcuit->parseProfile($result);

        //tweet
        if ($render['profile']->protected && !$render['profile']->following) { // not shown - if user is protected and NOT following
            $render['timeline'] = '<strong>@' . $screen_name . '\'s Tweets are protected.</strong><br /><br />';
            $render['timeline'] .= 'Only confirmed followers have access to @' . $screen_name . '\'s Tweets and complete profile.<br />';
            $render['timeline'] .= 'Click the "Follow" button to send a follow request.';
        } else {
            $param = [
                'screen_name' => $screen_name,
                'count' => 10,
            ];
            if ($max_id) {
                $param['max_id'] = $max_id;
            }
            $result = $this->api->statuses_userTimeline($param);

            $error = $this->citcuit->parseError($result, 'User Tweet');
            if ($error) {
                return view('error', $error);
            }

            $render['rate']['User Tweet'] = $this->citcuit->parseRateLimit($result);
            $render['timeline'] = $this->citcuit->parseResult($result, 'tweet');
        }

        return view($this->view_prefix . 'profile', $render);
    }

    public function postTweet(Request $request) {
        $param = [
            'status' => $request->tweet,
        ];
        $result = $this->api->statuses_update($param);

        $error = $this->citcuit->parseError($result);
        if ($error) {
            return view('error', $error);
        }

        return redirect('');
    }

    public function getLike(Request $request, $tweet_id) {
        $param = [
            'id' => $tweet_id,
        ];
        $result = $this->api->favorites_create($param);

        $error = $this->citcuit->parseError($result, 'Like');
        if ($error) {
            return view('error', $error);
        }

        return redirect()->back();
    }

    public function getUnlike(Request $request, $tweet_id) {
        $param = [
            'id' => $tweet_id,
        ];
        $result = $this->api->favorites_destroy($param);

        $error = $this->citcuit->parseError($result, 'Unlike');
        if ($error) {
            return view('error', $error);
        }

        return redirect()->back();
    }

    public function getReply(Request $request, $tweet_id) {
        $param = [
            'id' => $tweet_id,
        ];
        $result = $this->api->statuses_show_ID($param);

        $error = $this->citcuit->parseError($result, 'Reply');
        if ($error) {
            return view('error', $error);
        }

        $render = [
            'rate' => [
                'Reply' => $this->citcuit->parseRateLimit($result),
            ],
            'tweet' => $this->citcuit->parseTweet($result),
        ];

        return view($this->view_prefix . 'reply', $render);
    }

    public function postReply(Request $request) {
        $param = [
            'status' => $request->tweet,
            'in_reply_to_status_id' => $request->in_reply_to_status_id,
        ];
        $result = $this->api->statuses_update($param);

        $error = $this->citcuit->parseError($result);
        if ($error) {
            return view('error', $error);
        }

        return redirect('');
    }

    public function getDelete(Request $request, $tweet_id) {
        $param = [
            'id' => $tweet_id,
        ];
        $result = $this->api->statuses_show_ID($param);

        $error = $this->citcuit->parseError($result, 'Delete');
        if ($error) {
            return view('error', $error);
        }

        $render = [
            'rate' => [
                'Delete' => $this->citcuit->parseRateLimit($result),
            ],
            'tweet' => $this->citcuit->parseTweet($result),
        ];

        return view($this->view_prefix . 'delete', $render);
    }

    public function postDelete(Request $request) {
        $param = [
            'id' => $request->id,
        ];
        $result = $this->api->statuses_destroy_ID($param);

        $error = $this->citcuit->parseError($result);
        if ($error) {
            return view('error', $error);
        }

        return redirect('');
    }

    public function getRetweet(Request $request, $tweet_id) {
        $param = [
            'id' => $tweet_id,
            'include_my_retweet' => 'true'
        ];
        $result = $this->api->statuses_show_ID($param);

        $error = $this->citcuit->parseError($result, 'Retweet');
        if ($error) {
            return view('error', $error);
        }

        $render = [
            'rate' => [
                'Retweet' => $this->citcuit->parseRateLimit($result),
            ],
            'tweet' => $this->citcuit->parseTweet($result),
        ];

        return view($this->view_prefix . 'retweet', $render);
    }

    public function postRetweetWithComment(Request $request) {
        $param = [
            'status' => $request->tweet . ' ' . $request->retweet_link,
        ];
        $result = $this->api->statuses_update($param);

        $error = $this->citcuit->parseError($result);
        if ($error) {
            return view('error', $error);
        }

        return redirect('');
    }

    public function postRetweet(Request $request) {
        $param = [
            'id' => $request->id,
        ];
        $result = $this->api->statuses_retweet_ID($param);

        $error = $this->citcuit->parseError($result);
        if ($error) {
            return view('error', $error);
        }

        return redirect()->back();
    }

    public function postUnretweet(Request $request) {
        $param = [
            'id' => $request->id,
        ];
        $result = $this->api->statuses_unretweet_ID($param);

        $error = $this->citcuit->parseError($result);
        if ($error) {
            return view('error', $error);
        }

        return redirect()->back();
    }

    public function getFollow(Request $request, $screen_name) {
        $param = [
            'screen_name' => $screen_name,
        ];
        $result = $this->api->friendships_create($param);

        $error = $this->citcuit->parseError($result);
        if ($error) {
            return view('error', $error);
        }

        return redirect()->back();
    }

    public function getUnfollow(Request $request, $screen_name) {
        $param = [
            'screen_name' => $screen_name,
        ];
        $result = $this->api->friendships_destroy($param);

        $error = $this->citcuit->parseError($result, 'Unfollow');
        if ($error) {
            return view('error', $error);
        }

        return redirect()->back();
    }

    public function getMessages(Request $request, $max_id = false) {
        $param = [
            'count' => 10,
        ];
        if ($max_id) {
            $param['max_id'] = $max_id;
        }
        $result = $this->api->directMessages($param);

        $error = $this->citcuit->parseError($result, 'Messages');
        if ($error) {
            return view('error', $error);
        }

        $render = [
            'rate' => [
                'Messages' => $this->citcuit->parseRateLimit($result),
            ],
            'timeline' => $this->citcuit->parseResult($result, 'message'),
        ];

//        print_r($render); die();

        return view($this->view_prefix . 'messages', $render);
    }
    
    public function getMessagesSent(Request $request, $max_id = false) {
        $param = [
            'count' => 10,
        ];
        if ($max_id) {
            $param['max_id'] = $max_id;
        }
        $result = $this->api->directMessages_sent($param);

        $error = $this->citcuit->parseError($result, 'Sent Messages');
        if ($error) {
            return view('error', $error);
        }

        $render = [
            'rate' => [
                'Sent Messages' => $this->citcuit->parseRateLimit($result),
            ],
            'timeline' => $this->citcuit->parseResult($result, 'message'),
        ];

        return view($this->view_prefix . 'messages_sent', $render);
    }

    public function getMessagesCreate(Request $request, $screen_name = NULL) {

        $render = [
            'screen_name' => $screen_name
        ];

        return view($this->view_prefix . 'messages_create', $render);
    }

    public function postMessagesCreate(Request $request) {
        $param = [
            'screen_name' => $request->screen_name,
            'text' => $request->text,
        ];
        $result = $this->api->directMessages_new($param);

        $error = $this->citcuit->parseError($result);
        if ($error) {
            return view('error', $error);
        }

        return redirect('messages');
    }

    public function getMessagesDetail(Request $request, $id) {
        $param = [
            'id' => $id,
        ];
        $result = $this->api->directMessages_show($param);

        $error = $this->citcuit->parseError($result, 'Message Detail');
        if ($error) {
            return view('error', $error);
        }

        $render = [
            'rate' => [
                'Message Detail' => $this->citcuit->parseRateLimit($result),
            ],
            'message' => $this->citcuit->parseMessage($result),
        ];

        return view($this->view_prefix . 'messages_detail', $render);
    }

    public function getMessagesDelete(Request $request, $id) {
        $param = [
            'id' => $id,
        ];
        $result = $this->api->directMessages_show($param);

        $error = $this->citcuit->parseError($result, 'Message Delete');
        if ($error) {
            return view('error', $error);
        }

        $render = [
            'rate' => [
                'Message Delete' => $this->citcuit->parseRateLimit($result),
            ],
            'message' => $this->citcuit->parseMessage($result),
        ];

        return view($this->view_prefix . 'messages_delete', $render);
    }

    public function postMessagesDelete(Request $request) {
        $param = [
            'id' => $request->id,
        ];
        $result = $this->api->directMessages_destroy($param);

        $error = $this->citcuit->parseError($result);
        if ($error) {
            return view('error', $error);
        }

        return redirect('messages');
    }

}
