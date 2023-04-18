<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\HasTranslations;

class AboutUs extends Model
{
    use HasTranslations;

    public static $rules = [
        'category_id' => 'required',
        'description' => 'required',
    ];
    public $translatable = [
        // 'tsh_name',
        // 'tsh_code'
    ];
    public $table = 'about_us';
    public $fillable = [
        'category_id',
        'description',
    ];

}
