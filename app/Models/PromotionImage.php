<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\HasTranslations;

class PromotionImage extends Model
{
    use HasTranslations;

    public $table = 'promotion_images';
    public $fillable = [
        'promotion_id',
        'img_path',
        'img_name',
    ];

}
