<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserFavourite extends Model
{
    //
    protected $fillable = [
        'user_id',
        'eprovider_id'
    ];
}