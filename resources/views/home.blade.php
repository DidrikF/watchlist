@extends('layouts.app')

@section('content')
    

    <div class="content-head">
        <h2>Your watchlists</h2>
    </div>

    <div class="content-body">

    	@if (Auth::check())
            @foreach($watchlists as $watchlist)
                <watchlist v-bind:watchlist="{{ $watchlist }}"></watchlist>
            @endforeach
        @endif
        

    </div>

@endsection
