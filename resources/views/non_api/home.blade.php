@extends('layout')
@section('title', 'Home')

@section('content')
<section>
    <img src="{{ url('assets/img/logo.png') }}" alt="CitCuit logo" /><br />
    <br />
    <strong>Welcome to CitCuit v2</strong><br />
    CitCuit is a mobile Twitter client from Indonesia.<br />
    Slim, fast, lightweight, no ads, and of course it's <a href="https://github.com/dodyagung/citcuit" target="_blank">open source</a>!<br />
    <br />
    <a href="{{ url('signin') }}">
        <img src="{{ url('assets/img/signin.png') }}" alt="Sign in with Twitter" />
    </a><br />
    Don't have a Twitter account? <a href="https://mobile.twitter.com/signup">Sign up</a>.<br />
    <br />
    Join and like our <a href="https://fb.me/citcuit.in">Facebook</a> to stay in touch with our news and updates!
</section>
@endsection