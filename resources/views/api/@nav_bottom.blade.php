<strong>{{ $global_time }}</strong> |
<a href="#top">[&uarr;] Top</a> |
<a href="{{ url('following/' . session('auth.screen_name')) }}">Following</a> |
<a href="{{ url('followers/' . session('auth.screen_name')) }}">Followers</a> |
<a href="{{ url('likes/' . session('auth.screen_name')) }}">Likes</a> |
<a href="{{ url('tools') }}">Tools</a> |
<a href="{{ url('settings') }}">Settings</a> |
<a href="{{ url('about') }}">About</a> |
<a href="https://status.citcuit.in" target="_blank">Status</a> |
<a href="{{ url('signout') }}">Sign Out</a>
