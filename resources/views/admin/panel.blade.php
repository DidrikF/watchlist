@extends('layouts.app')

@section('content')
    

    <div>
        <h2 class="title" style="margin: 15px 0">&nbsp; Your watchlists</h2>
    </div>

    <div class="content-body">
        @if (Auth::user()->isAdmin())
        	
        	<div>
        		<h3 class="title is-3">Not Accepted Users</h3>
        		@foreach($notAcceptedUsers as $user)
        			<div>
        				{{ $user->email }} <a class="button is-primary" href="/admin/accept/{{ $user->id }}"></a> <!-- Button to accept -->
        			</div>
        		@endforeach
        	</div>
        	<div>
        		<h3 class="title is-3">Accepted Users</h3>
        		@foreach($acceptedUsers as $user)
        			<div>
        				{{ $user->email }} 
        				<a class="button is-primary" href="/admin/ban/{{ $user->id }}"></a>
        				@if(Auth::->user()->isPrimeBoss())
        					<a class="button is-danger" href="/admin/makeadmin/{{ $user->id }}"></a>
        				@endif
        					<!-- Button to ban and make admin (make admin only visible to me) --> 
        				}
        			</div>
        		@endforeach
        	</div>
        	<div>
        		<h3 class="title is-3">Site Admins</h3>
        		@foreach($admins as $admin)
        			<div>
        				{{ $admin->email }} <!-- Button to delete admin (only visible to me)-->

        				@if(Auth::->user()->isPrimeBoss())
	    					<a class="button is-warning" href="/admin/makeadmin/{{ $admin->id }}"></a>
	    				@endif
        			</div>
        			
        		@endforeach
        	</div>

        @endif
    </div>

@endsection
