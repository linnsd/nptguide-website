<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Township;
use App\Models\EProvider;
use App\Models\PrivacyAbout;
use App\Repositories\UserRepository;
use App\Repositories\UploadRepository;
use App\Repositories\EProviderRepository;
use Illuminate\Support\Facades\Hash;
use App\Models\TextSlide;
use App\Models\User;
use App\Models\Contact;
use Illuminate\Support\Facades\Auth;
use DB;
use File;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\HasMedia;
use App\Events\EProviderChangedEvent;
use App\Criteria\EProviders\EProvidersOfUserCriteria;

use Illuminate\Support\Str;

class HomeController extends Controller
{
    private $userRepository;
    private $eProviderRepository;
    private $promotionRepository;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    private $uploadRepository;
    private $categoryRepository;


    public function __construct(EProviderRepository $eProviderRepo, UserRepository $userRepository, UploadRepository $uploadRepo)
    {
        $this->eProviderRepository = $eProviderRepo;
        $this->userRepository = $userRepository;
        $this->uploadRepository = $uploadRepo;
    }

    public function index()
    {
        $categories = Category::with('eProviders')->get();
        $eProviders = EProvider::get();
        $myarr = [];
        $shop_count = 0;

        foreach ($categories as $key => $category) {
            $data['id'] = $category->id;
            $data['name'] = $category->name;
            $data['order'] = $category->order;
            $data['feature'] = $category->featured;
            $data['has_media'] = $category->has_media;
            $data['imgPath'] =  ($category->has_media) ? $category->media[0]->url : '';
            $data['shop_count'] = $category->eProviders->count();
            array_push($myarr, $data);
        }

        $eProviders = new EProvider();
        // $providers = $eProviders
        //     ->leftjoin('townships', 'townships.id', '=', 'e_providers.tsh_id')
        //     ->leftjoin('e_provider_ratings', 'e_providers.id', '=', 'e_provider_ratings.eprovider_id')
        //     ->select([
        //         'e_providers.*',
        //         'townships.tsh_name'
        //     ])->where("featured", 1)->where("available", 1)->where("accepted", 1)
        //     ->where('category_id', '!=', 22);
        // ->selectRaw('SUM(e_provider_ratings.rating_grade) as rates')
        // ->limit(10)
        // ->orderBy('rates', 'desc')->get();

        // dd($eproviders);

        $providers = DB::table('e_provider_ratings')
            ->select('eprovider_id', 'e_providers.*', 'townships.tsh_name', DB::raw('sum(rating_grade) as total'))
            ->leftJoin('e_providers', 'e_providers.id', '=', 'e_provider_ratings.eprovider_id')
            ->leftjoin('townships', 'townships.id', '=', 'e_providers.tsh_id')
            ->groupBy('eprovider_id')
            ->orderBy('total', 'desc')
            ->get();

        // $providers = [];

        if (count($providers) == 0) {
            $providers = new EProvider();
            $providers = $providers->leftjoin('townships', 'townships.id', '=', 'e_providers.tsh_id')
                ->select([
                    'e_providers.*',
                    'townships.tsh_name'
                ])->where("featured", 1)->where("available", 1)->where("accepted", 1)
                ->where('category_id', '!=', 22)
                ->get()
                ->random(10);
        }
        // $providers =EProvider::where("featured",1)->where("available",1)->where("accepted",1)->get();

        $providerArr = [];
        foreach ($providers as $key => $provider) {

            if ($provider->id === null) {
                continue;
            }

            // dd($provider);
            $pdata['id'] = $provider->id;
            $pdata['name'] = $provider->name;
            $pdata['phone_number'] = $provider->phone_number;
            $pdata['featured'] = $provider->featured;
            $pdata['available'] = $provider->available;

            $pdata['address'] = $provider->address;
            $pdata['latitude'] = $provider->latitude;
            $pdata['longitude'] = $provider->longitude;
            $pdata['tsh_name'] = $provider->tsh_name;

            // $pdata['imgPath'] = ($provider->has_media) ? $provider->media[0]->url : "";
            $pdata['imgPath'] = $this->get_img($provider->id);

            array_push($providerArr, $pdata);
        }
        $townships = Township::all();
        $places = new EProvider();
        $places = $places->leftjoin('townships', 'townships.id', '=', 'e_providers.tsh_id')
            ->select([
                'e_providers.*',
                'townships.tsh_name'
            ])->where("e_providers.category_id", 22)->where("e_providers.available", 1)->where("e_providers.accepted", 1)
            ->inRandomOrder()
            ->limit(10)
            ->get();


        // dd($places);

        $placesArr = [];
        foreach ($places as $key => $provider) {
            $placedata['id'] = $provider->id;
            $placedata['name'] = $provider->name;
            $placedata['phone_number'] = $provider->phone_number;
            $placedata['featured'] = $provider->featured;
            $placedata['available'] = $provider->available;

            $placedata['address'] = $provider->address;
            $placedata['latitude'] = $provider->latitude;
            $placedata['longitude'] = $provider->longitude;
            $placedata['tsh_name'] = $provider->tsh_name;

            $placedata['imgPath'] = ($provider->has_media) ? $provider->media[0]->url : "";
            array_push($placesArr, $placedata);
        }

        $text_slides = TextSlide::where('status', 1)->get();

        // dd($placesArr);

        // start
        $adSliders = $eProviders->where('e_provider_type_id', 2)->where("available", 1)->where("accepted", 1)->get();

        $adsSliders = [];
        foreach ($adSliders as $key => $provider) {
            // dd($provider->media);
            $sliderdata['id'] = $provider->id;
            $sliderdata['name'] = $provider->name;
            $sliderdata['imgPath'] = ($provider->has_media) ? $provider->media[0]->url : "";
            array_push($adsSliders, $sliderdata);
        }

        // my promotion

        // dd($adSliders);
        return view('frontend.home.home_03', compact('myarr', 'adsSliders', 'townships', 'providerArr', 'placesArr', 'text_slides'));
    }

    public function get_img($shop_id)
    {
        $e_providers = $this->eProviderRepository->find($shop_id);

        // dd($e_providers->has);
        $img_path = ($e_providers->has_media) ? $e_providers->media[0]->url : "";
        return $img_path;
    }

    public function page_search_listing(Request $request)
    {
        // dd($request['category']);
        $categories = Category::all();
        $popular_cities = Township::all();

        $eProviders = new EProvider();
        $eProviders = $eProviders->leftjoin('townships', 'townships.id', '=', 'e_providers.tsh_id')
            ->select([
                'e_providers.*',
                'townships.tsh_name'
            ])->where('e_providers.category_id', $request['category'])->get();

        // dd($eProviders->where('available',1));

        $eProviders = $eProviders->where('available', '=', 1)->map(function ($item, $key) {
            // dd($item);
            return [
                'id' => $item->id,
                'name' => $item->name,
                'featured' => $item->featured,
                'available' => $item->available,
                'available' => $item->available,
                'phone_number' => $item->phone_number,
                'address' => $item->address,
                'latitude' => $item->latitude,
                'longitude' => $item->longitude,
                'tsh_name' => $item->tsh_name,
                'has_media' => $item->has_media,
                'imgPath' => ($item->has_media) ? $item->media[0]->url : ""
            ];
        });
        // dd($eProviders);
        return view('frontend.home.home_02', compact('categories', 'popular_cities', 'eProviders'));
    }

    public function about()
    {
        return view('frontend.page.about');
    }

    public function faq()
    {
        return view('frontend.page.faq');
    }

    public function contact()
    {

        return view('frontend.page.contact');
    }

    public function appLanding()
    {
        return view('frontend.page.app_landing');
    }

    public function maintainance()
    {
        return view('frontend.page.maintainance');
    }

    public function countDown()
    {
        return view('frontend.page.count_down');
    }

    public function termsAndCondtion()
    {
        $termsAndCondtion = PrivacyAbout::where('category', 1)->get();
        // dd($termsAndCondtion[0]);
        if (count($termsAndCondtion) > 0) {
            $termsAndCondtion = $termsAndCondtion[0];
        } else {
            $termsAndCondtion = null;
        }
        return view('frontend.page.term_condition', compact('termsAndCondtion'));
    }

    public function privacyPolicy()
    {
        $privacyPolicy = PrivacyAbout::where('category', 2)->get();
        // dd($privacyPolicy[0]);
        if (count($privacyPolicy) > 0) {
            $privacyPolicy = $privacyPolicy[0];
        } else {
            $privacyPolicy = null;
        }
        return view('frontend.page.privacy', compact('privacyPolicy'));
    }


    public function userProfile()
    {
        $user = $this->userRepository->findByField('id', auth()->user()->id)->first();

        if (!$user) {
            return $this->sendError('User not found', 200);
        }

        $profileImg = '';
        if ($user->has_media && !empty($user->media)) {
            $mediaArr = $user->media->toArray();
            foreach ($mediaArr as $photo) {
                $profileImg = $photo['url'];
            }
        }
        $data['image'] = $profileImg;

        $img = $data['image'];

        return view('frontend.user.user_profile', compact('img'));
    }

    public function usermyPlaces(Request $request)
    {
        // dd("Here");
        $categories = Category::all();
        $townships = Township::all();

        $filter['keyword'] = $request->keyword;
        $filter['township'] = $request->township_id;
        $filter['category'] = $request->category_id;

        $user = $this->userRepository->findByField('id', auth()->user()->id)->first();
        $places = [];
        $menus = [];
        if (count($user->eProviders) > 0) {
            foreach ($user->eProviders as $key => $provider) {
                $temp['id'] = $provider->id;
                $temp['name'] = $provider->name;
                $temp['e_provider_type_id'] = $provider->e_provider_type_id;
                $temp['description'] = $provider->description;
                $temp['phone_number'] = $provider->phone_number;
                $temp['available'] = $provider->available;
                $temp['featured'] = $provider->featured;
                $temp['accepted'] = $provider->accepted;
                $temp['tsh_id'] = $provider->tsh_id;
                $temp['tsh_name'] = (!empty($provider->townships)) ? $provider->townships->tsh_name : '';
                $temp['address'] = $provider->address;
                $temp['latitude'] = $provider->latitude;
                $temp['longitude'] = $provider->longitude;
                $temp['category_id'] = $provider->category_id;
                $temp['category_name'] = (!empty($provider->category)) ? $provider->category->name : '';;
                $temp['fburl'] = $provider->fburl;
                $temp['created_at'] = $provider->created_at;
                $temp['updated_at'] = $provider->updated_at;
                $temp['has_media'] = $provider->has_media;
                $temp['imgPath'] = (!empty($provider->has_media) > 0) ? last($provider->media) : '';
                // var_dump($temp['imgPath']);
                $temp['services'] = [];

                foreach ($provider->eServices as $service) {
                    $stemp['id'] = $service->id;
                    $stemp['e_provider_id'] = $service->e_provider_id;
                    $stemp['name'] = $service->name;
                    $stemp['price'] = $service->price;
                    $stemp['featured'] = $service->featured;
                    $stemp['available'] = $service->available;
                    $stemp['has_media'] = $service->has_media;
                    $stemp['imgurl'] = (!empty($service->has_media) > 0) ? $service->media[0]->url : '';


                    array_push($menus, $stemp);
                    array_push($temp['services'], $stemp);
                }

                array_push($places, $temp);
            }
        }

        // dd(end($places[0]['imgPath'])->url);

        return view('frontend.user.user_my_place', compact('categories', 'townships', 'filter', 'places', 'menus'));
    }

    public function usereditPlaces($id)
    {

        // dd($id);
        $place = eProvider::find($id);
        // dd($place);
        $categories = Category::all();
        $townships = Township::all();

        $arr['media'] = [];
        if ($place->has_media) {
            foreach ($place->media as $media) {
                $temp['uuid'] = $media->custom_properties['uuid'];
                $temp['mime_type'] = $media->mime_type;
                $temp['formated_size'] = $media->formated_size;
                $temp['url'] = $media->url;
                array_push($arr['media'], $temp);
            }
        }
        // dd($arr['media']);
        $img = $arr['media'];
        // dd(end($img)['url']);
        return view('frontend.place.place_edit', compact('categories', 'townships', 'place', 'img'));
    }


    public function place_addnew()
    {
        $categories = Category::all();
        $townships = Township::all();
        return view('frontend.place.place_addnew', compact('categories', 'townships'));
    }

    public function add_place(Request $request)
    {
        $input = $request->all();
        // dd($input);
        $input['users'] = [$request->user_id];
        $input['e_provider_type_id'] = 3;

        $uploadInput = [];

        try {
            $eProvider = $this->eProviderRepository->create($input);
            if (isset($input['image']) && $input['image'] && is_array($input['image'])) {
                foreach ($input['image'] as $image) {
                    $uploadInput['field'] = "image";
                    $uploadInput['uuid'] = Str::uuid()->toString();
                    $uploadInput['file'] = $image;

                    $upload = $this->uploadRepository->create($uploadInput);
                    $upload->addMedia($uploadInput['file'])
                        ->withCustomProperties(['uuid' => $uploadInput['uuid'], 'user_id' => $request->user_id])
                        ->toMediaCollection($uploadInput['field']);


                    $cacheUpload = $this->uploadRepository->getByUuid($uploadInput['uuid']);
                    $mediaItem = $cacheUpload->getMedia('image')->first();
                    $mediaItem->copy($eProvider, 'image');


                    $uploadInput = [];
                }
            }
            event(new EProviderChangedEvent($eProvider, $eProvider));
        } catch (ValidatorException $e) {
            dd('Provider store error!');
        }
        // Flash::success(__('lang.saved_successfully', ['operator' => __('lang.category')]));
        return redirect(route('home'));
    }

    public function profileUpdate(Request $request)
    {
        $user = $this->userRepository->findWithoutFail($request->id);

        if ($user->phone_number != $request->phone_number) {
            $check = $this->userRepository->findByField('phone_number', $request->input('phone_number'))->first();

            if ($check) {
                dd('Phone number already exist.');
            }
        }



        if (empty($user)) {
            dd('User not found');
        }
        $input = $request->except(['api_token']);
        // dd($input);
        try {
            $user = $this->userRepository->update($input, $request->id);
            $input = $request->all();

            if (isset($input['avatar']) && $input['avatar']) {
                // dd("Here");
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
        } catch (ValidatorException $e) {
            return $this->sendError($e->getMessage(), 200);
        }

        $data['id'] = $user->id;
        $data['name'] = $user->name;
        $data['phone_number'] = $user->phone_number;
        $data['api_token'] = $user->api_token;
        $data['device_token'] = $user->device_token;
        $data['created_at'] = $user->created_at;
        $data['updated_at'] = $user->updated_at;
        $data['has_media'] = $user->has_media;

        $profileImg = '';
        if ($user->has_media && !empty($user->media)) {
            $mediaArr = $user->media->toArray();
            foreach ($mediaArr as $photo) {
                $profileImg = $photo['url'];
            }
        }
        $data['image'] = $profileImg;

        return redirect()->route('user_profile')->with('success', 'Updated Sucessfully.');
    }

    public function passwordchange(Request $request)
    {
        // dd($request->all());
        $user = User::findOrFail($request->id);

        /*
        * Validate all input fields
        */
        $this->validate($request, [
            'password' => 'required|min:6|confirmed',
            'password_confirmation' => 'required|min:6'
        ]);

        if (Hash::check($request->old_password, $user->password)) {
            // dd("Here");
            $user->fill([
                'password' => Hash::make($request->password_confirmation)
            ])->save();

            $request->session()->flash('success', 'Password changed');
            return view('frontend.user.user_profile');
        } else {
            // dd("Hello"); 
            $request->session()->flash('error', 'Password does not match');
            return view('frontend.user.user_profile');
        }
    }

    public function update_place(Request $request)
    {
        $oldEProvider = $this->eProviderRepository->findWithoutFail($request->id);

        $input = $request->all();
        $input['users'] = [$request->user_id];
        $uploadInput = [];

        try {
            $eProvider = $this->eProviderRepository->update($input, $request->id);


            if (isset($input['image']) && $input['image'] && is_array($input['image'])) {
                foreach ($input['image'] as $image) {
                    $uploadInput['field'] = "image";
                    $uploadInput['uuid'] = Str::uuid()->toString();
                    $uploadInput['file'] = $image;

                    $upload = $this->uploadRepository->create($uploadInput);
                    $upload->addMedia($uploadInput['file'])
                        ->withCustomProperties(['uuid' => $uploadInput['uuid'], 'user_id' => $request->user_id])
                        ->toMediaCollection($uploadInput['field']);


                    $cacheUpload = $this->uploadRepository->getByUuid($uploadInput['uuid']);
                    $mediaItem = $cacheUpload->getMedia('image')->first();
                    $mediaItem->copy($eProvider, 'image');


                    $uploadInput = [];
                }
            }

            event(new EProviderChangedEvent($eProvider, $oldEProvider));
        } catch (ValidatorException $e) {
            dd('Provider store error!');
        }

        // return $this->sendResponse($eProvider, 'EProvider update successfully');
        return redirect()->route('user_profile');
    }

    public function send_message(Request $request)
    {
        // dd($request->all());

        if (is_null(Auth::user())) {
            return redirect(url('/'))->with('warning', 'You need to login first!');
        }

        $contact = Contact::create([
            'name' => $request->name,
            'phone_number' => $request->phone_number,
            'message' => $request->note
        ]);

        $request->session()->flash('success', 'Success');
        return redirect(url('contact'));
    }
}