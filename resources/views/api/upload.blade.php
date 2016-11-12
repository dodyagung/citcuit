@extends('layout')
@section('title', 'Upload Image')

@section('content')
<nav class="sub-menu">
    @yield('title')
</nav>
<section class="tweet even">
    Upload Image | <a href="{{ url('#') }}">Upload Video</a> (soon)
</section>
<section>
    @if (count($errors) > 0)
        <div class="alert error">
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
    <form method="POST" action="{{ url('upload') }}" enctype="multipart/form-data">
        Image 1 (required, png/jpg/bmp/gif, <= 5 MB) :<br />
        <input type="file" name="image1" required>
        Image 2 (optional) :<br />
        <input type="file" name="image2">
        Image 3 (optional) :<br />
        <input type="file" name="image3">
        Image 4 (optional) :<br />
        <input type="file" name="image4">
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
    <hr />
    <strong>Or, if you want to upload via the image URL :</strong><br />
    <em>Example : http://www.w3schools.com/images/colorpicker.gif</em><br />
    <br />
    <form method="POST" action="{{ url('upload/remote') }}">
        Image 1 (required) :<br />
        <input type="text" name="image1" required>
        Image 2 (optional) :<br />
        <input type="text" name="image2">
        Image 3 (optional) :<br />
        <input type="text" name="image3">
        Image 4 (optional) :<br />
        <input type="text" name="image4">
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
