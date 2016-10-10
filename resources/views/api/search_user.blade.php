@extends('layout')
@section('title', 'User Search')

@section('content')
<nav class="sub-menu">
    @yield('title')
</nav>
<section class="tweet even">
    <a href="{{ url('search') }}">Search Tweet</a> | <strong>Search User</strong>
</section>
<section class="tweet odd">
    <form method="GET" action="{{ url('search/user') }}">
        User :<br />
        <input type="text" name="q" @if(isset($q)) value="{{ $q }}" @endif required>
               <button type="submit">Search</button>
    </form>
</section>
@if (isset($q))
<nav class="sub-menu">
    Results
</nav>
@if (!is_object($users))
<section>
    <div class="alert error">
        {!! $users !!}
    </div>
</section>
@else
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
    @if (!is_null($page_prev)) 
    @if (!$page_prev) 
    <a class="pagination left" href="{{ url('search/user?q=' . $q) }}">
        [&larr;] Page 1
    </a>
    @else
    <a class="pagination left" href="{{ url('search/user?q=' . $q . '&page=' . $page_prev) }}">
        [&larr;] Page {{ $page_prev }}
    </a>
    @endif
    @endif
    <a class="pagination right" href="{{ url('search/user?q=' . $q . '&page=' . $page_next) }}">
        Page {{ $page_next }} [&rarr;] 
    </a>
</section>
<section class="clear"></section>
@endif
@endif
@endsection