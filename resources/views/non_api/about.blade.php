@extends('layout')
@section('title', 'About')

@section('content')
<nav class="sub-menu">
    @yield('title')
</nav>
<section class="about">
    <br />
    <img src="{{ url('assets/img/logo.png') }}" alt="CitCuit logo" class="logo" /><br />
    <br />
    <strong>Welcome to CitCuit!</strong><br />
    CitCuit is a mobile Twitter client, alternative of official <a href="https://mobile.twitter.com" target="_blank">mobile.twitter.com</a> website.<br />
    Secure, slim, fast, no ads, no database and of course we're <a href="https://github.com/dodyagung/CitCuit" target="_blank">open-sourced</a>!<br />
    <br />
    Built with <a href="https://laravel.com" target="_blank">Laravel</a>. Made with &hearts; in Jakarta, Indonesia.<br />
    <br />
    <strong id="official">Contact Us</strong><br />
    Join our official account to stay connected and updated.<br />
    Found an error? Have a question? Just contact our official account below.<br />
    <ul>
        <li>
            Twitter : <a href="@if (session('auth')) {{ url('user/citcuit_in') }} @else https://twitter.com/citcuit_in @endif" target="_blank">https://twitter.com/citcuit_in</a>
        </li>
        <li>
            Facebook : <a href="https://fb.me/citcuit.in" target="_blank">https://facebook.com/citcuit.in</a><br />
        </li>
    </ul>
    <br />
    <strong id="contribute">Contribute</strong><br />
    If you are developer, open an issue or push a Pull Request below.<br />
    <ul>
        <li>
            GitHub : <a href="https://github.com/dodyagung/CitCuit" target="_blank">https://github.com/dodyagung/CitCuit</a><br />
        </li>
    </ul>
    <br />
    <strong id="author">Author & Founder</strong><br />
    Hi, I am Dody Agung Saputro. I make the WWW fun.
    <ul>
        <li>
            Website : <a href="https://dodyagung.com" target="_blank">https://dodyagung.com</a><br />
        </li>
    </ul>
    <br />
    <strong id="thanks">Special Thanks to </strong><br />
    <ul>
        <li><a href="https://fb.me/ridvan.aji" target="_blank">Ridvandani Dwi</a> for original CitCuit logo, it's awesome.</li>
        <li>All CitCuit <a href="https://github.com/dodyagung/CitCuit/graphs/contributors" target="_blank">open source contributors</a> on GitHub.</li>
        <li>You and all of CitCuit users in the world! :)</li>
    </ul>
    <br />
    <strong id="license">License</strong><br />
    CitCuit is open-sourced and licensed under <a href="https://github.com/dodyagung/citcuit/blob/develop/LICENSE.md" target="_blank">MIT license</a>.<br />
    <br />
</section>
<!--<section class="tweet odd">
    - <a href="{{ url('about/about') }}">About</a>
</section>
<section class="tweet even">
    - <a href="{{ url('about/contact_contribute') }}">Contact or Contribute</a>
</section>
<section class="tweet odd">
    - <a href="{{ url('about/behindthescene') }}">Behind The Scene</a>
</section>
<section class="tweet even">
    - <a href="{{ url('about/license') }}">License</a>
</section>-->
@endsection
