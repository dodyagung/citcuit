@extends('layout')
@if ($screen_name) 
@section('title', 'Message to @' . $screen_name)
@else
@section('title', 'Create Message')
@endif

@section('content')
<nav class="sub-menu">
    @yield('title')
</nav>
<section>
    <form method="POST" action="{{ url('messages/create') }}">
        @if (!$screen_name)
        Username (without @) :<br />
        <input type="text" name="screen_name" required>
        Message :<br />
        @else
        <input type="hidden" name="screen_name" value="{{ $screen_name }}" required>
        Message to {{ '@' . $screen_name }} :<br />
        @endif
        <textarea name="text" required></textarea>
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <button type="submit">Message</button>
    </form>
</section>
@endsection