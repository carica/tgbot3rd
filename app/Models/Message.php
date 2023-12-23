<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $fillable = [];
    protected $hidden = [];
    
    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function caller()
    {
        return $this->belongsTo('App\Caller');
    }
}