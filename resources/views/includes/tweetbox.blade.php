@if(session()->has("access_token"))
<section>
    <form method="POST" action="{{ url('tweet') }}">
        <img alt="CitCuit Logo" src="{{ url('favicon.ico') }}" /> What's happening?
        <textarea name="tweet" required></textarea>
        {{ csrf_field() }}
        <button type="submit">Tweet</button>
    </form>
</section>
@endif