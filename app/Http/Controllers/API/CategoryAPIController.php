<?php
/*
 * File name: CategoryAPIController.php
 * Last modified: 2021.03.24 at 21:33:26
 * Author: SmarterVision - https://codecanyon.net/user/smartervision
 * Copyright (c) 2021
 */

namespace App\Http\Controllers\API;


use App\Criteria\Categories\NearCriteria;
use App\Criteria\Categories\ParentCriteria;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Repositories\CategoryRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use InfyOm\Generator\Criteria\LimitOffsetCriteria;
use Prettus\Repository\Criteria\RequestCriteria;
use Prettus\Repository\Exceptions\RepositoryException;

/**
 * Class CategoryController
 * @package App\Http\Controllers\API
 */
class CategoryAPIController extends Controller
{
    /** @var  CategoryRepository */
    private $categoryRepository;

    public function __construct(CategoryRepository $categoryRepo)
    {
        $this->categoryRepository = $categoryRepo;
    }

    /**
     * Display a listing of the Category.
     * GET|HEAD /categories
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request)
    {
        $categories = Category::where('featured',1)->get();
        // dd($categories);
        $myarr = [];
        foreach ($categories as $key => $category) {
            $data['id'] =$category->id;
            $data['name']= $category->name;
            $data['order']=$category->order;
            $data['feature']=$category->featured;
            $data['has_media'] = $category->has_media;
            $data['imgPath']=  ($category->has_media)? $category->media[0]->url:'';
            array_push($myarr, $data);
        }

        return $this->sendResponse($myarr, 'Categories retrieved successfully');
    }

    /**
     * Display the specified Category.
     * GET|HEAD /categories/{id}
     *
     * @param int $id
     *
     * @return JsonResponse
     */
    public function show($id)
    {
        /** @var Category $category */
        if (!empty($this->categoryRepository)) {
            $category = $this->categoryRepository->findWithoutFail($id);
        }

        if (empty($category)) {
            return $this->sendError('Category not found');
        }

        $data['id'] = $category->id;
        $data['name']= $category->name;
        $data['order']=$category->order;
        $data['feature']=$category->featured;
        $data['has_media'] = $category->has_media;
        $data['imgPath']=  ($category->has_media)? $category->media[0]->url:'';

        return $this->sendResponse($data, 'Category retrieved successfully');
    }
}
