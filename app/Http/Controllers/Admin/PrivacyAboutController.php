<?php
/*
 * File name: TownshipController.php
 * Copyright (c) 2021
 */

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\DataTables\PrivacyAboutDataTable;
use App\Http\Requests\CreatePrivacyAboutRequest;
use App\Http\Requests\UpdatePrivacyAboutRequest;
use App\Repositories\PrivacyAboutRepository;
use Exception;
use Flash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;
use Prettus\Validator\Exceptions\ValidatorException;
use App\Models\PrivacyAbout;

class PrivacyAboutController extends Controller
{
    /** @var  PrivacyAboutRepository */
    private $pAboutRepository;

    public function __construct(PrivacyAboutRepository $pAboutRepo)
    {
        parent::__construct();
        $this->pAboutRepository = $pAboutRepo;
    }

    /**
     * Display a listing of the tonwship.
     *
     * @param PrivacyAboutDataTable $privacyAboutDataTable
     * @return Response
     */
    public function index(privacyAboutDataTable $privacyAboutDataTable)
    {
        $privacyAbouts = PrivacyAbout::all();
        return view('admin.privacy_about.index',compact('privacyAbouts'));
        // return $privacyAboutDataTable->render('admin.privacy_about.index');
    }

    /**
     * Show the form for creating a new Township.
     *
     * @return Response
     */
    public function create()
    {
        $category = [
            1 => "Terms & Conditions",
            2 => "Privacy Policy"
          ];
        $categorySelected = [];
        return view('admin.privacy_about.create')->with("category", $category)->with("categorySelected", $categorySelected);
    }

    /**
     * Store a newly created Township in storage.
     *
     * @param CreatePrivacyAboutRequest $request
     *
     * @return Response
     */
    public function store(CreatePrivacyAboutRequest $request)
    {
        $input = $request->all();
        try {
            $pAbout = $this->pAboutRepository->create($input);
        } catch (ValidatorException $e) {
            Flash::error($e->getMessage());
        }

        Flash::success(__('lang.saved_successfully', ['operator' => __('lang.category')]));

        return redirect(route('privacy_abouts.index'));
    }

    /**
     * Display the specified Category.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $privacyPolicy = $this->pAboutRepository->findWithoutFail($id);

        if (empty($privacyPolicy)) {
            Flash::error('Privacy and About not found');

            return redirect(route('privacy_about.index'));
        }
        
        if ($privacyPolicy->category == 1) {
            $termsAndCondtion = $privacyPolicy;
            return view('frontend.page.term_condition',compact('termsAndCondtion'));
        }else{
            return view('frontend.page.privacy',compact('privacyPolicy'));
        }
        
    }

    /**
     * Show the form for editing the specified Category.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $pAbout = $this->pAboutRepository->findWithoutFail($id);
        // dd($pAbout);
        if ($pAbout->category == 1) {
            $category = "Terms & Conditions";
            $cat_id = 1;
        }else{
            $category = "Privacy Policy";
            $cat_id = 2;
        }

        // $category = [
        //     1 => "Terms & Conditions",
        //     2 => "Privacy Policy"
        //   ];
        // $categorySelected = [
        //       0 => $pAbout->category
        //     ];
        // dd($categorySelected);
        if (empty($pAbout)) {
            Flash::error(__('lang.not_found', ['operator' => __('lang.category')]));

            return redirect(route('privacy_abouts.index'));
        }
        // dd($pAbout);

        return view('admin.privacy_about.edit')->with('pAbout', $pAbout)->with("category", $category)->with("cat_id", $cat_id);

        // return view('admin.privacy_about.edit')->with('pAbout', $pAbout)->with("category", $category)->with("categorySelected", $categorySelected);
    }

    /**
     * Update the specified Township in storage.
     *
     * @param int $id
     * @param UpdatePrivacyAboutRequest $request
     *
     * @return Response
     */
    public function update($id, UpdatePrivacyAboutRequest $request)
    {
        $pAbout = $this->pAboutRepository->findWithoutFail($id);

        if (empty($pAbout)) {
            Flash::error('Privacy and terms & conditions not found');
            return redirect(route('privacy_abouts.index'));
        }
        $input = $request->all();
        try {
            $pAbout = $this->pAboutRepository->update($input, $id);
        } catch (ValidatorException $e) {
            Flash::error($e->getMessage());
        }

        Flash::success(__('lang.updated_successfully', ['operator' => __('lang.category')]));

        return redirect(route('privacy_abouts.index'));
    }

    /**
     * Remove the specified Category from storage.
     *
     * @param int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $pAbout = $this->pAboutRepository->findWithoutFail($id);

        if (empty($pAbout)) {
            Flash::error('Privacy and about not found');

            return redirect(route('privacy_abouts.index'));
        }

        $this->pAboutRepository->delete($id);

        Flash::success(__('lang.deleted_successfully', ['operator' => __('lang.category')]));

        return redirect(route('privacy_abouts.index'));
    }
}
