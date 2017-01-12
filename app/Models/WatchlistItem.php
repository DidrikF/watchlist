<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WatchlistItem extends Model
{
    protected $fillable = [
    	'watchlist_id',
    	'ticker',
    ];

    protected $hidden = [
        'id', //watchlist_id and ticker is used to find the correct model
        'deleted_at',
        'created_at',
        'updated_at',
    ];

    public function watchlist()
    {
        return $this->belongsTo(Watchlist::class);
    }

    public function companies() //a company does not belong to a watchlistItem, but through the ticker it is a strong relationship between a watchlist item and a company. HOW TO EXPRESS IT IN CODE???
    {
    	return $this->belongsTo(Company::class);
    }
}
