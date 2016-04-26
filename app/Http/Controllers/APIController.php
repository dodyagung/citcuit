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
        ];

        if (isset($result->content)) {
            $render['timeline'] = $this->citcuit->parseResult($result, 'tweet');
        } else {
            $render['timeline'] = 'Your timeline is currently empty. Follow people and topics you find interesting to see their Tweets in your timeline.';
        }

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
        ];

        if (isset($result->content)) {
            $render['timeline'] = $this->citcuit->parseResult($result, 'tweet');
        } else {
            $render['timeline'] = 'Your mentions is currently empty. Get it here when people interact with you.';
        }

        return view($this->view_prefix . 'mentions', $render);
    }

    public function getUser(Request $request, $screen_name, $max_id = false) {
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
            if (isset($result->content)) {
                $render['timeline'] = $this->citcuit->parseResult($result, 'tweet');
            } else {
                $render['timeline'] = '@' . $screen_name . ' hasn\'t tweeted yet.';
            }
        }

        return view($this->view_prefix . 'user', $render);
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
        ];

        if (isset($result->content)) {
            $render['timeline'] = $this->citcuit->parseResult($result, 'message');
        } else {
            $render['timeline'] = 'You don\'t have any incoming messages yet.';
        }

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
        ];

        if (isset($result->content)) {
            $render['timeline'] = $this->citcuit->parseResult($result, 'message');
        } else {
            $render['timeline'] = 'You don\'t have any sent messages yet.';
        }

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

    public function getSearch(Request $request) {
        $q = $request->input('q');
        $result_type = $request->input('result_type');
        $max_id = $request->input('max_id');

        if (!$result_type) {
            $result_type = 'mixed';
        }

        if (!$q) {
            $render = [
                'result_type' => $result_type,
            ];

            return view($this->view_prefix . 'search', $render);
        } else {
            $param = [
                'count' => 10,
                'q' => $q,
                'result_type' => $result_type
            ];
            if ($max_id) {
                $param['max_id'] = $max_id;
            }
            $result = $this->api->search_tweets($param);

            $error = $this->citcuit->parseError($result, 'Tweet Search');
            if ($error) {
                return view('error', $error);
            }

            $render = [
                'rate' => [
                    'Tweet Search' => $this->citcuit->parseRateLimit($result),
                ],
                'q' => $q,
                'result_type' => $result_type,
            ];

            if (count($result->statuses) != 0) {
                $render['timeline'] = $this->citcuit->parseResult($result->statuses, 'search');
            } else {
                $render['timeline'] = 'No results.';
            }

            return view($this->view_prefix . 'search', $render);
        }
    }

    public function getSettings(Request $request) {
        $render = [];

        return view($this->view_prefix . 'settings', $render);
    }

    public function getSettingsProfile(Request $request) {
        $param = [
            'screen_name' => session('citcuit.oauth.screen_name')
        ];
        $result = $this->api->users_show($param);

        $error = $this->citcuit->parseError($result, 'Edit Profile');
        if ($error) {
            return view('error', $error);
        }

        $render = [
            'rate' => [
                'Profile' => $this->citcuit->parseRateLimit($result),
            ],
            'profile' => $this->citcuit->parseProfile($result),
        ];

        return view($this->view_prefix . 'settings_profile', $render);
    }

    public function postSettingsProfile(Request $request) {
        $param = [
            'name' => $request->name,
            'url' => $request->url,
            'location' => $request->location,
            'description' => $request->description,
        ];
        $result = $this->api->account_updateProfile($param);

        $error = $this->citcuit->parseError($result);
        if ($error) {
            return view('error', $error);
        }

        return redirect()
                        ->back()
                        ->with('success', 'Profile updated!');
    }

    public function getSettingsProfileImage(Request $request) {
        $param = [
            'screen_name' => session('citcuit.oauth.screen_name')
        ];
        $result = $this->api->users_show($param);

        $error = $this->citcuit->parseError($result, 'Edit Profile Image');
        if ($error) {
            return view('error', $error);
        }

        $render = [
            'rate' => [
                'Profile' => $this->citcuit->parseRateLimit($result),
            ],
            'profile' => $this->citcuit->parseProfile($result),
        ];

        return view($this->view_prefix . 'settings_profile_image', $render);
    }

    public function postSettingsProfileImage(Request $request) {
        $param = [
            'image' => $request->file('image'),
        ];

        $result = $this->api->account_updateProfileImage($param);

        $error = $this->citcuit->parseError($result);
        if ($error) {
            return view('error', $error);
        }

        return redirect()
                        ->back()
                        ->with('success', 'Profile image updated!');
    }

    public function getTrends(Request $request) {
        // locations
        $result = $this->api->trends_available();

        $error = $this->citcuit->parseError($result, 'Trends Location');
        if ($error) {
            return view('error', $error);
        }

        $render = [
            'rate' => [
                'Trends Location' => $this->citcuit->parseRateLimit($result),
            ],
            'locations' => $this->citcuit->parseTrendsLocations($result),
        ];

        // trends
        $location = $request->input('location');

        if (!$location) {
            $location = 1;
        }

        $param = [
            'id' => $location,
        ];
        $result = $this->api->trends_place($param);

        $error = $this->citcuit->parseError($result, 'Trends Result');
        if ($error) {
            return view('error', $error);
        }

        $render['rate']['Trends Result'] = $this->citcuit->parseRateLimit($result);
        $render['results'] = $this->citcuit->parseTrendsResults($result);
        $render['curent_location'] = $location;

        return view($this->view_prefix . 'trends', $render);
    }

    public function getUpload(Request $request) {
        return view($this->view_prefix . 'upload');
    }

    public function postUpload(Request $request) {
        $media_files = [
            $request->file('image1')
        ];
        if ($request->hasFile('image2') && $request->file('image2')->isValid()) {
            $media_files[] = $request->file('image2');
        }
        if ($request->hasFile('image3') && $request->file('image3')->isValid()) {
            $media_files[] = $request->file('image3');
        }
        if ($request->hasFile('image4') && $request->file('image4')->isValid()) {
            $media_files[] = $request->file('image4');
        }

        $media_ids = [];

        foreach ($media_files as $file) {
            $result = $this->api->media_upload([
                'media' => $file
            ]);

            $error = $this->citcuit->parseError($result);
            if ($error) {
                return view('error', $error);
            }

            $media_ids[] = $result->media_id_string;
        }

        $media_ids = implode(',', $media_ids);

        $param = [
            'status' => $request->tweet,
            'media_ids' => $media_ids
        ];

        $result = $this->api->statuses_update($param);

        $error = $this->citcuit->parseError($result);
        if ($error) {
            return view('error', $error);
        }

        return redirect('');
    }

    public function getFollowers(Request $request, $screen_name, $cursor = null) {
        $param = [
            'screen_name' => $screen_name,
            'count' => 10,
        ];
        if ($cursor) {
            $param['cursor'] = $cursor;
        }
        $result = $this->api->followers_list($param);

        $error = $this->citcuit->parseError($result, 'Followers');
        if ($error) {
            return view('error', $error);
        }

        $render = [
            'rate' => [
                'Followers' => $this->citcuit->parseRateLimit($result),
            ],
            'screen_name' => $screen_name,
        ];

        if (isset($result->content)) {
            $render['users'] = $this->citcuit->parseResult($result, 'profile');
        } else {
            $render['users'] = '@' . $screen_name . ' isn\'t following anyone yet.';
        }

        return view($this->view_prefix . 'followers', $render);
    }

    public function getFollowing(Request $request, $screen_name, $cursor = null) {
        $param = [
            'screen_name' => $screen_name,
            'count' => 10,
        ];
        if ($cursor) {
            $param['cursor'] = $cursor;
        }
        $result = $this->api->friends_list($param);

        $error = $this->citcuit->parseError($result, 'Following');
        if ($error) {
            return view('error', $error);
        }

        $render = [
            'rate' => [
                'Following' => $this->citcuit->parseRateLimit($result),
            ],
            'screen_name' => $screen_name,
        ];

        if (isset($result->content)) {
            $render['users'] = $this->citcuit->parseResult($result, 'profile');
        } else {
            $render['users'] = '@' . $screen_name . ' isn\'t following anyone yet.';
        }

        return view($this->view_prefix . 'following', $render);
    }

    public function getLikes(Request $request, $screen_name, $max_id = false) {
        $param = [
            'count' => 10,
            'screen_name' => $screen_name
        ];
        if ($max_id) {
            $param['max_id'] = $max_id;
        }
        $result = $this->api->favorites_list($param);

        $error = $this->citcuit->parseError($result, 'Likes');
        if ($error) {
            return view('error', $error);
        }

        $render = [
            'rate' => [
                'Likes' => $this->citcuit->parseRateLimit($result),
            ],
            'screen_name' => $screen_name
        ];

        if (isset($result->content)) {
            $render['timeline'] = $this->citcuit->parseResult($result, 'tweet');
        } else {
            $render['timeline'] = '@' . $screen_name . ' hasn\'t liked any Tweets yet.';
        }

        return view($this->view_prefix . 'likes', $render);
    }

}
