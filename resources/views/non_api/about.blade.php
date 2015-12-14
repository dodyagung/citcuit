@extends('layout')
@section('title', 'About')

@section('content')
<section>
    <img src="{{ url('assets/img/logo.png') }}" alt="CitCuit logo" /><br />
    <br />
    <strong>Welcome to CitCuit v2</strong><br />
    CitCuit is a mobile Twitter client from Indonesia.<br />
    Slim, fast, lightweight, no ads, and of course it's <a href="https://github.com/dodyagung/citcuit" target="_blank">open source</a>!<br />
    <br />
    <strong>What's the differences from v1</strong><br />
    We don't use <a href="https://code.google.com/p/dabr" target="_blank">Dabr open source</a> anymore, now we make the "base code" ourselves.<br />
    CitCuit v2 is rebuilt from zero with <a href="http://lumen.laravel.com" target="_blank">Lumen</a> micro-framework by <a href="http://laravel.com" target="_blank">Laravel</a>.<br />
    <br />
    Join and like our <a href="https://fb.me/citcuit.in">Facebook</a> to stay in touch with our news and updates!
</section>
@endsection