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
    <h4>Requirements</h4>
    <ul>
        <li>PHP >= 5.5.9</li>
        <li>OpenSSL PHP Extension</li>
        <li>Mbstring PHP Extension</li>
        <li>Tokenizer PHP Extension</li>
    </ul>
    <h4>Installation</h4>
    <ol>
        <li>Run <code>composer create-project dodyagung/citcuit your-project-name</code></li>
        <li>Rename <code>.env.example</code> to <code>.env</code></li>
        <li>Edit the <code>.env</code> file according your config needs</li>
        <li>Run the application via your favorite browser</li>
    </ol>
    <h4>Contributing</h4>
    Thanks for your desire to help us. Please read our <a href="https://github.com/dodyagung/citcuit/blob/develop/CONTRIBUTING.md" target="_blank">contributing guide</a> first.
    <h4>Versioning</h4>
    CitCuit is maintained under <a href="http://semver.org">Semantic Versioning</a> guide with &lt;major&gt;.&lt;minor&gt;.&lt;patch&gt; format :
    <ul>
        <li>major: breaks backward compatibility (resets the minor and patch)</li>
        <li>minor: new additions with backward compatibility (resets the patch)</li>
        <li>patch: bug fixes and misc changes</li>
    </ul>
    <h4>Behind the Scene</h4>
    Official account
    <ul>
        <li>Twitter : <a href="{{ url('user/citcuit_in') }}" target="_blank">https://twitter.com/citcuit_in</a></li>
        <li>Facebook : <a href="https://fb.me/citcuit.in" target="_blank">https://facebook.com/citcuit.in</a></li>
    </ul>
    Founder & author (Dody Agung Saputro)
    <ul>
        <li>Email : <a href="mailto:hello@dodyagung.com" target="_blank">hello@dodyagung.com</a></li>
        <li>Website : <a href="https://dodyagung.com" target="_blank">https://dodyagung.com</a></li>
        <li>GitHub : <a href="https://github.com/dodyagung" target="_blank">https://github.com/dodyagung</a></li>
        <li>Twitter : <a href="https://twitter.com/dodyags" target="_blank">https://twitter.com/dodyags</a></li>
    </ul>
    Logo designer (Ridvandani Dwi P.A)
    <ul>
        <li>Facebook : <a href="https://fb.me/ridvan.aji" target="_blank">https://facebook.com/ridvan.aji</a></li>
    </ul>
    <h4>License</h4>
    CitCuit is open-sourced and licensed under <a href="https://github.com/dodyagung/citcuit/blob/develop/LICENSE.md" target="_blank">MIT license</a>.<br />
    <br />
</section>
@endsection