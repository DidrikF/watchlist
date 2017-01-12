<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class Watchlist extends Model
{
	protected $fillable = [
    	'user_id',
    	'name',
    	'description'
    ];

    protected $hidden = [
        'deleted_at',
        'created_at',
        'updated_at',
    ];

    public function user()
    {
    	return $this->belongsTo(User::class);
    }

    public function watchlistItem()
    {
        return $this->hasMany(WatchlistItem::class);
    }
}
