<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Update extends Model
{

    //type attribute:
    //command = 1

    protected $fillable = ['update_id'];
    protected $hidden = [];
    
    public function user()
    {
        return $this->belongsTo('App\User');
    }

}