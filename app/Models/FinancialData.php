<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;

class FinancialData extends Model
{
    protected $fillable = ["ticker", "p", "y", "d", "d1", "t8", "m4", "g3", "s6", "w", "j1", "n", "x", "j2", "v", "e", "b4", "j4", "p5", "p6", "r", "r5", "s7"]; 

    protected $table = 'financialdata';

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

}
