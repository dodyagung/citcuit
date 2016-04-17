@extends('layout')
@section('title', 'Sent Messages')

@section('content')

<nav class="sub-menu">
    @yield('title')
</nav>
<section class="tweet even">
    <a href="{{ url('messages/create') }}">Create</a> | 
    <a href="{{ url('messages') }}">Inbox</a> | 
    <strong>Sent</strong>
</section>
@foreach ($timeline->content as $message)
<section class="tweet {{ $message->citcuit_class }}">
    <div class="split-left">
        <img src="{{ $message->sender->profile_image_url_https }}" class="profpic">
    </div>
    <div class="split-right">
        <a href="{{ url('user/' . $message->sender->screen_name) }}"><strong>{{ $message->sender->name }}</strong></a>
        @if ($message->sender->protected == 1)
        <img class="action" src="{{ url('assets/img/protected.png') }}" alt="Protected" />
        @endif
        @if ($message->sender->verified == 1)
        <img class="action" src="{{ url('assets/img/verified.png') }}" alt="Verified" />
        @endif
        <span class="sender_id"><small>({{ '@' . $message->sender->screen_name }})</small></span><br />
        <span class="action">
            <a href="{{ url('messages/create/' . $message->sender->screen_name) }}"><img class="action" src="{{ url('assets/img/message.png') }}" alt="Reply" /></a>
            &nbsp;&nbsp;&nbsp;&bullet;&nbsp;&nbsp;&nbsp;
            <a href="{{ url('messages/delete/' . $message->id_str) }}"><img class="action" src="{{ url('assets/img/delete.png') }}" alt="Delete" /></a>
        </span><br />
        {!! $message->text !!}<br />
        <small><a href="{{ url('messages/detail/' . $message->id_str) }}">{{ $message->created_at }}</a></small>
    </div>
</section>
@endforeach
<section>
    <a class="pagination right" href="{{ url('messages/sent/older/' . $timeline->max_id) }}">
        Older [&rarr;] 
    </a>
</section>
<section class="clear"></section>
@endsection