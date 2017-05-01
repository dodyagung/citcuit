<form method="POST" action="{{ url('tweet') }}">
    <img src="{{ url('favicon.ico') }}" /> What's happening?
    <textarea id="status" name="tweet" required>@if (isset($screen_name)){{ $screen_name }}@endif</textarea>
    @if (session('auth.facebook_token'))
    <label><input type="checkbox" name="fb" id="fb" value="yes"> Share to Facebook</label><br />
    @else
    <a href="{{ url('settings/facebook') }}">Share to Facebook</a><br />
    @endif
    {{ csrf_field() }}
    <button type="submit">Tweet</button>
</form>
