@extends('layout')
@section('title', 'Settings')

@section('content')
<nav class="sub-menu">
    @yield('title')
</nav>
<section class="tweet odd">
    - <a href="{{ url('settings/profile') }}">Profile</a>
</section>
<section class="tweet even">
    - <a href="{{ url('settings/profile_image') }}">Profile Image</a>
</section>
@endsection