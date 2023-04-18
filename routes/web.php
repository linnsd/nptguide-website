<?php
/*
 * File name: web.php
 * Last modified: 2021.06.28 at 23:26:00
 * Author: SmarterVision - https://codecanyon.net/user/smartervision
 * Copyright (c) 2021
 */

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/page_landing', function () {
    return view('frontend.page.landing_03');
})->name('page_landing');

Route::get('/page_contact', function () {
    return view('frontend.page.landing_03');
})->name('page_contact');

Route::get('/post_list_all', function () {
    // return view('frontend.home.home_02');
})->name('post_list_all');

Route::get('/post_detail', function () {
    // return view('frontend.home.home_02');
})->name('post_detail');


Route::post('/user_login', 'Frontend\UserLoginController@login')->name('user_login');

Route::post('/user_register', 'Frontend\UserRegisterController@register')->name('user_register');

Route::post('/logout', function () {
})->name('logout');



Route::get('/user_profile', 'Frontend\HomeController@userProfile')->name('user_profile');

Route::post('/user_profile_update', 'Frontend\HomeController@profileUpdate')->name('user_profile_update');

Route::post('/user_password_update', 'Frontend\HomeController@passwordchange')->name('user_password_update');

Route::get('/user_my_place', 'Frontend\HomeController@usermyPlaces')->name('user_my_place');

Route::get('/place_edit/{id}', 'Frontend\HomeController@usereditPlaces')->name('place_edit');

Route::get('/place_detail/{id}', 'Frontend\HomeController@usermyPlaces')->name('place_detail');

Route::delete('/user_my_place_delete/{id}', 'Frontend\HomeController@usermyPlaces')->name('user_my_place_delete');



Route::get('/place_addnew', 'Frontend\HomeController@place_addnew')->name('place_addnew');


Route::post('/add_place', 'Frontend\HomeController@add_place')->name('add_place');

Route::post('/update_place', 'Frontend\HomeController@update_place')->name('update_place');

Route::get('/', 'Frontend\HomeController@index')->name('home');

Route::get('/new_home', 'Frontend\HomeController@index')->name('home');

Route::get('/places', 'Frontend\PlaceController@index')->name('places');

Route::get('add_to_fav/{id}', 'Frontend\PlaceController@add_to_fav')->name('add_to_fav');


Route::get('/place_detail/{id}', 'Frontend\PlaceController@show')->name('place_detail');

Route::get('/promotion_detail/{id}', 'Frontend\PlaceController@promotion_detail')->name('promotion_detail');

Route::get('/search-listing', 'Frontend\PlaceController@placeSearch')->name('place_search');

Route::get('/promotions_list', 'Frontend\PlaceController@promotions')->name('promotions.list');

Route::get('/menu_service/{id}', 'Frontend\PlaceController@menu_service_detail')->name('menu_service_detail');

Route::get('/popular_shops', 'Frontend\PlaceController@popular_shops')->name('popular_shops');

Route::get('/visiting_places', 'Frontend\PlaceController@visiting_places')->name('visiting_places');

// add review
Route::post('/add_review', 'Frontend\PlaceController@add_review')->name('add_review');




Route::get('/page_search_listing', 'Frontend\PlaceController@page_search_listing')->name('page_search_listing');


Route::get('/contact', 'Frontend\HomeController@contact')->name('page_contact');

Route::post('/send_message', 'Frontend\HomeController@send_message')->name('send_message');

Route::get('/about', 'Frontend\HomeController@about')->name('about');

Route::get('/faq', 'Frontend\HomeController@faq')->name('faq');

Route::get('/app_landing', 'Frontend\HomeController@appLanding')->name('app_landing');

Route::get('/count_down', 'Frontend\HomeController@countDown')->name('count_down');

Route::get('/maintainance', 'Frontend\HomeController@maintainance')->name('maintainance');

Route::get('/term-condition', 'Frontend\HomeController@termsAndCondtion')->name('term-condition');

Route::get('/privacy-policy', 'Frontend\HomeController@privacyPolicy')->name('privacy-policy');






Route::get('login/{service}', 'Auth\LoginController@redirectToProvider');
Route::get('login/{service}/callback', 'Auth\LoginController@handleProviderCallback');
Auth::routes([
  'register' => false,
  'reset' => false,
  'verify' => false,
]);

Route::get('payments/failed', 'PayPalController@index')->name('payments.failed');
Route::get('payments/razorpay/checkout', 'RazorPayController@checkout');
Route::post('payments/razorpay/pay-success/{bookingId}', 'RazorPayController@paySuccess');
Route::get('payments/razorpay', 'RazorPayController@index');

Route::get('payments/stripe/checkout', 'StripeController@checkout');
Route::get('payments/stripe/pay-success/{bookingId}/{paymentMethodId}', 'StripeController@paySuccess');
Route::get('payments/stripe', 'StripeController@index');

Route::get('payments/paypal/express-checkout', 'PayPalController@getExpressCheckout')->name('paypal.express-checkout');
Route::get('payments/paypal/express-checkout-success', 'PayPalController@getExpressCheckoutSuccess');
Route::get('payments/paypal', 'PayPalController@index')->name('paypal.index');

Route::get('firebase/sw-js', 'Admin\AppSettingController@initFirebase');


Route::get('storage/app/public/{id}/{conversion}/{filename?}', 'Admin\UploadController@storage');

Route::get('/fav_place', 'Frontend\PlaceController@fav_place')->name('fav_place');
