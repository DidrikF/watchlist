<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Watchlist;

use Illuminate\Support\Facades\Auth;

use GuzzleHttp\Client;

class CompanyController extends Controller
{

	public $yahooDataString = 'pydd1t8m4g3s6wj1nxj2veb4j4p5p6rs7';
	public $yahooKeyArray = ["p", "y", "d", "d1", "t8", "m4", "g3", "s6", "w", "j1", "n", "x", "j2", "v", "e", "b4", "j4", "p5", "p6", "r", "s7"]; 
	public $yahooKeyTranslation = ["p" => "Previous close", "y" => "Dividend yield", "d" => "Dividend per share", "d1" => "Last trade date", "t8" => "1 year target price", "m4" => "200 day moving avg", "g3" => "Annualizd gain", "s6" => "Revenue", "w" => "52 week range", "j1" => "Market capitalization", "n" => "Name", "x" => "Stock exchange", "j2" => "Shares outstanding", "v" => "Volume", "e" => "EPS", "b4" => "Book value", "j4" => "EBITDA", "p5" => "Price/Sales", "p6" => "Price/Book", "r" => "P/E ratio", "r5" => "PEG ratio", "s7" => "Short ratio"];

    public function index(Request $request, $ticker)
    {

    	$data = $this->getCompanyData($ticker);
        $watchlists = (new Watchlist)->where('user_id', Auth::user()->id)->get();
    	//dd(gettype($data['body']['Name']), gettype($data['body']['Stock exchange']), gettype($ticker));
    	return view('company.index', [
    		'data' => $data,
    		'ticker' => $ticker,
            'watchlists' => $watchlists,
            'companyName' => $data['body']['Name'],
            'companyExchange' => $data['body']['Stock exchange']
    	]);
    }

    public function getCompanyData($ticker)
    {
    	//build query
    	$url = $this->yahooQueryLink($ticker);
    	//Create guzzle client
    	$client = new Client;
    	//send request
    	$response = $client->request('GET', $url);
    	//Csv string to array
    	$responseArray = $this->_csvToArray($response->getBody(), $this->yahooKeyArray); 
    	//Change keys
    	
    	$responseFullKeys = $this->_changeKey($responseArray, $this->yahooKeyTranslation);

    	//Array to json and return
    	return ['body' => $responseFullKeys, 'responseCode' => $response->getStatusCode()];
    }

    public function yahooQueryLink($ticker){
		return "http://finance.yahoo.com/d/quotes.csv?s={$ticker}&f={$this->yahooDataString}";
	}

    public function _csvToArray($csv, $keys){
		$values = str_getcsv($csv, ',', '"'); //"facebook, Inc"
		return array_combine($keys, $values); //array("p"=>34.09, "m4"=>87.00)...
	}

	//not used atm
	private function _changeKey($array, $newKeys){ 
	    foreach($array as $key => $value){
	    	$keyArray[] = $newKeys[$key];
	    	$valueArray[] = $value;
	    }
	    return array_combine($keyArray, $valueArray);
	  }
}

