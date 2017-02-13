@extends('layouts.app')

@section('content')
    

    <div>
        <h2 class="title" style="margin: 15px 0">&nbsp; Admin Panel</h2>
    </div>

    <div class="content-body">
        @if (Auth::check())

            @if(Auth::user()->isAdmin())
        	   <admin-panel :users-prop="{{ $users }}"></admin-panel>
            @endif

            @if(Auth::user()->isPrimeBoss())

            @endif        	

        @endif
    </div>

@endsection
