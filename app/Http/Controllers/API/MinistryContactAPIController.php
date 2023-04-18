<?php
/*
 * File name: TownshipAPIController.php
 */

namespace App\Http\Controllers\API;


use App\Http\Controllers\Controller;
use App\Models\MinistryContact;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Prettus\Repository\Exceptions\RepositoryException;
use App\Repositories\MinistryContactRepository;
/**
 * Class TownshipAPIController
 * @package App\Http\Controllers\API
 */
class MinistryContactAPIController extends Controller
{
    /** @var  MinistryContactRepository */
    private $contactRepository;

    public function __construct(MinistryContactRepository $contactRepo)
    {
        $this->contactRepository = $contactRepo;
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

        $contacts = MinistryContact::all();

        $myarr =[];
        foreach ($contacts as $key => $contact) {
            $data['id'] = $contact->id;
            $data['ministry_name'] = $contact->ministry_name;
            $data['tsh_name'] = $contact->townships->tsh_name;
            $data['phone'] = $contact->phone;
            $data['address'] = $contact->address;
            $data['latitude'] = $contact->lat;
            $data['longitude'] = $contact->long;
            $data['created_at'] = $contact->created_at;
            $data['updated_at'] = $contact->updated_at;

            array_push($myarr,$data);
        }
        return $this->sendResponse($myarr, 'Emergancy Contact retrieved successfully');
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
        if (!empty($this->contactRepository)) {
            $contact = $this->contactRepository->findWithoutFail($id);
        }

        if (empty($contact)) {
            return $this->sendError('contact not found');
        }

        $data['id'] = $contact->id;
        $data['ministry_name'] = $contact->ministry_name;
        $data['tsh_name'] = $contact->townships->tsh_name;
        $data['phone'] = $contact->phone;
        $data['address'] = $contact->address;
        $data['latitude'] = $contact->lat;
        $data['longitude'] = $contact->long;
        $data['created_at'] = $contact->created_at;
        $data['updated_at'] = $contact->updated_at;

        return $this->sendResponse($contact->toArray(), 'township retrieved successfully');
    }
}
