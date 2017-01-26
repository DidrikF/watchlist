<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NotificationCondition extends Model
{
    protected $fillable = [
    	'notification_id',
    	'data_id',
    	'comparison_operator',
    	'data_value'
    ];

    protected $hidden = [
        'deleted_at',
        'created_at',
        'updated_at',
    ];

    public function notification()
    {
    	return $this->belongsTo(Notification::class);
    }

}
