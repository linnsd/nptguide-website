<?php
/*
 * File name: EmergancyContactController.php
 * Copyright (c) 2021
 */
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\DataTables\EmergancyContactDataTable;
use App\Http\Requests\CreateEmergancyContactRequest;
use App\Http\Requests\UpdateEmergancyContactRequest;
use App\Repositories\EmergancyContactRepository;
use App\Repositories\TownshipRepository;
use Exception;
use Flash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;
use Prettus\Validator\Exceptions\ValidatorException;

class EmergancyContactController extends Controller
{
    /** @var  EmergancyContactRepository */
    private $emergancyRepository;

    private $townshipRepository;

    public function __construct(EmergancyContactRepository $emergancyContactRepo,TownshipRepository $townshipRepo)
    {
        parent::__construct();
        $this->emergancyRepository = $emergancyContactRepo;
        $this->townshipRepository = $townshipRepo;
    }

    /**
     * Display a listing of the tonwship.
     *
     * @param EmergancyContactDataTable $EmergancyContactDataTable
     * @return Response
     */
    public function index(EmergancyContactDataTable $emergancyContactDataTable)
    {
        // dd("Here");
        return $emergancyContactDataTable->render('admin.emergancy_contact.index');
    }

    /**
     * Show the form for creating a new EmergancyContact.
     *
     * @return Response
     */
    public function create()
    {
        $township = $this->townshipRepository->pluck('tsh_name', 'id');
        $townshipSelected = [];
        return view('admin.emergancy_contact.create')->with("township", $township)->with("townshipSelected", $townshipSelected);
    }

    /**
     * Store a newly created EmergancyContact in storage.
     *
     * @param CreateEmergancyContactRequest $request
     *
     * @return Response
     */
    public function store(CreateEmergancyContactRequest $request)
    {
        // dd($request->all());
        $input = $request->all();
        try {
            $emergancyContact = $this->emergancyRepository->create($input);
        } catch (ValidatorException $e) {
            Flash::error($e->getMessage());
        }

        Flash::success(__('lang.saved_successfully', ['operator' => __('lang.category')]));

        return redirect(route('emergancy_contact.index'));
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
        $emergancyContact = $this->emergancyRepository->findWithoutFail($id);

        if (empty($emergancyContact)) {
            Flash::error('EmergancyContact not found');

            return redirect(route('emergancy_contact.index'));
        }

        return view('admin.emergancy_contact.show')->with('EmergancyContact', $emergancyContact);
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
        $emergancyContact = $this->emergancyRepository->findWithoutFail($id);
        $township = $this->townshipRepository->pluck('tsh_name', 'id');
        $townshipSelected = $emergancyContact->townships()->pluck('townships.id')->toArray();
        
        if (empty($emergancyContact)) {
            Flash::error(__('lang.not_found', ['operator' => __('lang.category')]));

            return redirect(route('emergancy_contact.index'));
        }

        return view('admin.emergancy_contact.edit')->with('emergancyContact', $emergancyContact)->with("township", $township)->with("townshipSelected", $townshipSelected);
    }

    /**
     * Update the specified EmergancyContact in storage.
     *
     * @param int $id
     * @param UpdateEmergancyContactRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateEmergancyContactRequest $request)
    {
        $emergancyContact = $this->emergancyRepository->findWithoutFail($id);

        if (empty($emergancyContact)) {
            Flash::error('EmergancyContact not found');
            return redirect(route('emergancy_contact.index'));
        }
        $input = $request->all();
      
        try {
            $emergancyContact = $this->emergancyRepository->update($input, $id);
        } catch (ValidatorException $e) {
            Flash::error($e->getMessage());
        }

        Flash::success(__('lang.updated_successfully', ['operator' => __('lang.category')]));

        return redirect(route('emergancy_contact.index'));
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
        $emergancyContact = $this->emergancyRepository->findWithoutFail($id);

        if (empty($emergancyContact)) {
            Flash::error('EmergancyContact not found');

            return redirect(route('emergancy_contact.index'));
        }

        $this->emergancyRepository->delete($id);

        Flash::success(__('lang.deleted_successfully', ['operator' => __('lang.category')]));

        return redirect(route('emergancy_contact.index'));
    }
}
