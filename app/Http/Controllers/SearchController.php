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

    public function jsonSearch($searchWord)
    {
        $client = new Client(['base_uri' => config('watchlist.api.search')]);

        $response = $client->request('GET', "?query={$searchWord}&region=US&lang=en-US");
            
        $jsonResponse = json_decode($response->getBody());

        return response()->json($jsonResponse, 200);
    }

    public function getSearchResults(Request $request) 
    {

    	$searchWord = $request->get('q'); 
    	$client = new Client(['base_uri' => config('watchlist.api.search')]);
    	return $client->request('GET', "?query={$searchWord}&region=US&lang=en-US");

    }

}
