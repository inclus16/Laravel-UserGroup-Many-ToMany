<?php

namespace App;


use Illuminate\Foundation\Auth\User as Authenticable;

class Admin extends Authenticable
{
    public function role()
    {
        return $this->belongsTo(Role::class,'role_id','id');
    }
}
