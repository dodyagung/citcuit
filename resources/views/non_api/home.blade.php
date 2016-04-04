@extends('layout')
@section('title', 'Home')

@section('content')
<section>
    <img src="{{ url('assets/img/logo.png') }}" alt="CitCuit logo" />
    <h4>Welcome to CitCuit</h4>
    CitCuit is a mobile Twitter client, alternative of official <a href="https://mobile.twitter.com" target="_blank">mobile.twitter.com</a> website.<br />
    Secure, slim, fast, lightweight, no ads, no database and of course we're <a href="https://github.com/dodyagung/CitCuit" target="_blank">open-sourced</a>!<br />
    <br />
    Built with <a href="https://lumen.laravel.com" target="_blank">Lumen</a> micro-framework by <a href="https://laravel.com" target="_blank">Laravel</a>. Made with &hearts; in Indonesia.<br />
    <br />
    <a href="{{ url('signin') }}">
        <img src="{{ url('assets/img/signin.png') }}" alt="Sign in with Twitter" />
    </a><br />
    Don't have a Twitter account? <a href="https://mobile.twitter.com/signup" target="_blank">Sign up</a>.<br />
    <br />
    Join and like our <a href="https://fb.me/citcuit.in" target="_blank">Facebook</a> to stay in touch with our news and updates.
</section>
@endsection