@extends('layout')
@section('title', 'Messages Detail')

@section('content')

<nav class="nav-submenu">
    @yield('title')
</nav>
<section class="tweet tweet-even">
    <a href="{{ url('messages/create') }}">Create</a> | 
    <a href="{{ url('messages') }}">Inbox</a> | 
    <a href="{{ url('messages/sent') }}">Sent</a>
</section>
<section class="tweet tweet-odd">
    <div class="split-left">
        <img src="{{ $message->sender->profile_image_url_https }}" class="img-avatar">
    </div>
    <div class="split-right">
        <a href="{{ url('user/' . $message->sender->screen_name) }}"><strong>{{ $message->sender->name }}</strong></a>
        @if ($message->sender->protected == 1)
        <img class="img-action" src="{{ url('assets/img/protected.png') }}" alt="Protected" />
        @endif
        @if ($message->sender->verified == 1)
        <img class="img-action" src="{{ url('assets/img/verified.png') }}" alt="Verified" />
        @endif
        <span class="sender_id"><small>({{ '@' . $message->sender->screen_name }})</small></span><br />
        <span class="action">
            <a href="{{ url('messages/create/' . $message->sender->screen_name) }}"><img class="img-action" src="{{ url('assets/img/message.png') }}" alt="Reply" /></a>
            &nbsp;&nbsp;&nbsp;&bullet;&nbsp;&nbsp;&nbsp;
            <a href="{{ url('messages/delete/' . $message->id_str) }}"><img class="img-action" src="{{ url('assets/img/delete.png') }}" alt="Delete" /></a>
        </span><br />
        {!! $message->text !!}<br />
        @if (isset($message->citcuit_media))
        @foreach ($message->citcuit_media as $media)
        {!! $media !!}
        @endforeach
        @endif
        <small>{{ $message->created_at_original }}</small>
    </div>
</section>
<section class="clear"></section>
@endsection