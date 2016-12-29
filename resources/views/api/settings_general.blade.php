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
        Theme :<br />
        <select name="theme">
            <option value="citcuit_blue" @if($settings['theme'] == 'citcuit_blue') selected @endif >CitCuit Blue</option>
            <option value="citcuit_green" @if($settings['theme'] == 'citcuit_green') selected @endif >CitCuit Green</option>
            <option value="citcuit_grey" @if($settings['theme'] == 'citcuit_grey') selected @endif >CitCuit Grey</option>
            <option value="citcuit_orange" @if($settings['theme'] == 'citcuit_orange') selected @endif >CitCuit Orange</option>
            <option value="citcuit_pink" @if($settings['theme'] == 'citcuit_pink') selected @endif >CitCuit Pink</option>
            <option value="citcuit_red" @if($settings['theme'] == 'citcuit_red') selected @endif >CitCuit Red</option>
        </select>
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
        Page auto refresh :<br />
        <select name="auto_refresh">
            @for ($i = 0; $i <= 300; $i = $i + 30)
                @if ($i == 0)
                <option value="{{ $i }}" @if($settings['auto_refresh'] == $i) selected @endif >Disable</option>
                @else
                <option value="{{ $i }}" @if($settings['auto_refresh'] == $i) selected @endif >Every {{ $i/60 }} minutes</option>
                @endif
            @endfor
        </select>
        Timezone :<br />
        <select name="timezone">
            @foreach($timezone as $value)
                <option value="{{ $value['zone'] }}" @if($settings['timezone'] == $value['zone']) selected @endif >{{ $value['zone'] }} - {{ $value['time'] }} ({{ $value['diff'] }})</option>
            @endforeach
        </select>
        Show Time Difference :<br />
        <select name="time_diff">
            <option value="1" @if($settings['time_diff'] == 1) selected @endif >Yes ({{ $time_diff[1] }})</option>
            <option value="0" @if($settings['time_diff'] == 0) selected @endif >No ({{ $time_diff[0] }})</option>
        </select>
        {{ csrf_field() }}
        <button type="submit">Update</button>
        <br />
        <br />
        <small><a href="{{ url('settings/general/reset') }}">Click here</a> to reset all of your settings to default.</small>
    </form>
</section>
@endsection
