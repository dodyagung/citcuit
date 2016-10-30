@extends('layout')
@section('title', 'Settings - Profile Image')

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
    <form method="POST" action="{{ url('settings/profile_image') }}" enctype="multipart/form-data">
        Current profile image :<br />
        <a href="{{ $profile->profile_image_url_https_full }}" target="_blank"><img src="{{ $profile->profile_image_url_https }}" class="profpic"></a><br />
        <br />
        Upload your profile image :<br />
        <input type="file" name="image" required>
        <small>
            <ul>
                <li>Must be a valid GIF, JPG, or PNG image of less than 700 kilobytes in size.</li>
                <li>Images with width larger than 400 pixels will be scaled down. </li>
                <li>Animated GIFs will be converted to a static GIF of the first frame, removing the animation.</li>
            </ul>
        </small>
        <br />
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <button type="submit">Save</button>
    </form>
</section>
@endsection
