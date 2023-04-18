<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\HasTranslations;

class PrivacyAbout extends Model
{ 
    use HasTranslations;

    public static $rules = [
        'category' => 'required',
        'content' => 'required',
    ];
    public $translatable = [
    ];
    public $table = 'privacy_abouts';
    public $fillable = [
        'category',
        'content',
    ];
}
 