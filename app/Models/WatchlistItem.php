<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WatclistItem extends Model
{
    protected $fillable = [
    	'watchlist_id',
    	'ticker',
    ];

    public function watchlist()
    {
        return $this->belongsTo(Watchlist::class);
    }

    public function company() //a company does not belong to a watchlistItem, but through the ticker it is a strong relationship between a watchlist item and a company. HOW TO EXPRESS IT IN CODE???
    {
    	return $this->belongsTo(Company::class)
    }
}
