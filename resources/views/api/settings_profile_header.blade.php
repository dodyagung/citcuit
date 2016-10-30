@extends('layout')
@section('title', 'Settings - Profile Header')

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
    <form method="POST" action="{{ url('settings/profile_header') }}" enctype="multipart/form-data">
        Current profile header :<br />
        @if(isset($profile->profile_banner_url))
        <a href="{{ $profile->profile_banner_url }}" target="_blank"><img src="{{ $profile->profile_banner_url }}" width="100%"></a>
        <br />
        @else
        <span class="error">You don't have a profile header.</span>
        <br />
        @endif
        <br />
        Upload your profile header :
        <input type="file" name="image" required>
        <br />
        <br />
        {{ csrf_field() }}
        <button type="submit">Save</button>
        <br />
        <br />
        <small><a href="{{ url('settings/profile_header/remove') }}">Click here</a> to remove your profile header image.</small>
    </form>
</section>
@endsection
