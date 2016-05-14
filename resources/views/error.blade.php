@extends('layout')
@section('title', 'Error')

@section('content')
<nav class="sub-menu">
    @yield('title')
</nav>
<section>
    <div class="alert error">
        <strong>Error! :(</strong><br />
        <br />
        {!! $description !!}
    </div>
</section>
@endsection