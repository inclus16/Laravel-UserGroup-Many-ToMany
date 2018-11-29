<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    public function users()
    {
        /**
         * Всё это можно было в теории не писать - как правило ORM сам поймёт по наименованиям,
         * но были случаи, когда имена не совпадали - лучше явно указать всё.
         */
        return $this->belongsToMany(User::class,
            'users_groups','group_id','user_id')->withTimestamps();
    }
}
