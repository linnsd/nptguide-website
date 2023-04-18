<?php
/*
 * File name: DashboardAPIController.php
 * Last modified: 2021.02.21 at 14:56:24
 * Author: SmarterVision - https://codecanyon.net/user/smartervision
 * Copyright (c) 2021
 */

namespace App\Http\Controllers\API;

use App\Criteria\Bookings\BookingsOfUserCriteria;
use App\Criteria\Earnings\EarningOfUserCriteria;
use App\Criteria\Eservices\EProvidersOfManagerCriteria;
use App\Criteria\EProviders\EProvidersOfUserCriteria;
use App\Criteria\EServices\EServicesOfUserCriteria;
use App\Http\Controllers\Controller;
use App\Repositories\BookingRepository;
use App\Repositories\EarningRepository;
use App\Repositories\EProviderRepository;
use App\Repositories\EServiceRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Prettus\Repository\Exceptions\RepositoryException;
use App\Repositories\CategoryRepository;
use App\Repositories\PromotionRepository;
use App\Models\TextSlide;
use App\Models\Promotion;
use App\Models\EProviderRating;

class DashboardAPIController extends Controller
{
    /** @var  BookingRepository */
    private $bookingRepository;

    /** @var  EProviderRepository */
    private $eproviderRepository;
    /**
     * @var EServiceRepository
     */
    private $eserviceRepository;
    /**
     * @var EarningRepository
     */
    private $earningRepository;

    /**
     * @var CategoryRepository
     */
    private $categoryRepository;

    /**
     * @var PromotionRepository
     */
    private $promotionRepository;

    public function __construct(BookingRepository $bookingRepo, EarningRepository $earningRepository, EProviderRepository $eproviderRepo, EServiceRepository $eserviceRepository, CategoryRepository $categoryRepo, PromotionRepository $promotionRepo)
    {
        parent::__construct();
        $this->bookingRepository = $bookingRepo;
        $this->eproviderRepository = $eproviderRepo;
        $this->eserviceRepository = $eserviceRepository;
        $this->earningRepository = $earningRepository;
        $this->categoryRepository = $categoryRepo;
        $this->promotionRepository = $promotionRepo;
    }

    /**
     * Display a listing of the Faq.
     * GET|HEAD /provider/dashboard
     * @param Request $request
     * @return JsonResponse
     */
    public function provider(Request $request): JsonResponse
    {
        $statistics = [];
        try {

            $this->earningRepository->pushCriteria(new EarningOfUserCriteria(auth()->id()));
            $earning['description'] = 'total_earning';
            $earning['value'] = $this->earningRepository->all()->sum('e_provider_earning');
            $statistics[] = $earning;

            $this->bookingRepository->pushCriteria(new BookingsOfUserCriteria(auth()->id()));
            $bookingsCount['description'] = "total_bookings";
            $bookingsCount['value'] = $this->bookingRepository->all('bookings.id')->count();
            $statistics[] = $bookingsCount;

            $this->eproviderRepository->pushCriteria(new EProvidersOfUserCriteria(auth()->id()));
            $eprovidersCount['description'] = "total_e_providers";
            $eprovidersCount['value'] = $this->eproviderRepository->all('e_providers.id')->count();
            $statistics[] = $eprovidersCount;

            $this->eserviceRepository->pushCriteria(new EServicesOfUserCriteria(auth()->id()));
            $eservicesCount['description'] = "total_e_services";
            $eservicesCount['value'] = $this->eserviceRepository->all('e_services.id')->count();
            $statistics[] = $eservicesCount;
        } catch (RepositoryException $e) {
            return $this->sendError($e->getMessage());
        }

        return $this->sendResponse($statistics, 'Statistics retrieved successfully');
    }

    public function mainPage(Request $request): JsonResponse
    {
        $categories = $this->categoryRepository->where("featured", 1)->take(5)->get();

        $catArr = [];
        foreach ($categories as $key => $category) {
            $data['id'] = $category->id;
            $data['name'] = $category->name;
            $data['order'] = $category->order;
            $data['featured'] = $category->featured;
            $data['iconUrl'] = ($category->has_media) ? $category->media[0]->url : "";

            array_push($catArr, $data);
        }

        // $promotions = new Promotion();
        // $promotions = $promotions->leftjoin('e_providers', 'e_providers.id', '=', 'promotions.place_id')->select('e_providers.id', 'e_providers.name', 'e_providers.phone_number', 'e_providers.latitude', 'e_providers.longitude', 'e_providers.address')->where('promotions.status', 1)->whereDate('promotions.from_date', '<=', date('Y-m-d'))->whereDate('promotions.to_date', '>=', date('Y-m-d'))->get();
        $promotions = $this->promotionRepository->where("status", 1)->get();

        $promotionArr = [];
        foreach ($promotions as $key => $value) {
            // dd($value->media);
            $p_data['id'] = $value->id;
            $p_data['name'] = $value->title;
            $p_data['from_date'] = date('d-m-Y',strtotime($value->from_date));
            $p_data['to_date'] = date('d-m-Y',strtotime($value->to_date));
            $p_data['shop_name'] = $this->get_shop_name($value->place_id);
            // $p_data['lat'] = $value->latitude;
            // $p_data['lng'] = $value->longitude;
            $p_data['imgPath'] =($value->has_media) ? $value->media[0]->url: "";
            array_push($promotionArr, $p_data);
        }

        // dd($promotionArr);


        $adSliders = $this->eproviderRepository->where('e_provider_type_id', 2)->where("available", 1)->where("accepted", 1)->get();

        $adSliderArr = [];
        foreach ($adSliders as $key => $provider) {
            $sliderdata['id'] = $provider->id;
            $sliderdata['name'] = $provider->name;
            $sliderdata['imgPath'] = ($provider->has_media) ? $provider->media[0]->url : "";
            array_push($adSliderArr, $sliderdata);
        }


        $providers = $this->eproviderRepository->where("featured", 1)->where("available", 1)->where("accepted", 1)->where('category_id', '!=', 22)->get();

        $providerArr = [];
        foreach ($providers as $key => $provider) {
            $pdata['id'] = $provider->id;
            $pdata['name'] = $provider->name;
            $pdata['phone_number'] = $provider->phone_number;
            $pdata['featured'] = $provider->featured;
            $pdata['available'] = $provider->available;

            $pdata['address'] = $provider->address;
            $pdata['latitude'] = $provider->latitude;
            $pdata['longitude'] = $provider->longitude;

            $pdata['imgPath'] = ($provider->has_media) ? $provider->media[0]->url : "";
            $pdata['rating_count'] = $this->get_rating($provider->id);
            array_push($providerArr, $pdata);
        }

        // $services = $this->eserviceRepository->where('featured',1)->where('available',1)->get();

        // $serviceArr = [];
        // foreach ($services as $key => $service) {
        //     $sdata['id'] = $service->id;
        //     $sdata['e_provider_id']= $service->e_provider_id;
        //     $sdata['name'] = $service->name;
        //     $sdata['price'] = $service->price;
        //     $sdata['featured'] =$service->featured;
        //     $sdata['available'] =$service->available;
        //     $sdata['imgPath'] =($service->has_media)?$service->media[0]->url:"";
        //     array_push($serviceArr,$sdata);
        // }

        $places = $this->eproviderRepository->where("category_id", 22)->where("available", 1)->where("accepted", 1)->get();
        // dd($places);
        $placesArr = [];
        foreach ($places as $key => $place) {
            $pdata['id'] = $place->id;
            $pdata['name'] = $place->name;
            $pdata['phone_number'] = $place->phone_number;
            $pdata['featured'] = $place->featured;
            $pdata['available'] = $place->available;

            $pdata['address'] = $place->address;
            $pdata['latitude'] = $place->latitude;
            $pdata['longitude'] = $place->longitude;

            $pdata['imgPath'] = ($place->has_media) ? $place->media[0]->url : "";
            array_push($placesArr, $pdata);
        }

        $slider_texts = TextSlide::where('status', 1)->get();

        return response()->json([
            'success' => true,
            'adslider' => $adSliderArr,
            'categories' => $catArr,
            'providers' => $providerArr,
            'promotion_list' => $promotionArr,
            'places' => $placesArr,
            'adproducts' => [],
            'slider_texts' => $slider_texts,
            'message' => 'Main Data retrieved successfully'
        ]);
    }

    public function get_shop_name($shop_id)
    {
        $shop = $this->eproviderRepository->findWithoutFail($shop_id);
        $shop_name = $shop != null ? $shop->name : null;
        return $shop_name;
    }

    public function get_rating($shop_id)
    {
        $count = EProviderRating::where('eprovider_id',$shop_id)->get()->count();

        $ratings = EProviderRating::where('eprovider_id',$shop_id)->get();

        $sum = 0;

        foreach ($ratings as $key => $value) {
            // dd($value);
            $sum = $sum + ($value->rating_grade * $value->rating_grade);
            // dd($sum);
        }

        $avg = $sum > 0 ? $sum / (5 * $count) : 0;

        $rate_count = $avg > 0 ? round($avg,1) : 0;
        return $rate_count;

    }

    public function get_img($shop_id)
    {
        $e_providers = $this->eproviderRepository->find($shop_id);

        // dd($e_providers->has);
        $img_path = ($e_providers->has_media) ? $e_providers->media[0]->url : "";
        return $img_path;
    }

    public function convertB64Password(Request $request)
    {
        $hashstring = base64_encode(md5($request->password, true));

        return response()->json([
            'success' => true,
            'hashstring' => $hashstring,
            'message' => 'Hash String retrieved successfully'
        ]);
    }
}