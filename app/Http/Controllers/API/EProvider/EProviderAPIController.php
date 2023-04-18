<?php
/*
 * File name: EProviderAPIController.php
 * Last modified: 2021.05.23 at 16:24:25
 * Author: SmarterVision - https://codecanyon.net/user/smartervision
 * Copyright (c) 2021
 */

namespace App\Http\Controllers\API\EProvider;


use App\Criteria\EProviders\EProvidersOfUserCriteria;
use App\Http\Controllers\Controller;
use App\Repositories\EProviderRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use InfyOm\Generator\Criteria\LimitOffsetCriteria;
use Prettus\Repository\Criteria\RequestCriteria;
use Prettus\Repository\Exceptions\RepositoryException;
use App\Models\EProviderView;
use App\Models\EProvider;
use App\Models\EProviderRating;
use App\Models\UserFavourite;
use PHPUnit\Util\Json;

/**
 * Class EProviderController
 * @package App\Http\Controllers\API
 */
class EProviderAPIController extends Controller
{
    /** @var  EProviderRepository */
    private $eProviderRepository;

    public function __construct(EProviderRepository $eProviderRepo)
    {
        $this->eProviderRepository = $eProviderRepo;
        parent::__construct();
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
        try {
            $this->eProviderRepository->pushCriteria(new RequestCriteria($request));
            $this->eProviderRepository->pushCriteria(new LimitOffsetCriteria($request));
            $this->eProviderRepository->pushCriteria(new EProvidersOfUserCriteria(auth()->id()));
        } catch (RepositoryException $e) {
            return $this->sendError($e->getMessage());
        }
        $eProviders = $this->eProviderRepository->all();
        $this->filterCollection($request, $eProviders);

        return $this->sendResponse($eProviders->toArray(), 'E Providers retrieved successfully');
    }

    public function favourite(Request $request): JsonResponse
    {
        try {
            $this->eProviderRepository->pushCriteria(new RequestCriteria($request));
            $this->eProviderRepository->pushCriteria(new LimitOffsetCriteria($request));
        } catch (RepositoryException $e) {
            return $this->sendError($e->getMessage());
        }


        $id = $request->e_provider_id;
        $user_id = $request->user_id;

        if (empty($id) or empty($user_id)) {
            return response()->json(["message" => 'Please login!', "status" => 0]);
        }

        $rating = new UserFavourite();
        $rating->user_id = $user_id;
        $rating->eprovider_id = $id;
        $rating->save();

        return response()->json(["message" => 'success', "status" => 1]);
    }

    public function remove_favourite(Request $request): JsonResponse
    {
        try {
            $this->eProviderRepository->pushCriteria(new RequestCriteria($request));
            $this->eProviderRepository->pushCriteria(new LimitOffsetCriteria($request));
        } catch (RepositoryException $e) {
            return $this->sendError($e->getMessage());
        }

        $id = $request->e_provider_id;
        $user_id = $request->user_id;

        if (empty($id) or empty($user_id)) {
            return response()->json(["message" => 'Please login!', "status" => 0]);
        }

        $rating =  UserFavourite::where('user_id', $user_id)->where('eprovider_id', $id);
        if (count($rating->get()) != 0) {
            $rating->delete();
        } else {
            return response()->json(["message" => 'Not Found!', "status" => 0]);
        }


        return response()->json(["message" => 'success', "status" => 1]);
    }

    public function get_fav_places(Request $request): JsonResponse
    {
        try {
            $this->eProviderRepository->pushCriteria(new RequestCriteria($request));
            $this->eProviderRepository->pushCriteria(new LimitOffsetCriteria($request));
        } catch (RepositoryException $e) {
            return $this->sendError($e->getMessage());
        }

        $id = $request->user_id;

        if (empty($id)) {
            return $this->sendError('User ID is required');
        }

        $data = new UserFavourite();
        $data = $data->leftjoin('e_providers', 'user_favourites.eprovider_id', '=', 'e_providers.id')
            ->select('e_providers.*')
            ->where('user_favourites.user_id', $id)
            ->get();

        // $arr = []''
        // foreach ($data as $key => $value) {
        //     $fav['id'] = $value->id;
        //     $fav['name']= $value->name;
        //     $fav['address'] = $value->address;
        //     $fav['phone_number'] = $value->phone_number;
        //     $fav['latitude'] = $value->latitude;
        //     $fav['longitude'] = $value->longitude;
        //     $fav['imgPath'] = ($this->eProviderRepository->findWithoutFail($value->id)->has_media) ? $this->eProviderRepository->findWithoutFail($value->id)->media[0]->url : "";

        //     array_push($arr,$fav);
        // }

        $favorites = $data->map(function ($item, $key) {
            return [
                'id' => $item->id,
                'name' => $item->name,
                'address' => $item->address,
                'address' => $item->address,
                'phone_number' => $item->phone_number,
                'latitude' => $item->latitude,
                'has_media' => $item->has_media,
                'latitude'=>$item->latitude,
                'longitude'=>$item->longitude,
                'imgPath' => ($this->eProviderRepository->findWithoutFail($item->id)->has_media) ? $this->eProviderRepository->findWithoutFail($item->id)->media[0]->url : "",
                'is_fav'=>1
            ];
        });


        return response()->json(["status" => 200, "message" => 'success', "data" => $favorites->toArray()]);
    }

    public function get_ratings(Request $request): JsonResponse
    {
        try {
            $this->eProviderRepository->pushCriteria(new RequestCriteria($request));
            $this->eProviderRepository->pushCriteria(new LimitOffsetCriteria($request));
        } catch (RepositoryException $e) {
            return $this->sendError($e->getMessage());
        }

        $id = $request->e_provider_id;

        if (empty($id)) {
            return $this->sendError('EProvider ID is required');
        }

        $data = new EProviderRating();
        $data = $data->where('eprovider_id', $id)->get();

        return response()->json(["status" => 200, "message" => 'success', "data" => $data]);
    }

    public function rate(Request $request): JsonResponse
    {
        try {
            $this->eProviderRepository->pushCriteria(new RequestCriteria($request));
            $this->eProviderRepository->pushCriteria(new LimitOffsetCriteria($request));
        } catch (RepositoryException $e) {
            return $this->sendError($e->getMessage());
        }

        $id = $request->e_provider_id;
        $user_id = $request->user_id;
        $rating_grade = $request->rating_grade;

        if (empty($id) or empty($user_id) or empty($rating_grade)) {
            return $this->sendError('EProvider ID or User ID or Rating Grade not found');
        }

        $rating = new EProviderRating();
        $rating->user_id = $user_id;
        $rating->eprovider_id = $id;
        $rating->rating_grade = $rating_grade;
        $rating->remark = $request->remark;
        $rating->save();

        return response()->json(["message" => 'success'], 200);
    }

    public function add_view(Request $request): JsonResponse
    {
        try {
            $this->eProviderRepository->pushCriteria(new RequestCriteria($request));
            $this->eProviderRepository->pushCriteria(new LimitOffsetCriteria($request));
        } catch (RepositoryException $e) {
            return $this->sendError($e->getMessage());
        }

        $id = $request->e_provider_id;
        $user_id = $request->user_id;

        if (empty($id) or empty($user_id)) {
            return $this->sendError('EProvider ID Or User ID not found');
        }
        $e_providers = EProviderView::all();
        $is_already_view = $e_providers->where('eprovider_id', $id)->where('user_id', $user_id,);

        // is not view
        if (count($is_already_view) == 0) {
            $e_provider = new EProviderView();
            $e_provider->user_id = $user_id;
            $e_provider->eprovider_id = $id;
            $e_provider->save();

            $view_count = EProviderView::where('eprovider_id', $id)->count();

            $place = EProvider::find($id);
            $place->view = $view_count;
            $place->save();

            return response()->json(["message" => 'success'], 200);
        }

        return response()->json(["message" => 'already viewed'], 200);
    }

    public function get_view_count(Request $request): JsonResponse
    {
        try {
            $this->eProviderRepository->pushCriteria(new RequestCriteria($request));
            $this->eProviderRepository->pushCriteria(new LimitOffsetCriteria($request));
        } catch (RepositoryException $e) {
            return $this->sendError($e->getMessage());
        }

        $id = $request->e_provider_id;

        if (empty($id)) {
            return $this->sendError('EProvider ID not found');
        }

        $view_count = EProviderView::where('eprovider_id', $id)->count();

        return response()->json(["status" => 200, "count" => $view_count], 200);
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
        try {
            $this->eProviderRepository->pushCriteria(new RequestCriteria($request));
            $this->eProviderRepository->pushCriteria(new LimitOffsetCriteria($request));
        } catch (RepositoryException $e) {
            return $this->sendError($e->getMessage());
        }
        $eProvider = $this->eProviderRepository->with(['eServices', 'townships', 'category'])->findWithoutFail($id);
        if (empty($eProvider)) {
            return $this->sendError('EProvider not found');
        }
        $this->filterModel($request, $eProvider);



        $arr = [];

        $arr['id'] = $eProvider->id;
        $arr['category'] = (!empty($eProvider->category) > 0) ? $eProvider->category->name : '';
        $arr['name'] = $eProvider->name;
        $arr['description'] = $eProvider->description;
        $arr['phone_number'] = $eProvider->phone_number;
        $arr['featured'] = $eProvider->featured;
        $arr['available'] = $eProvider->available;
        $arr['accepted'] = $eProvider->accepted;
        $arr['address'] = $eProvider->address;
        $arr['township'] = (!empty($eProvider->townships) > 0) ? $eProvider->townships->tsh_name : '';
        $arr['latitude'] = $eProvider->latitude;
        $arr['longitude'] = $eProvider->longitude;
        $arr['has_media'] = $eProvider->has_media;
        $arr['fb_page_url'] = $eProvider->fburl;

        $arr['media'] = [];

        if ($eProvider->has_media) {
            foreach ($eProvider->media as $media) {
                $temp['id'] = $media->id;
                $temp['mime_type'] = $media->mime_type;
                $temp['formated_size'] = $media->formated_size;
                $temp['url'] = $media->url;


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


        return $this->sendResponse($arr, 'EProvider Detail retrieved successfully');
    }
}
