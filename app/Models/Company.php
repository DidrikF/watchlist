<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    protected $fillable = [

    ];

    public function watchlistItem()
    {
    	return $this->hasMany(WatchlistItem::class);
    }
}
