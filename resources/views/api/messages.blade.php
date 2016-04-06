@extends('layout')
@section('title', 'Messages')

@section('content')

<nav class="sub-menu">
    @yield('title')
</nav>
@foreach ($timeline->content as $message)
<section class="tweet {{ $message->citcuit_class }}">
    <div class="split-left">
        <img src="{{ $message->sender->profile_image_url_https }}" class="profpic">
    </div>
    <div class="split-right">
        <a href="{{ url('profile/' . $message->sender->screen_name) }}"><strong>{{ $message->sender->name }}</strong></a>
        @if ($message->sender->protected == 1)
        <img class="action" src="{{ url('assets/img/protected.png') }}" alt="Protected" />
        @endif
        @if ($message->sender->verified == 1)
        <img class="action" src="{{ url('assets/img/verified.png') }}" alt="Verified" />
        @endif
        <span class="sender_id"><small>({{ '@' . $message->sender->screen_name }})</small></span><br />
        <span class="action">
            <a href="{{ url('reply/' . $message->id_str) }}"><img class="action" src="{{ url('assets/img/reply.png') }}" alt="Reply" /></a>
            &nbsp;&nbsp;&nbsp;&bullet;&nbsp;&nbsp;&nbsp;
            <a href="{{ url('delete/' . $message->id_str) }}"><img class="action" src="{{ url('assets/img/delete.png') }}" alt="Delete" /></a>
        </span><br />
        {!! $message->text !!}<br />
        <small>{{ $message->created_at }}</small>
    </div>
</section>
@endforeach
<section>
    <a class="pagination right" href="{{ url('/message/older/' . $timeline->max_id) }}">
        Older [&rarr;] 
    </a>
</section>
<section class="clear"></section>
@endsection