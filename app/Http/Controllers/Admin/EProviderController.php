<?php
/*
 * File name: EProviderController.php
 */

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Criteria\Addresses\AddressesOfUserCriteria;
use App\Criteria\EProviders\EProvidersOfUserCriteria;
use App\Criteria\Users\EProvidersCustomersCriteria;
use App\DataTables\EProviderDataTable;
use App\DataTables\RequestedEProviderDataTable;
use App\Events\EProviderChangedEvent;
use App\Http\Requests\CreateEProviderRequest;
use App\Http\Requests\UpdateEProviderRequest;
use App\Models\EProvider;
use App\Models\EProviderRating;
use App\Repositories\AddressRepository;
use App\Repositories\CustomFieldRepository;
use App\Repositories\EProviderRepository;
use App\Repositories\EProviderTypeRepository;
use App\Repositories\TaxRepository;
use App\Repositories\UploadRepository;
use App\Repositories\UserRepository;
use Exception;
use Flash;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;
use Illuminate\View\View;
use Prettus\Repository\Exceptions\RepositoryException;
use Prettus\Validator\Exceptions\ValidatorException;

use App\Repositories\TownshipRepository;
use App\Repositories\CategoryRepository;
use App\Repositories\TokenRepository;
use App\Models\UserFavourite;

use Session;
use GuzzleHttp\Client;
use Guzzle\Http\Exception\ClientErrorResponseException;
use Str;

class EProviderController extends Controller
{
    /** @var  EProviderRepository */
    private $eProviderRepository;

    /**
     * @var CustomFieldRepository
     */
    private $customFieldRepository;

    /**
     * @var UploadRepository
     */
    private $uploadRepository;
    /**
     * @var EProviderTypeRepository
     */
    private $eProviderTypeRepository;
    /**
     * @var UserRepository
     */
    private $userRepository;
    /**
     * @var AddressRepository
     */
    private $addressRepository;
    /**
     * @var TaxRepository
     */
    private $taxRepository;

    private $townshipRepository;

    private $categoryRepository;

    private $tokenRepository;

    public function __construct(
        EProviderRepository $eProviderRepo,
        CustomFieldRepository $customFieldRepo,
        UploadRepository $uploadRepo,
        EProviderTypeRepository $eProviderTypeRepo,
        UserRepository $userRepo,
        AddressRepository $addressRepo,
        TaxRepository $taxRepo,
        TownshipRepository $townshipRepo,
        CategoryRepository $categoryRepo,
        TokenRepository $tokenRepo
    ) {
        parent::__construct();
        $this->eProviderRepository = $eProviderRepo;
        $this->customFieldRepository = $customFieldRepo;
        $this->uploadRepository = $uploadRepo;
        $this->eProviderTypeRepository = $eProviderTypeRepo;
        $this->userRepository = $userRepo;
        $this->addressRepository = $addressRepo;
        $this->taxRepository = $taxRepo;
        $this->townshipRepository = $townshipRepo;
        $this->categoryRepository = $categoryRepo;
        $this->tokenRepository = $tokenRepo;
    }

    /**
     * Display a listing of the EProvider.
     *
     * @param EProviderDataTable $eProviderDataTable
     * @return mixed
     */
    public function index(EProviderDataTable $eProviderDataTable)
    {
        return $eProviderDataTable->render('admin.e_providers.index');
    }

    /**
     * Display a listing of the EProvider.
     *
     * @param EProviderDataTable $eproviderDataTable
     * @return mixed
     */

    public function rating(int $id)
    {
        $this->eProviderRepository->pushCriteria(new EProvidersOfUserCriteria(auth()->id()));
        $eProvider = $this->eProviderRepository->with(['media'])->findWithoutFail($id);

        if (empty($eProvider)) {
            Flash::error(__('lang.not_found', ['operator' => __('lang.e_provider')]));

            return redirect(route('eProviders.index'));
        }
        $ratings = new EProviderRating();
        $ratings = $ratings->leftjoin('users', 'e_provider_ratings.user_id', '=', 'users.id')
            ->select('e_provider_ratings.*', 'users.name AS username')
            ->where('eprovider_id', $id)->get();

        return view('admin.e_providers.rating', compact('ratings'))->with('eProvider', $eProvider);
    }

    public function requestedEProviders(RequestedEProviderDataTable $requestedEProviderDataTable)
    {
        return $requestedEProviderDataTable->render('admin.e_providers.requested');
    }

    /**
     * Show the form for creating a new EProvider.
     *
     * @return Application|Factory|Response|View
     */
    public function create()
    {
        $category = $this->categoryRepository->pluck('name', 'id');
        $eProviderType = $this->eProviderTypeRepository->pluck('name', 'id');
        $user = $this->userRepository->getByCriteria(new EProvidersCustomersCriteria())->pluck('name', 'id');
        $township = $this->townshipRepository->pluck('tsh_name', 'id');
        $tax = $this->taxRepository->pluck('name', 'id');
        $usersSelected = [];
        $townshipSelected = [];
        $categoriesSelected = [];
        $taxesSelected = [];
        $hasCustomField = in_array($this->eProviderRepository->model(), setting('custom_field_models', []));
        if ($hasCustomField) {
            $customFields = $this->customFieldRepository->findByField('custom_field_model', $this->eProviderRepository->model());
            $html = generateCustomField($customFields);
        }
        return view('admin.e_providers.create')->with("customFields", isset($html) ? $html : false)->with("eProviderType", $eProviderType)->with("user", $user)->with("usersSelected", $usersSelected)->with("township", $township)->with("townshipSelected", $townshipSelected)->with("tax", $tax)->with("taxesSelected", $taxesSelected)->with("category", $category)->with("categoriesSelected", $categoriesSelected);
    }

    /**
     * Store a newly created EProvider in storage.
     *
     * @param CreateEProviderRequest $request
     *
     * @return Application|RedirectResponse|Redirector|Response
     */
    public function store(CreateEProviderRequest $request)
    {
        $input = $request->all();

        // dd($input);
        if (auth()->user()->hasRole(['provider', 'customer'])) {
            $input['users'] = [auth()->id()];
        }
        // $customFields = $this->customFieldRepository->findByField('custom_field_model', $this->eProviderRepository->model());
        try {
            $eProvider = $this->eProviderRepository->create($input);
            // $eProvider->customFieldsValues()->createMany(getCustomFieldsValues($customFields, $request));
            if (isset($input['image']) && $input['image'] && is_array($input['image'])) {
                foreach ($input['image'] as $fileUuid) {
                    $cacheUpload = $this->uploadRepository->getByUuid($fileUuid);
                    $mediaItem = $cacheUpload->getMedia('image')->first();
                    $mediaItem->copy($eProvider, 'image');
                }
            }
            event(new EProviderChangedEvent($eProvider, $eProvider));
        } catch (ValidatorException $e) {
            Flash::error($e->getMessage());
        }

        Flash::success(__('lang.saved_successfully', ['operator' => __('lang.e_provider')]));

        return redirect(route('eProviders.index'));
    }

    /**
     * Display the specified EProvider.
     *
     * @param int $id
     *
     * @return Application|RedirectResponse|Redirector|Response
     * @throws RepositoryException
     */
    public function show(int $id)
    {
        $this->eProviderRepository->pushCriteria(new EProvidersOfUserCriteria(auth()->id()));
        $eProvider = $this->eProviderRepository->with(['media'])->findWithoutFail($id);

        if (empty($eProvider)) {
            Flash::error(__('lang.not_found', ['operator' => __('lang.e_provider')]));

            return redirect(route('eProviders.index'));
        }

        // dd($eProvider);

        return view('admin.e_providers.show')->with('eProvider', $eProvider);
    }


    /**
     * Show the form for editing the specified EProvider.
     *
     * @param int $id
     *
     * @return Application|RedirectResponse|Redirector|Response
     * @throws RepositoryException
     */
    public function edit(int $id)
    {
        $category = $this->categoryRepository->pluck('name', 'id');
        $this->eProviderRepository->pushCriteria(new EProvidersOfUserCriteria(auth()->id()));
        $eProvider = $this->eProviderRepository->findWithoutFail($id);
        if (empty($eProvider)) {
            Flash::error(__('lang.not_found', ['operator' => __('lang.e_provider')]));
            return redirect(route('eProviders.index'));
        }
        $eProviderType = $this->eProviderTypeRepository->pluck('name', 'id');
        $user = $this->userRepository->getByCriteria(new EProvidersCustomersCriteria())->pluck('name', 'id');
        $township = $this->townshipRepository->pluck('tsh_name', 'id');
        $tax = $this->taxRepository->pluck('name', 'id');
        $usersSelected = $eProvider->users()->pluck('users.id')->toArray();
        $townshipSelected = $eProvider->townships()->pluck('townships.id')->toArray();
        $taxesSelected = $eProvider->taxes()->pluck('taxes.id')->toArray();
        $categoriesSelected = $eProvider->category()->pluck('categories.id')->toArray();

        $customFieldsValues = $eProvider->customFieldsValues()->with('customField')->get();
        $customFields = $this->customFieldRepository->findByField('custom_field_model', $this->eProviderRepository->model());
        $hasCustomField = in_array($this->eProviderRepository->model(), setting('custom_field_models', []));
        if ($hasCustomField) {
            $html = generateCustomField($customFields, $customFieldsValues);
        }

        return view('admin.e_providers.edit')->with('eProvider', $eProvider)->with("customFields", isset($html) ? $html : false)->with("eProviderType", $eProviderType)->with("user", $user)->with("usersSelected", $usersSelected)->with("township", $township)->with("townshipSelected", $townshipSelected)->with("tax", $tax)->with("taxesSelected", $taxesSelected)->with("category", $category)->with("categoriesSelected", $categoriesSelected);
    }

    /**
     * Update the specified EProvider in storage.
     *
     * @param int $id
     * @param UpdateEProviderRequest $request
     *
     * @return Application|RedirectResponse|Redirector|Response
     * @throws RepositoryException
     */
    public function update(int $id, UpdateEProviderRequest $request)
    {

        $this->eProviderRepository->pushCriteria(new EProvidersOfUserCriteria(auth()->id()));
        $oldEProvider = $this->eProviderRepository->findWithoutFail($id);
        // dd($oldEProvider)
        if (empty($oldEProvider)) {
            Flash::error(__('lang.not_found', ['operator' => __('lang.e_provider')]));
            return redirect(route('eProviders.index'));
        }
        $input = $request->all();
        $customFields = $this->customFieldRepository->findByField('custom_field_model', $this->eProviderRepository->model());
        try {
            $input['users'] = isset($input['users']) ? $input['users'] : [];
            $input['addresses'] = isset($input['addresses']) ? $input['addresses'] : [];
            $input['taxes'] = isset($input['taxes']) ? $input['taxes'] : [];
            $eProvider = $this->eProviderRepository->update($input, $id);

            // $noti_tokens = $this->tokenRepository->where('user_id',$id)->get();
            // // dd($noti_token[]);
            // if (count($noti_tokens)>0) {
            //     $token = $noti_tokens[0]->token;
            //     $this->notification($token,"Approved","Test");
            //     $this->IOSNotification($token,"Approved","Test");
            // }

            // $all_tokens = $this->tokenRepository->where('user_id','!=',$id)->get();
            // // dd($all_tokens);
            // $tokens = [];
            // foreach ($all_tokens as $key => $noti_token) {
            //     array_push($tokens, $noti_token->token);
            // }
            // $this->IOSNotification($tokens,"New Shop","New Shop");

            // if (count($all_tokens)>0) {
            //     foreach ($all_tokens as $key => $all_token) {
            //         if ($all_token->token != null) {
            //             $this->notification($all_token->token,"New Shop","New Shop");
            //         }
            //     }
            // }


            if (isset($input['image']) && $input['image'] && is_array($input['image'])) {
                foreach ($input['image'] as $fileUuid) {
                    $cacheUpload = $this->uploadRepository->getByUuid($fileUuid);
                    $mediaItem = $cacheUpload->getMedia('image')->first();
                    $mediaItem->copy($eProvider, 'image');
                }
            }
            foreach (getCustomFieldsValues($customFields, $request) as $value) {
                $eProvider->customFieldsValues()
                    ->updateOrCreate(['custom_field_id' => $value['custom_field_id']], $value);
            }
            event(new EProviderChangedEvent($eProvider, $oldEProvider));
        } catch (ValidatorException $e) {
            Flash::error($e->getMessage());
        }

        Flash::success(__('lang.updated_successfully', ['operator' => __('lang.e_provider')]));

        return redirect(route('eProviders.index'));
    }


    // public function IOSNotification($token,$title,$body)
    // {
    //     $client = new Client();
    //     $apiUrl = "https://exp.host/--/api/v2/push/send";

    //         $headers =  [
    //             'headers' => [
    //                 'Accept' => 'application/json',
    //                 'Accept-encoding'=> 'gzip, deflate',
    //                 'Content-Type' => 'application/json',
    //             ],
    //             'body' => json_encode(
    //                     [
    //                     "to"=>$token,
    //                     "sound"=>"default",
    //                     "title"=> $title,
    //                     // "subtitle"=>$title,
    //                     "badge" => 1,
    //                     "body"=>$body,
    //                 ]
    //                 )
    //           ];
    //         // dd($headers);
    //         $res = $client->request('POST',$apiUrl , $headers);
    //         // dd($res);
    //         return true;
    // }


    // public function notification($token,$title,$body) 
    // {
    //     $fcmUrl = 'https://fcm.googleapis.com/fcm/send';
    //     $token=$token;

    //     $notification = [
    //         'title' => $title,
    //         'sound' => true,
    //         'body' => $body,
    //     ];

    //     // dd($notification);

    //     $extraNotificationData = ["message" => $notification,"moredata" =>$body];

    //     $fcmNotification = [
    //         //'registration_ids' => $tokenList, //multple token array
    //         'to'        => 'cp4jr-S-T6K4xbiXBfGHxZ:APA91bEJwKfZL67G3Bc43oNpdDmQNdfAKxpqLLqQitrjpN54WROZdyIBI_KLKGKZF0GIrLea2kF2_gAnYxZSBuTwxe6K5-wWgYmT5BQ0pDVt1Vziy4-Rd992V1Sg5YUokKb1eZyDy93a', //single token
    //         'notification' => $notification,
    //         'body' => $body,
    //         'click_action'=>'com.linn.solution.linn_hr.FCM_NOTIFICATION_DETAIL'
    //     ];
    //     // dd($fcmNotification);
    //     $headers = [
    //         'Authorization: key=AAAAqlAnG8s:APA91bEFggVhCp8zUGfLCLXItwqvtP2hWCY5j77W-5J_raX-nCEEWGVh6_JyXyUMSIAeyoTFrnQ8yJzgBqMgrvBVf2GoE8viCADWVCsESwVntbXNW4xZeX0A0Lt_pRLGq9loS0iY6EfM',
    //         'Content-Type: application/json'
    //     ];


    //     $ch = curl_init();
    //     curl_setopt($ch, CURLOPT_URL,$fcmUrl);
    //     curl_setopt($ch, CURLOPT_POST, true);
    //     curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    //     curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    //     curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    //     curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fcmNotification));
    //     // dd($ch);
    //     $result = curl_exec($ch);
    //     dd($result);
    //     curl_close($ch);

    //     return true;
    // }

    /**
     * Remove the specified EProvider from storage.
     *
     * @param int $id
     *
     * @return Application|RedirectResponse|Redirector|Response
     * @throws RepositoryException
     */
    public function destroy(int $id)
    {
        if (config('installer.demo_app')) {
            Flash::warning('This is only demo app you can\'t change this section ');
            return redirect(route('eProviders.index'));
        }
        $this->eProviderRepository->pushCriteria(new EProvidersOfUserCriteria(auth()->id()));
        $eProvider = $this->eProviderRepository->findWithoutFail($id);

        if (empty($eProvider)) {
            Flash::error(__('lang.not_found', ['operator' => __('lang.e_provider')]));

            return redirect(route('eProviders.index'));
        }

        $this->eProviderRepository->delete($id);

        Flash::success(__('lang.deleted_successfully', ['operator' => __('lang.e_provider')]));

        return redirect(route('eProviders.index'));
    }

    /**
     * Remove Media of EProvider
     * @param Request $request
     */
    public function removeMedia(Request $request)
    {
        $input = $request->all();
        // dd($input);
        $eProvider = $this->eProviderRepository->findWithoutFail($input['id']);
        // dd($eProvider);
        try {
            if ($eProvider->hasMedia($input['collection'])) {
                $eProvider->getFirstMedia($input['collection'])->delete();
            }
        } catch (Exception $e) {
            Log::error($e->getMessage());
        }
    }

    public function deleteRequestPlace(int $id)
    {
        if (config('installer.demo_app')) {
            Flash::warning('This is only demo app you can\'t change this section ');
            return redirect(route('eProviders.index'));
        }
        $this->eProviderRepository->pushCriteria(new EProvidersOfUserCriteria(auth()->id()));
        $eProvider = $this->eProviderRepository->findWithoutFail($id);

        if (empty($eProvider)) {
            Flash::error(__('lang.not_found', ['operator' => __('lang.e_provider')]));

            return redirect(route('eProviders.index'));
        }

        $this->eProviderRepository->delete($id);

        Flash::success(__('lang.deleted_successfully', ['operator' => __('lang.e_provider')]));

        return redirect(route('requestedEProviders.index'));
    }
}