@extends('layout')
@section('title', 'Following @' . $screen_name)

@section('content')

<nav class="sub-menu">
    @yield('title')
</nav>
@foreach ($users->content as $user)
<section class="tweet {{ $user->citcuit_class }}">
    <div class="split-left">
        <img src="{{ $user->profile_image_url_https }}" class="profpic">
    </div>
    <div class="split-right">
        <a href="{{ url('user/' . $user->screen_name) }}"><strong>{{ $user->name }}</strong></a>
        @if ($user->protected == 1)
        <img class="action" src="{{ url('assets/img/protected.png') }}" alt="Protected" />
        @endif
        @if ($user->verified == 1)
        <img class="action" src="{{ url('assets/img/verified.png') }}" alt="Verified" />
        @endif
        <small>({{ '@' . $user->screen_name }})</small><br />
        <small>{!! $user->description !!}</small><br />
        <small><img class="action" src="{{ url('assets/img/location.png') }}" alt="Location" />&nbsp;&nbsp;{{ $user->location }}</small><br />
        <small><img class="action" src="{{ url('assets/img/url.png') }}" alt="Url" />&nbsp;{!! $user->url !!}</small><br />
    </div>
</section>
@endforeach
<section>
    @if ($users->previous_cursor_str != 0)
    <a class="pagination left" href="{{ url('following/' . $screen_name . '/cursor/' . $users->previous_cursor_str) }}">
        [&larr;] Previous 
    </a>
    @endif
    <a class="pagination right" href="{{ url('following/' . $screen_name . '/cursor/' . $users->next_cursor_str) }}">
        Next [&rarr;] 
    </a>
</section>
<section class="clear"></section>
@endsection