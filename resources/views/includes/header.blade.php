{{-- @if (session('auth'))
<a href="{{ url('user/' . session('auth.screen_name')) }}">{{ '@' . session('auth.screen_name') }}</a> -
@endif --}}
@if(session()->has("access_token"))
<a href="{{ url('user/' . session()->get("access_token.screen_name")) }}">
    {{ '@' . session()->get("access_token.screen_name") }}</a> -
@endif
CitCuit