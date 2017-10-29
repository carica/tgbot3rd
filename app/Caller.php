<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Caller extends Model
{
    use SoftDeletes;
    protected $fillable = [];
    protected $hidden = [];
    protected $dates = ['deleted_at'];

    public function messages()
    {
        return $this->hasMany('App\Message');
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }
}