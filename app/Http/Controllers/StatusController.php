<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Twitter;

class StatusController extends Controller
{
    public function tweet(Request $request)
    {
        $request->validate([
            "tweet" => "required",
        ]);

        Twitter::postTweet(["status" => $request->tweet]);

        return back()->with("status", "Your tweet has been sent!");
    }
}
