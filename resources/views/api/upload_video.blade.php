@extends('layout')
@section('title', 'Upload Video')

@section('content')
<nav class="nav-submenu">
    @yield('title')
</nav>
<section class="tweet tweet-even">
    <a href="{{ url('upload') }}">Upload Image</a> | Upload Video
</section>
<section>
    @if (count($errors) > 0)
        <div class="alert alert-error">
            <strong>Error :(</strong><br />
            <br />
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        <br />
    @endif
    <form method="POST" action="{{ url('upload/video') }}" enctype="multipart/form-data">
        Video (required, mp4, <= 15 MB) :<br />
        <input type="file" name="video" required>
        Text :
        <textarea id="status" name="tweet" required></textarea>
        @if (session('auth.facebook_token'))
        <label><input type="checkbox" name="fb" id="fb" value="yes"> Share to Facebook</label><br />
        @else
        <a href="{{ url('settings/facebook') }}">Share to Facebook</a><br />
        @endif
        {{ csrf_field() }}
        <button type="submit">Tweet</button>
    </form>
</section>
@endsection
