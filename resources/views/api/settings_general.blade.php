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
            <option value="1" @if($settings['header_image'] == 1) selected @endif >Yes</option>
            <option value="0" @if($settings['header_image'] == 0) selected @endif >No</option>
        </select>
        Tweets per page :<br />
        <select name="tweets_per_page">
            @for ($i = 5; $i <= 200; $i = $i + 5)
                <option value="{{ $i }}" @if($settings['tweets_per_page'] == $i) selected @endif >{{ $i }}</option>
            @endfor
        </select>
        Auto refresh :<br />
        <select name="auto_refresh">
            @for ($i = 0; $i <= 300; $i = $i + 30)
                @if ($i == 0)
                <option value="{{ $i }}" @if($settings['auto_refresh'] == $i) selected @endif >Disable</option>
                @else
                <option value="{{ $i }}" @if($settings['auto_refresh'] == $i) selected @endif >Every {{ $i/60 }} minutes</option>
                @endif
            @endfor
        </select>
        {{ csrf_field() }}
        <button type="submit">Update</button>
        <br />
        <br />
        <small><a href="{{ url('settings/general/reset') }}">Click here</a> to reset all of your settings to default.</small>
    </form>
</section>
@endsection
