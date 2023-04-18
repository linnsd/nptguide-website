<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Township;
use App\Models\EProvider;
use App\Models\Promotion;
use App\Models\UserFavourite;
use App\Models\EService;
use App\Models\EProviderRating;
use Illuminate\Support\Facades\Auth;
use Flash;

class PlaceController extends Controller
{
    public function index()
    {
        return view('frontend.place.place_detail_01');
    }

    public function show($id)
    {
        $categories = [];
        $place = EProvider::find($id);
        $arr = [];

        $arr['id'] = $place->id;
        $arr['cat_id'] = (!empty($place->category) > 0) ? $place->category->id : '';
        $arr['category'] = (!empty($place->category) > 0) ? $place->category->name : '';
        $arr['name'] = $place->name;
        $arr['description'] = $place->description;
        $arr['phone_number'] = $place->phone_number;
        $arr['featured'] = $place->featured;
        $arr['available'] = $place->available;
        $arr['accepted'] = $place->accepted;
        $arr['address'] = $place->address;
        $arr['tsh_id'] = $place->tsh_id;
        $arr['township'] = (!empty($place->townships) > 0) ? $place->townships->tsh_name : '';
        $arr['latitude'] = $place->latitude;
        $arr['longitude'] = $place->longitude;
        $arr['has_media'] = $place->has_media;
        $arr['fb_page_url'] = $place->fburl;

        $arr['media'] = [];

        if ($place->has_media) {
            // dd($place->media);
            foreach ($place->media as $media) {
                $temp['id'] = $media->id;
                $temp['mime_type'] = $media->mime_type;
                $temp['formated_size'] = $media->formated_size;
                $temp['url'] = $media->url;


                array_push($arr['media'], $temp);
            }
        }

        $arr['services'] = [];

        if (!empty($place->eServices)) {
            foreach ($place->eServices as $service) {
                $stemp['id'] = $service->id;
                $stemp['name'] = $service->name;
                $stemp['description'] = $service->description;
                $stemp['price'] = $service->price;
                $stemp['featured'] = $service->featured;
                $stemp['available'] = $service->available;
                $stemp['imgurl'] = ($service->has_media) ? $service->media[0]->url : '';
                array_push($arr['services'], $stemp);
            }
        }


        $eProviders = new EProvider();
        // dd($place->category_id);
        $providers = $eProviders->leftjoin('townships', 'townships.id', '=', 'e_providers.tsh_id')
            ->leftjoin('categories', 'categories.id', '=', 'e_providers.category_id')
            ->select([
                'e_providers.*',
                'townships.tsh_name',
                'categories.name AS category_name'
            ])->where("e_providers.featured", 1)->where("e_providers.available", 1)->where("e_providers.accepted", 1)->where('e_providers.category_id', $place->category_id)
            ->inRandomOrder()->limit(10)
            ->get();

        // $providers =EProvider::where("featured",1)->where("available",1)->where("accepted",1)->get();

        $similar_places = [];
        foreach ($providers as $key => $provider) {
            $pdata['id'] = $provider->id;
            $pdata['name'] = $provider->name;
            $pdata['phone_number'] = $provider->phone_number;
            $pdata['featured'] = $provider->featured;
            $pdata['available'] = $provider->available;

            $pdata['address'] = $provider->address;
            $pdata['latitude'] = $provider->latitude;
            $pdata['longitude'] = $provider->longitude;
            $pdata['tsh_name'] = $provider->tsh_name;
            $pdata['category_name'] = $provider->category_name;
            $pdata['imgPath'] = ($provider->has_media) ? $provider->media[0]->url : "";
            array_push($similar_places, $pdata);
        }

        // dd($similar_places)2

        // dd($arr);
        return view('frontend.place.place_detail_02', compact('place', 'categories', 'similar_places', 'arr'));
    }

    public function visiting_places()
    {
        $data = new EProvider();
        $data = $data->leftjoin('townships', 'townships.id', '=', 'e_providers.tsh_id')
            ->select([
                'e_providers.*',
                'townships.tsh_name'
            ])->where("category_id", 22)->where("available", 1)->where("accepted", 1)
            ->paginate(12);

        $places = [];
        foreach ($data as $key => $provider) {
            $placedata['id'] = $provider->id;
            $placedata['name'] = $provider->name;
            $placedata['phone_number'] = $provider->phone_number;
            $placedata['featured'] = $provider->featured;
            $placedata['available'] = $provider->available;
            $placedata['address'] = $provider->address;
            $placedata['latitude'] = $provider->latitude;
            $placedata['longitude'] = $provider->longitude;
            $placedata['tsh_name'] = $provider->tsh_name;

            $placedata['imgPath'] = ($provider->has_media) ? $provider->media[0]->url : "";
            array_push($places, $placedata);
        }


        // $places = $data;
        return view('frontend.place.visiting', compact('places', 'data'));
    }

    public function add_review(Request $request)

    {
        // dd($request->all());
        if (is_null(Auth::user())) {
            return redirect()->route('login');
        }

        if (is_null($request->rating_grade)) {
            return redirect()->back()->with('error', 'You need to give at least 1 Star for review!');
        }
        $eprovider_id = $request->eprovider_id;
        $rating_grade = $request->rating_grade;
        $user_id = Auth::user()->id;

        $rating = new EProviderRating();
        $rating->user_id = $user_id;
        $rating->eprovider_id = $eprovider_id;
        $rating->rating_grade = $rating_grade;
        $rating->remark = $request->remark;
        $rating->save();

        Flash::success(__('lang.added_successfully', ['operator' => __('lang.e_provider')]));
        return redirect()->back()->with('success', 'Thanks for your review.');;
    }

    public function promotions(Request $request)
    {
        $promotions = Promotion::list($request);
        $count = $promotions->get()->count();
        $promotions = $promotions->orderBy('created_at', 'desc')->paginate(12);
        return view('frontend.search.promotions', compact('promotions', 'count'));
    }

    public function promotion_detail($id)
    {
        $promotions = new Promotion();
        $promotions = $promotions->leftjoin('e_providers', 'e_providers.id', '=', 'promotions.place_id')
            ->select('promotions.*', 'e_providers.id AS place_id', 'e_providers.name AS place_name', 'e_providers.fburl AS facebookurl', 'e_providers.phone_number AS phone')
            ->where('promotions.status', 1)
            ->where('promotions.id', $id)->get();
        if (!$promotions) {
            die();
        }
        // dd($promotions);
        $promotion = [];
        foreach ($promotions as $key => $provider) {
            $pdata['id'] = $provider->id;
            $pdata['title'] = $provider->title;
            $pdata['description'] = $provider->description;
            $pdata['place_name'] = $provider->place_name;
            $pdata['place_id'] = $provider->place_id;
            $pdata['phone'] = $provider->phone;
            $pdata['facebookurl'] = $provider->facebookurl;
            $pdata['from_date'] = $provider->from_date;
            $pdata['to_date'] = $provider->to_date;
            $pdata['place_id'] = $provider->place_id;
            $pdata['status'] = $provider->status;
            $pdata['imgPath'] = ($provider->has_media) ? $provider->media[0]->url : "";
            array_push($promotion, $pdata);
        }
        // dd($promotion);
        return view('frontend.place.promotion', compact('promotion'));
    }

    public function popular_shops()
    {
        $eProviders = new EProvider();
        $data = $eProviders->leftjoin('townships', 'townships.id', '=', 'e_providers.tsh_id')
            ->select([
                'e_providers.*',
                'townships.tsh_name'
            ])->where("featured", 1)->where("available", 1)->where("accepted", 1)->where('category_id', '!=', 22)
            ->paginate(12);

        $places = [];
        foreach ($data as $key => $provider) {
            $pdata['id'] = $provider->id;
            $pdata['name'] = $provider->name;
            $pdata['phone_number'] = $provider->phone_number;
            $pdata['featured'] = $provider->featured;
            $pdata['available'] = $provider->available;

            $pdata['address'] = $provider->address;
            $pdata['latitude'] = $provider->latitude;
            $pdata['longitude'] = $provider->longitude;
            $pdata['tsh_name'] = $provider->tsh_name;

            $pdata['imgPath'] = ($provider->has_media) ? $provider->media[0]->url : "";
            array_push($places, $pdata);
        }

        // dd($places);

        return view('frontend.place.popular', compact('places', 'data'));
    }

    public function menu_service_detail($id)
    {
        // dd($id);
        $eservice = new EService();
        $eservice = $eservice->leftjoin('e_providers', 'e_providers.id', '=', 'e_services.e_provider_id')
            ->select('e_services.*', 'e_providers.id AS place_id', 'e_providers.name AS place_name')
            ->where('e_services.id', $id)
            ->get();


        $data = [];
        foreach ($eservice as $key => $provider) {
            $pdata['id'] = $provider->id;
            $pdata['name'] = $provider->name;
            $pdata['price'] = $provider->price;
            $pdata['price_unit'] = $provider->price_unit;
            $pdata['discount_price'] = $provider->discount_price;
            $pdata['quantity_unit'] = $provider->quantity_unit;
            $pdata['duration'] = $provider->duration;
            $pdata['description'] = $provider->description;
            $pdata['featured'] = $provider->featured;
            $pdata['featured_request'] = $provider->featured_request;
            $pdata['available'] = $provider->available;
            $pdata['place_id'] = $provider->place_id;
            $pdata['place_name'] = $provider->place_name;
            $pdata['imgPath'] = ($provider->has_media) ? $provider->media[0]->url : "";
            array_push($data, $pdata);
        }

        return view('frontend.place.service_detail', compact('data'));
    }

    public function placeSearch(Request $request)
    {
        $categories = Category::get();
        $eProviders = EProvider::get();
        $townships = Township::all();
        $places = [];
        $eProviders = new EProvider();

        $eProviders = $eProviders->leftjoin('townships', 'townships.id', '=', 'e_providers.tsh_id')
            ->select([
                'e_providers.*',
                'townships.tsh_name'
            ]);


        if (!empty($request->category) && !empty($request->category[0])) {
            $eProviders = $eProviders->whereIn('category_id', $request->category);
        }

        if (!empty($request->township) && !empty($request->township[0])) {
            $eProviders = $eProviders->whereIn('tsh_id', $request->township);
        }

        if (!empty($request->keyword)) {
            $eProviders = $eProviders->where('name', 'LIKE', '%' . $request->keyword . '%');
        }

        $eProviders = $eProviders->where('available', '=', 1)->where('accepted', '=', 1);


        $count = $eProviders->count();

        $eProviders = $eProviders->paginate(10);
        // $eProviders= $eProviders->where('available','=',1)->map(function ($item, $key) {
        //                          return [
        //                             'id' => $item->id,
        //                             'name' => $item->name,
        //                             'featured' => $item->featured,
        //                             'available' => $item->available,
        //                             'available' => $item->available,
        //                             'phone_number' => $item->phone_number,
        //                             'address' => $item->address,
        //                             'latitude' => $item->latitude,
        //                             'longitude' => $item->longitude,
        //                             'tsh_name'=>$item->tsh_name,
        //                             'has_media' => $item->has_media,
        //                             'imgPath' => ($item->has_media)?$item->media[0]->url:""
        //                         ];

        //                 });




        // dd($count);
        $filter_township = $request->township;
        $filter_category = $request->category;

        // search-listing?page=2?category%5B%5D=1#
        // validit search-listing?category%5B%5D=1&category%5B%5D=2#


        // dd($filter_category);
        $similar_places = [];
        return view('frontend.search.search_02', compact('places', 'categories', 'similar_places', 'townships', 'filter_township', 'filter_category', 'eProviders', 'count'));
    }

    public function page_search_listing()
    {
        // code...
    }

    public function fav_place()
    {
        if (is_null(Auth()->user())) {
            $places = [];
            $count = 0;
        } else {
            $places = new UserFavourite();
            $places = $places->leftjoin('e_providers', 'user_favourites.eprovider_id', '=', 'e_providers.id')
                ->where('user_favourites.user_id', Auth()->user()->id)
                ->select('e_providers.id')
                ->get();
            $places_id = $places;

            // $places = EProvider::findMany($places_id);
            $places = EProvider::whereIn('id', $places_id);
            $count = $places->count();
            $places = $places->paginate(12);
        }
        // dd($places);

        return view('frontend.search.fav_place', compact('places', 'count'));
    }

    public function add_to_fav($id)
    {

        if (is_null(Auth::user())) {
            // return redirect(url('/login'));
             return redirect(url('/'))->with('warning', 'You need to login first!');
            // ->with('warning', 'You need to login first!')
        }

        $user_id = Auth::user()->id;

        $fav = UserFavourite::where('eprovider_id', $id)->where('user_id', $user_id);

        $isAlready = $fav->count();

        if ($isAlready) {
            $old_fav = UserFavourite::where('eprovider_id', $id)->where('user_id', $user_id);
            $old_fav->delete();

            return redirect(url('/place_detail/' . $id))->with('success', 'Successfully, Removed');
        } else {
            $fav = new UserFavourite();
            $fav->user_id = $user_id;
            $fav->eprovider_id = $id;
            $fav->save();

            return redirect(url('/place_detail/' . $id))->with('success', 'Successfully, Added.');
        }
    }
}