@extends('layout')
@section('title', 'Search Tweet')

@section('content')
<nav class="sub-menu">
    @yield('title')
</nav>
<section class="tweet even">
    <strong>Search Tweet</strong> | <a href="#">Search User (coming soon)</a>
</section>
<section class="tweet odd">
    <form method="GET" action="{{ url('search/tweet') }}">
        Search Query :<br />
        <input type="text" name="q" value="{{ $q }}" required>
        Result Type :<br />
        <select name="result_type">
            <option value="mixed"@if($result_type == 'mixed') selected @endif>- Mixed (recent & popular)</option>
            <option value="recent"@if($result_type == 'recent') selected @endif>- Recent</option>
            <option value="popular"@if($result_type == 'popular') selected @endif>- Popular</option>
        </select>
        <button type="submit">Search</button>
    </form>
</section>
@if (isset($timeline->content) && count($timeline->content) > 0)
<nav class="sub-menu">
    Results
</nav>
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
            <!--because mentions API don't return retweeted tweet ID-->
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
            @if ($tweet->user->id_str == session('citcuit.oauth.user_id'))
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
        <!--retweeted by other-->
        @if (isset($tweet_original->retweeted_status))
        <br />
        <img class="action" src="{{ url('assets/img/retweet-green.png') }}" /> <small><strong><a href="{{ url('user/' . $tweet_original->user->screen_name) }}">{{ $tweet_original->user->name }}</a> retweeted</strong></small>
        @endif
        <!--retweeted by me-->
        @if ($tweet->retweeted == 1)
        <br />
        <img class="action" src="{{ url('assets/img/retweet-green.png') }}" /> <small><strong><a href="{{ url('user/' . session('citcuit.oauth.screen_name')) }}">You</a> retweeted</strong></small>
        @endif
    </div>
</section>
@endforeach
<section>
    <a class="pagination right" href="{{ url('search/tweet?q=' . $q . '&result_type='.$result_type.'&max_id='.$timeline->max_id) }}">
        Older [&rarr;] 
    </a>
</section>
<section class="clear"></section>
@endif
@endsection