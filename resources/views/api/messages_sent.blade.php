@extends('layout')
@section('title', 'Sent Messages')

@section('content')

<nav class="nav-submenu">
    @yield('title')
</nav>
<section class="tweet tweet-even">
    <a href="{{ url('messages/create') }}">Create</a> | 
    <a href="{{ url('messages') }}">Inbox</a> | 
    <strong>Sent</strong>
</section>
@if (!is_object($timeline))
<section>
    <div class="alert alert-error">
        {!! $timeline !!}
    </div>
</section>
@else
@foreach ($timeline->content as $message)
<section class="tweet {{ $message->citcuit_class }}">
    <div class="split-left">
        <img src="{{ $message->recipient->profile_image_url_https }}" class="img-avatar">
    </div>
    <div class="split-right">
        <a href="{{ url('user/' . $message->recipient->screen_name) }}"><strong>{{ $message->recipient->name }}</strong></a>
        @if ($message->recipient->protected == 1)
        <img class="img-action" src="{{ url('assets/img/protected.png') }}" alt="Protected" />
        @endif
        @if ($message->recipient->verified == 1)
        <img class="img-action" src="{{ url('assets/img/verified.png') }}" alt="Verified" />
        @endif
        <span class="sender_id"><small>({{ '@' . $message->recipient->screen_name }})</small></span><br />
        <span class="action">
            <a href="{{ url('messages/create/' . $message->recipient->screen_name) }}"><img class="img-action" src="{{ url('assets/img/message.png') }}" alt="Reply" /></a>
            &nbsp;&nbsp;&nbsp;&bullet;&nbsp;&nbsp;&nbsp;
            <a href="{{ url('messages/delete/' . $message->id_str) }}"><img class="img-action" src="{{ url('assets/img/delete.png') }}" alt="Delete" /></a>
        </span><br />
        {!! $message->text !!}<br />
        @if (isset($message->citcuit_media))
        @foreach ($message->citcuit_media as $media)
        {!! $media !!}
        @endforeach
        @endif
        <small><a href="{{ url('messages/detail/' . $message->id_str) }}">{{ $message->created_at }}</a></small>
    </div>
</section>
@endforeach
<section>
    <a class="pagination pagination-right" href="{{ url('messages/sent/older/' . $timeline->max_id) }}">
        Older [&rarr;] 
    </a>
</section>
<section class="clear"></section>
@endif
@endsection