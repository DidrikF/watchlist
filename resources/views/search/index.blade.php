@extends('layouts.app')

@section('content')
    


	<div class="container" style="margin-top: 20px;">
		@if(count($searchResults->ResultSet->Result))
			<table class="table">
			  <thead>
			    <tr>
			      <th><abbr title="Company">Company</abbr></th>
			      <th><abbr title="Ticker">Ticker</abbr></th>
			      <th><abbr title="Exchange">Exchange</abbr></th>
			    </tr>
			  </thead>
			  <tbody>
			  	@foreach($searchResults->ResultSet->Result as $company)
			  		<tr>
			  			<td><a href="/livedemo/companywatchlist/company/{{ $company->symbol }}">{{ $company->name }}</a></td>
			  			<td>{{ $company->symbol }}</td>
			  			<td>{{ $company->exch }}/{{ $company->exchDisp }}</td>
					</tr>
				@endforeach
			  </tbody>
			</table>
		@else
			<div class="has-text-centered">
				No results returned
			</div>
		@endif
	</div>

@endsection