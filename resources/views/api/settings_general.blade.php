@extends('layout')
@section('title', 'Setting - General')

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
    <form method="post" action="{{ url('settings/general') }}">
        Show profile header image :<br />
        <select name="header_image">
            <option value="1" @if($settings['header_image'] == 1) selected @endif >Enable</option>
            <option value="0" @if($settings['header_image'] == 0) selected @endif >Disable</option>
        </select>
        Tweets per page :<br />
        <select name="tweets_per_page">
            @for ($i = 5; $i <= 200; $i = $i + 5)
                <option value="{{ $i }}" @if($settings['tweets_per_page'] == $i) selected @endif >{{ $i }}</option>
            @endfor
        </select>
        {{ csrf_field() }}
        <button type="submit">Update</button>
    </form>
</section>
@endsection
