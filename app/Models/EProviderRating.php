<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
// use App\Models\EProviderRating;

class EProviderRating extends Model
{
    //
    public $fillable = [
        'eprovider_id',
        'user_id',
        'remark',
        'rating_grade'
    ];

    public static function list($request)
    {

        $ratings = new EProviderRating();


        $ratings = $ratings->leftjoin('e_providers', 'e_providers.id', '=', 'e_provider_ratings.eprovider_id')
            ->leftjoin('users', 'users.id', '=', 'e_provider_ratings.user_id')
            ->select('e_providers.name AS place_name', 'users.name AS username', 'e_provider_ratings.*');

        if ($request->keyword != null) {
            $ratings = $ratings->where('e_providers.name', 'LIKE', '%' . $request->keyword . '%')
                ->orWhere('users.name', 'LIKE', '%' . $request->keyword . '%');
        }

        if ($request->rating_grade != null) {
            $ratings = $ratings->where('e_provider_ratings.rating_grade', $request->rating_grade);
        }

        $ratings = $ratings->orderBy('e_provider_ratings.created_at', 'desc');

        return $ratings;
    }
}