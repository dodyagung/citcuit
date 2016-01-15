@extends('layout')
@section('title', 'About')

@section('content')
<section>
    <img src="{{ url('assets/img/logo.png') }}" alt="CitCuit logo" />
    <h4>Welcome to CitCuit</h4>
    CitCuit is a mobile Twitter client, alternative of official <a href="https://mobile.twitter.com" target="_blank">mobile.twitter.com</a> website.<br />
    Secure, slim, fast, lightweight, no ads, no database and of course we're <a href="https://github.com/dodyagung/CitCuit" target="_blank">open-sourced</a>!<br />
    <br />
    Built with <a href="https://lumen.laravel.com" target="_blank">Lumen</a> micro-framework by <a href="https://laravel.com" target="_blank">Laravel</a>. Made with &hearts; in Indonesia.<br />
    <br />
    Join and like our <a href="https://fb.me/citcuit.in">Facebook</a> to stay in touch with our news and updates.
    <h4>Requirements</h4>
    <ul>
        <li>PHP >= 5.5.9</li>
        <li>OpenSSL PHP Extension</li>
        <li>PDO PHP Extension</li>
        <li>Mbstring PHP Extension</li>
    </ul>
    <h4>Installation</h4>
    <ol>
        <li>Run <code>composer create-project dodyagung/citcuit dev-master your-project-name</code></li>
        <li>Rename <code>.env.example</code> to <code>.env</code></li>
        <li>Edit the <code>.env</code> file according your config needs</li>
        <li>Run the application via your favorite browser</li>
    </ol>
    <h4>Contributing</h4>
    Thanks for your desire to help us. Please read our <a href="https://github.com/dodyagung/citcuit/blob/develop/CONTRIBUTING.md" target="_blank">contributing guide</a> first.
    <h4>Behind the Scene</h4>
    Dody Agung Saputro (founder, author)
    <ul>
        <li><a href="https://dodyagung.com" target="_blank">https://dodyagung.com</a></li>
        <li><a href="https://github.com/dodyagung" target="_blank">https://github.com/dodyagung</a></li>
        <li><a href="https://twitter.com/dodyags" target="_blank">https://twitter.com/dodyags</a></li>
    </ul>
    <h4>License</h4>
    CitCuit is open-sourced and licensed under <a href="https://github.com/dodyagung/citcuit/blob/develop/LICENSE.md" target="_blank">MIT license</a>.
</section>
@endsection