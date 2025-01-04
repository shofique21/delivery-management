<?php

use Illuminate\Http\Request;

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

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });
//  lafz Api
 Route::get('/lafz', 'VisitorController@lafzApi');
//  Route::get('/api', 'VisitorController@api');
    // Merchant operation

Route::post('merchant/login', 'MerchantApiController@login')->middleware('apiresponse');
Route::post('merchant/register', 'MerchantApiController@register')->middleware('apiresponse');
Route::post('merchant-create-parcel', 'MerchantApiController@merchant_parcel_create');
 Route::get('deliverycharge-id', 'MerchantApiController@deliverycharge');
 Route::get('area-id', 'MerchantApiController@area_id');
//Deliveryman Operation

Route::post('deliveryman/login', 'DeliverymanApiController@login')->middleware('apiresponse');

// reset pass by number
 Route::post('merchant/password/reset','MerchantApiController@passfromreset');
 Route::post('merchant/reset/password','MerchantApiController@saveResetPassword');
// otp
Route::get('/send-Otp', 'MerchantApiController@sendOtp');
Route::post('/login-With-Otp', 'MerchantApiController@loginWithOtp');
Route::get('track/parcel/{trackId}', 'MerchantApiController@parceltrackfont');
// status
Route::get('/parcel-status', 'MerchantApiController@status');
// announcements
Route::get('/merchant/announcements','MerchantApiController@notifications');
// push notification
Route::post('/push', 'MerchantApiController@push');
Route::get('/push-get', 'MerchantApiController@push_get');
Route::prefix('merchant')->middleware('merchantauthapi')->group( function(){
    Route::get('/dashboard', 'MerchantApiController@dashboard');
    Route::get('/profile', 'MerchantApiController@profile');
    Route::post('/profile/update', 'MerchantApiController@profileupdate');
    Route::get('/parcels', 'MerchantApiController@parcel');
    Route::get('/parcels/{slug}', 'MerchantApiController@parcels');
    Route::get('/parcel/details/{id}', 'MerchantApiController@parceldetails');
    Route::get('/parcel/invoice/{id}', 'MerchantApiController@invoice');
    Route::get('/parcel/track/{trackId}', 'MerchantApiController@parceltrack');
    Route::get('/parcel/{trackId}', 'MerchantApiController@parceltrack');
    Route::get('/choose-service', 'MerchantApiController@chooseservice');
    Route::get('/zone', 'MerchantApiController@zone');
    Route::post('/pickup/request', 'MerchantApiController@pickuprequest');
    Route::get('/archive','MerchantApiController@archive');
    Route::get('/new-order/{slug}', 'MerchantApiController@parcelcreate');
    Route::post('/create', 'MerchantApiController@parcelstoreApi');
    // Route::post('withdrawal','MerchantApiController@withdrawal');
    Route::post('/withdrawal-request', 'MerchantApiController@withdrawal_request');
    Route::get('getwithdrawal','MerchantApiController@withdrawalget');
    Route::get('/pickup/manage', 'MerchantApiController@pickupmanage');
    Route::get('/parcelnotification', 'MerchantApiController@parcelnotification');
    Route::get('/allparcelnotification', 'MerchantApiController@allparcelnotification');
    Route::get('/payments','MerchantApiController@payments');
    Route::get('/payment/details/{id}', 'MerchantApiController@inovicedetails');
    Route::get('/reports', 'MerchantApiController@report');
    Route::get('/profile/edit', 'MerchantApiController@profileEdit');
    Route::get('/settings/edit', 'MerchantApiController@profileEdit');
    Route::get('/profile/update', 'MerchantApiController@profileUpdate');
    Route::post('/support','MerchantApiController@merchantsupport');
    Route::get('/complain', 'MerchantApiController@complain');
    Route::get('/getIssueDetalisByIssue/{issue_id}', 'MerchantApiController@getIssueDetalisByIssue');
    Route::post('/addcomplain', 'MerchantApiController@addComplain');
    Route::get('/logout', 'MerchantApiController@logout');
    
});

// Deliveryman Api

Route::prefix('deliveryman')->middleware('deliverymanauthapi')->group( function(){
    Route::get('/dashboard', 'DeliverymanApiController@dashboard');
    Route::get('/report', 'DeliverymanApiController@report');
    Route::get('/parcels', 'DeliverymanApiController@parcels');
    Route::get('/parcels/{slug}','DeliverymanApiController@parcel');
    Route::get('/parcel/details/{id}', 'DeliverymanApiController@parceldetails');
    Route::get('/parcel/invoice/{id}', 'DeliverymanApiController@invoice');
    Route::get('/commissionhistory', 'DeliverymanApiController@commissionHistory');
    Route::get('/transaction', 'DeliverymanApiController@transaction');
    Route::post('/transactions', 'DeliverymanApiController@transactions'); 
    Route::post('/parcel/partial_pay', 'DeliverymanApiController@partial_pay');
     Route::get('/settings/edit', 'DeliverymanApiController@profileEdit');
    Route::post('/settings/update', 'DeliverymanApiController@profileUpdate');
    Route::post('/parcel/pickupaccept', 'DeliverymanApiController@pickupaccept');
    Route::post('/parcel/accept', 'DeliverymanApiController@accept');
    Route::post('/parcel/status-update', 'DeliverymanApiController@statusupdate');
    Route::get('/logout', 'DeliverymanApiController@logout');
});




