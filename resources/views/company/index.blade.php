@extends('layouts.app')

@section('content')
    
	
    <div class="content-head">
    	@if($data['responseCode'] === 200 && $data['body']['name'] != 'N/A')
			{{ $data['body']['name'] }}
    	@endif
    </div>

    <div class="content-body">

    	@if($data['responseCode'] === 200 && $data['body']['name'] != 'N/A')
			<ul>
			@foreach($data['body'] as $key => $value)				
				<li>{{ $key }} -> {{ $value }}</li>
			@endforeach
			</ul>
		@else
			Company could not be found.
		@endif
		<company-analysis ticker="{{ $ticker }}"></company-analysis>

    </div>


@endsection
