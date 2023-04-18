<?php
/*
 * File name: TownshipAPIController.php
 */

namespace App\Http\Controllers\API;


use App\Http\Controllers\Controller;
use App\Models\Township;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Prettus\Repository\Exceptions\RepositoryException;
use App\Repositories\TownshipRepository;
/**
 * Class TownshipAPIController
 * @package App\Http\Controllers\API
 */
class TownshipAPIController extends Controller
{
    /** @var  TownshipRepository */
    private $townshipRepository;

    public function __construct(TownshipRepository $townshipRepo)
    {
        $this->townshipRepository = $townshipRepo;
    }

    /**
     * Display a listing of the Township.
     * GET|HEAD /categories
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request)
    {

        $tonwships = Township::all();

        $myarr =[];
        foreach ($tonwships as $key => $tonwship) {
            $data['id'] = $tonwship->id;
            $data['tsh_name'] = $tonwship->tsh_name;
            $data['tsh_code'] = $tonwship->tsh_code;
            $data['tsh_color'] = $tonwship->tsh_color;

            array_push($myarr,$data);
        }
        return $this->sendResponse($myarr, 'Township retrieved successfully');
    }

    /**
     * Display the specified Township.
     * GET|HEAD /categories/{id}
     *
     * @param int $id
     *
     * @return JsonResponse
     */
    public function show($id)
    {
        /** @var Township $category */
        if (!empty($this->townshipRepository)) {
            $township = $this->townshipRepository->findWithoutFail($id);
        }

        if (empty($township)) {
            return $this->sendError('township not found');
        }

        $data['id'] = $township->id;
        $data['tsh_name'] = $township->tsh_name;
        $data['tsh_code'] = $township->tsh_code;
        $data['tsh_color'] = $township->tsh_color;

        return $this->sendResponse($township->toArray(), 'township retrieved successfully');
    }
}
