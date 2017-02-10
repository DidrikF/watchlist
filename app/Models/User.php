<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token', 'accepted', 'admin'
    ];

    public function analysis()
    {
        return $this->hasMany(Analysis::class);
    }

    public function watchlist()
    {
        return $this->hasMany(Watchlist::class);
    }

    public function notification()
    {
        return $this->hasMany(Notification::class);
    }

    public function isAccepted()
    {
        return (bool) $this->accepted;
    }

    public function isAdmin()
    {
        return (bool) $this->admin;
    }

    public function isPrimeBoss()
    {
        return (bool) ($this->email === 'didrik@watchlist.com' && $this->id === 1);
    }
}
