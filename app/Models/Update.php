<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Update extends Model
{

    //type attribute:
    //command = 1
    const typeCommand = 1;
    const typeOther = 0;
    const requestDone = 1;
    const requestError = 2;
    const requestInit = 0;


    protected $fillable = ['update_id'];
    protected $hidden = [];
    
    public function user()
    {
        return $this->belongsTo('App\User');
    }

}