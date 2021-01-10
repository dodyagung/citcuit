@extends('layouts.mobile')

@section('content')
<section>
    <div class="alert alert-error">
        <strong>@yield('code') @yield('message')</strong>
        <br />
        @if($exception->getMessage()) {{ $exception->getMessage() }}<br>@endif
        <br />
        If often, try to <a href="{{ url('signout') }}">signout</a> then signin again, or <a
            href="{{ url('about#contribute') }}">contact us</a>.
    </div>
</section>
@endsection