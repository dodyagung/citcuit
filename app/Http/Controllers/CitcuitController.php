<?php

namespace App\Http\Controllers;

use Carbon\Carbon;

class CitcuitController {

    public function parseProfile($profile) {
        if (isset($profile->entities->url->urls) && count($profile->entities->url->urls) != 0) {
            $urls = $profile->entities->url->urls;
            foreach ($urls as $url) {
                $profile->url = str_replace($url->url, '<a href="' . $url->url . '" target="_blank">' . $url->display_url . '</a>', $profile->url);
            }
        }

        if (isset($profile->entities->description->urls) && count($profile->entities->description->urls) != 0) {
            $urls = $profile->entities->description->urls;
            foreach ($urls as $url) {
                $profile->description = str_replace($url->url, '<a href="' . $url->url . '" target="_blank">' . $url->display_url . '</a>', $profile->description);
            }
        }

        if ($profile->description == NULL) {
            $profile->description = '-';
        }
        if ($profile->location == NULL) {
            $profile->location = '-';
        }
        if ($profile->url == NULL) {
            $profile->url = '-';
        }

        $profile->statuses_count = number_format($profile->statuses_count);
        $profile->friends_count = number_format($profile->friends_count);
        $profile->followers_count = number_format($profile->followers_count);
        $profile->favourites_count = number_format($profile->favourites_count);

        $profile->profile_image_url_https_full = str_replace('_normal', '', $profile->profile_image_url_https);

        return $profile;
    }

    public function parseTweet($tweet) {
        //created at
        $timeTweet = Carbon::createFromTimestamp(strtotime($tweet->created_at));
        $timeNow = Carbon::now();
        $tweet->created_at_original = $timeTweet->format('H:i \\- j M Y');
        $tweet->created_at = $timeTweet->diffForHumans();

        //source
        $tweet->source = preg_replace('/<a href=".*" rel="nofollow">(.*)<\/a>/i', '$1', $tweet->source);

        // form - reply destination
        if ($tweet->user->screen_name != session('citcuit.oauth.screen_name')) {
            $tweet->reply_destination = '@' . $tweet->user->screen_name . ' ';
        } else {
            $tweet->reply_destination = null;
        }
        preg_match_all('/@([a-zA-Z0-9_]{1,15})/i', $tweet->text, $matchs);
        foreach ($matchs[1] as $match) {
            if ($match != session('citcuit.oauth.screen_name')) {
                $tweet->reply_destination .= '@' . $match . ' ';
            }
        }
        if (isset($tweet->quoted_status)) {
            $tweet->reply_destination .= '@' . $tweet->quoted_status->user->screen_name . ' ';
            preg_match_all('/@([a-zA-Z0-9_]{1,15})/i', $tweet->quoted_status->text, $matchs);
            foreach ($matchs[1] as $match) {
                if ($match != $tweet->user->screen_name) {
                    $tweet->reply_destination .= '@' . $match . ' ';
                }
            }
        }
        if (trim($tweet->reply_destination) == '') {
            $tweet->reply_destination .= '@' . $tweet->user->screen_name;
        }

        // text - t.co
        $urls = $tweet->entities->urls;
        foreach ($urls as $url) {
            // if contain twitter quote, delete it
            if (strpos($url->expanded_url, 'twitter.com') !== false && strpos($url->expanded_url, '/status/') !== false) {
                $tweet->text = str_replace($url->url, '', $tweet->text);
            } else {
                $tweet->text = str_replace($url->url, '<a href="' . $url->url . '" target="_blank">' . $url->display_url . '</a>', $tweet->text);
            }
        }

        // text - media
        if (isset($tweet->extended_entities->media)) {
            $medias = $tweet->extended_entities->media;
            $tweet->citcuit_media = [];
            foreach ($medias as $media) {
                $tweet->text = str_replace($media->url, '', $tweet->text);
                if (isset($media->video_info)) {
                    $video_bitrate = PHP_INT_MAX;
                    $video_url = NULL;
                    foreach ($media->video_info->variants as $video) {
                        if (isset($video->bitrate) && $video->bitrate < $video_bitrate) {
                            $video_bitrate = $video->bitrate;
                            $video_url = $video->url;
                        }
                    }
                    $tweet->citcuit_media[] = '<a href="' . $video_url . '" target="_blank"><img src="' . $media->media_url_https . '" width="' . $media->sizes->thumb->w . '" /><br />(click to play this video)</a><br />';
                } else {
                    $tweet->citcuit_media[] = '<a href="' . $media->media_url_https . '" target="_blank"><img src="' . $media->media_url_https . '" width="' . $media->sizes->thumb->w . '" /></a><br />';
                }
            }
        }

        // text - @user
        $tweet->text = preg_replace('/@([a-zA-Z0-9_]{1,15})/i', '<a href="' . url('profile/$1') . '">$0</a>', $tweet->text);

        // text - #hashtag
        $tweet->text = preg_replace('/#([a-zA-Z0-9_]+)/i', '<a href="' . url('hashtag/$1') . '">$0</a>', $tweet->text);

        // text - quoted
        if (isset($tweet->quoted_status)) {
            $tweet->quoted_status = self::parseTweet($tweet->quoted_status);
        }

        // text - retweeted
        if (isset($tweet->retweeted_status)) {
            $tweet->retweeted_status = self::parseTweet($tweet->retweeted_status);
        }

        // Twitter tweet link, used for "Retweet with Comment"
        $tweet->citcuit_retweet_link = 'https://twitter.com/' . $tweet->user->screen_name . '/status/' . $tweet->id_str;

        // trim it and convert newlines
        $tweet->text = nl2br(trim($tweet->text));

        return $tweet;
    }

    public function parseError($response, $location = FALSE) {
        if (isset($response->errors)) {
            $error_data = [
                'title' => 'Error :(',
                'description' => NULL,
            ];
            if ($location && $response->rate != NULL) {
                $error_data['rate'][$location] = self::parseRateLimit($response);
            }
            foreach ($response->errors as $error) {
                $error_data['description'] .= $response->httpstatus . ' - ' . $error->message . ' (<a href="https://dev.twitter.com/overview/api/response-codes" target="_blank">#' . $error->code . '</a>)<br />';
            }
            return $error_data;
        } else {
            return false;
        }
    }

    public function parseRateLimit($response) {
        $rate_remaining = $response->rate->remaining;
        $rate_limit = $response->rate->limit;
        $rate_reset = Carbon::createFromTimestamp($response->rate->reset, 'UTC')->diffInMinutes(Carbon::now('UTC'));

        return [
            'remaining' => $rate_remaining,
            'limit' => $rate_limit,
            'reset' => $rate_reset,
        ];
    }

    public function parseTweets($tweets) {
        unset($tweets->rate);
        unset($tweets->httpstatus);

        $tweets = (array) $tweets;
        $max_id = NULL;

        for ($i = 0; $i < count($tweets); $i++) {
            if ($i % 2 == 0) {
                $tweets[$i]->citcuit_class = 'odd';
            } else {
                $tweets[$i]->citcuit_class = 'even';
            }
            $max_id = $tweets[$i]->id_str;
            $tweets[$i] = self::parseTweet($tweets[$i]);
        }

        $result = new \stdClass();
        $result->tweets = $tweets;
        $result->max_id = $max_id;

        return $result;
    }

}
