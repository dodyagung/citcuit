<form method="POST" action="{{ url('tweet') }}">
    <img src="{{ url('assets/img/favicon.png') }}" /> What's happening?
    <textarea id="status" name="tweet" required></textarea>
    <input type="hidden" name="_token" value="{{ csrf_token() }}">
    <button type="submit">Tweet</button>
</form>