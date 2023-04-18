<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\HasTranslations;

class Township extends Model
{
    use HasTranslations;

    public static $rules = [
        'tsh_name' => 'required|max:127',
        'tsh_color' => 'required|max:36',
        'tsh_code' => 'nullable',
    ];
    public $translatable = [
        // 'tsh_name',
        // 'tsh_code'
    ];
    public $table = 'townships';
    public $fillable = [
        'tsh_name',
        'tsh_color',
        'tsh_code'
    ];
}
