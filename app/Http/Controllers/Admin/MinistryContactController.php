<?php
/*
 * File name: MinistryContactController.php
 * Copyright (c) 2021
 */

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\DataTables\MinistryContactDataTable;
use App\Http\Requests\CreateMinistryContactRequest;
use App\Http\Requests\UpdateMinistryContactRequest;
use App\Repositories\MinistryContactRepository;
use App\Repositories\TownshipRepository;
use Exception;
use Flash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;
use Prettus\Validator\Exceptions\ValidatorException;

class MinistryContactController extends Controller
{
    /** @var  MinistryContactRepository */
    private $ministryRepository;

    private $townshipRepository;

    public function __construct(MinistryContactRepository $ministryContactRepo,TownshipRepository $townshipRepo)
    {
        parent::__construct();
        $this->ministryRepository = $ministryContactRepo;
        $this->townshipRepository = $townshipRepo;
    }

    /**
     * Display a listing of the tonwship.
     *
     * @param MinistryContactDataTable $MinistryContactDataTable
     * @return Response
     */
    public function index(MinistryContactDataTable $ministryContactDataTable)
    {
        // dd("Here");
        return $ministryContactDataTable->render('admin.ministry_contact.index');
    }

    /**
     * Show the form for creating a new MinistryContact.
     *
     * @return Response
     */
    public function create()
    {
        $township = $this->townshipRepository->pluck('tsh_name', 'id');
        $townshipSelected = [];
        return view('admin.ministry_contact.create')->with("township", $township)->with("townshipSelected", $townshipSelected);
    }

    /**
     * Store a newly created MinistryContact in storage.
     *
     * @param CreateMinistryContactRequest $request
     *
     * @return Response
     */
    public function store(CreateMinistryContactRequest $request)
    {
        // dd($request->all());
        $input = $request->all();
        try {
            $ministryContact = $this->ministryRepository->create($input);
        } catch (ValidatorException $e) {
            Flash::error($e->getMessage());
        }

        Flash::success(__('lang.saved_successfully', ['operator' => __('lang.category')]));

        return redirect(route('ministry_contact.index'));
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
        $ministryContact = $this->ministryRepository->findWithoutFail($id);

        if (empty($ministryContact)) {
            Flash::error('MinistryContact not found');

            return redirect(route('ministry_contact.index'));
        }

        return view('admin.ministry_contact.show')->with('MinistryContact', $ministryContact);
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
        $ministryContact = $this->ministryRepository->findWithoutFail($id);
        $township = $this->townshipRepository->pluck('tsh_name', 'id');
        $townshipSelected = $ministryContact->townships()->pluck('townships.id')->toArray();
        
        if (empty($ministryContact)) {
            Flash::error(__('lang.not_found', ['operator' => __('lang.category')]));

            return redirect(route('ministry_contact.index'));
        }

        return view('admin.ministry_contact.edit')->with('ministryContact', $ministryContact)->with("township", $township)->with("townshipSelected", $townshipSelected);
    }

    /**
     * Update the specified MinistryContact in storage.
     *
     * @param int $id
     * @param UpdateMinistryContactRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateMinistryContactRequest $request)
    {
        $ministryContact = $this->ministryRepository->findWithoutFail($id);

        if (empty($ministryContact)) {
            Flash::error('MinistryContact not found');
            return redirect(route('ministry_contact.index'));
        }
        $input = $request->all();
      
        try {
            $ministryContact = $this->ministryRepository->update($input, $id);
        } catch (ValidatorException $e) {
            Flash::error($e->getMessage());
        }

        Flash::success(__('lang.updated_successfully', ['operator' => __('lang.category')]));

        return redirect(route('ministry_contact.index'));
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
        $ministryContact = $this->ministryRepository->findWithoutFail($id);

        if (empty($ministryContact)) {
            Flash::error('MinistryContact not found');

            return redirect(route('ministry_contact.index'));
        }

        $this->ministryRepository->delete($id);

        Flash::success(__('lang.deleted_successfully', ['operator' => __('lang.category')]));

        return redirect(route('ministry_contact.index'));
    }
}
