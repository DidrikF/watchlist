@extends('layouts.app')

@section('content')


<div class="columns">
  <div class="column is-one-third">
  	<div class="title is-2" style="margin: 15px;">
    	
    	@if($data['responseCode'] === 200 && $data['body']['Name'] != 'N/A')
			 {{ $data['body']['Name'] }}
    	@endif

    </div>
  </div>
  <div class="column is-two-thirds">
  	<add-to-watchlist ticker="{{ $ticker }}" company-name="{{ $companyName }}" company-exchange="{{ $companyExchange }}" :watchlists="{{ $watchlists }}"></add-to-watchlist> 
  </div>
</div>

    
	
    
    <!-- use v-bind if you want vue to enterperate the prop as a data structure, not a string -->

    <div class="container">

    	@if($data['responseCode'] === 200 && $data['body']['Name'] != 'N/A')
	    	<table class="table is-striped is-narrow">
			  <thead>
			    <tr>
			      <th><abbr title="Data">Data</abbr></th>
			      <th><abbr title="Value">Value</abbr></th>
			      <th><abbr title="Data">Data</abbr></th>
			      <th><abbr title="Value">Value</abbr></th>
			    </tr>
			  </thead>
			  <tbody>
			  	<?php 
			  		$countData = count($data['body']);
			  		$keys = array_keys($data['body']);
			  	?>
			 	@for ($i=0; $i<$countData; $i+=2)	<!-- $data['body'] as $key => $value -->
			 		<tr>
			 			@if($i<$countData)
							<td>{{ $keys[$i] }}</td> 
							<td>{{ $data['body'][$keys[$i]] }}</td>
						@endif
						@if(($i+1)<$countData)
							<td>{{ $keys[$i+1] }}</td> 
							<td>{{ $data['body'][$keys[$i+1]] }}</td>
						@else
							<td>-</td>
							<td>-</td>
						@endif
					</tr>
				@endfor

			  </tbody>
			</table>

			<company-analysis ticker="{{ $ticker }}"></company-analysis>

			
			<create-notification ticker="{{ $ticker }}" v-bind:prop-active-notifications="{{ $activeNotifications }}"></create-notification>

		@else
			<div class="title is-3">Company could not be found.</div>
		@endif

    </div>


@endsection
