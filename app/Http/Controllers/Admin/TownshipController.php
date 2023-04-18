<?php
/*
 * File name: TownshipController.php
 * Copyright (c) 2021
 */

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\DataTables\TownshipDataTable;
use App\Http\Requests\CreateTownshipRequest;
use App\Http\Requests\UpdateTownshipRequest;
use App\Repositories\TownshipRepository;
use Exception;
use Flash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;
use Prettus\Validator\Exceptions\ValidatorException;

class TownshipController extends Controller
{
    /** @var  TownshipRepository */
    private $townshpRepository;

    public function __construct(TownshipRepository $townshipRepo)
    {
        parent::__construct();
        $this->townshpRepository = $townshipRepo;
    }

    /**
     * Display a listing of the tonwship.
     *
     * @param TownshipDataTable $townshipDataTable
     * @return Response
     */
    public function index(TownshipDataTable $townshipDataTable)
    {
        return $townshipDataTable->render('admin.townships.index');
    }

    /**
     * Show the form for creating a new Township.
     *
     * @return Response
     */
    public function create()
    {
        return view('admin.townships.create');
    }

    /**
     * Store a newly created Township in storage.
     *
     * @param CreateTownshipRequest $request
     *
     * @return Response
     */
    public function store(CreateTownshipRequest $request)
    {
        $input = $request->all();
        try {
            $township = $this->townshpRepository->create($input);
        } catch (ValidatorException $e) {
            Flash::error($e->getMessage());
        }

        Flash::success(__('lang.saved_successfully', ['operator' => __('lang.category')]));

        return redirect(route('townships.index'));
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
        $township = $this->townshpRepository->findWithoutFail($id);

        if (empty($township)) {
            Flash::error('Township not found');

            return redirect(route('townships.index'));
        }

        return view('admin.townships.show')->with('township', $township);
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
        $township = $this->townshpRepository->findWithoutFail($id);

        if (empty($township)) {
            Flash::error(__('lang.not_found', ['operator' => __('lang.category')]));

            return redirect(route('townshps.index'));
        }

        return view('admin.townships.edit')->with('township', $township);
    }

    /**
     * Update the specified Township in storage.
     *
     * @param int $id
     * @param UpdateTownshipRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateTownshipRequest $request)
    {
        $township = $this->townshpRepository->findWithoutFail($id);

        if (empty($township)) {
            Flash::error('Township not found');
            return redirect(route('townshps.index'));
        }
        $input = $request->all();
      
        try {
            $township = $this->townshpRepository->update($input, $id);
        } catch (ValidatorException $e) {
            Flash::error($e->getMessage());
        }

        Flash::success(__('lang.updated_successfully', ['operator' => __('lang.category')]));

        return redirect(route('townships.index'));
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
        $township = $this->townshpRepository->findWithoutFail($id);

        if (empty($township)) {
            Flash::error('township not found');

            return redirect(route('townships.index'));
        }

        $this->townshpRepository->delete($id);

        Flash::success(__('lang.deleted_successfully', ['operator' => __('lang.category')]));

        return redirect(route('townships.index'));
    }
}
