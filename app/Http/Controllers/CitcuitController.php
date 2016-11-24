<?php

namespace App\Http\Controllers;

use Carbon\Carbon;

class CitcuitController
{
    public function parseEncodeURI($url)
    {
        // http://stackoverflow.com/questions/4929584/encodeuri-in-php/6059053#6059053
        // http://php.net/manual/en/function.rawurlencode.php
        // https://developer.mozilla.org/en/JavaScript/Reference/Global_Objects/encodeURI
        $unescaped = array(
            '%2D' => '-', '%5F' => '_', '%2E' => '.', '%21' => '!', '%7E' => '~',
            '%2A' => '*', '%27' => "'", '%28' => '(', '%29' => ')',
        );
        $reserved = array(
            '%3B' => ';', '%2C' => ',', '%2F' => '/', '%3F' => '?', '%3A' => ':',
            '%40' => '@', '%26' => '&', '%3D' => '=', '%2B' => '+', '%24' => '$',
        );
        $score = array(
            '%23' => '#',
        );

        return strtr(rawurlencode($url), array_merge($reserved, $unescaped, $score));
    }

    public function parseTimeZone()
    {
        $zones_array = [];
        foreach (timezone_identifiers_list() as $key => $zone) {
            $zones_array[$key]['zone'] = $zone;
            $zones_array[$key]['time'] = Carbon::now($zone)->format('H:i');
            $zones_array[$key]['diff'] = 'UTC'.sprintf('%+d', Carbon::now($zone)->offset / 3600);
        }

        return $zones_array;
    }
    public function parseSetting($setting_id)
    {
        $setting_default = config('citcuit.settings');
        $setting_session = session('auth.settings');

        if (!isset($setting_session[$setting_id]) || is_null($setting_session[$setting_id])) {
            $result = $setting_default[$setting_id];
        } else {
            $result = $setting_session[$setting_id];
        }

        return $result;
    }

    private function parseNumber($n, $startfrom = 10000)
    {
        if (!is_null($n) || $n != '') {
            if ($n < $startfrom) {
                $n_format = number_format($n);
            } elseif ($n < 1000000) {
                $n_format = number_format($n / 1000, 1).'K';
            } elseif ($n < 1000000000) {
                $n_format = number_format($n / 1000000, 1).'M';
            }

            return $n_format;
        } else {
            return $n;
        }
    }

    private function parseLinkHttp($text)
    {
        return preg_replace('/(^|\s)(https?:\/\/[\da-z\.-]+\.[a-z]+)(\/[^\s]*)?/i', ' <a href="$2$3" target="_blank">$2$3</a>', $text);
    }

    private function parseLinkUser($text)
    {
        return preg_replace('/(^|\s)@(\w{1,15})/i', ' <a href="'.url('user/$2').'">@$2</a>', $text);
    }

    private function parseLinkEmail($text)
    {
        return preg_replace('/([\w\.-]+@[\da-z\.-]+\.[a-z]+)/i', ' <a href="mailto:$1">$1</a>', $text);
    }

    private function parseLinkHashtag($text)
    {
        return preg_replace('/(^|\s)#(\w+)/i', ' <a href="'.url('search?q=%23$2').'">#$2</a>', $text);
    }

    public function parseProfile($profile)
    {
        $profile->description_nohref = $profile->description;
        if ($profile->description == null) {
            $profile->description = '-';
        } else {
            if (isset($profile->entities->description->urls) && count($profile->entities->description->urls) != 0) {
                $urls = $profile->entities->description->urls;
                foreach ($urls as $url) {
                    $profile->description_original = $profile->description;
                    $profile->description = str_replace($url->url, '<a href="'.$url->url.'" target="_blank">'.$url->display_url.'</a>', $profile->description);
                    $profile->description_nohref = str_replace($url->url, $url->display_url, $profile->description);
                }
            }

            $profile->description = $this->parseLinkEmail($profile->description);
            $profile->description = $this->parseLinkHashtag($profile->description);
            $profile->description = $this->parseLinkHttp($profile->description);
            $profile->description = $this->parseLinkUser($profile->description);
        }

        $profile->location_nohref = $profile->location;
        if ($profile->location == null) {
            $profile->location = '-';
        }

        if ($profile->url == null) {
            $profile->url_nohref = $profile->url;
            $profile->url = '-';
        } else {
            if (isset($profile->entities->url->urls) && count($profile->entities->url->urls) != 0) {
                $urls = $profile->entities->url->urls;
                foreach ($urls as $url) {
                    $profile->url_original = $profile->url;
                    if (!isset($url->display_url)) {
                        $url->display_url = $url->url;
                    }
                    $profile->url = str_replace($url->url, '<a href="'.$url->url.'" target="_blank">'.$url->display_url.'</a>', $profile->url);
                    $profile->url_nohref = $url->display_url;
                }
            }
        }

        $created_at_utc = Carbon::parse($profile->created_at);
        $created_at = Carbon::createFromTimestamp($created_at_utc->timestamp, $this->parseSetting('timezone'));
        $profile->created_at = $created_at->format('j M Y \\- H:i');
        $diffInDays = $created_at->diffInDays(Carbon::now($this->parseSetting('timezone')));
        if ($diffInDays == 0) { // prevent division by zero
            $diffInDays = 1;
        }
        $profile->tweets_per_day = round($profile->statuses_count / $diffInDays, 1);

        $profile->statuses_count = $this->parseNumber($profile->statuses_count);
        $profile->friends_count = $this->parseNumber($profile->friends_count);
        $profile->followers_count = $this->parseNumber($profile->followers_count);
        $profile->favourites_count = $this->parseNumber($profile->favourites_count);

        $profile->profile_image_url_https_full = str_replace('_normal', '', $profile->profile_image_url_https);

        return $profile;
    }

    public function parseTweet($tweet, $search = false)
    {
        if ($search) {
            if (isset($tweet->retweeted_status)) {
                // on search, retweeted quoted status doesn't contain user entities
                unset($tweet->retweeted_status->quoted_status);
            }
        }

        //created at
        $timeTweet = Carbon::createFromTimestamp(strtotime($tweet->created_at), $this->parseSetting('timezone'));
        $tweet->created_at_original = $timeTweet->format('H:i \\- j M Y');
        if ($this->parseSetting('time_diff') == 1) {
            $tweet->created_at = $timeTweet->diffForHumans();
        } else {
            $tweet->created_at = $tweet->created_at_original;
        }

        //source
        $tweet->source = preg_replace('/<a href=".*" rel="nofollow">(.*)<\/a>/i', '$1', $tweet->source);

        // form - reply destination
        if ($tweet->user->screen_name != session('citcuit.oauth.screen_name')) {
            $tweet->reply_destination = '@'.$tweet->user->screen_name.' ';
        } else {
            $tweet->reply_destination = null;
        }
        preg_match_all('/@([a-zA-Z0-9_]{1,15})/i', $tweet->text, $matchs);
        foreach ($matchs[1] as $match) {
            if ($match != session('citcuit.oauth.screen_name')) {
                $tweet->reply_destination .= '@'.$match.' ';
            }
        }
        if (isset($tweet->quoted_status)) {
            if (isset($tweet->quoted_status->user)) {
                $tweet->reply_destination .= '@'.$tweet->quoted_status->user->screen_name.' ';
            }
            preg_match_all('/@([a-zA-Z0-9_]{1,15})/i', $tweet->quoted_status->text, $matchs);
            foreach ($matchs[1] as $match) {
                if ($match != $tweet->user->screen_name) {
                    $tweet->reply_destination .= '@'.$match.' ';
                }
            }
        }
        if (trim($tweet->reply_destination) == '') {
            $tweet->reply_destination .= '@'.$tweet->user->screen_name;
        }

        // text - t.co
        $urls = $tweet->entities->urls;
        foreach ($urls as $url) {
            // if contain twitter quote, delete it
            if (strpos($url->expanded_url, 'twitter.com') !== false && strpos($url->expanded_url, '/status/') !== false) {
                $tweet->text = str_replace($url->url, '', $tweet->text);
            } else {
                $tweet->text = str_replace($url->url, '<a href="'.$url->url.'" target="_blank">'.$url->display_url.'</a>', $tweet->text);
            }
        }

        // text - image
        if (isset($tweet->extended_entities->media)) {
            $medias = $tweet->extended_entities->media;
            $tweet->citcuit_media = [];
            $remove_image_link = true;
            foreach ($medias as $media) {
                if ($remove_image_link) {
                    $tweet->text = str_replace($media->url, '<a href="'.$media->url.'" target="_blank">'.$media->display_url.'</a>', $tweet->text);
                }
                $tweet->citcuit_media[] = '<a href="'.$media->media_url_https.':large" target="_blank"><img src="'.$media->media_url_https.'" width="'.$media->sizes->thumb->w.'" /></a><br />';
                $remove_image_link = false;
            }
        }

        // text - twitter official video
        if (isset($tweet->extended_entities->media)) {
            $medias = $tweet->extended_entities->media;
            foreach ($medias as $media) {
                if (isset($media->video_info)) {
                    $tweet->citcuit_media = [];
                    $video_bitrate = 0;
                    $video_bitrate_preview = PHP_INT_MAX;
                    $video_url = null;
                    $video_url_preview = null;
                    foreach ($media->video_info->variants as $video) {
                        if (isset($video->bitrate) && $video->bitrate > $video_bitrate) {
                            $video_bitrate = $video->bitrate;
                            $video_url = $video->url;
                        }
                        if (isset($video->bitrate) && $video->bitrate < $video_bitrate_preview) {
                            $video_bitrate_preview = $video->bitrate;
                            $video_url_preview = $video->url;
                        }
                    }
                    $tweet->citcuit_media[] = '<a href="'.$media->media_url_https.':large" target="_blank"><img src="'.$media->media_url_https.'" width="'.$media->sizes->thumb->w.'" /></a><br />(<a href="'.$video_url_preview.'" target="_blank">preview</a> or <a href="'.$video_url.'" target="_blank">download</a>)<br />';
                }
            }
        }

        // text - other parse
        $tweet->text = $this->parseLinkEmail($tweet->text);
        $tweet->text = $this->parseLinkHashtag($tweet->text);
        $tweet->text = $this->parseLinkHttp($tweet->text);
        $tweet->text = $this->parseLinkUser($tweet->text);

        // text - quoted
        if (isset($tweet->quoted_status)) {
            $tweet->quoted_status = $this->parseTweet($tweet->quoted_status);
        }

        // text - retweeted
        if (isset($tweet->retweeted_status)) {
            $tweet->retweeted_status = $this->parseTweet($tweet->retweeted_status);
        }

        // Twitter tweet link, used for "Retweet with Comment"
        $tweet->citcuit_retweet_link = 'https://twitter.com/'.$tweet->user->screen_name.'/status/'.$tweet->id_str;

        // Parse number
        $tweet->favorite_count = $this->parseNumber($tweet->favorite_count, 1000);
        $tweet->retweet_count = $this->parseNumber($tweet->retweet_count, 1000);

        // trim it and convert newlines
        $tweet->text = nl2br(trim($tweet->text));

        return $tweet;
    }

    public function parseMessage($message)
    {
        //created at
        $timeTweet = Carbon::createFromTimestamp(strtotime($message->created_at), $this->parseSetting('timezone'));
        $message->created_at_original = $timeTweet->format('H:i \\- j M Y');
        if ($this->parseSetting('time_diff') == 1) {
            $message->created_at = $timeTweet->diffForHumans();
        } else {
            $message->created_at = $message->created_at_original;
        }

        // parse link
        $urls = $message->entities->urls;
        foreach ($urls as $url) {
            $message->text = str_replace($url->url, '<a href="'.$url->url.'" target="_blank">'.$url->display_url.'</a>', $message->text);
        }

        // parse
        $message->text = $this->parseLinkEmail($message->text);
        $message->text = $this->parseLinkHashtag($message->text);
        $message->text = $this->parseLinkHttp($message->text);
        $message->text = $this->parseLinkUser($message->text);

        // trim it and convert newlines
        $message->text = nl2br(trim($message->text));

        return $message;
    }

    public function parseTrendsLocations($locations)
    {
        unset($locations->rate);
        unset($locations->httpstatus);

        foreach ($locations as $value) {
            $country[] = $value->country;
            $code[] = $value->placeType->code;
            $name[] = $value->name;
            $woeid[] = $value->woeid;
        }

        array_multisort($country, SORT_ASC, $code, SORT_DESC, $name, SORT_ASC, $woeid, SORT_ASC);

        for ($i = 0; $i < count($country); ++$i) {
            if ($code[$i] == 7) {
                $result[$i]['name'] = '- ';
            } else {
                $result[$i]['name'] = '';
            }
            $result[$i]['name'] .= $name[$i];
            $result[$i]['woeid'] = $woeid[$i];

            $result[$i] = (object) $result[$i];
        }

        return $result;
    }

    public function parseTrendsResults($results)
    {
        unset($results->rate);
        unset($results->httpstatus);

        $results = (array) $results;

        for ($i = 0; $i < count($results[0]->trends); ++$i) {
            $results_new[$i]['name'] = $results[0]->trends[$i]->name;
            $results_new[$i]['query'] = $results[0]->trends[$i]->query;
            $results_new[$i]['tweet_volume'] = $this->parseNumber($results[0]->trends[$i]->tweet_volume);
            if ($i % 2 == 0) {
                $results_new[$i]['class'] = 'even';
            } else {
                $results_new[$i]['class'] = 'odd';
            }

            $results_new[$i] = (object) $results_new[$i];
        }

        return $results_new;
    }

    public function parseError($response, $location = false)
    {
        if (isset($response->errors)) {
            $error_data = [
                'description' => null,
                'httpstatus' => $response->httpstatus,
            ];
            if ($location && $response->rate != null) {
                $error_data['rate'][$location] = $this->parseRateLimit($response);
            }
            foreach ($response->errors as $error) {
                $error_data['description'] .= $response->httpstatus.' - '.$error->message.' (<a href="https://dev.twitter.com/overview/api/response-codes" target="_blank">#'.$error->code.'</a>)<br />';
            }

            return $error_data;
        } elseif (isset($response->error)) { // different return on upload images
            $error_data = [
                'description' => null,
                'httpstatus' => $response->httpstatus,
            ];

            $error_data['description'] .= $response->httpstatus.' - '.ucfirst($response->error).'<br />';

            return $error_data;
        } elseif ($response->httpstatus == 401) { // sometimes when doing oAuth Twitter return 401 - This feature is temporarily unavailable
            $error_data = [
                'description' => null,
                'httpstatus' => 401,
            ];

            $error_data['description'] .= '401 - '.ucfirst($response->message).'<br />';

            return $error_data;
        } else {
            return false;
        }
    }

    public function parseRateLimit($response)
    {
        // sometimes Twitter returning empty rate value, don't know why.
        if (is_null($response->rate)) {
            return [
                'remaining' => '-',
                'limit' => '-',
                'reset' => '-',
            ];
        }

        $rate_remaining = $response->rate->remaining;
        $rate_limit = $response->rate->limit;
        $rate_reset = Carbon::createFromTimestamp($response->rate->reset, $this->parseSetting('timezone'))->diffInMinutes(Carbon::now($this->parseSetting('timezone')));

        return [
            'remaining' => $rate_remaining,
            'limit' => $rate_limit,
            'reset' => $rate_reset,
        ];
    }

    public function parseResult($content, $type)
    {
        unset($content->rate);
        unset($content->httpstatus);
        unset($content->message);

        $result = new \stdClass();

        if ($type == 'profile') {
            $result->next_cursor_str = $content->next_cursor_str;
            $result->previous_cursor_str = $content->previous_cursor_str;
            $content = (array) $content->users;
        } elseif ($type == 'search_user') {
            $content = (array) $content;
        } else {
            $content = (array) $content;
            $max_id = null;
        }

        for ($i = 0; $i < count($content); ++$i) {
            if ($i % 2 == 0) {
                $content[$i]->citcuit_class = 'odd';
            } else {
                $content[$i]->citcuit_class = 'even';
            }
            switch ($type) {
                case 'tweet':
                    $content[$i] = $this->parseTweet($content[$i]);
                    $max_id = $content[$i]->id_str;
                    break;
                case 'search':
                    $content[$i] = $this->parseTweet($content[$i], true);
                    $max_id = $content[$i]->id_str;
                    break;
                case 'message':
                    $content[$i] = $this->parseMessage($content[$i]);
                    $max_id = $content[$i]->id_str;
                    break;
                case 'profile':
                case 'search_user':
                    $content[$i] = $this->parseProfile($content[$i]);
                    break;
                default:
                    break;
            }
        }

        $result->content = $content;

        if (isset($max_id)) {
            $result->max_id = $max_id;
        }

        return $result;
    }
}
