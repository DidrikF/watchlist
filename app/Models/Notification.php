<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $fillable = [
    	'user_id',
    	'ticker',
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

    public function notificationCondition()
    {
        return $this->hasMany(NotificationCondition::class);
    }
}
