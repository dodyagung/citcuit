{{-- @if(isset($rate))
<div class="rate">
    <strong>Rate limit <a href="https://blog.twitter.com/2008/what-does-rate-limit-exceeded-mean-updated"
            target="_blank">[?]</a> :</strong>
    @foreach ($rate as $key => $value)
    <br />&bullet; {{ $key }} : {{ $value['remaining'] }} hit / {{ $value['reset'] }} min.
@endforeach
</div>
<hr />
@endif --}}
We're open-sourced at <a href="https://github.com/dodyagung/citcuit" target="_blank">GitHub</a>. Join our <a
    href="{{ url('about#official') }}">official account</a> to stay connected and updated.