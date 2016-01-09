<form method="POST" action="{{ url('tweet') }}">
    <textarea id="status" name="tweet" placeholder="What's happening?" required></textarea>
    <input type="hidden" name="_token" value="{{ csrf_token() }}">
    <button type="submit">Tweet</button>
</form>