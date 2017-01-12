<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Analysis extends Model
{
	protected $fillable = [
    	'financial',
    	'cash_flow',
    	'growth_potential',
    	'risk',
    	'text_analysis',
    ];
    protected $table = 'analysis';

    /*protected $hidden = [
        //'id', //might mot need this, but for now I keep it safe
        'deleted_at',
        'created_at',
        'updated_at',
    ];*/


    /**
     * Get the route key for the model.
     *
     * @return string
     */
    public function getRouteKeyName()
    {
        return 'ticker';
    }

    public function user()
    {
    	return $this->belongsTo(User::class);
    }

    public function company()
    {
    	return $this->belongsTo(Company::class);
    }

}