<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use GuzzleHttp\Client;

class SearchController extends Controller
{
    public function index(Request $request) 
    {
    	$response = $this->getSearchResults($request);
    		
    	$jsonResponse = json_decode($response->getBody()); //getBody(returns a string...)

		return view('search.index', [
			'searchResults' => $jsonResponse,
		]);

    }

    public function getSearchResults(Request $request) 
    {

    	$searchWord = $request->get('q'); 
    	$client = new Client(['base_uri' => config('watchlist.api.search')]);
    	return $client->request('GET', "?query={$searchWord}&region=US&lang=en-US");

    }

}
