<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Promotion;
use Illuminate\Http\Request;
use App\Http\Requests\CreatePromotionRequest;
use App\Repositories\PromotionRepository;
use App\Repositories\UploadRepository;
use App\Repositories\CustomFieldRepository;
use App\Repositories\EProviderRepository;
use App\Criteria\EProviders\EProvidersOfUserCriteria;
use Flash;

class PromotionController extends Controller
{
    private $promotionRepository;

    private $uploadRepository;

    private $customFieldRepository;

    private $eProviderRepository;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function __construct(promotionRepository $promotionRepo, UploadRepository $uploadRepo, CustomFieldRepository $customFieldRepo, EProviderRepository $eProviderRepo)
    {
        parent::__construct();
        $this->promotionRepository = $promotionRepo;
        $this->uploadRepository = $uploadRepo;
        $this->customFieldRepository = $customFieldRepo;
        $this->eProviderRepository = $eProviderRepo;
    }

    public function index(Request $request)
    {
        $promotions = Promotion::list($request);
        $count = $promotions->get()->count();
        $promotions = $promotions->orderBy('created_at', 'desc')->paginate(10);
        return view('admin.promotion.index', compact('promotions'))->with('i', (request()->input('page', 1) - 1) * 12);;
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $promotion = null;
        return view('admin.promotion.create', compact('promotion'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreatePromotionRequest $request)
    {
        $input = $request->all();
        $customFields = $this->customFieldRepository->findByField('custom_field_model', $this->promotionRepository->model());

        try {
            $promotion = $this->promotionRepository->create(
                [
                    'title' => $request->title,
                    'description' => $request->description,
                    'place_id' => $request->owner,
                    'from_date' => date('Y-m-d', strtotime($request->from_date)),
                    'to_date' => date('Y-m-d', strtotime($request->to_date)),
                ]
            );
            $promotion->customFieldsValues()->createMany(getCustomFieldsValues($customFields, $request));

            if (isset($input['image']) && $input['image'] && is_array($input['image'])) {
                foreach ($input['image'] as $fileUuid) {
                    $cacheUpload = $this->uploadRepository->getByUuid($fileUuid);
                    $mediaItem = $cacheUpload->getMedia('image')->first();
                    $mediaItem->copy($promotion, 'image');
                }
            }
        } catch (ValidatorException $e) {
            Flash::error($e->getMessage());
        }

        Flash::success(__('lang.saved_successfully', ['operator' => __('lang.promotion')]));

        return redirect(route('promotions.index'));
    }

    public function change_status(Request $request)
    {
        $promotion = Promotion::find($request->id);
        $promotion->status = $request->status;
        $promotion->save();
        return response()->json(['success' => 'Status change successfully.']);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Promotion  $promotion
     * @return \Illuminate\Http\Response
     */
    public function show(Promotion $promotion)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Promotion  $promotion
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $promotion = $this->promotionRepository->findWithoutFail($id);
        if (empty($promotion)) {
            Flash::error(__('lang.not_found', ['operator' => __('lang.e_service')]));

            return redirect(route('promotions.index'));
        }

        $eProvider = $this->eProviderRepository->getByCriteria(new EProvidersOfUserCriteria(auth()->id()))->pluck('name', 'id');

        $customFieldsValues = $promotion->customFieldsValues()->with('customField')->get();
        $customFields = $this->customFieldRepository->findByField('custom_field_model', $this->promotionRepository->model());
        $hasCustomField = in_array($this->promotionRepository->model(), setting('custom_field_models', []));
        if ($hasCustomField) {
            $html = generateCustomField($customFields, $customFieldsValues);
        }
        return view('admin.promotion.edit')->with('promotion', $promotion)->with("customFields", isset($html) ? $html : false)->with("eProvider", $eProvider);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Promotion  $promotion
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // $promotion = Promotion::update_data($request,$id);
        // return redirect()->route('promotions.index')->with('success','Success');

        $eService = $this->promotionRepository->findWithoutFail($id);

        if (empty($eService)) {
            Flash::error('Promotion not found');
            return redirect(route('promotions.index'));
        }
        $input = $request->all();
        $customFields = $this->customFieldRepository->findByField('custom_field_model', $this->promotionRepository->model());
        try {

            $promotion = $this->promotionRepository->update(
                [
                    'title' => $request->title,
                    'description' => $request->description,
                    'place_id' => $request->owner,
                    'from_date' => date('Y-m-d', strtotime($request->from_date)),
                    'to_date' => date('Y-m-d', strtotime($request->to_date))
                ],
                $id
            );
            if (isset($input['image']) && $input['image'] && is_array($input['image'])) {
                foreach ($input['image'] as $fileUuid) {
                    $cacheUpload = $this->uploadRepository->getByUuid($fileUuid);
                    $mediaItem = $cacheUpload->getMedia('image')->first();
                    $mediaItem->copy($promotion, 'image');
                }
            }
            foreach (getCustomFieldsValues($customFields, $request) as $value) {
                $promotion->customFieldsValues()
                    ->updateOrCreate(['custom_field_id' => $value['custom_field_id']], $value);
            }
        } catch (ValidatorException $e) {
            Flash::error($e->getMessage());
        }

        Flash::success(__('lang.updated_successfully', ['operator' => __('lang.e_service')]));

        return redirect(route('promotions.index'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Promotion  $promotion
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $promotion = Promotion::find($id)->delete();
        return redirect()->route('promotions.index')->with('success', 'Success');
    }
}