<?php

namespace App\Http\Controllers;

use SammyK\LaravelFacebookSdk\LaravelFacebookSdk;
use Cookie;

class FacebookController extends Controller {

    private $fb;

    public function __construct() {
        $this->fb = app(LaravelFacebookSdk::class);
    }

    private function saveToken($token) {
        session(['auth.facebook_token' => (string) $token]);
        $this->fb->setDefaultAccessToken($token);
    }

    public function loadToken() {
        $token = session('auth.facebook_token');
        $this->fb->setDefaultAccessToken($token);
    }

    public function checkToken() {
        if (session('auth.facebook_token')) {
            return true;
        }
        return false;
    }

    public function getUser() {
        $user = $this->fb->get('/me')->getGraphUser();
        return $user;
    }

    public function postFeed($message) {
        $data = [
            'message' => $message,
            'link' => 'https://citcuit.in/v2',
        ];
        $this->fb->post('/me/feed', $data);
    }

    public function postImage($message, $images) {
        $batch = [];
        $no = 1;

        foreach ($images as $image) {
            $batch['photo-'.$no] = $this->fb->request('POST', '/me/photos', [
                'caption' => $message,
                'images' => $this->fb->fileToUpload($image),
            ]);
            $no++;
        }

        $this->fb->sendBatchRequest($batch);
    }

    public function loginUrl() {
        return $this->fb->getLoginUrl(['publish_actions'], url('settings/facebook/login'));
    }

    public function loginCallback($url) {
        $token = $this->fb->getAccessTokenFromRedirect('settings/facebook/login');
        if (!$token->isLongLived()) {
            $token = $this->fb->getOAuth2Client()->getLongLivedAccessToken($token);
        }
        $this->saveToken($token);
    }

    public function logout($request) {
        $request->session()->forget('auth.facebook_token');
    }

}
