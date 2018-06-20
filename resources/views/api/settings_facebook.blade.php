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
    Facebook Connect is currently unavailable due to Facebook new policy updates that doesn't allow posting without being done by the user itself.<br /><br />

    See <a target="_blank" href="https://developers.facebook.com/blog/post/2018/04/24/new-facebook-platform-product-changes-policy-updates/">https://developers.facebook.com/blog/post/2018/04/24/new-facebook-platform-product-changes-policy-updates/</a>
    <!-- @if ($logged_in)
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
    @endif -->
</section>
@endsection