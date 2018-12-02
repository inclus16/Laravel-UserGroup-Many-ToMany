<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
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
        'last_name', 'email', 'first_name','status'
    ];



    public function groups()
    {
        /**
         * Всё это можно было в теории не писать - как правило ORM сам поймёт по наименованиям,
         * но были случаи, когда имена не совпадали - лучше явно указать всё.
         */
        return $this->belongsToMany(Group::class,
            'users_groups','user_id','group_id')->withTimestamps();
    }
}
