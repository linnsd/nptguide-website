<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\HasTranslations;

class PrivacyPolicy extends Model
{
    use HasTranslations;

    public static $rules = [
        'title' => 'required',
        'content' => 'required',
    ];
    public $translatable = [
    ];
    public $table = 'privacy_policies';
    public $fillable = [
        'title',
        'content',
    ];
}
 