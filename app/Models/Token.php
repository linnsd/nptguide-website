<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\HasTranslations;

class Token extends Model
{
    use HasTranslations;

    public static $rules = [
        'token' => 'required',
    ];
    public $translatable = [
        
    ];
    public $table = 'tokens';
    public $fillable = [
        'user_id',
        'token'
    ];
}
