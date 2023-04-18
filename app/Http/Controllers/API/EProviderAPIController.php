<?php
/*
 * File name: EProviderAPIController.php
 * Last modified: 2021.02.21 at 19:06:49
 * Author: SmarterVision - https://codecanyon.net/user/smartervision
 * Copyright (c) 2021
 */

namespace App\Http\Controllers\API;


use App\Http\Controllers\Controller;
use App\Repositories\EProviderRepository;
use App\Repositories\EServiceRepository;
use App\Repositories\PromotionRepository;
use App\Repositories\UserRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use InfyOm\Generator\Criteria\LimitOffsetCriteria;
use Prettus\Repository\Criteria\RequestCriteria;
use Prettus\Repository\Exceptions\RepositoryException;
use App\Http\Requests\CreateEProviderRequest;
use App\Repositories\UploadRepository;
use App\Events\EProviderChangedEvent;

use App\Models\EProviderUser;
use App\Models\EProviderRating;
use App\Models\Promotion;
use App\Models\Media;
use App\Models\EService;
use App\Models\EProviderView;
use App\Models\EProvider;
use Str;

/**
 * Class EProviderController
 * @package App\Http\Controllers\API
 */
class EProviderAPIController extends Controller
{
    /** @var  EProviderRepository */
    private $eProviderRepository;

    public function __construct(EProviderRepository $eProviderRepo, UploadRepository $uploadRepository, PromotionRepository $promotionRepository, EServiceRepository $eServiceRepo,UserRepository $userRepository)
    {
        $this->eProviderRepository = $eProviderRepo;
        $this->uploadRepository = $uploadRepository;
        $this->promotionRepository = $promotionRepository;
        $this->eServiceRepository = $eServiceRepo;
        $this->userRepository = $userRepository;
    }

    /**
     * Display a listing of the EProvider.
     * GET|HEAD /eProviders
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        // dd($request->all());
        // try {
        //     $this->eProviderRepository->pushCriteria(new RequestCriteria($request));
        //     $this->eProviderRepository->pushCriteria(new LimitOffsetCriteria($request));
        // } catch (RepositoryException $e) {
        //     return $this->sendError($e->getMessage());
        // }

        // dd($request->all());
        $eProviders = $this->eProviderRepository;
        // $this->filterCollection($request, $eProviders);

        if ($request->keyword != '') {
            $eProviders = $eProviders->where('name', 'LIKE', '%' . $request->keyword . '%');
        }

        $cat_id = $request->cat_id;
        if ($cat_id != '') {
            $eProviders = $eProviders->where(function ($query) use ($cat_id) {
                $query->where('category_id', $cat_id);
            });
        }

        $tsh_id = $request->tsh_id;
        if ($tsh_id != '') {
            $eProviders = $eProviders->where(function ($query) use ($tsh_id) {
                $query->where('tsh_id', $tsh_id);
            });
        }

        $eProviders = $eProviders->where('available', 1)->where('accepted', 1)->orderBy('featured', 'desc')->get()->map(function ($item, $key) {
            return [
                'id' => $item->id,
                'name' => $item->name,
                'featured' => $item->featured,
                'available' => $item->available,
                'available' => $item->available,
                'phone_number' => $item->phone_number,
                'address' => $item->address,
                'latitude' => $item->latitude,
                'longitude' => $item->longitude,
                'has_media' => $item->has_media,
                'imgPath' => ($item->has_media) ? $item->media[0]->url : ""
            ];
        });;


        return $this->sendResponse($eProviders->toArray(), 'Providers retrieved successfully');
    }

    /**
     * Display the specified EProvider.
     * GET|HEAD /eProviders/{id}
     *
     * @param int $id
     *
     * @return JsonResponse
     */
    public function show(int $id, Request $request): JsonResponse
    {
        $user_id = isset($_GET['user_id']) ? $_GET['user_id'] : false;


        try {
            $this->eProviderRepository->pushCriteria(new RequestCriteria($request));
            $this->eProviderRepository->pushCriteria(new LimitOffsetCriteria($request));
        } catch (RepositoryException $e) {
            return $this->sendError($e->getMessage());
        }
        $eProvider = $this->eProviderRepository->with(['eServices', 'townships', 'category', 'promotions'])->findWithoutFail($id);
        if (empty($eProvider)) {
            return $this->sendError('EProvider not found');
        }
        $this->filterModel($request, $eProvider);

        $arr = [];


        $arr['id'] = $eProvider->id;
        if ($user_id) {
            $arr['is_fav'] =  checkFav($id, $user_id) ? 1 : 0;
        } else {
            return response()->json(["message" => 'User id required!', "status" => 0]);
        }

        $arr['cat_id'] = (!empty($eProvider->category) > 0) ? $eProvider->category->id : '';
        $arr['category'] = (!empty($eProvider->category) > 0) ? $eProvider->category->name : '';
        $arr['name'] = $eProvider->name;
        $arr['description'] = $eProvider->description;
        $arr['phone_number'] = $eProvider->phone_number;
        $arr['featured'] = $eProvider->featured;
        $arr['available'] = $eProvider->available;
        $arr['accepted'] = $eProvider->accepted;
        $arr['address'] = $eProvider->address;
        $arr['tsh_id'] = $eProvider->tsh_id;
        $arr['township'] = (!empty($eProvider->townships) > 0) ? $eProvider->townships->tsh_name : '';
        $arr['latitude'] = $eProvider->latitude;
        $arr['longitude'] = $eProvider->longitude;
        $arr['has_media'] = $eProvider->has_media;
        $arr['fb_page_url'] = $eProvider->fburl;
        $arr['status'] = 1;
        $arr['rating_avg'] = $this->get_rating($eProvider->id);
        $arr['progress_rating'] = $this->get_progress_rating($eProvider->id);
        $arr['rating_reviews'] = $this->get_rating_review($eProvider->id);
        $arr['is_already_rating'] = $this->get_already_rating($eProvider->id,$user_id);

        $arr['media'] = [];
        if ($eProvider->has_media) {
            foreach ($eProvider->media as $media) {
                $temp['uuid'] = $media->custom_properties['uuid'];
                $temp['mime_type'] = $media->mime_type;
                $temp['formated_size'] = $media->formated_size;
                $temp['url'] = $media->url;
                $temp['id'] = $media->id;
                array_push($arr['media'], $temp);
            }
        }

        $arr['services'] = [];

        if (!empty($eProvider->eServices)) {
            foreach ($eProvider->eServices as $service) {
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

        $arr['promotions'] = [];
        if (!empty($eProvider->promotions)) {
            foreach ($eProvider->promotions as $promotion) {
                $ptemp['id'] = $promotion->id;
                $ptemp['title'] = $promotion->title;
                $ptemp['description'] = $promotion->description;
                $ptemp['from_date'] = date('d-m-Y', strtotime($promotion->from_date));
                $ptemp['to_date'] = date('d-m-Y', strtotime($promotion->to_date));
                $ptemp['imgurl'] = ($promotion->has_media) ? $promotion->media[0]->url : '';
                array_push($arr['promotions'], $ptemp);
            }
        }


        if ($request->user_id != null) {
            // $e_providers = EProviderView::all();
            $is_already_view = EProviderView::where('eprovider_id', $id)->where('user_id', $user_id)->get()->count();

            // is not view
            if ($is_already_view == 0) {
                // dd("Here");
                $e_provider = new EProviderView();
                $e_provider->user_id = $request->user_id;
                $e_provider->eprovider_id = $id;
                $e_provider->save();

                $view_count = EProviderView::where('eprovider_id', $id)->count();

                $place = EProvider::find($id);
                $place->view = $view_count;
                $place->save();

            }
        }
        
        return $this->sendResponse($arr, 'EProvider Detail retrieved successfully');
    }

    public function get_already_rating($shop_id,$user_id)
    {
        $rating = EProviderRating::where('eprovider_id',$shop_id)->where('user_id',$user_id)->get()->count();

        if ($rating > 0) {
            $already_rating = 1;
        }else{
            $already_rating = 0;
        }
        return $already_rating;
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

    public function get_progress_rating($shop_id)
    {
        $arr = [];

        for ($i=5; $i >=1 ; $i--) {

            $count = EProviderRating::where('eprovider_id',$shop_id)->where('rating_grade',$i)->get()->count();

            $total = EProviderRating::where('eprovider_id',$shop_id)->get()->count();


            $avg = $count > 0 ? ($count /$total) : 0;

            $obj = ['rate_value' => $i, 'avg' => $avg];

            array_push($arr,$obj);
        }

        return $arr;
    }

    public function get_rating_review($shop_id)
    {
        // dd($shop_id);
        $rating_list = new EProviderRating();
        $rating_list = $rating_list->leftjoin('users','users.id','=','e_provider_ratings.user_id')->where('e_provider_ratings.eprovider_id',$shop_id)->select('users.id','users.name','e_provider_ratings.*')->orderBy('e_provider_ratings.id', 'desc')->take(3)->get()
        ->map(function ($item, $key) {
            return [
                'id' => $item->id,
                'name' => $item->name,
                'rating_grade' => $item->rating_grade,
                'created_date' => date('d/m/Y',strtotime($item->created_at)),
                'remark' => $item->remark,
                'imgPath' => $this->get_profile_img($item->id)
            ];
        });

        return $rating_list->toArray();

    }

    public function get_profile_img($user_id)
    {
        $user = $this->userRepository->findWithoutFail($user_id);

        
        $img_path = $user != null ? ($user->has_media) ? $user->media[0]->url : "" : "";
        return $img_path;
    }

    public function store(Request $request)
    {
        $input = $request->all();
        $input['users'] = [$request->user_id];
        $input['e_provider_type_id'] = 3;


        $uploadInput = [];

        try {
            $eProvider = $this->eProviderRepository->create($input);
            if (isset($input['image']) && $input['image'] && is_array($input['image'])) {
                foreach ($input['image'] as $image) {
                    $uploadInput['field'] = "image";
                    $uploadInput['uuid'] = Str::uuid()->toString();
                    $uploadInput['file'] = $image;

                    $upload = $this->uploadRepository->create($uploadInput);
                    $upload->addMedia($uploadInput['file'])
                        ->withCustomProperties(['uuid' => $uploadInput['uuid'], 'user_id' => $request->user_id])
                        ->toMediaCollection($uploadInput['field']);


                    $cacheUpload = $this->uploadRepository->getByUuid($uploadInput['uuid']);
                    $mediaItem = $cacheUpload->getMedia('image')->first();
                    $mediaItem->copy($eProvider, 'image');


                    $uploadInput = [];
                }
            }
            event(new EProviderChangedEvent($eProvider, $eProvider));
        } catch (ValidatorException $e) {
            return $this->sendError('Provider store error!');
        }

        return $this->sendResponse($eProvider, 'EProvider create successfully');
    }

    public function update($id, Request $request)
    {

        $oldEProvider = $this->eProviderRepository->findWithoutFail($id);

        $input = $request->all();
        $input['users'] = [$request->user_id];
        $uploadInput = [];

        try {
            $eProvider = $this->eProviderRepository->update($input, $id);


            if (isset($input['image']) && $input['image'] && is_array($input['image'])) {
                foreach ($input['image'] as $image) {
                    $uploadInput['field'] = "image";
                    $uploadInput['uuid'] = Str::uuid()->toString();
                    $uploadInput['file'] = $image;

                    $upload = $this->uploadRepository->create($uploadInput);
                    $upload->addMedia($uploadInput['file'])
                        ->withCustomProperties(['uuid' => $uploadInput['uuid'], 'user_id' => $request->user_id])
                        ->toMediaCollection($uploadInput['field']);


                    $cacheUpload = $this->uploadRepository->getByUuid($uploadInput['uuid']);
                    $mediaItem = $cacheUpload->getMedia('image')->first();
                    $mediaItem->copy($eProvider, 'image');


                    $uploadInput = [];
                }
            }

            event(new EProviderChangedEvent($eProvider, $oldEProvider));
        } catch (ValidatorException $e) {
            return $this->sendError('Provider store error!');
        }

        return $this->sendResponse($eProvider, 'EProvider update successfully');
    }

    public function requestFeature($id, Request $request)
    {
        $oldEProvider = $this->eProviderRepository->findWithoutFail($id);
        $input['featured_request'] = !$oldEProvider->featured_request;
        if ($oldEProvider->featured_request == 1) {
            $input['featured'] = 0;
        }

        try {
            $eProvider = $this->eProviderRepository->update($input, $id);

            event(new EProviderChangedEvent($eProvider, $oldEProvider));
        } catch (ValidatorException $e) {
            return $this->sendError('Provier Request Feature  error!');
        }

        return $this->sendResponse($eProvider, 'EProvider request feature successfully');
    }

    public function promotion_detail($id)
    {
        $promotions = $this->promotionRepository->findWithoutFail($id);

        if (empty($promotions)) {
            return $this->sendError('Promotion not found');
        }
        $arr = [];

        $arr['id'] = $promotions->id;
        $arr['title'] = $promotions->title;
        $arr['description'] = $promotions->description;
        $arr['from_date'] = date('d-m-Y', strtotime($promotions->from_date));
        $arr['to_date'] = date('d-m-Y', strtotime($promotions->to_date));
        $arr['place'] = $this->eProviderRepository->findWithoutFail($promotions->place_id)->name;
        $arr['place_id'] = $promotions->place_id;
        $arr['media'] = [];
        if ($promotions->has_media) {
            foreach ($promotions->media as $media) {
                $temp['id']=$media->id;
                $temp['uuid'] = $media->custom_properties['uuid'];
                $temp['mime_type'] = $media->mime_type;
                $temp['formated_size'] = $media->formated_size;
                $temp['url'] = $media->url;
                $temp['model_id'] = $media->model_id;
                array_push($arr['media'], $temp);
            }
        }

        return $this->sendResponse($arr, 'Promotion Detail retrieved successfully');
    }

    public function service_detail(Request $request)
    {

        // dd($request->all());
        $e_services = $this->eServiceRepository->findWithoutFail($request->service_id);

        if (empty($e_services)) {
            return $this->sendError('Service not found');
        }
        $arr = [];

        $arr['id'] = $e_services->id;
        $arr['name'] = $e_services->name;
        $arr['price'] = $e_services->price;

        $eProvider = $this->eProviderRepository->with(['eServices'])->findWithoutFail($request->shop_id);

        $arr['services'] = [];

        if (!empty($eProvider->eServices)) {
            foreach ($eProvider->eServices as $service) {
                if ($service->id != $request->service_id) {
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
        }


        $arr['media'] = [];
        if ($e_services->has_media) {
            foreach ($e_services->media as $media) {
                $temp['uuid'] = $media->custom_properties['uuid'];
                $temp['mime_type'] = $media->mime_type;
                $temp['formated_size'] = $media->formated_size;
                $temp['url'] = $media->url;
                array_push($arr['media'], $temp);
            }
        }

        return $this->sendResponse($arr, 'Service Detail retrieved successfully');
    }

    public function create_promotion(Request $request)
    {
        $input = $request->all();

        $uploadInput = [];

        try {
            $promotions = $this->promotionRepository->create(
                [
                    'title' => $request->title,
                    'description' => $request->description,
                    'place_id' => $request->place_id,
                    'from_date' => date('Y-m-d', strtotime($request->from_date)),
                    'to_date' => date('Y-m-d', strtotime($request->to_date)),
                ]
            );

            if (isset($input['image']) && $input['image'] && is_array($input['image'])) {
                foreach ($input['image'] as $image) {
                    $uploadInput['field'] = "image";
                    $uploadInput['uuid'] = Str::uuid()->toString();
                    $uploadInput['file'] = $image;

                    $upload = $this->uploadRepository->create($uploadInput);
                    $upload->addMedia($uploadInput['file'])
                        ->withCustomProperties(['uuid' => $uploadInput['uuid'], 'user_id' => $request->user_id])
                        ->toMediaCollection($uploadInput['field']);


                    $cacheUpload = $this->uploadRepository->getByUuid($uploadInput['uuid']);
                    $mediaItem = $cacheUpload->getMedia('image')->first();
                    $mediaItem->copy($promotions, 'image');


                    $uploadInput = [];
                }
            }
           
        } catch (ValidatorException $e) {
            return $this->sendError('Promotion store error!');
        }

        return $this->sendResponse($promotions, 'Promotion create successfully');
    }

    public function user_shops(Request $request)
    {
        // dd("Here");
        $shops = new EProviderUser();
        $shops = $shops->leftjoin('e_providers','e_providers.id','=','e_provider_users.e_provider_id')->leftjoin('categories','categories.id','=','e_providers.category_id')->select('e_providers.name','e_providers.id')->where('category_id','!=',22);

        if ($request->user_id != 1) {
            $shops = $shops->where('e_provider_users.user_id',$request->user_id)->get();
        }else{
            $shops = $shops->get();
        }
        return $this->sendResponse($shops, 'Shop retrieved successfully');
    }

    public function promotion_list(Request $request)
    {
        if ($request->user_id != null && $request->user_id != 1) {
            $shops = new EProviderUser();
            $shops = $shops->select('e_provider_id')->where('user_id',$request->user_id)->get();
            
            $arr = [];
            foreach ($shops as $key => $value) {
               array_push($arr,$value->e_provider_id);
            }

            $promotions = $this->promotionRepository->whereIn('place_id',$arr)->where('status',1)->get()->map(function ($item, $key) {
                return [
                    'id' => $item->id,
                    'title' => $item->title,
                    'description' => $item->description,
                    'from_date' => date('d-m-Y',strtotime($item->from_date)),
                    'to_date' => date('d-m-Y',strtotime($item->to_date)),
                    'phone_number' => $item->phone_number,
                    'has_media' => $item->has_media,
                    'imgPath' => ($item->has_media) ? $item->media[0]->url : "",
                    'shop_name'=>$this->get_shop_name($item->place_id)
                ];
            });
        }else{
            $promotions = $this->promotionRepository->where('status',1)->get()->map(function ($item, $key) {
                return [
                    'id' => $item->id,
                    'title' => $item->title,
                    'description' => $item->description,
                    'from_date' => date('d-m-Y',strtotime($item->from_date)),
                    'to_date' => date('d-m-Y',strtotime($item->to_date)),
                    'phone_number' => $item->phone_number,
                    'has_media' => $item->has_media,
                    'imgPath' => ($item->has_media) ? $item->media[0]->url : "",
                    'shop_name'=>$this->get_shop_name($item->place_id)
                ];
            });
        }
        
        return $this->sendResponse($promotions->toArray(), 'Providers retrieved successfully');

    }

    public function get_shop_name($shop_id)
    {
        // dd($shop_id);
        $shop = $this->eProviderRepository->findWithoutFail($shop_id);
        $shop_name = $shop != null ? $shop->name : null;
        return $shop_name;
    }

    public function delete_promotion(Request $request)
    {
        $promotion = Promotion::find($request->promotion_id)->delete();
        return response()->json(['message'=>'Success','status'=>1]);
    }

    public function update_promotion(Request $request)
    {
        $eService = $this->promotionRepository->findWithoutFail($request->pro_id);

        if (empty($eService)) {
            Flash::error('Promotion not found');
            return redirect(route('promotions.index'));
        }
        $input = $request->all();
        
        try {

            $promotion = $this->promotionRepository->update(
                [
                    'title' => $request->title,
                    'description' => $request->description,
                    'place_id' => $request->place_id,
                    'from_date' => date('Y-m-d', strtotime($request->from_date)),
                    'to_date' => date('Y-m-d', strtotime($request->to_date))
                ],
                $request->pro_id
            );
            // if (isset($input['image']) && $input['image'] && is_array($input['image'])) {
            //     foreach ($input['image'] as $fileUuid) {
            //         $cacheUpload = $this->uploadRepository->getByUuid($fileUuid);
            //         $mediaItem = $cacheUpload->getMedia('image')->first();
            //         $mediaItem->copy($promotion, 'image');
            //     }
            // }

            if (isset($input['image']) && $input['image'] && is_array($input['image'])) {
                foreach ($input['image'] as $image) {
                    $uploadInput['field'] = "image";
                    $uploadInput['uuid'] = Str::uuid()->toString();
                    $uploadInput['file'] = $image;

                    $upload = $this->uploadRepository->create($uploadInput);
                    $upload->addMedia($uploadInput['file'])
                        ->withCustomProperties(['uuid' => $uploadInput['uuid'], 'user_id' => $request->user_id])
                        ->toMediaCollection($uploadInput['field']);


                    $cacheUpload = $this->uploadRepository->getByUuid($uploadInput['uuid']);
                    $mediaItem = $cacheUpload->getMedia('image')->first();
                    $mediaItem->copy($promotion, 'image');


                    $uploadInput = [];
                }
            }

           
        } catch (ValidatorException $e) {
            Flash::error($e->getMessage());
        }

        return response()->json(['message'=>'Success','status'=>1]);
    }

    public function delete_img(Request $request)
    {
        $media = Media::find($request->id)->delete();
        return response()->json(['message'=>'Success','status'=>1]);

    }

    public function service_create(Request $request)
    {
        // dd($request->all());
        $input = $request->all();

        $uploadInput = [];

        try {
            $promotions = $this->eServiceRepository->create(
                [
                    'name' => $request->name,
                    'description' => $request->description,
                    'e_provider_id' => $request->e_provider_id,
                    'price' => $request->price,
                ]
            );

            if (isset($input['image']) && $input['image'] && is_array($input['image'])) {
                foreach ($input['image'] as $image) {
                    $uploadInput['field'] = "image";
                    $uploadInput['uuid'] = Str::uuid()->toString();
                    $uploadInput['file'] = $image;

                    $upload = $this->uploadRepository->create($uploadInput);
                    $upload->addMedia($uploadInput['file'])
                        ->withCustomProperties(['uuid' => $uploadInput['uuid'], 'user_id' => $request->user_id])
                        ->toMediaCollection($uploadInput['field']);


                    $cacheUpload = $this->uploadRepository->getByUuid($uploadInput['uuid']);
                    $mediaItem = $cacheUpload->getMedia('image')->first();
                    $mediaItem->copy($promotions, 'image');


                    $uploadInput = [];
                }
            }
           
        } catch (ValidatorException $e) {
            return $this->sendError('Service store error!');
        }

        return $this->sendResponse($promotions, 'Service create successfully');

    }

    public function service_delete(Request $request)
    {
        $e_service = EService::find($request->service_id)->delete();
        return response()->json(['message'=>'Success','status'=>1]);
    }
}