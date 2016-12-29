@extends('layout')
@section('title', 'Setting - Facebook Connect')

@section('content')
<nav class="nav-submenu">
    @yield('title')
</nav>
<section class="tweet tweet-even">
    <a href="{{ url('settings') }}">&laquo; Back to Settings</a>
</section>
<section>
    @if ($logged_in)
    Congratulations, your account is connected to Facebook!<br />
    <br />
    Facebook ID : {{ $user->getID() }}<br />
    Facebook Name : {{ $user->getName() }}<br />
    <br />
    <a href="{{ url('settings/facebook/logout') }}"><button>Logout</button></a>
    @else
    Connect to Facebook and share your status to Twitter and Facebook in single click.<br />
    <br />
    <a href="{{ url('settings/facebook/login') }}"><img src="{{ url('assets/img/fb.png') }}" width="100" alt="Facebook Connect" /></a>
    @endif
</section>
@endsection