<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\HasTranslations;

class MinistryContact extends Model
{
    use HasTranslations;

    public static $rules = [
        'ministry_name' => 'required|max:127',
        'township_id' => 'required',
        'phone' => 'required',
        'address'=>'required',
        'lat'=>'nullable',
        'long'=>'nullable'
    ];
    public $translatable = [
        // 'tsh_name',
        // 'tsh_code'
    ];
    public $table = 'ministry_contacts';
    public $fillable = [
        'ministry_name',
        'township_id',
        'phone',
        'address',
        'lat',
        'long'
    ];

    public function townships()
    {
        return $this->belongsTo(Township::class, 'township_id', 'id');
    }
}
