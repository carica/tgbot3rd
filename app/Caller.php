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
}