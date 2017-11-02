<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    //status reserved
    const started = 0;
    const called = 1;

    protected $fillable = ['tg_user_id'];
    protected $hidden = [];
    
    public function callers()
    {
        return $this->hasMany('App\Caller');
    }

    public function messages()
    {
        return $this->hasMany('App\Message');
    }

    public function updates()
    {
        return $this->hasMany('App\Update');
    }
}