<?php
/*
 * File name: api.php
 */

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

/*
| nptguide api routes
*/

//login & register api
Route::post('login', 'API\UserAPIController@login');
Route::post('register', 'API\UserAPIController@register');

//main page API for mobile
Route::get('main', 'API\DashboardAPIController@mainPage');


//  View Add Place
Route::post('e_providers/add_view', 'API\EProvider\EProviderAPIController@add_view');
Route::post('get_view_count', 'API\EProvider\EProviderAPIController@get_view_count');

Route::post('promotion_detail/{id}', 'API\EProviderAPIController@promotion_detail');

Route::post('create_promotion','API\EProviderAPIController@create_promotion');

//promotion delete
Route::post('delete_promotion','API\EProviderAPIController@delete_promotion');

//promotion update
Route::post('update_promotion','API\EProviderAPIController@update_promotion');

//image delete
Route::post('delete_img','API\EProviderAPIController@delete_img');

//service crerate
Route::post('service_create','API\EProviderAPIController@service_create');

//service delete
Route::post('service_delete','API\EProviderAPIController@service_delete');


Route::post('service_detail', 'API\EProviderAPIController@service_detail');

//get user shop
Route::post('user_shops','API\EProviderAPIController@user_shops');

//promotion list
Route::post('promotion_list','API\EProviderAPIController@promotion_list');

//  Rating
Route::post('e_providers/rate', 'API\EProvider\EProviderAPIController@rate');
Route::post('get_ratings', 'API\EProvider\EProviderAPIController@get_ratings');

//  Favourite
Route::post('favourite', 'API\EProvider\EProviderAPIController@favourite');
Route::post('remove_favourite', 'API\EProvider\EProviderAPIController@remove_favourite');
Route::post('get_fav_places', 'API\EProvider\EProviderAPIController@get_fav_places');


// category api
Route::resource('categories', 'API\CategoryAPIController');

//township api
Route::resource('townships', 'API\TownshipAPIController');

// emergency_contact api
Route::resource('emergancy_contact', 'API\EmergancyContactAPIController');

// ministry_contact api
Route::resource('ministry_contact', 'API\MinistryContactAPIController');

Route::post('forgetPassword/', 'API\UserAPIController@forgetPassword');

Route::get('b64password', 'API\DashboardAPIController@convertB64Password');

Route::get('about_us', 'API\AboutUsAPIController@get_about_us');

Route::post('contact_us','API\AboutUsAPIController@contact_us');

//create token
// Route::post('create_token','API\TokenAPIController@create_token');

// Route::middleware('auth:api')->group(function () {

//login user profile
Route::get('user', 'API\UserAPIController@user');
Route::post('uploads/store', 'API\UploadAPIController@store');
Route::post('uploads/clear', 'API\UploadAPIController@clear');
Route::post('user/{id}', 'API\UserAPIController@update');
Route::post('changepassword/{id}', 'API\UserAPIController@changePassword');
//providers api
Route::resource('e_providers', 'API\EProviderAPIController');
Route::post('e_providers/store', 'API\EProviderAPIController@store');
Route::post('e_providers/update/{id}', 'API\EProviderAPIController@update');
Route::post('e_providers/request_feature/{id}', 'API\EProviderAPIController@requestFeature');


Route::get('get_my_providers/{id}', 'API\EServiceAPIController@getMyProvider');
Route::post('create_e_services', 'API\EServiceAPIController@store');
Route::post('update_e_services/{id}', 'API\EServiceAPIController@update');
Route::post('e_services/request_feature/{id}', 'API\EServiceAPIController@requestFeature');

Route::resource('notifications', 'API\NotificationAPIController');

//image delete
Route::post('/img_delete', 'API\UploadAPIController@removeMedia')->name('img_delete');


// });


// Route::prefix('provider')->group(function () {
//     Route::post('login', 'API\EProvider\UserAPIController@login');
//     Route::post('register', 'API\EProvider\UserAPIController@register');
//     Route::post('send_reset_link_email', 'API\UserAPIController@sendResetLinkEmail');
//     Route::get('user', 'API\EProvider\UserAPIController@user');
//     Route::get('logout', 'API\EProvider\UserAPIController@logout');
//     Route::get('settings', 'API\EProvider\UserAPIController@settings');
// });



// Route::post('send_reset_link_email', 'API\UserAPIController@sendResetLinkEmail');
// Route::get('logout', 'API\UserAPIController@logout');
// Route::get('settings', 'API\UserAPIController@settings');


// Route::resource('e_provider_types', 'API\EProviderTypeAPIController');
// Route::resource('availability_hours', 'API\AvailabilityHourAPIController');
// Route::resource('awards', 'API\AwardAPIController');
// Route::resource('experiences', 'API\ExperienceAPIController');

// Route::resource('faq_categories', 'API\FaqCategoryAPIController');
// Route::resource('faqs', 'API\FaqAPIController');
// Route::resource('custom_pages', 'API\CustomPageAPIController');




// Route::resource('galleries', 'API\GalleryAPIController');
// Route::get('e_service_reviews/{id}', 'API\EServiceReviewAPIController@show')->name('e_service_reviews.show');
// Route::get('e_service_reviews', 'API\EServiceReviewAPIController@index')->name('e_service_reviews.index');

// Route::resource('currencies', 'API\CurrencyAPIController');
// Route::resource('slides', 'API\SlideAPIController')->except([
//     'show'
// ]);
// Route::resource('booking_statuses', 'API\BookingStatusAPIController')->except([
//     'show'
// ]);
// Route::resource('option_groups', 'API\OptionGroupAPIController');
// Route::resource('options', 'API\OptionAPIController');

// Route::middleware('auth:api')->group(function () {
//     Route::group(['middleware' => ['role:provider']], function () {
//         Route::prefix('provider')->group(function () {
//             Route::post('users/{id}', 'API\UserAPIController@update');
//             Route::get('dashboard', 'API\DashboardAPIController@provider');
//             Route::resource('e_providers', 'API\EProvider\EProviderAPIController');
//             Route::resource('notifications', 'API\NotificationAPIController');
//             Route::get('e_service_reviews', 'API\EServiceReviewAPIController@index')->name('e_service_reviews.index');
//             Route::get('e_services', 'API\EServiceAPIController@index')->name('e_services.index');
//             Route::put('payments/{id}', 'API\PaymentAPIController@update')->name('payments.update');
//         });
//     });


Route::get('payments/byMonth', 'API\PaymentAPIController@byMonth')->name('payments.byMonth');
//     Route::resource('payments', 'API\PaymentAPIController')->except(['update']);
//     Route::resource('payment_methods', 'API\PaymentMethodAPIController')->only([
//         'index'
//     ]);
//     Route::post('e_service_reviews', 'API\EServiceReviewAPIController@store')->name('e_service_reviews.store');

//     Route::resource('favorites', 'API\FavoriteAPIController');
//     Route::resource('addresses', 'API\AddressAPIController');

//     Route::get('notifications/count', 'API\NotificationAPIController@count');
//     Route::resource('notifications', 'API\NotificationAPIController');
//     Route::resource('bookings', 'API\BookingAPIController');

//     Route::resource('earnings', 'API\EarningAPIController');

//     Route::resource('e_provider_payouts', 'API\EProviderPayoutAPIController');

//     Route::resource('coupons', 'API\CouponAPIController')->except([
//         'show'
//     ]);
// });