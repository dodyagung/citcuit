@extends('layout')
@section('title', '@' . $screen_name)

@section('content')
<nav class="sub-menu">
    @yield('title')
</nav>
<section class="tweet odd">
    @if ($setting['header_image'] == 1 && isset($profile->profile_banner_url))
    <img src="{{ $profile->profile_banner_url }}" width="100%" />
    @endif
    <div class="split-left">
        <a href="{{ $profile->profile_image_url_https_full }}" target="_blank"><img src="{{ $profile->profile_image_url_https }}" class="profpic"></a>
    </div>
    <div class="split-right">
        <strong>{{ $profile->name }}</strong>
        @if ($profile->protected == 1)
        <img class="action" src="{{ url('assets/img/protected.png') }}" alt="Protected" />
        @endif
        @if ($profile->verified == 1)
        <img class="action" src="{{ url('assets/img/verified.png') }}" alt="Verified" />
        @endif
        <small>({{ '@' . $profile->screen_name }})</small><br />
        <small>{!! $profile->description !!}</small><br />
        <small><img class="action" src="{{ url('assets/img/location.png') }}" alt="Location" />&nbsp;&nbsp;{{ $profile->location }}</small><br />
        <small><img class="action" src="{{ url('assets/img/url.png') }}" alt="Url" />&nbsp;{!! $profile->url !!}</small><br />
    </div>
    <br />
    <small><strong>Joined</strong> : {{ $profile->created_at }}</small><br />
    <small><strong>Stats</strong> : &plusmn;{{ $profile->tweets_per_day }} tweets per day</small>
    <br />
    <br />
    <small>{{ $profile->statuses_count }} tweets | @if ($protected) {{ $profile->friends_count }} following @else <a href="{{ url('following/' . $profile->screen_name) }}">{{ $profile->friends_count }} following</a> @endif | @if ($protected) {{ $profile->followers_count }} followers @else <a href="{{ url('followers/' . $profile->screen_name) }}">{{ $profile->followers_count }} followers</a> @endif | @if ($protected) {{ $profile->favourites_count }} likes @else <a href="{{ url('likes/' . $profile->screen_name) }}">{{ $profile->favourites_count }} likes</a> @endif </small>
    <br />
    <br />
    @if ($screen_name != session('auth.screen_name'))
    <small>
        @if (!$profile->following)
        <span class="error">You're not following!</span> <a href="{{ url('follow/' . $screen_name) }}"><strong>[Follow]</strong></a>
        @else
        <span class="success">You're following!</span> <a href="{{ url('unfollow/' . $screen_name) }}"><strong>[Unfollow]</strong></a>
        @endif
    </small>
    @else
    <small>
        <a href="{{ url('settings/profile') }}"><strong>[Edit Profile]</strong></a>
    </small>
    @endif
</section>
<section>
    @include('api.@tweet', ['screen_name' => '@'.$screen_name.' '])
</section>
<nav class="sub-menu">
    Tweets
</nav>
@if (!is_object($timeline))
<section>
    <div class="alert error">
        {!! $timeline !!}
    </div>
</section>
@else
@foreach ($timeline->content as $tweet)
<section class="tweet {{ $tweet->citcuit_class }}">
    <?php
    $tweet_original = null;
    if (isset($tweet->retweeted_status)) {
        $tweet_original = $tweet;
        $tweet = $tweet->retweeted_status; // Blade don't support variable declaration yet
    }
    ?>
    <div class="split-left">
        <img src="{{ $tweet->user->profile_image_url_https }}" class="profpic">
    </div>
    <div class="split-right">
        <a href="{{ url('user/' . $tweet->user->screen_name) }}"><strong>{{ $tweet->user->name }}</strong></a>
        @if ($tweet->user->protected == 1)
        <img class="action" src="{{ url('assets/img/protected.png') }}" alt="Protected" />
        @endif
        @if ($tweet->user->verified == 1)
        <img class="action" src="{{ url('assets/img/verified.png') }}" alt="Verified" />
        @endif
        <span class="user_id"><small>({{ '@' . $tweet->user->screen_name }})</small></span><br />
        <span class="action">
            <a href="{{ url('reply/' . $tweet->id_str) }}"><img class="action" src="{{ url('assets/img/reply.png') }}" alt="Reply" /></a>
            &nbsp;&nbsp;&nbsp;&bullet;&nbsp;&nbsp;&nbsp;
            @if ($tweet->retweeted == 1)
            <a href="{{ url('detail/' . $tweet->id_str) }}"><img class="action" src="{{ url('assets/img/retweet-green.png') }}" alt="Unretweet" /></a>
            @else
            <a href="{{ url('retweet/' . $tweet->id_str) }}"><img class="action" src="{{ url('assets/img/retweet.png') }}" alt="Retweet" /></a>
            @endif
            &nbsp;&nbsp;<small>{{ $tweet->retweet_count }}</small>
            &nbsp;&nbsp;&nbsp;&bullet;&nbsp;&nbsp;&nbsp;
            @if ($tweet->favorited == 1)
            <a href="{{ url('unlike/' . $tweet->id_str) }}"><img class="action" src="{{ url('assets/img/like-red.png') }}" alt="Unlike" /></a>
            @else
            <a href="{{ url('like/' . $tweet->id_str) }}"><img class="action" src="{{ url('assets/img/like.png') }}" alt="Like" /></a>
            @endif
            &nbsp;&nbsp;<small>{{ $tweet->favorite_count }}</small>
            @if ($tweet->user->screen_name == session('auth.screen_name'))
            &nbsp;&nbsp;&nbsp;&bullet;&nbsp;&nbsp;&nbsp;
            <a href="{{ url('delete/' . $tweet->id_str) }}"><img class="action" src="{{ url('assets/img/delete.png') }}" alt="Delete" /></a>
            @endif
        </span><br />
        {!! $tweet->text !!}<br />
        @if (isset($tweet->citcuit_media))
        @foreach ($tweet->citcuit_media as $media)
        {!! $media !!}
        @endforeach
        @endif
        @if (isset($tweet->quoted_status))
        <section class="tweet quoted">
            <div class="split-left">
                <img src="{{ $tweet->quoted_status->user->profile_image_url_https }}" class="profpic">
            </div>
            <div class="split-right">
                <span class="screen_name"><a href="{{ url('user/' . $tweet->quoted_status->user->screen_name) }}"><strong>{{ $tweet->quoted_status->user->name }}</strong></a></span> <span class="user_id"><small>({{ '@' . $tweet->quoted_status->user->screen_name }})</small></span><br />
                {!! $tweet->quoted_status->text !!}<br />
                @if (isset($tweet->citcuit_media))
                @foreach ($tweet->citcuit_media as $media)
                {!! $media !!}
                @endforeach
                @endif
                <small><a href="{{ url('detail/' . $tweet->quoted_status->id_str) }}">[Details]</a></small>
            </div>
        </section>
        @endif
        <small><a href="{{ url('detail/' . $tweet->id_str) }}">{{ $tweet->created_at }}</a> from {{ $tweet->source }}</small>
        @if (isset($tweet->in_reply_to_status_id_str))
        <br />
        <img class="action" src="{{ url('assets/img/reply-blue.png') }}" /> <small><strong>In reply to <a href="{{ url('detail/' . $tweet->in_reply_to_status_id_str) }}">{{ '@' . $tweet->in_reply_to_screen_name }}</a></strong></small>
        @endif
        <!--retweeted by me-->
        @if ($tweet->retweeted == 1)
        <br />
        <img class="action" src="{{ url('assets/img/retweet-green.png') }}" /> <small><strong><a href="{{ url('user/' . session('auth.screen_name')) }}">You</a> retweeted</strong></small>
        <!--retweeted by other-->
        @elseif (isset($tweet_original->retweeted_status))
        <br />
        <img class="action" src="{{ url('assets/img/retweet-green.png') }}" /> <small><strong><a href="{{ url('user/' . $tweet_original->user->screen_name) }}">{{ $tweet_original->user->name }}</a> retweeted</strong></small>
        @endif
    </div>
</section>
@endforeach
<section>
    <a class="pagination right" href="{{ url('/user/' . $screen_name . '/older/' . $timeline->max_id) }}">
        Older [&rarr;]
    </a>
</section>
<section class="clear"></section>
@endif
@endsection
