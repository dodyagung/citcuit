@extends('layout')
@section('title', 'Tools')

@section('content')
<nav class="nav-submenu">
    @yield('title')
</nav>
<section class="tweet tweet-odd">
    - <a href="{{ url('tools/autotext') }}">Autotext</a>
</section>
@endsection
