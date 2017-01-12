<?php

namespace App\Http\Controllers;

use App\Models\{Watchlist, WatchlistItem, User, Company, Industry, Analysis};

use Illuminate\Support\Facades\Auth;

use Illuminate\Http\Request;

class WatchlistController extends Controller
{
    public function create(Request $request) //FORM REQUEST, auth = true as it was done in middleware
    {
    	//Authenticated as all that is needed
    	$watchlist = new Watchlist;
    	$watchlist->user_id = Auth::user()->id;
    	$watchlist->title = $request->title;
    	$watchlist->description = $request->description;
    	$watchlist->save();

    	return resoponse()->json(null, 200);

    }

    public function readAll() //Not User ATM
    {
    	$watchlists = Auth::user()->watchlist()->get();

    	return response()->json($watchlists, 200); 
    }

    public function update(Watchlist $watchlist) //FORM REQUEST
    {
    	$this->authorize('update', $watchlist);
    	$watchlist->update([
    			'title' => $request->title,
    			'description' => $request->description,
    		]);
    	return response()->json(null, 200);
    }

    public function delete(Watchlist $watchlist)
    {
    	$this->authorize('delete', $watchlist);
    	$watchlist->delete();
    	return request()->json(null, 200);
    }

    //Working with watchlist items

    public function readItems(Watchlist $watchlist)
    {
        //Need items in this format
        //{ticker: "STO", companyName: "Statoil ASA", exchange:"NYSE", score: 34, companyLink: "/company/STO"}
        $this->authorize('read', $watchlist);
        $watchlistItems = (new WatchlistItem)->where('watchlist_id', $watchlist->id)->companies()->get(); 
        //watchlist_id, ticker, using relationship

        $tickers = $watchlistItems->pluck('ticker'); //get array/collection of tickers

        $analysisScores = (new Analysis)->select('ticker', 'financial', 'cash_flow', 'growth_potential', 'risk')
                                        ->where('user_id', Auth::user()->id)->whereIn('ticker', $tickers)->get();

        //NEED WORK HERE
        return response()->json([
                'title' => $watchlist->title,
                'description' => $watchlist->description,
                'items' => $watchlistItems,
                'scores' => $analysisScores,

            ], 200);

    }

    //resolving Watchlist and passing normal paramenter, hope it understands
    public function createItem(Request $request, Watchlist $watchlist, $ticker) //FORM REQUEST
    {
        $wathclistItem = new WatchlistItem;
        
        //ADD company if not allready in the DB, getting company data from the client...

        $this->authorize('createItem', $watchlist, $item); //DO VIA FORM REQUEST
        $item->watchlist_id = $request->watchlistId;
        $item->ticker = $ticker;
        return response()->json(null, 201); //created
    }

    public function deleteItem(Request $request, Watchlist $watchlist, $ticker)
    {
        //$watchlistItem = //get item based on watchlist id and ticker

        $this->authorize('deleteItem', $watchlist, $item);
        $item->delete();
        return response()->json(null, 200); 
    }




}
