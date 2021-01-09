@extends('layouts.mobile')
@section('title', 'About')

@section('content')
<section class="p-moreheight">
    <br>
    <img src="{{ url('assets/img/logo.png') }}" alt="CitCuit Logo" class="img-logo" /><br>
    <br>
    CitCuit is a mobile Twitter client, alternative of official <a href="https://mobile.twitter.com"
        target="_blank">mobile.twitter.com</a> website.<br>
    Secure, slim, fast, lightweight, no ads, no database and of course we're <a
        href="https://github.com/dodyagung/CitCuit" target="_blank">open-sourced</a>!<br>
    <br>
    Built with <a href="https://laravel.com" target="_blank">Laravel</a>. Made with &hearts; by <a
        href="https://twitter.com/dodyags" target="_blank">@dodyags</a> in Jakarta,
    Indonesia.
    <br>
    <br>
    <strong>Live Website</strong>
    <br>
    Go to <a href="https://citcuit.in" target="_blank">citcuit.in</a> and sign-in with your Twitter account.
    <br>
    <br>
    <strong>Roadmap</strong>
    <br>
    <table>
        <thead>
            <tr>
                <th>Version</th>
                <th>Framework</th>
                <th>Support</th>
                <th>Development</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>1.x</td>
                <td><a href="https://code.google.com/archive/p/dabr/" target="_blank">Dabr</a>
                    &amp; <a href="https://github.com/themattharris/tmhOAuth" target="_blank">tmhOAuth</a>
                </td>
                <td>&#10006;</td>
                <td>&#10006;</td>
            </tr>
            <tr>
                <td>2.x</td>
                <td><a href="https://lumen.laravel.com" target="_blank">Lumen</a> &amp; <a
                        href="https://www.jublo.net/projects/codebird/php" target="_blank">Codebird</a>
                </td>
                <td>&#10006;</td>
                <td>&#10006;</td>
            </tr>
            <tr>
                <td>3.x</td>
                <td><a href="https://laravel.com" target="_blank">Laravel 5.6</a> &amp; <a
                        href="https://www.jublo.net/projects/codebird/php" target="_blank">Codebird</a>
                </td>
                <td>&#10006;</td>
                <td>&#10006;</td>
            </tr>
            <tr>
                <td>4.x</td>
                <td><a href="https://laravel.com" target="_blank">Laravel 6 LTS</a> &amp; <a
                        href="https://github.com/atymic/twitter" target="_blank">Atmyc Twitter</a>
                </td>
                <td>&#10004;</td>
                <td>&#10004;</td>
            </tr>
        </tbody>
    </table>
    <br>
    <strong>Requirements</strong>
    <br>
    Same as <a href="https://laravel.com/docs/6.x#server-requirements" target="_blank">Laravel 6</a> requirements :
    <ul>
        <li>PHP >= 7.2.5</li>
        <li>BCMath PHP Extension</li>
        <li>Ctype PHP Extension</li>
        <li>Fileinfo PHP Extension</li>
        <li>JSON PHP Extension</li>
        <li>Mbstring PHP Extension</li>
        <li>OpenSSL PHP Extension</li>
        <li>PDO PHP Extension</li>
        <li>Tokenizer PHP Extension</li>
        <li>XML PHP Extension</li>
    </ul>
    <br>
    <strong>Installation</strong>
    <ol>
        <li>Run <code>composer create-project dodyagung/citcuit your-project-name</code></li>
        <li>Edit the <code>.env</code> file according your config needs</li>
        <li>Run the application via your favorite browser</li>
    </ol>
    <br>
    <strong>Contributing</strong>
    <br>
    Thanks for your desire to help us. Please read our <a
        href="https://github.com/dodyagung/citcuit/blob/develop/CONTRIBUTING.md" target="_blank">contributing
        guide</a> first.
    <br>
    <br>
    <strong>Versioning</strong>
    <br>
    CitCuit is maintained under Semantic Versioning guide with <code>&lt;major&gt;.&lt;minor&gt;.&lt;patch&gt;</code>
    format :
    <ul>
        <li><code>major</code> : breaks backward compatibility (resets the <code>minor</code> and <code>patch</code>)
        </li>
        <li><code>minor</code> : new additions with backward compatibility (resets the <code>patch</code>)</li>
        <li><code>patch</code> : bug fixes and misc changes</li>
    </ul>
    <br>
    <strong>Behind the Scene</strong>
    <br>
    Official account
    <ul>
        <li>Twitter : <a href="https://twitter.com/citcuit_in" target="_blank">@citcuit_in</a>
        </li>
        </li>
        <li>Facebook : <a href="https://www.facebook.com/citcuit.in" target="_blank">/citcuit.in</a>
        </li>
        </li>
    </ul>
    Founder & author (Dody Agung Saputro)
    <ul>
        <li>Website : <a href="https://www.dodyagung.com" target="_blank">www.dodyagung.com</a>
        </li>
        </li>
        <li>Twitter : <a href="https://twitter.com/dodyags" target="_blank">@dodyags</a>
        </li>
        </li>
    </ul>
    Logo designer (Ridvandani Dwi P.A)
    <ul>
        <li>Facebook : <a href="https://www.facebook.com/ridvan.aji" target="_blank">/ridvan.aji</a>
        </li>
    </ul>
    <br>
    <strong>License</strong>
    <br>
    CitCuit is open-sourced and licensed under <a href="https://github.com/dodyagung/citcuit/blob/develop/LICENSE.md"
        target="_blank">MIT icense</a>.
    <br>
    <br>
</section>
@endsection