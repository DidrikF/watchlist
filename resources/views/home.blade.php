@extends('layouts.app')

@section('content')
    

    <div class="content-head">
        <h2>Your watchlists</h2>
    </div>

    <div class="content-body">
        <watchlist></watchlist> <!-- I can pass initial values into the component here, so I don't have to performe Ajax -->

        <!-- more wathclists, use blade -->
    </div>


@endsection
