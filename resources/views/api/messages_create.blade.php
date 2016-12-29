@extends('layout')
@if ($screen_name) 
@section('title', 'Message to @' . $screen_name)
@else
@section('title', 'Create Message')
@endif

@section('content')
<nav class="nav-submenu">
    @yield('title')
</nav>
<section class="tweet tweet-even">
    <strong>Create</strong> | 
    <a href="{{ url('messages') }}">Inbox</a> | 
    <a href="{{ url('messages/sent') }}">Sent</a>
</section>
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
        {{ csrf_field() }}
        <button type="submit">Message</button>
    </form>
</section>
@endsection