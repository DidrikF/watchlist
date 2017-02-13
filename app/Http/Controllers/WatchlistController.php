<?php

namespace App\Http\Controllers;

use App\Models\{Watchlist, WatchlistItem, User, Company, Industry, Analysis};

use Illuminate\Support\Facades\Auth;

//use Illuminate\Http\Request;
use App\Http\Requests\Request;

use App\Http\Requests\{WatchlistRequest, WatchlistItemRequest};


class WatchlistController extends Controller
{
    public function create(WatchlistRequest $request) //Validation, authentication is all that is required
    {
    	//Authenticated as all that is needed
    	$watchlist = new Watchlist;
    	$watchlist->user_id = Auth::user()->id;
    	$watchlist->name = $request->name;
    	$watchlist->description = $request->description;
    	if($watchlist->save()){
            $watchlists = Auth::user()->watchlist()->get();
            return response()->json($watchlists, 201);
        }
        return response()->json(null, 400);
    }

    public function readAll() //Not Used ATM
    {
    	$watchlists = Auth::user()->watchlist()->get();

    	return response()->json($watchlists, 200); 
    }

    public function update(Watchlist $watchlist, WatchlistRequest $request) //Validation
    {
    	$this->authorize('update', $watchlist);
    	$watchlist->update([
    			'name' => $request->name,
    			'description' => $request->description,
    		]);
    	return response()->json(null, 200);
    }

    public function delete(Watchlist $watchlist)
    {
    	$this->authorize('delete', $watchlist);
    	$watchlist->delete();
    	return response()->json(null, 200);
    }

    //Working with watchlist items

    public function read(Watchlist $watchlist)
    {
        //Need items in this format
        //{ticker: "STO", companyName: "Statoil ASA", exchange:"NYSE", score: 34, companyLink: "/company/STO"}
        $this->authorize('read', $watchlist);
        $watchlistItems = (new WatchlistItem)->where('watchlist_id', $watchlist->id)->get(); //
        //watchlist_id, ticker, using relationship
        $companies = $watchlistItems->map(function ($watchlistItem, $key) {
            return (new Company)->where('ticker', $watchlistItem->ticker)->first();
        });

        $tickers = $watchlistItems->pluck('ticker'); //get array/collection of tickers

        $analysisScores = (new Analysis)->select('ticker', 'financial', 'cash_flow', 'growth_potential', 'risk')
                                        ->where('user_id', Auth::user()->id)->whereIn('ticker', $tickers)->get();

        //NEED WORK HERE
        return response()->json([
                'title' => $watchlist->name,
                'description' => $watchlist->description,
                'items' => $companies,
                'scores' => $analysisScores,
            ], 200);

    }

    //resolving Watchlist and passing normal paramenter, hope it understands
    public function createItem(Watchlist $watchlist, WatchlistItemRequest $request) //FORM REQUEST
    {
        $this->authorize('createItem', $watchlist); //DO VIA FORM REQUEST
        
        //ADD company if not allready in the DB
        $company = new Company;
        $responseCode = 201;
        if(!$company->where('ticker', $request->ticker)->exists()){
            $company->ticker = $request->ticker;
            $company->name = $request->name;
            $company->exchange = $request->exchange;
            $company->save();
        }

        //ADD watchlist item, if not allready in DB (this is a job for validation)
        $wathclistItem = new WatchlistItem;
        if(!$wathclistItem->where('watchlist_id', $watchlist->id)->where('ticker', $request->ticker)->exists()){
            $wathclistItem->watchlist_id = $watchlist->id;
            $wathclistItem->ticker = $request->ticker;
            $wathclistItem->save();
        }else{
            $responseCode = 200;
        }

        //Getting new item list and scores (the user might have old analysises)
        $watchlistItems = (new WatchlistItem)->where('watchlist_id', $watchlist->id)->get(); //
        //watchlist_id, ticker, using relationship
        $companies = $watchlistItems->map(function ($watchlistItem, $key) {
            return (new Company)->where('ticker', $watchlistItem->ticker)->first();
        });

        $tickers = $watchlistItems->pluck('ticker');

        $analysisScores = (new Analysis)->select('ticker', 'financial', 'cash_flow', 'growth_potential', 'risk')
                                        ->where('user_id', Auth::user()->id)->whereIn('ticker', $tickers)->get();

        return response()->json([
            'items' => $companies,
            'scores' => $analysisScores,
        ], $responseCode); //created
    }

    public function deleteItem(Watchlist $watchlist, $ticker)
    {
        //$watchlistItem = //get item based on watchlist id and ticker

        $this->authorize('deleteItem', $watchlist);

        (new WatchlistItem)->where('watchlist_id', $watchlist->id)->where('ticker', $ticker)->delete();
        return response()->json([
                'ticker' => $ticker,
                'watchlist' => $watchlist,
            ], 200); 
    }




}
