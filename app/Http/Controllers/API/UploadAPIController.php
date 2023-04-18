<?php
/*
 * File name: UploadAPIController.php
 * Last modified: 2021.06.10 at 20:38:02
 * Author: SmarterVision - https://codecanyon.net/user/smartervision
 * Copyright (c) 2021
 */

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\UploadRequest;
use App\Repositories\UploadRepository;
use App\Repositories\EProviderRepository;
use Exception;
use Prettus\Validator\Exceptions\ValidatorException;
use App\Models\Upload;
use Illuminate\Http\Request;

class UploadAPIController extends Controller
{
    private $uploadRepository;
    private $eProviderRepository;
    /**
     * UploadController constructor.
     * @param UploadRepository $uploadRepository
     */
    public function __construct(UploadRepository $uploadRepository,EProviderRepository $eProviderRepository)
    {
        parent::__construct();
        $this->uploadRepository = $uploadRepository;
        $this->eProviderRepository = $eProviderRepository;
    }

    /**
     * @param UploadRequest $request
     */
    public function store(UploadRequest $request)
    {
        $input = $request->all();
        try {
            $upload = $this->uploadRepository->create($input);
            $upload->addMedia($input['file'])
                ->withCustomProperties(['uuid' => $input['uuid'], 'user_id' => auth()->id()])
                ->toMediaCollection($input['field']);
            return $this->sendResponse($input['uuid'], "Uploaded Successfully");
        } catch (ValidatorException $e) {
            return $this->sendResponse(false, $e->getMessage());
        }
    }

    /**
     * clear cache from Upload table
     */
    public function clear(UploadRequest $request)
    {
        $input = $request->all();
        if (!isset($input['uuid'])) {
            return $this->sendResponse(false, 'Media not found');
        }
        try {
            if (is_array($input['uuid'])) {
                $result = $this->uploadRepository->clearWhereIn($input['uuid']);
            } else {
                // dd($input['uuid']);
                $result = $this->uploadRepository->clear($input['uuid']);
                // $result = Upload::find($input['uuid'])->delete();
                // dd($result);
            }
            return $this->sendResponse($result, 'Media deleted successfully');
        } catch (Exception $e) {
            return $this->sendResponse(false, 'Error when delete media');
        }

    }

    public function removeMedia(Request $request)
    {
        // dd("Here");
        $input = $request->all();
        $eProvider = $this->eProviderRepository->findWithoutFail($input['id']);
        // dd($eProvider);
        try {
            if ($eProvider->hasMedia($input['collection'])) {
                $eProvider->getFirstMedia($input['collection'])->delete();
            }
            return $this->sendResponse(true,'Media deleted successfully');
        } catch (Exception $e) {
            return $this->sendResponse(false, 'Error when delete media');
        }
    }
}
