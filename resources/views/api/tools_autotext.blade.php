@extends('layout')
@section('title', 'Autotext')

@section('content')
<section>
    @include('api.@tweet')
</section>
<section class="tweet tweet-even">
    <a href="{{ url('tools') }}">&laquo; Back to Tools</a>
</section>
<nav class="nav-submenu">
    @yield('title')
</nav>
<section class="p-moreheight">
    <strong>How to use :</strong><br />
    Copy the left value, it will be replaced into it's right value.
    Autotext can be disabled on <a href="{{ url('settings/general') }}">general settings</a>.
    Want to add your own autotext? Just <a href="{{ url('about') }}">contact us</a> with "text + autotext" format.
</section>
@foreach ($autotext as $key => $value)
<nav class="nav-submenu">
    {{ $key }}
</nav>
<?php $tmp_toogle = true; ?>
@foreach ($value as $key1 => $value1)
<section class="tweet @if($tmp_toogle) tweet-even @else tweet-odd @endif">
    <div style="text-align: center">{{ $key1 }}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;=>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{ $value1 }}</div>
</section>
<?php $tmp_toogle = !$tmp_toogle; ?>
@endforeach
@endforeach
@endsection
