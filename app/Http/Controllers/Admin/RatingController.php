<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\EProvider;
use App\Models\EProviderRating;
use Flash;

class RatingController extends Controller
{
    //
    public function index(Request $request)
    {
        $ratings = EProviderRating::list($request);
        $count = $ratings->count();
        $ratings = $ratings->paginate(10);
        return view('admin.rating.index', compact('ratings', 'count'))->with('i', ((request()->input('page', 1) - 1) * 10));
    }

    public function destory($id)
    {
        $ratings = EProviderRating::find($id);
        // dd($id);
        $ratings->delete();
        Flash::success(__('lang.deleted_successfully', ['operator' => "Rating"]));
        return redirect(route('rating.index'));
    }
}
