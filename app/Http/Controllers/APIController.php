<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Codebird\Codebird;

class APIController extends Controller
{
    private $api;
    private $citcuit;
    private $view_prefix = 'api.';

    public function __construct()
    {
        Codebird::setConsumerKey(env('TWITTER_CONSUMER_KEY'), env('TWITTER_CONSUMER_SECRET'));

        $this->citcuit = new CitcuitController();

        $this->api = Codebird::getInstance();
        $this->api->setConnectionTimeout(env('TWITTER_CONNECTION_TIME'), 3000);
        $this->api->setTimeout(env('TWITTER_TIMEOUT'), 10000);

        $this->middleware(function ($request, $next) {
            $this->api->setToken(session('auth.oauth_token'), session('auth.oauth_token_secret'));

            return $next($request);
        });
    }

    public function getHome(Request $request, $max_id = false)
    {
        $param = [
            'count' => $this->citcuit->parseSetting('tweets_per_page'),
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

        $parse = $this->citcuit->parseResult($result, 'tweet');
        if (count($parse->content) != 0) {
            $render['timeline'] = $parse;
        } else {
            $render['timeline'] = 'Your timeline is currently empty. Follow people and topics you find interesting to see their Tweets in your timeline.';
        }

        return view($this->view_prefix.'home', $render);
    }

    public function getDetail(Request $request, $tweet_id)
    {
        $param = [
            'id' => $tweet_id,
            'include_my_retweet' => 'true',
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

        return view($this->view_prefix.'detail', $render);
    }

    public function getMentions(Request $request, $max_id = false)
    {
        $param = [
            'count' => $this->citcuit->parseSetting('tweets_per_page'),
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

        $parse = $this->citcuit->parseResult($result, 'tweet');
        if (count($parse->content) != 0) {
            $render['timeline'] = $parse;
        } else {
            $render['timeline'] = 'Your mentions is currently empty. Get it here when people interact with you.';
        }

        return view($this->view_prefix.'mentions', $render);
    }

    public function getUser(Request $request, $screen_name, $max_id = false)
    {
        $render = [
            'screen_name' => $screen_name,
        ];

        $render['rate'] = [];

        //user
        $param = [
            'screen_name' => $screen_name,
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
            $render['protected'] = true;
            $render['timeline'] = '<strong>@'.$screen_name.'\'s Tweets are protected.</strong><br /><br />';
            $render['timeline'] .= 'Only confirmed followers have access to @'.$screen_name.'\'s Tweets and complete profile.<br />';
            $render['timeline'] .= 'Click the "Follow" button to send a follow request.';
        } else {
            $render['protected'] = false;

            $param = [
                'screen_name' => $screen_name,
                'count' => $this->citcuit->parseSetting('tweets_per_page'),
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

            $parse = $this->citcuit->parseResult($result, 'tweet');
            if (count($parse->content) != 0) {
                $render['timeline'] = $parse;
            } else {
                $render['timeline'] = '@'.$screen_name.' hasn\'t tweeted yet.';
            }
        }

        $render['setting'] = [
            'header_image' => $this->citcuit->parseSetting('header_image')
        ];

        return view($this->view_prefix.'user', $render);
    }

    public function postTweet(Request $request)
    {
        $param = [
            'status' => $request->input('tweet'),
        ];
        $result = $this->api->statuses_update($param);

        $error = $this->citcuit->parseError($result);
        if ($error) {
            return view('error', $error);
        }

        if ($request->has('fb')) {
            $fb = new FacebookController();
            $fb->loadToken();
            $fb->postFeed($request->input('tweet'));
        }

        return redirect('/');
    }

    public function getLike(Request $request, $tweet_id)
    {
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

    public function getUnlike(Request $request, $tweet_id)
    {
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

    public function getReply(Request $request, $tweet_id)
    {
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

        return view($this->view_prefix.'reply', $render);
    }

    public function postReply(Request $request)
    {
        $param = [
            'status' => $request->input('tweet'),
            'in_reply_to_status_id' => $request->in_reply_to_status_id,
        ];
        $result = $this->api->statuses_update($param);

        $error = $this->citcuit->parseError($result);
        if ($error) {
            return view('error', $error);
        }

        if ($request->has('fb')) {
            $fb = new FacebookController();
            $fb->loadToken();
            $fb->postFeed($request->input('tweet'));
        }

        return redirect('/');
    }

    public function getDelete(Request $request, $tweet_id)
    {
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

        return view($this->view_prefix.'delete', $render);
    }

    public function postDelete(Request $request)
    {
        $param = [
            'id' => $request->input('id'),
        ];
        $result = $this->api->statuses_destroy_ID($param);

        $error = $this->citcuit->parseError($result);
        if ($error) {
            return view('error', $error);
        }

        return redirect('/');
    }

    public function getRetweet(Request $request, $tweet_id)
    {
        $param = [
            'id' => $tweet_id,
            'include_my_retweet' => 'true',
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

        return view($this->view_prefix.'retweet', $render);
    }

    public function postRetweetWithComment(Request $request)
    {
        $param = [
            'status' => $request->input('tweet').' '.$request->input('retweet_link'),
        ];
        $result = $this->api->statuses_update($param);

        $error = $this->citcuit->parseError($result);
        if ($error) {
            return view('error', $error);
        }

        if ($request->has('fb')) {
            $fb = new FacebookController();
            $fb->loadToken();
            $fb->postFeed($request->input('tweet'));
        }

        return redirect('/');
    }

    public function postRetweet(Request $request)
    {
        $param = [
            'id' => $request->input('id'),
        ];
        $result = $this->api->statuses_retweet_ID($param);

        $error = $this->citcuit->parseError($result);
        if ($error) {
            return view('error', $error);
        }

        return redirect()->back();
    }

    public function postUnretweet(Request $request)
    {
        $param = [
            'id' => $request->input('id'),
        ];
        $result = $this->api->statuses_unretweet_ID($param);

        $error = $this->citcuit->parseError($result);
        if ($error) {
            return view('error', $error);
        }

        return redirect()->back();
    }

    public function getFollow(Request $request, $screen_name)
    {
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

    public function getUnfollow(Request $request, $screen_name)
    {
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

    public function getMessages(Request $request, $max_id = false)
    {
        $param = [
            'count' => $this->citcuit->parseSetting('tweets_per_page'),
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

        $parse = $this->citcuit->parseResult($result, 'message');
        if (count($parse->content) != 0) {
            $render['timeline'] = $parse;
        } else {
            $render['timeline'] = 'You don\'t have any incoming messages yet.';
        }

        return view($this->view_prefix.'messages', $render);
    }

    public function getMessagesSent(Request $request, $max_id = false)
    {
        $param = [
            'count' => $this->citcuit->parseSetting('tweets_per_page'),
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

        $parse = $this->citcuit->parseResult($result, 'message');
        if (count($parse->content) != 0) {
            $render['timeline'] = $parse;
        } else {
            $render['timeline'] = 'You don\'t have any sent messages yet.';
        }

        return view($this->view_prefix.'messages_sent', $render);
    }

    public function getMessagesCreate(Request $request, $screen_name = null)
    {
        $render = [
            'screen_name' => $screen_name,
        ];

        return view($this->view_prefix.'messages_create', $render);
    }

    public function postMessagesCreate(Request $request)
    {
        $param = [
            'screen_name' => $request->input('screen_name'),
            'text' => $request->input('text'),
        ];
        $result = $this->api->directMessages_new($param);

        $error = $this->citcuit->parseError($result);
        if ($error) {
            return view('error', $error);
        }

        return redirect('messages');
    }

    public function getMessagesDetail(Request $request, $id)
    {
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

        return view($this->view_prefix.'messages_detail', $render);
    }

    public function getMessagesDelete(Request $request, $id)
    {
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

        return view($this->view_prefix.'messages_delete', $render);
    }

    public function postMessagesDelete(Request $request)
    {
        $param = [
            'id' => $request->input('id'),
        ];
        $result = $this->api->directMessages_destroy($param);

        $error = $this->citcuit->parseError($result);
        if ($error) {
            return view('error', $error);
        }

        return redirect('messages');
    }

    public function getSearch(Request $request)
    {
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

            return view($this->view_prefix.'search', $render);
        } else {
            $param = [
                'count' => $this->citcuit->parseSetting('tweets_per_page'),
                'q' => $q,
                'result_type' => $result_type,
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

            return view($this->view_prefix.'search', $render);
        }
    }

    public function getSearchUser(Request $request)
    {
        $q = $request->input('q');
        $page = $request->input('page');

        if (!$q) {
            $render = [];

            return view($this->view_prefix.'search_user', $render);
        } else {
            $param = [
                'count' => $this->citcuit->parseSetting('tweets_per_page'),
                'q' => $q,
            ];

            if (!$page) {
                $page = 1;
            } else {
                $page = (int) $page;
            }

            $param['page'] = $page;

            $result = $this->api->users_search($param);

            $error = $this->citcuit->parseError($result, 'User Search');
            if ($error) {
                return view('error', $error);
            }

            $render = [
                'rate' => [
                    'User Search' => $this->citcuit->parseRateLimit($result),
                ],
                'q' => $q,
            ];

            $parse = $this->citcuit->parseResult($result, 'search_user');

            if (count($parse->content) != 0) {
                $render['users'] = $parse;
            } else {
                $render['users'] = 'No user search result.';
            }

            if ($page == 1) {
                $render['page_next'] = $page + 1;
                $render['page_prev'] = null;
            } elseif ($page == 2) {
                $render['page_next'] = $page + 1;
                $render['page_prev'] = false;
            } else {
                $render['page_next'] = $page + 1;
                $render['page_prev'] = $page - 1;
            }

            return view($this->view_prefix.'search_user', $render);
        }
    }

    public function getSettings(Request $request)
    {
        $render = [];

        return view($this->view_prefix.'settings', $render);
    }

    public function getSettingsGeneralReset(Request $request)
    {
        $request->session()->forget('auth.settings');

        return redirect()
                        ->back()
                        ->with('success', 'General setting reseted!');
    }

    public function getSettingsGeneral(Request $request)
    {
        $render = [
            'settings' => [
                'header_image' => $this->citcuit->parseSetting('header_image'),
                'tweets_per_page' => $this->citcuit->parseSetting('tweets_per_page'),
                'auto_refresh' => $this->citcuit->parseSetting('auto_refresh'),
                'timezone' => $this->citcuit->parseSetting('timezone'),
            ],
            'timezone' => $this->citcuit->parseTimeZone(),
        ];

        return view($this->view_prefix.'settings_general', $render);
    }

    public function postSettingsGeneral(Request $request)
    {
        session([
            'auth.settings.header_image' => $request->header_image,
            'auth.settings.tweets_per_page' => $request->tweets_per_page,
            'auth.settings.auto_refresh' => $request->auto_refresh,
            'auth.settings.timezone' => $request->timezone,
        ]);

        return redirect()
                        ->back()
                        ->with('success', 'General setting updated!');
    }

    public function getSettingsProfile(Request $request)
    {
        $param = [
            'screen_name' => session('auth.screen_name'),
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

        return view($this->view_prefix.'settings_profile', $render);
    }

    public function postSettingsProfile(Request $request)
    {
        $param = [
            'name' => $request->input('name'),
            'url' => $request->input('url'),
            'location' => $request->input('location'),
            'description' => $request->input('description'),
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

    public function getSettingsProfileImage(Request $request)
    {
        $param = [
            'screen_name' => session('auth.screen_name'),
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

        return view($this->view_prefix.'settings_profile_image', $render);
    }

    public function postSettingsProfileImage(Request $request)
    {
        $param = [
            'image' => $request->image,
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

    public function getSettingsProfileHeader(Request $request)
    {
        $param = [
            'screen_name' => session('auth.screen_name'),
        ];
        $result = $this->api->users_show($param);

        $error = $this->citcuit->parseError($result, 'Edit Profile Header');
        if ($error) {
            return view('error', $error);
        }

        $render = [
            'rate' => [
                'Profile' => $this->citcuit->parseRateLimit($result),
            ],
            'profile' => $this->citcuit->parseProfile($result),
        ];

        return view($this->view_prefix.'settings_profile_header', $render);
    }

    public function getSettingsProfileHeaderRemove(Request $request)
    {
        $result = $this->api->account_removeProfileBanner();

        $error = $this->citcuit->parseError($result);
        if ($error) {
            return view('error', $error);
        }

        return redirect()
                        ->back()
                        ->with('success', 'Profile header removed!');
    }

    public function postSettingsProfileHeader(Request $request)
    {
        $param = [
            'banner' => $request->image,
        ];

        $result = $this->api->account_updateProfileBanner($param);

        $error = $this->citcuit->parseError($result);
        if ($error) {
            return view('error', $error);
        }

        return redirect()
                        ->back()
                        ->with('success', 'Profile header updated!');
    }

    public function getSettingsFacebookLogin(Request $request)
    {
        $fb = new FacebookController();

        if ($request->input('code')) {
            $fb->loginCallback($request->fullUrl());

            return redirect('settings/facebook');
        } else {
            return redirect($fb->loginUrl());
        }
    }

    public function getSettingsFacebookLogout(Request $request)
    {
        $fb = new FacebookController();
        $fb->logout($request);

        return redirect('settings/facebook');
    }

    public function getSettingsFacebook(Request $request)
    {
        $fb = new FacebookController();

        if ($fb->checkToken()) {
            $fb->loadToken();
            $render = [
                'logged_in' => true,
                'user' => $fb->getUser(),
            ];
        } else {
            $render = [
                'logged_in' => false,
            ];
        }

        return view($this->view_prefix.'settings_facebook', $render);
    }

    public function getTrends(Request $request)
    {
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

        return view($this->view_prefix.'trends', $render);
    }

    public function getUpload(Request $request)
    {
        return view($this->view_prefix.'upload');
    }

    public function postUpload(Request $request)
    {
        $this->validate($request, [
              'image1' => 'required|image',
              'image2' => 'image',
              'image3' => 'image',
              'image4' => 'image',
          ]);
        $media_files = [
            $request->file('image1'),
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
                'media' => $file,
            ]);

            $error = $this->citcuit->parseError($result);
            if ($error) {
                return view('error', $error);
            }

            $media_ids[] = $result->media_id_string;
        }

        if ($request->has('fb')) {
            $fb = new FacebookController();
            $fb->loadToken();
            $fb->postImage($request->input('tweet'), $media_files);
        }

        $media_ids = implode(',', $media_ids);

        $param = [
            'status' => $request->input('tweet'),
            'media_ids' => $media_ids,
        ];

        $result = $this->api->statuses_update($param);

        $error = $this->citcuit->parseError($result);
        if ($error) {
            return view('error', $error);
        }

        return redirect('/');
    }

    public function getFollowers(Request $request, $screen_name, $cursor = null)
    {
        $param = [
            'screen_name' => $screen_name,
            'count' => $this->citcuit->parseSetting('tweets_per_page'),
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

        $parse = $this->citcuit->parseResult($result, 'profile');
        if (count($parse->content) != 0) {
            $render['users'] = $parse;
        } else {
            $render['users'] = '@'.$screen_name.' doesn\'t have any followers yet.';
        }

        return view($this->view_prefix.'followers', $render);
    }

    public function getFollowing(Request $request, $screen_name, $cursor = null)
    {
        $param = [
            'screen_name' => $screen_name,
            'count' => $this->citcuit->parseSetting('tweets_per_page'),
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

        $parse = $this->citcuit->parseResult($result, 'profile');
        if (count($parse->content) != 0) {
            $render['users'] = $parse;
        } else {
            $render['users'] = '@'.$screen_name.' isn\'t following anyone yet.';
        }

        return view($this->view_prefix.'following', $render);
    }

    public function getLikes(Request $request, $screen_name, $max_id = false)
    {
        $param = [
            'count' => $this->citcuit->parseSetting('tweets_per_page'),
            'screen_name' => $screen_name,
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
            'screen_name' => $screen_name,
        ];

        $parse = $this->citcuit->parseResult($result, 'tweet');
        if (count($parse->content) != 0) {
            $render['timeline'] = $parse;
        } else {
            $render['timeline'] = '@'.$screen_name.' hasn\'t liked any Tweets yet.';
        }

        return view($this->view_prefix.'likes', $render);
    }
}
