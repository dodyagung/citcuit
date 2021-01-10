<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    {{-- SEO Meta --}}
    <meta name="description"
        content="CitCuit is a mobile Twitter client, alternative of official mobile.twitter.com website. Secure, slim, fast, no ads, no database and of course we're open-sourced! Built with Laravel. Made with <3 by @dodyags in Jakarta, Indonesia.">
    <meta name="keywords"
        content="citcuit, twitter, mobile, client, indonesia, secure, slim, fast, no database, no ads, open source">
    <meta name="author" content="www.dodyagung.com">

    {{-- Facebook Meta --}}
    <meta property="og:url" content="{{ url()->current() }}" />
    <meta property="og:site_name" content="CitCuit" />
    <meta property="og:title" content="@yield('title') - CitCuit" />
    <meta property="og:description"
        content="CitCuit is a mobile Twitter client, alternative of official mobile.twitter.com website. Secure, slim, fast, no ads, no database and of course we're open-sourced! Built with Laravel. Made with <3 by @dodyags in Jakarta, Indonesia." />
    <meta property="og:image" content="{{ url('assets/img/logo_square.png') }}" />
    <meta property="fb:app_id" content="132082170210911" />

    {{-- Twitter Meta --}}
    <meta name="twitter:card" content="summary" />
    <meta name="twitter:site" content="@citcuit_in" />
    <meta name="twitter:site:id" content="722402114614333440" />
    <meta name="twitter:creator" content="@dodyags" />
    <meta name="twitter:creator:id" content="70055176" />
    <meta name="twitter:title" content="@yield('title') - CitCuit" />
    <meta name="twitter:description"
        content="CitCuit is a mobile Twitter client, alternative of official mobile.twitter.com website. Secure, slim, fast, no ads, no database and of course we're open-sourced! Built with Laravel. Made with <3 by @dodyags in Jakarta, Indonesia." />
    <meta name="twitter:image" content="{{ url('assets/img/logo_square.png') }}" />
    <meta name="twitter:image:alt" content="CitCuit Logo" />

    <title>@yield('title') - {{ config('app.name', 'Laravel') }}</title>

    {{-- Favicon --}}
    @include('includes.favicon')

    {{-- CSS --}}
    @include('includes.css')
</head>

<body>
    <a id="top"></a>

    {{-- Header --}}
    <header>
        @include('includes.header')
    </header>

    {{-- Navigation Top --}}
    <nav class="nav-menu">
        @include('includes.nav-top')
    </nav>

    {{-- Content --}}
    <article>
        <section>
            <div class="alert alert-info">
                @include('includes.alert')
            </div>
        </section>
        {{-- Tweet Box - Top --}}
        @hasSection('tweetbox-top') @include('includes.tweetbox') @endif
        <nav class="nav-submenu">
            @yield('title')
        </nav>
        @yield('content')
    </article>

    {{-- Navigation Bottom --}}
    <nav class="nav-menu">
        @include('includes.nav-bottom')
    </nav>

    {{-- Footer --}}
    <footer>
        @include('includes.footer')
    </footer>

    <a id="bottom"></a>
</body>

</html>