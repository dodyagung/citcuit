@extends('layout')
@section('title', 'Error')

@section('content')
<nav class="sub-menu">
    @yield('title')
</nav>
<section>
    <div class="alert error">
        <strong>Error :(</strong><br />
        <br />
        503 - Service Unavailable<br />
        <br />
        Because we are doing a maintenance right now.
        </div>
</section>
@endsection
