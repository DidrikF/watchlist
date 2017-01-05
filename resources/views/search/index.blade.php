@extends('layouts.app')

@section('content')
    
	<div class="content flex-center-top">
	    <div class="content-head">
	        Search result for "{{ Request::get('q') }}"
	    </div>

	    <div class="content-body">
	       	
	       	@if(count($searchResults->ResultSet->Result))
				<ol>
				   	@foreach($searchResults->ResultSet->Result as $company)
						<li class="search-result-element"><strong>{{ $company->name }} ({{ $company->symbol }})</strong>- Exchange: {{ $company->exch }}/{{ $company->exchDisp }}  -->  
							
							<a href="/company/{{ $company->symbol }}">Go to {{ $company->symbol }}</a>
							
						</li>
				   	@endforeach
			   	</ol>
			@else
				No results returned
	       	@endif

	    </div>
	</div>

@endsection