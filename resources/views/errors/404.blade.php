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
        404 - Not Found<br />
        <br />
        Go back to Home if you lost.
    </div>
</section>
@endsection
