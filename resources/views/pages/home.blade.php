@extends('layouts.mobile')
@section('title', 'Home')

@section('content')
<section class="p-moreheight">
    <br>
    <img src="{{ url('assets/img/logo.png') }}" alt="CitCuit logo" class="img-logo" /><br>
    <br>
    CitCuit is a mobile Twitter client, alternative of official <a href="https://mobile.twitter.com"
        target="_blank">mobile.twitter.com</a> website.<br>
    Secure, slim, fast, lightweight, no ads, no database and of course we're <a
        href="https://github.com/dodyagung/CitCuit" target="_blank">open-sourced</a>!<br>
    <br>
    Built with <a href="https://laravel.com" target="_blank">Laravel</a>. Made with &hearts; in Jakarta,
    Indonesia.<br>
    <br>
    <a href="{{ url('signin') }}">
        <img src="{{ url('assets/img/signin.png') }}" alt="Sign in with Twitter" />
    </a><br>
    Don't have a Twitter account? <a href="https://mobile.twitter.com/i/flow/signup" target="_blank">Sign up</a>.<br>
    <br>
</section>
@endsection