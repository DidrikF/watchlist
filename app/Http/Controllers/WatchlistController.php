<?php

namespace App\Http\Controllers;

use App\Models\{Watchlist, WatchlistItem, User, Company, Industry};

use Illuminate\Support\Facades\Auth;

use Illuminate\Http\Request;

class WatchlistController extends Controller
{
    public function create(Request $request)
    {
    	//Authenticated as all that is needed
    	$watchlist = new Watchlist;
    	$watchlist->user_id = Auth::user()->id;
    	$watchlist->title = $request->title;
    	$watchlist->description = $request->description;
    	$watchlist->save();

    	return resoponse()->json(null, 200);

    }

    public function readAll()
    {
    	$this->authorize('readAll'); //Dont know, the authenticated user can only see the watchlists that belongs to him/her

    	//get all watchlists that belongs to the user
    	private $watchlists = Auth::user()->watchlist()->all(); 

    	return response()->json($watchlists, 200); 
    }

    public function read(Watchlist $watchlist)
    {
    	$this->authorize('read', $watchlist);
    	return response()->json([
    			'title' => $watchlist->title,
    			'description' => $watchlist->description,

    		], 200);

    }

    public function update()
    {
    	$this->authorize('update', $watchlist);
    	$watchlist->update([
    			'title' => $request->title,
    			'description' => $request->description,
    		]);
    	return response()->json(null, 200);
    }

    public function delete()
    {
    	$this->authorize('delete', $watchlist);
    	$watchlist->delete();
    	return request()->json(null, 200);
    }

    //Working with watchlist items

    //resolving Watchlist and passing normal paramenter, hope it understands
    public function createItem(Request $request, Watchlist $watchlist, $ticker)
    {
        $wathclistItem = new WatchlistItem;
        
        //also putting the company into the companies table... need to sort this also out on the client side 

        //validation/salitation in Request class //watchlist exists and company is not allready in it
        $this->authorize('createItem', $watchlist, $item); //user ownes the watchlist
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
