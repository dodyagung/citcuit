@extends('layout')
@section('title', 'Settings')

@section('content')
<nav class="nav-submenu">
    @yield('title')
</nav>
<section class="tweet tweet-odd">
    - <a href="{{ url('settings/general') }}">General</a>
</section>
<section class="tweet tweet-even">
    - <a href="{{ url('settings/profile') }}">Profile</a>
</section>
<section class="tweet tweet-odd">
    - <a href="{{ url('settings/profile_image') }}">Profile Image</a>
</section>
<section class="tweet tweet-even">
    - <a href="{{ url('settings/profile_header') }}">Profile Header</a>
</section>
<section class="tweet tweet-odd">
    - <a href="{{ url('settings/facebook') }}">Facebook Connect</a>
</section>
@endsection
