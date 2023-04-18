<?php
/*
 * File name: TownshipAPIController.php
 */

namespace App\Http\Controllers\API;


use App\Http\Controllers\Controller;
use App\Models\AboutUs;
use App\Models\Contact;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Prettus\Repository\Exceptions\RepositoryException;
use App\Repositories\AboutUsRepository;
/**
 * Class TownshipAPIController
 * @package App\Http\Controllers\API
 */
class AboutUsAPIController extends Controller
{
    /** @var  AboutUsRepository */
    private $aboutUsRepository;

    public function __construct(AboutUsRepository $aboutUsRepo)
    {
        $this->aboutUsRepository = $aboutUsRepo;
    }

    /**
     * Display a listing of the Township.
     * GET|HEAD /categories
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function get_about_us(Request $request)
    {

        $about_us = AboutUs::all();

        $myarr =[];
        foreach ($about_us as $key => $contact) {
            $data['id'] = $contact->id;
            $data['title'] = $contact->title;
            $data['description'] = $contact->description;
            $data['created_at'] = $contact->created_at;
            $data['updated_at'] = $contact->updated_at;

            array_push($myarr,$data);
        }
        return $this->sendResponse($myarr, 'About Us retrieved successfully');
    }

    public function contact_us(Request $request)
    {
        $contact = Contact::create([
            'name' => $request->name,
            'phone_number' => $request->phone_number,
            'message' => $request->note
        ]);
        return $this->sendResponse($contact, 'Success');
    }

    /**
     * Display the specified Township.
     * GET|HEAD /categories/{id}
     *
     * @param int $id
     *
     * @return JsonResponse
     */
    
}
