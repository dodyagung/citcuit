@extends('layout')
@section('title', 'Error')

@section('content')
<nav class="nav-submenu">
    @yield('title')
</nav>
<section>
    <div class="alert alert-error">
        <strong>Error :(</strong><br />
        <br />
        {!! $description !!}
        <br />
        If often, try to <a href="{{ url('signout') }}">signout</a> then signin again, or <a href="{{ url('about#contribute') }}">contact us</a>.
    </div>
</section>
@endsection