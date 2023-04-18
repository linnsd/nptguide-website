<?php
/*
 * File name: EServiceAPIController.php
 * Last modified: 2021.06.10 at 20:38:00
 * Author: SmarterVision - https://codecanyon.net/user/smartervision
 * Copyright (c) 2021
 */

namespace App\Http\Controllers\API;


use App\Criteria\EServices\EServicesOfUserCriteria;
use App\Criteria\EServices\NearCriteria;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreateEServiceRequest;
use App\Http\Requests\UpdateEServiceRequest;
use App\Repositories\EServiceRepository;
use App\Repositories\UploadRepository;
use App\Repositories\UserRepository;
use App\Repositories\EProviderRepository;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use InfyOm\Generator\Criteria\LimitOffsetCriteria;
use Prettus\Repository\Criteria\RequestCriteria;
use Prettus\Repository\Exceptions\RepositoryException;
use Str;
/**
 * Class EServiceController
 * @package App\Http\Controllers\API
 */
class EServiceAPIController extends Controller
{
    /** @var  eServiceRepository */
    private $eServiceRepository;
    /** @var UserRepository */
    private $userRepository;
    /**
     * @var UploadRepository
     */
    private $uploadRepository;

    public function __construct(EProviderRepository $eProviderRepo,EServiceRepository $eServiceRepo, UserRepository $userRepository, UploadRepository $uploadRepository)
    {
        parent::__construct();
        $this->eProviderRepository = $eProviderRepo;
        $this->eServiceRepository = $eServiceRepo;
        $this->userRepository = $userRepository;
        $this->uploadRepository = $uploadRepository;
    }

    /**
     * Display a listing of the EService.
     * GET|HEAD /eServices
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request)
    {

        try {
            $this->eServiceRepository->pushCriteria(new RequestCriteria($request));
            $this->eServiceRepository->pushCriteria(new EServicesOfUserCriteria(auth()->id()));
            $this->eServiceRepository->pushCriteria(new NearCriteria($request));
        } catch (RepositoryException $e) {
            return $this->sendError($e->getMessage());
        }
        $eServices = $this->eServiceRepository->all();

        $this->availableEServices($eServices);
        $this->availableEProvider($request, $eServices);
        $this->orderByRating($request, $eServices);
        $this->limitOffset($request, $eServices);
        $this->filterCollection($request, $eServices);
        $eServices = array_values($eServices->toArray());

        return $this->sendResponse($eServices, 'E Services retrieved successfully');
    }

    /**
     * @param Collection $eServices
     */
    private function availableEServices(Collection &$eServices)
    {
        $eServices = $eServices->where('available', true);
    }

    /**
     * @param Request $request
     * @param Collection $eServices
     */
    private function availableEProvider(Request $request, Collection &$eServices)
    {
        if ($request->has('available_e_provider')) {
            $eServices = $eServices->filter(function ($element) {
                return $element->eProvider->available;
            });
        }
    }

    /**
     * @param Request $request
     * @param Collection $eServices
     */
    private function orderByRating(Request $request, Collection &$eServices)
    {
        if ($request->has('rating')) {
            $eServices = $eServices->sortBy('rate', SORT_REGULAR, true);
        }
    }

    /**
     * Display the specified EService.
     * GET|HEAD /eServices/{id}
     *
     * @param Request $request
     * @param int $id
     *
     * @return JsonResponse
     */
    public function show(Request $request,$id): JsonResponse
    {
        try {
            $this->eServiceRepository->pushCriteria(new RequestCriteria($request));
            $this->eServiceRepository->pushCriteria(new LimitOffsetCriteria($request));
        } catch (RepositoryException $e) {
            return $this->sendError($e->getMessage());
        }
        $eService = $this->eServiceRepository->findWithoutFail($id);
        if (empty($eService)) {
            return $this->sendError('EService not found');
        }
        if ($request->has('api_token')) {
            $user = $this->userRepository->findByField('api_token', $request->input('api_token'))->first();
            if (!empty($user)) {
                auth()->login($user, true);
            }
        }
        $this->filterModel($request, $eService);

        return $this->sendResponse($eService->toArray(), 'EService retrieved successfully');
    }

    /**
     * Store a newly created EService in storage.
     *
     * @param CreateEServiceRequest $request
     *
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $input = $request->all();
            $input['users'] = [$request->e_provider_id];
            $eService = $this->eServiceRepository->create($input);
            if (isset($input['image']) && $input['image'] && is_array($input['image'])) {
                // foreach ($input['image'] as $fileUuid) {
                //     $cacheUpload = $this->uploadRepository->getByUuid($fileUuid);
                //     $mediaItem = $cacheUpload->getMedia('image')->first();
                //     $mediaItem->copy($eService, 'image');
                // }
                 foreach($input['image'] as $image){
                        $uploadInput['field'] = "image";
                        $uploadInput['uuid'] = Str::uuid()->toString();
                        $uploadInput['file'] = $image;

                        $upload = $this->uploadRepository->create($uploadInput);
                        $upload->addMedia($uploadInput['file'])
                                ->withCustomProperties(['uuid' => $uploadInput['uuid'], 'user_id' => $request->e_provider_id])
                                ->toMediaCollection($uploadInput['field']);


                        $cacheUpload = $this->uploadRepository->getByUuid($uploadInput['uuid']);
                        $mediaItem = $cacheUpload->getMedia('image')->first();
                        $mediaItem->copy($eService, 'image');


                        $uploadInput =[];
                   }

            }
        } catch (Exception $e) {
            return $this->sendError($e->getMessage());
        }
        return $this->sendResponse($eService->toArray(), __('lang.saved_successfully', ['operator' => __('lang.e_service')]));
    }

    /**
     * Update the specified EService in storage.
     *
     * @param int $id
     * @param UpdateEServiceRequest $request
     *
     * @return JsonResponse
     * @throws RepositoryException
     */
    public function update(int $id, Request $request): JsonResponse
    {
        // $this->eServiceRepository->pushCriteria(new EServicesOfUserCriteria(auth()->id()));
        $eService = $this->eServiceRepository->findWithoutFail($id);

        if (empty($eService)) {
            return $this->sendError('E Service not found');
        }

        try {
            $input = $request->all();
            $input['users'] = [$request->e_provider_id];
            // $input['categories'] = isset($input['categories']) ? $input['categories'] : [];
            $eService = $this->eServiceRepository->update($input, $id);
            if (isset($input['image']) && $input['image'] && is_array($input['image'])) {
                if ($eService->hasMedia('image')) {
                    $eService->getMedia('image')->each->delete();
                }
                foreach($input['image'] as $image){
                        $uploadInput['field'] = "image";
                        $uploadInput['uuid'] = Str::uuid()->toString();
                        $uploadInput['file'] = $image;

                        $upload = $this->uploadRepository->create($uploadInput);
                        $upload->addMedia($uploadInput['file'])
                                ->withCustomProperties(['uuid' => $uploadInput['uuid'], 'user_id' => $request->e_provider_id])
                                ->toMediaCollection($uploadInput['field']);


                        $cacheUpload = $this->uploadRepository->getByUuid($uploadInput['uuid']);
                        $mediaItem = $cacheUpload->getMedia('image')->first();
                        $mediaItem->copy($eService, 'image');


                        $uploadInput =[];
                }
            }
        } catch (Exception $e) {
            return $this->sendError($e->getMessage());
        }

        return $this->sendResponse($eService->toArray(), __('lang.updated_successfully', ['operator' => __('lang.e_service')]));
    }

    public function requestFeature($id, Request $request)
    {
        $eService = $this->eServiceRepository->findWithoutFail($id);

        if (empty($eService)) {
            return $this->sendError('E Service not found');
        }

        $input['featured_request'] = !$eService->featured_request;
        if($eService->featured_request == 1){
            $input['featured'] = 0;
        }

        try {
            $eService = $this->eServiceRepository->update($input, $id);
        } catch (ValidatorException $e) {
            return $this->sendError('Request Feature  error!');
        }

        return $this->sendResponse($eService, 'Featured request successfully');
    }

    /**
     * Remove the specified EService from storage.
     *
     * @param int $id
     *
     * @return JsonResponse
     * @throws RepositoryException
     */
    public function destroy(int $id): JsonResponse
    {
        $this->eServiceRepository->pushCriteria(new EServicesOfUserCriteria(auth()->id()));
        $eService = $this->eServiceRepository->findWithoutFail($id);

        if (empty($eService)) {
            return $this->sendError('EService not found');
        }

        $eService = $this->eServiceRepository->delete($id);

        return $this->sendResponse($eService, __('lang.deleted_successfully', ['operator' => __('lang.e_service')]));

    }

    /**
     * Remove Media of EService
     * @param Request $request
     * @throws RepositoryException
     */
    public function removeMedia(Request $request)
    {
        $input = $request->all();
        try {
            $this->eServiceRepository->pushCriteria(new EServicesOfUserCriteria(auth()->id()));
            $eService = $this->eServiceRepository->findWithoutFail($input['id']);
            if ($eService->hasMedia($input['collection'])) {
                $eService->getFirstMedia($input['collection'])->delete();
            }
        } catch (Exception $e) {
            Log::error($e->getMessage());
        }
    }

    public function getMyProvider(int $id): JsonResponse
    {
        $user = $this->userRepository->findWithoutFail($id);

        if (!$user) {
            return $this->sendError('User not found', 200);
        }

        $rolesSelected = $user->getRoleNames()->toArray();
        $providerArr =[];
        if(count($user->eProviders)>0){
            foreach($user->eProviders as $provider){
                $temp['id']= $provider->id;
                $temp['name']= $provider->name;
                $temp['e_provider_type_id']= $provider->e_provider_type_id;
                array_push($providerArr, $temp);

            }   
        }
        $data['providers'] = $providerArr;
        return $this->sendResponse($providerArr, 'My Providers retrieved successfully');
    }
}
