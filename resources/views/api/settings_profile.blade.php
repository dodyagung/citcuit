@extends('layout')
@section('title', 'Settings - Profile')

@section('content')
<nav class="nav-submenu">
    @yield('title')
</nav>
<section class="tweet tweet-even">
    <a href="{{ url('settings') }}">&laquo; Back to Settings</a>
</section>
@if (session('success'))
<section>
    <div class="alert alert-info">
        {{ session('success') }}
    </div>
</section>
@endif
<section>
    <form method="POST" action="{{ url('settings/profile') }}">
        Name (max 20 chars) :<br />
        <input type="text" name="name" value="{{ $profile->name }}" required>
        Url (max 100 chars) :<br />
        <input type="text" name="url" value="{{ $profile->url_nohref }}">
        Location (max 30 chars) :<br />
        <input type="text" name="location" value="{{ $profile->location_nohref }}">
        Description (max 160 chars) :<br />
        <textarea name="description">{{ $profile->description_nohref }}</textarea>
        {{ csrf_field() }}
        <button type="submit">Save</button>
    </form>
</section>
@endsection