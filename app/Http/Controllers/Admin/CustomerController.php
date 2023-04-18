<?php
/*
 * File name: UserController.php
 * Last modified: 2021.07.12 at 00:20:51
 * Author: SmarterVision - https://codecanyon.net/user/smartervision
 * Copyright (c) 2021
 */

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\DataTables\CustomerDataTable;
use App\Events\UserRoleChangedEvent;
use App\Http\Requests\CreateUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Repositories\CustomFieldRepository;
use App\Repositories\RoleRepository;
use App\Repositories\UploadRepository;
use App\Repositories\UserRepository;
use Exception;
use Flash;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Str;
use Prettus\Validator\Exceptions\ValidatorException;

use App\Models\Category;
use App\Models\Township;

class CustomerController extends Controller
{
    /** @var  UserRepository */
    private $userRepository;
    /**
     * @var RoleRepository
     */
    private $roleRepository;

    private $uploadRepository;

    /**
     * @var CustomFieldRepository
     */
    private $customFieldRepository;

    public function __construct(UserRepository $userRepo, RoleRepository $roleRepo, UploadRepository $uploadRepo,
                                CustomFieldRepository $customFieldRepo)
    {
        parent::__construct();
        $this->userRepository = $userRepo;
        $this->roleRepository = $roleRepo;
        $this->uploadRepository = $uploadRepo;
        $this->customFieldRepository = $customFieldRepo;
    }

    /**
     * Display a listing of the User.
     *
     * @param UserDataTable $userDataTable
     * @return Response
     */
    public function index(CustomerDataTable $customerDataTable)
    {
        // dd($customerDataTable);
        return $customerDataTable->render('admin.customer.index');
    }

    /**
     * Display a user profile.
     *
     * @param
     * @return Response
     */
    public function profile()
    {
        $user = $this->userRepository->findWithoutFail(auth()->id());
        unset($user->password);
        $customFields = false;
        $role = $this->roleRepository->pluck('name', 'name');
        $rolesSelected = $user->getRoleNames()->toArray();
        $customFieldsValues = $user->customFieldsValues()->with('customField')->get();
        //dd($customFieldsValues);
        $hasCustomField = in_array($this->userRepository->model(), setting('custom_field_models', []));
        if ($hasCustomField) {
            $customFields = $this->customFieldRepository->findByField('custom_field_model', $this->userRepository->model());
            $customFields = generateCustomField($customFields, $customFieldsValues);
        }
        return view('admin.customer.profile', compact(['user', 'role', 'rolesSelected', 'customFields', 'customFieldsValues']));
    }

    /**
     * Show the form for creating a new User.
     *
     * @return Response
     */
    public function create()
    {
        $role = $this->roleRepository->pluck('name', 'name');

        $rolesSelected = [];
        $hasCustomField = in_array($this->userRepository->model(), setting('custom_field_models', []));
        if ($hasCustomField) {
            $customFields = $this->customFieldRepository->findByField('custom_field_model', $this->userRepository->model());
            $html = generateCustomField($customFields);
        }

        return view('admin.customer.create')
            ->with("role", $role)
            ->with("customFields", isset($html) ? $html : false)
            ->with("rolesSelected", $rolesSelected);
    }

    /**
     * Store a newly created User in storage.
     *
     * @param CreateUserRequest $request
     *
     * @return Response
     */
    public function store(CreateUserRequest $request)
    {
        if (config('installer.demo_app')) {
            Flash::warning('This is only demo app you can\'t change this section ');
            return redirect(route('customer.index'));
        }

        $input = $request->all();
        $customFields = $this->customFieldRepository->findByField('custom_field_model', $this->userRepository->model());

        $input['roles'] = isset($input['roles']) ? $input['roles'] : [];
        $input['password'] = Hash::make($input['password']);
        $input['api_token'] = Str::random(60);

        try {
            $user = $this->userRepository->create($input);
            $user->syncRoles($input['roles']);
            $user->customFieldsValues()->createMany(getCustomFieldsValues($customFields, $request));

            if (isset($input['avatar']) && $input['avatar']) {
                $cacheUpload = $this->uploadRepository->getByUuid($input['avatar']);
                $mediaItem = $cacheUpload->getMedia('avatar')->first();
                $mediaItem->copy($user, 'avatar');
            }
            event(new UserRoleChangedEvent($user));
        } catch (ValidatorException $e) {
            Flash::error($e->getMessage());
        }

        Flash::success('saved successfully.');

        return redirect(route('customer.index'));
    }

    /**
     * Display the specified User.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        // dd("Here");
        // $user = $this->userRepository->findWithoutFail($id);

        // if (empty($user)) {
        //     Flash::error('User not found');

        //     return redirect(route('customer.index'));
        // }

        // return view('admin.customer.profile')->with('user', $user);
        // dd("Here");
        $categories = Category::all();
        $townships = Township::all();

        // $filter['keyword'] = $request->keyword;
        // $filter['township'] = $request->township_id;
        // $filter['category'] = $request->category_id;

        $user = $this->userRepository->findByField('id',$id)->first();
        // dd($user);
        $places =[];
        $menus =[]; 
        if(count($user->eProviders)>0){
            foreach($user->eProviders as $key=>$provider){
                $temp['id']= $provider->id;
                $temp['name']= $provider->name;
                $temp['e_provider_type_id']= $provider->e_provider_type_id;
                $temp['description']= $provider->description;
                $temp['phone_number']= $provider->phone_number;
                $temp['available']= $provider->available;
                $temp['featured']= $provider->featured;
                $temp['accepted']= $provider->accepted;
                $temp['tsh_id']= $provider->tsh_id;
                $temp['tsh_name']= (!empty($provider->townships))?$provider->townships->tsh_name:'';
                $temp['address']= $provider->address;
                $temp['latitude']= $provider->latitude;
                $temp['longitude']= $provider->longitude;
                $temp['category_id']= $provider->category_id;
                $temp['category_name']= (!empty($provider->category))?$provider->category->name:'';;
                $temp['fburl']= $provider->fburl;
                $temp['created_at']= $provider->created_at;
                $temp['updated_at']= $provider->updated_at;
                $temp['has_media']=$provider->has_media;
                $temp['imgPath']=(!empty($provider->has_media)>0)?last($provider->media):'';
                // var_dump($temp['imgPath']);
                $temp['services']=[];

               foreach( $provider->eServices as $service){
                    $stemp['id']=$service->id;
                    $stemp['e_provider_id']=$service->e_provider_id;
                    $stemp['name']=$service->name;
                    $stemp['price']=$service->price;
                    $stemp['featured']=$service->featured;
                    $stemp['available']=$service->available;
                    $stemp['has_media']=$service->has_media;
                    $stemp['imgurl']=(!empty($service->has_media)>0)?$service->media[0]->url:'';

                    
                    array_push($menus,$stemp);
                    array_push($temp['services'],$stemp);

               }

                array_push($places, $temp);

            }   
        }

        // dd(end($places[0]['imgPath'])->url);

        return view('admin.customer.show',compact('categories','townships','places','menus','user'));
    }

    public function loginAsUser(Request $request, $id)
    {
        $user = $this->userRepository->findWithoutFail($id);
        if (empty($user)) {
            Flash::error('User not found');
            return redirect(route('users.index'));
        }
        auth()->login($user, true);
        if (auth()->id() !== $user->id) {
            Flash::error('User not found');
        }
        return redirect(route('customer.profile'));
    }

    /**
     * Show the form for editing the specified User.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        if (!auth()->user()->hasRole('admin') && $id != auth()->id()) {
            Flash::error('Permission denied');
            return redirect(route('users.index'));
        }
        $user = $this->userRepository->findWithoutFail($id);
        unset($user->password);
        $html = false;
        $role = $this->roleRepository->pluck('name', 'name');
        $rolesSelected = $user->getRoleNames()->toArray();
        $customFieldsValues = $user->customFieldsValues()->with('customField')->get();
        $customFields = $this->customFieldRepository->findByField('custom_field_model', $this->userRepository->model());
        $hasCustomField = in_array($this->userRepository->model(), setting('custom_field_models', []));
        if ($hasCustomField) {
            $html = generateCustomField($customFields, $customFieldsValues);
        }

        if (empty($user)) {
            Flash::error('User not found');

            return redirect(route('customer.index'));
        }
        return view('admin.customer.edit')
            ->with('user', $user)->with("role", $role)
            ->with("rolesSelected", $rolesSelected)
            ->with("customFields", $html);
    }

    /**
     * Update the specified User in storage.
     *
     * @param int $id
     * @param UpdateUserRequest $request
     *
     * @return Application|RedirectResponse|Redirector|Response
     */
    public function update(int $id, UpdateUserRequest $request)
    {
        if (config('installer.demo_app')) {
            Flash::warning('This is only demo app you can\'t change this section ');
            return redirect(route('users.profile'));
        }
        if (!auth()->user()->hasRole('admin') && $id != auth()->id()) {
            Flash::error('Permission denied');
            return redirect(route('customer.profile'));
        }

        $user = $this->userRepository->findWithoutFail($id);


        if (empty($user)) {
            Flash::error(__('lang.not_found', ['operator' => __('lang.user')]));
            return redirect(route('users.profile'));
        }
        $customFields = $this->customFieldRepository->findByField('custom_field_model', $this->userRepository->model());

        $input = $request->all();
        if (!auth()->user()->can('permissions.index')) {
            unset($input['roles']);
        } else {
            $input['roles'] = isset($input['roles']) ? $input['roles'] : [];
        }
        if (empty($input['password'])) {
            unset($input['password']);
        } else {
            $input['password'] = Hash::make($input['password']);
        }
        if ($user['phone_number'] != $input['phone_number']) {
            $input['phone_verified_at'] = null;
        }
        try {
            $user = $this->userRepository->update($input, $id);
            if (empty($user)) {
                Flash::error('User not found');
                return redirect(route('users.profile'));
            }
            if (isset($input['avatar']) && $input['avatar']) {
                $cacheUpload = $this->uploadRepository->getByUuid($input['avatar']);
                $mediaItem = $cacheUpload->getMedia('avatar')->first();
                $mediaItem->copy($user, 'avatar');
            }
            if (auth()->user()->can('permissions.index')) {
                $user->syncRoles($input['roles']);
            }
            foreach (getCustomFieldsValues($customFields, $request) as $value) {
                $user->customFieldsValues()
                    ->updateOrCreate(['custom_field_id' => $value['custom_field_id']], $value);
            }
        } catch (ValidatorException $e) {
            Flash::error($e->getMessage());
        }


        Flash::success('User updated successfully.');

        return redirect()->back();

    }

    /**
     * Remove the specified User from storage.
     *
     * @param int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        if (config('installer.demo_app')) {
            Flash::warning('This is only demo app you can\'t change this section ');
            return redirect(route('customer.index'));
        }
        $user = $this->userRepository->findWithoutFail($id);

        if (empty($user)) {
            Flash::error('User not found');

            return redirect(route('customer.index'));
        }

        $this->userRepository->delete($id);

        Flash::success('User deleted successfully.');

        return redirect(route('customer.index'));
    }

    /**
     * Remove Media of User
     * @param Request $request
     */
    public function removeMedia(Request $request)
    {
        if (config('installer.demo_app')) {
            Flash::warning('This is only demo app you can\'t change this section ');
        } else {
            if (auth()->user()->can('medias.delete')) {
                $input = $request->all();
                $user = $this->userRepository->findWithoutFail($input['id']);
                try {
                    if ($user->hasMedia($input['collection'])) {
                        $user->getFirstMedia($input['collection'])->delete();
                    }
                } catch (Exception $e) {
                    Log::error($e->getMessage());
                }
            }
        }
    }
}
