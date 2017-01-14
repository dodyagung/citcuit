@extends('layout')
@section('title', 'Saved Search')

@section('content')
<nav class="nav-submenu">
    @yield('title')
</nav>
<section class="tweet tweet-even">
    <a href="{{ url('search') }}">Search Tweet</a> | <a href="{{ url('search/user') }}">Search User</a> | <strong>Saved Search</strong>
</section>
@if (!is_object($search_saved))
<section>
    <div class="alert alert-error">
        {!! $search_saved !!}
    </div>
</section>
@else
@foreach ($search_saved->content as $result)
<section class="tweet {{ $result->citcuit_class }}">
    <strong><a href="{{ url('search?q=' . urlencode($result->query) . '&result_type=mixed') }}">{{ $result->name }}</a></strong><br />
    <small>
        Saved at {{ $result->created_at }} 
        <form method="POST" action="{{ url('search/saved/delete') }}">
            <input type="hidden" name="saved_id" value="{{ $result->id }}" />
            {{ csrf_field() }}
            <small><button type="submit">Delete</button></small>
        </form>
    </small>
</section>
@endforeach
@endif
@endsection