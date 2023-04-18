<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\HasTranslations;

class TextSlide extends Model
{
    use HasTranslations;

    public static $rules = [
        'text' => 'required',
    ];
    public $translatable = [
        
    ];
    public $table = 'text_slides';
    public $fillable = [
        'text',
        'status',
    ];
 
}
