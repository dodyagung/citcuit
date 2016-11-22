@extends('layout')
@section('title', 'Error')

@section('content')
<nav class="sub-menu">
    @yield('title')
</nav>
<section>
    <div class="alert error">
        <strong>ERROR</strong><br />
        <br />
        {{ $message }}<br />
        <br />
        For more info :
        <ul>
            <li>
                Twitter : <a href=" https://twitter.com/citcuit_in " target="_blank">https://twitter.com/citcuit_in</a>
            </li>
            <li>
                Facebook : <a href="https://fb.me/citcuit.in" target="_blank">https://facebook.com/citcuit.in</a><br/>
            </li>
        </ul>
        </div>
</section>
@endsection
