<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Uuid;

class Caller extends Model
{
    use SoftDeletes;
    protected $fillable = [];
    protected $hidden = [];
    protected $dates = ['deleted_at'];

    //status columns reserved.
    const available = 0;

    public function messages()
    {
        return $this->hasMany('App\Message');
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    /**
     *  Setup model event hooks
     */
    public static function boot()
    {
        parent::boot();
        self::creating(function ($model) {
            $model->uuid = (string) Uuid::generate(4);
        });
    }
}