<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\HasTranslations;

class EmergancyContact extends Model
{
    use HasTranslations;

    public static $rules = [
        'contact_name' => 'required|max:127',
        'township_id' => 'required|max:36',
        'phone' => 'required',
        'address'=>'required',
        'lat'=>'nullable',
        'long'=>'nullable'
    ];
    public $translatable = [
        // 'tsh_name',
        // 'tsh_code'
    ];
    public $table = 'emergancy_contacts';
    public $fillable = [
        'contact_name',
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
