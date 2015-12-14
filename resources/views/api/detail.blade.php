@extends('layout')
@section('title', 'Detail')

@section('content')
<section>
    @include('api.@tweet')
</section>
<nav class="sub-menu">
    @yield('title')
</nav>
<section class="tweet odd">
    <?php
    if (isset($tweet->retweeted_status)) {
        $tweet_original = $tweet;
        $tweet = $tweet->retweeted_status; // Blade don't support variable declaration yet 
    }
    ?>
    <div class="split-left">        
        <img src="{{ $tweet->user->profile_image_url_https }}" class="profpic">
    </div>
    <div class="split-right">
        <a href="{{ url('profile/' . $tweet->user->screen_name) }}"><strong>{{ $tweet->user->name }}</strong></a>
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
            <a href="{{ url('unretweet/' . $tweet->id_str) }}"><img class="action" src="{{ url('assets/img/retweet-green.png') }}" alt="Unretweet" /></a>
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
        @if (isset($tweet->extended_entities->media))
        @foreach ($tweet->extended_entities->media as $media)
        <a href="{{ $media->media_url_https }}" target="_blank">
            <img src="{{ $media->media_url_https }}" width="{{ $media->sizes->thumb->w }}" />
        </a>
        @endforeach
        <br />
        @endif
        @if (isset($tweet->quoted_status))
        <section class="tweet quoted">
            <div class="split-left">
                <img src="{{ $tweet->quoted_status->user->profile_image_url_https }}" class="profpic">
            </div>
            <div class="split-right">
                <span class="screen_name"><a href="{{ url('profile/' . $tweet->quoted_status->user->screen_name) }}"><strong>{{ $tweet->quoted_status->user->name }}</strong></a></span> <span class="user_id"><small>({{ '@' . $tweet->quoted_status->user->screen_name }})</small></span><br />
                {!! $tweet->quoted_status->text !!}<br />
                @if (isset($tweet->quoted_status->extended_entities->media))
                @foreach ($tweet->quoted_status->extended_entities->media as $media)
                <a href="{{ $media->media_url_https }}" target="_blank">
                    <img src="{{ $media->media_url_https }}" width="{{ $media->sizes->thumb->w }}" />
                </a>
                @endforeach
                <br />
                @endif
                <small><a href="{{ url('detail/' . $tweet->quoted_status->id_str) }}">[Details]</a></small>
            </div>
        </section>
        @endif
        <small>{{ $tweet->created_at_original }} from {{ $tweet->source }}</small>
        @if (isset($tweet->in_reply_to_status_id_str))
        <br />
        <img class="action" src="{{ url('assets/img/reply-blue.png') }}" /> <small><strong>In reply to <a href="{{ url('detail/' . $tweet->in_reply_to_status_id_str) }}">{{ '@' . $tweet->in_reply_to_screen_name }}</a></strong></small>
        @endif
        @if (isset($tweet_original->retweeted_status))
        <br />
        <img class="action" src="{{ url('assets/img/retweet-green.png') }}" /> <small><strong><a href="{{ url('detail/' . $tweet_original->id_str) }}">Retweeted</a> by <a href="{{ url('profile/' . $tweet_original->user->screen_name) }}">{{ $tweet_original->user->name }}</a></strong></small>
        @endif
    </div>
</section>
@endsection