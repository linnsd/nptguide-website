<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\HasTranslations;

class Contact extends Model
{
    use HasTranslations;

    public static $rules = [
        'name' => 'required',
        'phone_number'=>'required',
        'note'=>'required'
    ];
    public $translatable = [
        
    ];
    public $table = 'contacts';
    public $fillable = [
        'name',
        'phone_number',
        'message'
    ];
 
}