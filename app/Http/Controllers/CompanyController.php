<?php

namespace App\Http\Controllers;

//use Illuminate\Http\Request;
use App\Http\Requests\Request;

use App\Models\{Watchlist, Notification, FinancialData};

use Illuminate\Support\Facades\Auth;

use GuzzleHttp\Client;

use Illuminate\Support\Facades\DB;

class CompanyController extends Controller
{

	public $yahooDataString = 'pydd1t8m4g3s6wj1nxj2veb4j4p5p6rs7';
    public $yahooKeyArray = ["ticker", "p", "y", "d", "d1", "t8", "m4", "g3", "s6", "w", "j1", "n", "x", "j2", "v", "e", "b4", "j4", "p5", 
        "p6", "r", "s7"]; 
	public $yahooKeyTranslation = ["ticker" => "ticker", "p" => "Previous close", "y" => "Dividend yield", "d" => "Dividend per share", "d1" => "Last trade date", 
        "t8" => "1 year target price", "m4" => "200 day moving avg", "g3" => "Annualizd gain", "s6" => "Revenue", 
        "w" => "52 week range", "j1" => "Market capitalization", "n" => "Name", "x" => "Stock exchange", 
        "j2" => "Shares outstanding", "v" => "Volume", "e" => "EPS", "b4" => "Book value", "j4" => "EBITDA", 
        "p5" => "Price/Sales", "p6" => "Price/Book", "r" => "P/E ratio", "r5" => "PEG ratio", "s7" => "Short ratio"];

    public function index(Request $request, $ticker)
    {
        $data = $this->getCompanyData($ticker);
        // return response()->json($data);
        //User must be authenticated to see watchlists and notifications
        if(Auth::user()){
            $watchlists = (new Watchlist)->where('user_id', Auth::user()->id)->get();
            $notifications = (new Notification)->where('user_id', Auth::user()->id)->where('ticker', $ticker)->get();
            
            foreach($notifications as $notification){
                $conditions = $notification->notificationCondition()->get();
                foreach($conditions as $condition){
                    $conditionsArray[] = [
                        'dataId' => $condition->data_id,
                        'dataName' => $this->yahooKeyTranslation[$condition->data_id],
                        'comparisonOperator' => $condition->comparison_operator,
                        'dataValue' => $condition->data_value
                    ];
                }
                $activeNotifications[] = [
                    'id' => $notification->id,
                    'name' => $notification->name, 
                    'description' => $notification->description,
                    'triggered' => $notification->triggered,
                    'conditions' => $conditionsArray
                ];
                $conditionsArray = [];
            }
        }
        $watchlists = (isset($watchlists)) ? $watchlists : json_encode([]);
        $activeNotifications = (isset($activeNotifications)) ? json_encode($activeNotifications) : json_encode([]);
    	//dd(gettype($data['body']['Name']), gettype($data['body']['Stock exchange']), gettype($ticker));
    	return view('company.index', [
    		'data' => $data,
    		'ticker' => $ticker,
            'watchlists' => $watchlists,
            'companyName' => $data['body']['Name'],
            'companyExchange' => $data['body']['Stock exchange'],
            'dataList' => $this->yahooKeyTranslation,
            'activeNotifications' => $activeNotifications,
    	]);
    }



    /* NEED TO CHANGE */
    public function getCompanyData($ticker)
    {
        
        /* YAHOO FINANE API IS DEAD, MOCK THIS PART */
    	//build query
    	//$url = $this->yahooQueryLink($ticker);
    	//Create guzzle client
    	//$client = new Client;
    	//send request
    	//$response = $client->request('GET', $url); 
        
        //Csv string to array
    	//$responseArray = $this->_csvToArray($response->getBody(), $this->yahooKeyArray); 
        
         // MOCK THE YAHOO FINANCE API!

        $responseArray = $this->getMockCompanyData($ticker);

        //Change keys
    	$responseFullKeys = $this->_changeKey($responseArray, $this->yahooKeyTranslation);

    	//Array to json and return
    	return ['body' => $responseFullKeys, 'responseCode' => 200]; //$response->getStatusCode()
    }



    public function getMockCompanyData($ticker) 
    {
        $data = (new FinancialData)->where('ticker', $ticker)->first();
        if (count($data) === 0) {
            $faker = \Faker\Factory::create();
            DB::table('financialdata')->insert([
                "ticker" => $ticker,
                "p" => $faker->randomFloat(2, 10, 1000),//"Previous close", 
                "y" => $faker->randomFloat(2, 0, 5),//"Dividend yield",
                "d" => $faker->randomFloat(2, 0, 100), //"Dividend per share",
                "d1" => $faker->date(),//"Last trade date", 
                "t8" => $faker->randomFloat(2, 10, 1000),//"1 year target price",
                "m4" => $faker->randomFloat(2, 10, 1000),//"200 day moving avg",
                "g3" => $faker->randomFloat(2, 10, 100),//"Annualizd gain",
                "s6" => $faker->randomFloat(2, 1000000, 100000000),//"Revenue", 
                "w" => "{$faker->randomFloat(2, 10, 500)}-{$faker->randomFloat(2, 500, 1000)}",//"52 week range",
                "j1" => $faker->randomFloat(2, 100000000, 9000000000),//"Market capitalization",
                "n" => $ticker,//"Name",
                "x" => 'NYSE',//"Stock exchange", 
                "j2" => $faker->randomFloat(2, 1000000, 100000000),//"Shares outstanding",
                "v" => $faker->randomFloat(2, 1000000, 100000000),//"Volume",
                "e" => $faker->randomFloat(2, 1, 50),//"EPS",
                "b4" => $faker->randomFloat(2, 100000000, 10000000000),//"Book value",
                "j4" => $faker->randomFloat(2, 1000000, 100000000),//"EBITDA", 
                "p5" => $faker->randomFloat(2, 0, 20),//"Price/Sales",
                "p6" => $faker->randomFloat(2, 0, 20),//"Price/Book", 
                "r" => $faker->randomFloat(2, 0, 20),//"P/E ratio", 
                "r5" => $faker->randomFloat(2, 0, 20),//"PEG ratio",
                "s7" => $faker->randomFloat(2, 0, 3),//"Short ratio"
            ]);
            $data = (new FinancialData)->where('ticker', $ticker)->first();
        }

        return $data->toArray();
    }




    public function yahooQueryLink($ticker){
		return "http://finance.yahoo.com/d/quotes.csv?s={$ticker}&f={$this->yahooDataString}";
	}

    public function _csvToArray($csv, $keys){
		$values = str_getcsv($csv, ',', '"'); //"facebook, Inc"
		return array_combine($keys, $values); //array("p"=>34.09, "m4"=>87.00)...
	}

	//not used atm
	private function _changeKey($array, $newKeys)
    { 
        foreach($array as $key => $value){
        	$keyArray[] = $newKeys[$key];
        	$valueArray[] = $value;
        }
        return array_combine($keyArray, $valueArray);
    }

}

