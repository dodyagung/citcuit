@extends('layout')
@section('title', 'Settings - Profile')

@section('content')
<nav class="sub-menu">
    @yield('title')
</nav>
<section class="tweet even">
    <a href="{{ url('settings') }}">&laquo; Back to Settings</a>
</section>
@if (session('success'))
<section>
    <div class="alert info">
        {{ session('success') }}
    </div>
</section>
@endif
<section>
    <form method="POST" action="{{ url('settings/profile') }}">
        Name (max 20 chars) :<br />
        <input type="text" name="name" value="{{ $profile->name }}" required>
        Url (max 100 chars) :<br />
        <input type="text" name="url" value="{{ $profile->url_no_href }}" required>
        Location (max 30 chars) :<br />
        <input type="text" name="location" value="{{ $profile->location }}" required>
        Description (max 160 chars) :<br />
        <textarea name="description" required>{{ $profile->description_no_href }}</textarea>
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <button type="submit">Save</button>
    </form>
</section>
@endsection