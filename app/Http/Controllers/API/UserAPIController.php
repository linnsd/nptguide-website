<?php
/*
 * File name: UserAPIController.php
 * Last modified: 2021.07.12 at 00:23:32
 * Author: SmarterVision - https://codecanyon.net/user/smartervision
 * Copyright (c) 2021
 */

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Repositories\CustomFieldRepository;
use App\Repositories\RoleRepository;
use App\Repositories\UploadRepository;
use App\Repositories\UserRepository;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Prettus\Validator\Exceptions\ValidatorException;
use Auth;

class UserAPIController extends Controller
{
    private $userRepository;
    private $uploadRepository;
    private $roleRepository;
    private $customFieldRepository;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(UserRepository $userRepository, UploadRepository $uploadRepository, RoleRepository $roleRepository, CustomFieldRepository $customFieldRepo)
    {
        $this->userRepository = $userRepository;
        $this->uploadRepository = $uploadRepository;
        $this->roleRepository = $roleRepository;
        $this->customFieldRepository = $customFieldRepo;
    }

    function login(Request $request)
    {
        try {
            $this->validate($request, [
                'phone_number' => 'required',
                'password' => 'required',
            ]);

            $credentials = request(['phone_number', 'password']);

            if (Auth::attempt($credentials)) {
                // Authentication passed...
                $user = auth()->user();
                $user->device_token = $request->input('device_token', '');
                $user->save();

               $data['id']=$user->id;
               $data['name']=$user->name;
               $data['phone_number']=$user->phone_number;
               $data['phone_verified_at']=$user->phone_verified_at;
               $data['api_token']=$user->api_token;
               $data['device_token']=$user->device_token;
               $data['created_at']=$user->created_at;
               $data['updated_at']=$user->updated_at;
               $data['has_media']=$user->has_media;

                $profileImg='';
                if($user->has_media && !empty($user->media)){
                    $mediaArr = $user->media->toArray();
                    foreach($mediaArr as $photo){
                        $profileImg = $photo['url'];
                       
                    }
                }
                $data['image']=$profileImg;
            
                return $this->sendResponse($data, 'User retrieved successfully');
            } else {
                return $this->sendError(__('auth.failed'), 200);
            }
        } catch (ValidationException $e) {
            return $this->sendError(array_values($e->errors()));
        } catch (Exception $e) {
            return $this->sendError($e->getMessage(), 200);
        }

    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param array $data
     * @return
     */
    function register(Request $request)
    {
        try {
            $this->validate($request, User::$rules);
            $user = new User;
            $user->name = $request->input('name');
            // $user->email = ($request->input('email')!='')?$request->input('email'):'';
            $user->phone_number = $request->input('phone_number');
            $user->phone_verified_at = $request->input('phone_verified_at');
            $user->device_token = $request->input('device_token', '');
            $user->password = Hash::make($request->input('password'));
            $user->api_token = Str::random(60);
            $user->save();

            $defaultRoles = $this->roleRepository->findByField('default', '1');
            $defaultRoles = $defaultRoles->pluck('name')->toArray();
            $user->assignRole($defaultRoles);

            // $customFields = $this->customFieldRepository->findByField('custom_field_model', $this->userRepository->model());

            // foreach (getCustomFieldsValues($customFields, $request) as $value) {
            //     $user->customFieldsValues()
            //         ->updateOrCreate(['custom_field_id' => $value['custom_field_id']], $value);
            // }
        } catch (ValidationException $e) {
            $errors = $e->errors();
            return $this->sendError($errors['phone_number'][0]);
        } catch (Exception $e) {
            return $this->sendError($e->getMessage(), 200);
        }


        return $this->sendResponse($user, 'User created successfully');
    }

    function logout(Request $request)
    {
        $user = $this->userRepository->findByField('api_token', $request->input('api_token'))->first();
        if (!$user) {
            return $this->sendError('User not found', 200);
        }
        try {
            auth()->logout();
        } catch (Exception $e) {
            $this->sendError($e->getMessage(), 200);
        }
        return $this->sendResponse($user['name'], 'User logout successfully');

    }

    function user(Request $request)
    {
        $user = $this->userRepository->findByField('id', $request->input('id'))->first();

        if (!$user) {
            return $this->sendError('User not found', 200);
        }


        $rolesSelected = $user->getRoleNames()->toArray();

        $data['id']=$user->id;
        $data['name']=$user->name;
        $data['user_role']=$rolesSelected[0];
        $data['phone_number']=$user->phone_number;
        $data['phone_verified_at']=$user->phone_verified_at;
        $data['api_token']=$user->api_token;
        $data['device_token']=$user->device_token;
        $data['created_at']=$user->created_at;
        $data['updated_at']=$user->updated_at;
        $data['has_media']=$user->has_media;

        $profileImg='';
        if($user->has_media && !empty($user->media)){
            $mediaArr = $user->media->toArray();
            foreach($mediaArr as $photo){
                $profileImg = $photo['url'];
               
            }
        }
        $data['image']=$profileImg;
        $providerArr =[];
        $servicesArr =[];
        if(count($user->eProviders)>0){
            foreach($user->eProviders as $provider){
                $temp['id']= $provider->id;
                $temp['name']= $provider->name;
                $temp['e_provider_type_id']= $provider->e_provider_type_id;
                $temp['description']= $provider->description;
                $temp['phone_number']= $provider->phone_number;
                $temp['available']= $provider->available;
                $temp['featured']= $provider->featured;
                $temp['accepted']= $provider->accepted;
                $temp['tsh_id']= $provider->tsh_id;
                $temp['address']= $provider->address;
                $temp['latitude']= $provider->latitude;
                $temp['longitude']= $provider->longitude;
                $temp['category_id']= $provider->category_id;
                $temp['fburl']= $provider->fburl;
                $temp['created_at']= $provider->created_at;
                $temp['updated_at']= $provider->updated_at;
                $temp['has_media']=$provider->has_media;
                $temp['imgPath']=(!empty($provider->has_media)>0)?$provider->media[0]->url:'';

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

                    
                    array_push($servicesArr,$stemp);
                    array_push($temp['services'],$stemp);

               }

                array_push($providerArr, $temp);

            }   
        }
        $data['providers'] = $providerArr;
        $data['services'] = $servicesArr;

        return $this->sendResponse($data, 'User profile retrieved successfully');
    }

    function settings(Request $request)
    {
        $settings = setting()->all();
        $settings = array_intersect_key($settings,
            [
                'default_tax' => '',
                'default_currency' => '',
                'default_currency_decimal_digits' => '',
                'app_name' => '',
                'currency_right' => '',
                'enable_paypal' => '',
                'enable_stripe' => '',
                'enable_razorpay' => '',
                'main_color' => '',
                'main_dark_color' => '',
                'second_color' => '',
                'second_dark_color' => '',
                'accent_color' => '',
                'accent_dark_color' => '',
                'scaffold_dark_color' => '',
                'scaffold_color' => '',
                'google_maps_key' => '',
                'fcm_key' => '',
                'mobile_language' => '',
                'app_version' => '',
                'enable_version' => '',
                'distance_unit' => '',
                'default_theme' => '',
            ]
        );

        if (!$settings) {
            return $this->sendError('Settings not found', 200);
        }

        return $this->sendResponse($settings, 'Settings retrieved successfully');
    }

    /**
     * Update the specified User in storage.
     *
     * @param int $id
     * @param Request $request
     *
     */
    public function update($id, Request $request)
    {
        $user = $this->userRepository->findWithoutFail($id);
        
        if($user->phone_number != $request->phone_number){
            $check = $this->userRepository->findByField('phone_number', $request->input('phone_number'))->first();
            
            if ($check) {
                return $this->sendError('Phone number already exist.', 200);
            }
        }

        

        if (empty($user)) {
            return $this->sendError('User not found');
        }
        $input = $request->except(['api_token']);
        try {
            if ($request->has('device_token')) {
                $user = $this->userRepository->update($request->only('device_token'), $id);
            } else {
                // $customFields = $this->customFieldRepository->findByField('custom_field_model', $this->userRepository->model());
                if (isset($input['password'])) {
                    $input['password'] = Hash::make($request->input('password'));
                }
                $user = $this->userRepository->update($input, $id);
                $input = $request->all();

                if (isset($input['avatar']) && $input['avatar']) {

                    $uploadInput['field'] = "avatar";
                    $uploadInput['uuid'] = Str::uuid()->toString();
                    $uploadInput['file'] = $input['avatar'];

                    $upload = $this->uploadRepository->create($uploadInput);
                    $upload->addMedia($uploadInput['file'])
                                ->withCustomProperties(['uuid' => $uploadInput['uuid'], 'user_id' => $request->user_id])
                                ->toMediaCollection($uploadInput['field']);

                    $cacheUpload = $this->uploadRepository->getByUuid($uploadInput['uuid']);
                    $mediaItem = $cacheUpload->getMedia('avatar')->first();
                    $mediaItem->copy($user, 'avatar');
                }

                // foreach (getCustomFieldsValues($customFields, $request) as $value) {
                //     $user->customFieldsValues()
                //         ->updateOrCreate(['custom_field_id' => $value['custom_field_id']], $value);
                // }
            }
        } catch (ValidatorException $e) {
            return $this->sendError($e->getMessage(), 200);
        }

        $data['id']=$user->id;
        $data['name']=$user->name;
        $data['phone_number']=$user->phone_number;
        $data['api_token']=$user->api_token;
        $data['device_token']=$user->device_token;
        $data['created_at']=$user->created_at;
        $data['updated_at']=$user->updated_at;
        $data['has_media']=$user->has_media;

        $profileImg='';
        if($user->has_media && !empty($user->media)){
            $mediaArr = $user->media->toArray();
            foreach($mediaArr as $photo){
                $profileImg = $photo['url'];
               
            }
        }
        $data['image']=$profileImg;

        return $this->sendResponse($data, __('lang.updated_successfully', ['operator' => __('lang.user')]));
    }

    function sendResetLinkEmail(Request $request): JsonResponse
    {
        try {
            $this->validate($request, ['email' => 'required|email|exists:users']);
            $response = Password::broker()->sendResetLink(
                $request->only('email')
            );
            if ($response == Password::RESET_LINK_SENT) {
                return $this->sendResponse(true, 'Reset link was sent successfully');
            } else {
                return $this->sendError('Reset link not sent');
            }
        } catch (ValidationException $e) {
            return $this->sendError($e->getMessage());
        } catch (Exception $e) {
            return $this->sendError("Email not configured in your admin panel settings");
        }

    }

    public function changePassword($id, Request $request)
    {
        $user = $this->userRepository->findWithoutFail($id);

        $input = $request->all();

        if (empty($user)) {
            return $this->sendError('User not found');
        }

        if (isset($input['new_psw'])) {
            $input['password'] = Hash::make($input['new_psw']);
        }

        $user->fill([
            'password' => $input['password']
            ])->save();;

        return $this->sendResponse($user, 'Password Change successfully');

        // if (Hash::check($input['current_psw'], $user->password)) { 
        //    $user->fill([
        //     'password' => $input['password']
        //     ])->save();;

        //   return $this->sendResponse($user, 'Password Change successfully');

        // } else {
        //     return $this->sendError("Password does not match");
        // }
    }

    public function forgetPassword(Request $request)
    {
        $user = $this->userRepository->findByField('phone_number', $request->input('phone_number'))->first();
        if (!$user) {
            return $this->sendError('User not found', 200);
        }

        $input = $request->all();

       
        if (isset($input['new_psw'])) {
            $input['password'] = Hash::make($input['new_psw']);
            $user->fill([
            'password' => $input['password']
            ])->save();;


          return $this->sendResponse($user, 'Password reset successfully');

        } else {
            return $this->sendError("something went wrong!");
        }
    }

}
