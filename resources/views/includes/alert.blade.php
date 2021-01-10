@if (session('status'))
{{ session('status') }}
@else
{!! env('CITCUIT_ALERT', 'Welcome to CitCuit!') !!}
@endif