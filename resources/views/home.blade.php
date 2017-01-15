@extends('layouts.app')

@section('content')
    

    <div>
        <h2 class="title" style="margin: 15px 0">&nbsp; Your watchlists</h2>
    </div>

    <div class="content-body">
        @if (Auth::check())
            <watchlist-container v-bind:watchlists="{{ $watchlists }}"></watchlist-container>
        @endif
    </div>

@endsection
