<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    protected $fillable = [
    	'ticker',
    	'name',
    	'exchange',
    	'industry',
    ];

    protected $hidden = [
        'id', //ticker is used to identify company, the id is not needed
        'deleted_at',
        'created_at',
        'updated_at',
    ];

    public function getRouteKeyName()
    {
        return 'ticker';
    }

    public function watchlistItem()
    {
    	return $this->hasMany(WatchlistItem::class);
    }
}
