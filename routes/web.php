<?php

use Illuminate\Support\Facades\Route;

use Brian2694\Toastr\Facades\Toastr;
Auth::routes();

//Clear Route cache:
Route::get('/route-clear', function() {
    $exitCode = Artisan::call('route:clear');
    return '<h1>Route cache cleared</h1>';
});
//Route cache:
Route::get('/route-cache', function() {
    $exitCode = Artisan::call('route:cache');
    return '<h1>Routes cached</h1>';
});
Route::get('/clear-cache', function() {
    
      $exitCode =Artisan::call('cache:clear');
 $exitCode1=  Artisan::call('config:clear');
//  $exitCode2=  Artisan::call('config:cache');
  $exitCode3= Artisan::call('view:clear');
     Toastr::success('message', 'Cache:clear Successfully !!');
    return back();
});
//Clear Config cache:
Route::get('/config-cache', function() {
    $exitCode = Artisan::call('config:cache');
    return '<h1>Clear Config cleared</h1>';
});

//Migrate create
Route::get('/migrate', function() {
    $exitCode = Artisan::call('migrate');
    return '<h1>Migrate Create</h1>';
});

Route::post('visitor/contact','VisitorController@visitorcontact');
Route::post('merchant/support','VisitorController@merchantsupport');
Route::post('career/apply','VisitorController@careerapply');
 Route::post('contact', 'VisitorController@contact_add');
 Route::get('test', 'VisitorController@test');
 Route::get('/track', 'VisitorController@parceltrackapi');
//  lafz Api
 Route::get('/lafz', 'VisitorController@lafzApi');
Route::group(['namespace'=>'FrontEnd'], function(){
	Route::get('/', 'FrontEndController@index');
    Route::get('/about-us', 'FrontEndController@aboutus');
    Route::get('/our-service/{id}', 'FrontEndController@ourservice');
    Route::get('/one-time-service', 'FrontEndController@onetimeservice');
    Route::get('/career', 'FrontEndController@career');
    Route::get('/career/{id}/{slug}', 'FrontEndController@careerdetails');
    Route::get('/gallery', 'FrontEndController@gallery');
    Route::get('/branches', 'FrontEndController@branches');
    Route::get('/searchbranches', 'FrontEndController@searchBranches')->name('searchbranches');
     Route::get('/notice', 'FrontEndController@notice');
      Route::get('/privacy-policy', 'FrontEndController@privacypolicy');
    Route::get('/notice/{id}/{slug}', 'FrontEndController@noticedetails');
    Route::get('/contact-us', 'FrontEndController@contact');
    
    Route::get('/terms-condition', 'FrontEndController@termscondition');
    Route::post('/track/parcel/', 'FrontEndController@parceltrack');
    
    Route::get('/track/parcel/{id}', 'FrontEndController@parceltrackget');
    Route::get('/cost/calculate/{cod}/{weight}', 'FrontEndController@costCalculate');
    Route::get('cost/calculate/result', 'FrontEndController@costCalculateResult');
    // otp
      Route::post('loginWithOtp', 'MerchantController@loginWithOtp')->name('loginWithOtp');
     Route::get('loginWithOtp', function () { return view('frontEnd.layouts.pages.OtpLogin'); })->name('loginWithOtp');
    Route::post('/sendOtp', 'MerchantController@sendOtp');
    // otp
     // Merchant Operation 
    Route::get('merchant/register', 'MerchantController@registerpage');
    Route::post('auth/merchant/register', 'MerchantController@register');
    Route::get('merchant/login', 'MerchantController@loginpage');
    Route::post('merchant/login', 'MerchantController@login');
    Route::get('merchant/logout', 'MerchantController@logout');
    Route::get('merchant/forget/password','MerchantController@passreset');
    Route::post('auth/merchant/password/reset','MerchantController@passfromreset');
    Route::get('/merchant/resetpassword/verify','MerchantController@resetpasswordverify');
    Route::get('resend/password-reset/code/{id}','MerchantController@resendPasswordcode');
    Route::post('auth/merchant/reset/password','MerchantController@saveResetPassword');
    Route::post('auth/merchant/single-servicer','MerchantController@singleservice');
 

    // Agent Operation
    Route::get('agent/login', 'AgentController@loginform');
    Route::post('auth/agent/login', 'AgentController@login');
    Route::get('agent/forget/password','AgentController@passreset');
    Route::post('auth/agent/password/reset','AgentController@passfromreset');
    Route::get('/agent/resetpassword/verify','AgentController@resetpasswordverify');
    Route::post('auth/agent/reset/password','AgentController@saveResetPassword');

    // Deliveryman Operation
    Route::get('deliveryman/login', 'DeliverymanController@loginform');
    Route::post('auth/deliveryman/login', 'DeliverymanController@login');
    Route::get('deliveryman/forget/password','DeliverymanController@passreset');
    Route::post('auth/deliveryman/password/reset','DeliverymanController@passfromreset');
    Route::get('/deliveryman/resetpassword/verify','DeliverymanController@resetpasswordverify');
    Route::post('auth/deliveryman/reset/password','DeliverymanController@saveResetPassword');
     
});

Route::group(['namespace'=>'FrontEnd','middleware'=>['agentauth']], function(){
    Route::get('/agent/dashboard', 'AgentController@dashboard');
    Route::get('agent/parcels-request', 'AgentController@parcel_request');
    Route::post('agent/parcel/acceptReturn', 'AgentController@acceptReturn');
     Route::post('agent/parcel/acceptReturn1', 'AgentController@acceptReturn1');
    Route::get('agent/logout', 'AgentController@logout');
    Route::get('agent/parcels', 'AgentController@parcel');
    Route::get('agent/report', 'AgentController@report');
    Route::get('agent/transaction', 'AgentController@transaction');
    Route::get('agent/transaction/deliveryman', 'AgentController@transaction_deliveryman');
    Route::post('agent/transactions', 'AgentController@transactions');
    Route::get('agent/parcel/invoice/{id}','AgentController@invoice');
    Route::get('agent/pickup', 'AgentController@pickup');
     Route::get('agent/hub-request', 'AgentController@hubRequest');
    Route::post('agent/deliveryman/asign','AgentController@delivermanasiagn');
    Route::get('agent/deliveryman/asign/report','AgentController@asingreport');
    Route::post('agent/parcel/status-update','AgentController@statusupdate');
     Route::post('agent/parcel/status-update2','AgentController@oldstatusupdate');
    Route::post('agent/pickup/deliveryman/asign','AgentController@pickupdeliverman');
    Route::post('agent/pickup/status-update','AgentController@pickupstatus');
    Route::post('agent/parcel/export','AgentController@export');
    Route::get('agent/parcels/{slug}','AgentController@parcels');
        Route::get('agent/parcel/accept/{id}','AgentController@accept');
        Route::post('agent/parcel/track','AgentController@track');
        Route::post('agent/parcel/rider','AgentController@rider');
        Route::post('agent/asigns','AgentController@agentasign');
});

Route::group(['namespace'=>'FrontEnd','middleware'=>['deliverymanauth']], function(){
 Route::get('deliveryman/dashboard', 'DeliverymanController@dashboard');
 Route::get('deliveryman/logout', 'DeliverymanController@logout');
 Route::get('deliveryman/transaction', 'DeliverymanController@transaction');
 Route::post('deliveryman/transactions', 'DeliverymanController@transactions'); 
 Route::get('deliveryman/report', 'DeliverymanController@report');
 Route::get('deliveryman/commissionhistory', 'DeliverymanController@commissionHistory');
 Route::post('deliveryman/profileUpdate','DeliverymanController@profileUpdate');
 
 Route::get('deliveryman/parcels', 'DeliverymanController@parcels');
 Route::get('deliveryman/parcels/{slug}','DeliverymanController@parcel');
//  Route::get('deliveryman/parcels', 'DeliverymanController@parcels');
 
 Route::get('deliveryman/qrcode-reader', 'DeliverymanController@qrcode');
 
 Route::post('deliveryman/parcel/partial_pay','DeliverymanController@partial_pay');
 Route::get('deliveryman/parcel/invoice/{id}','DeliverymanController@invoice');
 Route::post('deliveryman/parcel/status-update','DeliverymanController@statusupdate');
 Route::get('deliveryman/pickup', 'DeliverymanController@pickup');
 Route::post('deliveryman/pickup/status-update','AgentController@pickupstatus');
 Route::post('deliveryman/parcel/export','DeliverymanController@export');
 Route::post('deliveryman/parcel/accept','DeliverymanController@accept');
 
 Route::post('deliveryman/parcel/pickupaccept','DeliverymanController@pickupaccept');
 
});

Route::group(['namespace'=>'FrontEnd','middleware'=>['merchantauth']], function(){
      // Merchant operation
     Route::get('merchant/dashboard', 'MerchantController@dashboard');
     Route::post('merchant/parcel/import','MerchantController@import');
     Route::post('merchant/parcel/export','MerchantController@export');
     Route::get('merchant/new-order/{slug}', 'MerchantController@parcelcreate');
     Route::get('merchant/pricing/{slug}', 'MerchantController@pricing');
    //  Route::post('merchant/withdrawal','MerchantController@withdrawal');
     Route::get('merchant/payment/invoice-details/{id}', 'MerchantController@inovicedetails');
     Route::get('merchant/profile', 'MerchantController@profile');
     Route::get('merchant/profile/edit', 'MerchantController@profileEdit');
     Route::post('merchant/profile/edit', 'MerchantController@profileUpdate');
     Route::get('merchant/profile/settings', 'MerchantController@profileEdit');
     Route::get('merchant/stats', 'MerchantController@stats');
     Route::get('merchant/fraud-check', 'MerchantController@fraudcheck');
     Route::get('merchant/choose-service', 'MerchantController@chooseservice');
     Route::get('merchant/pickup', 'MerchantController@pickup');
     Route::get('merchant/support', 'MerchantController@support');
      Route::get('merchant/complain', 'MerchantController@complain');
     Route::post('merchant/getIssueDetalisByIssue', 'MerchantController@getIssueDetalisByIssue')->name('issuedetails.getIssueDetalisByIssue');
     Route::post('merchant/addcomplain', 'MerchantController@addComplain');
     Route::get('merchant/parcel/track', 'MerchantController@track');
     Route::get('merchant/parcel/invoice/{id}','MerchantController@invoice');
    // pickup request
     Route::post('merchant/pickup/request', 'MerchantController@pickuprequest');
      Route::get('merchant/pickup/manage', 'MerchantController@pickupmanage');
     // parcel oparation
      Route::get('merchant/report', 'MerchantController@report');
     Route::post('merchant/add/parcel','MerchantController@parcelstore');
     Route::get('merchant/parcels/{slug}','MerchantController@parcels');
    Route::get('merchant/parcel','MerchantController@parcel');
    Route::get('merchant/archive','MerchantController@archive');
     Route::post('merchant/parcel/cancel','MerchantController@cancel');
     Route::get('merchant/parcel/in-details/{id}','MerchantController@parceldetails');
     Route::get('merchant/parcel/edit/{id}','MerchantController@parceledit');
     Route::post('merchant/update/parcel','MerchantController@parcelupdate');
     Route::post('/merchant/parcel/track/', 'MerchantController@parceltrack');
      Route::post('/merchant/withdrawal-request/', 'MerchantController@withdrawal_request');
     Route::get('merchant/get/payments','MerchantController@payments');
});

Route::middleware('throttle:300,1')->group(function () {
 Route::group(['as'=>'superadmin.', 'prefix'=>'superadmin', 'namespace'=>'Superadmin','middleware'=>[ 'auth', 'superadmin']], function(){

 // superadmin dashboard
 Route::get('/dashboard', 'DashboardController@index')->name('dashboard');
    Route::get('/user/add', 'UserController@add');
    Route::post('/user/save', 'UserController@save');
    Route::get('/user/edit/{id}', 'UserController@edit');
    Route::post('/user/update', 'UserController@update');
    Route::get('/user/manage', 'UserController@manage');
    Route::post('/user/inactive', 'UserController@inactive');
    Route::post('/user/active', 'UserController@active');
    Route::post('/user/delete', 'UserController@destroy');
    
    
     Route::get('/sagent/add', 'UserController@sadd');
    Route::get('/sagent/withdrawal', 'UserController@withdrawal');
    Route::get('/sagent/paid/{id}', 'UserController@paid');
    Route::post('/sagent/save', 'UserController@ssave');
    Route::post('/sagent/update', 'UserController@supdate');
    Route::get('/sagent/edit/{id}', 'UserController@sedit');
    Route::get('/sagent/manage', 'UserController@smanage');
    Route::post('/sagent/delete', 'UserController@sdestroy');
    Route::get('secrat-merchant/{id}', 'UserController@secrat_merchant');
    Route::post('merchant-add', 'UserController@merchant_add');
    Route::get('secrat-merchant/delete/{id}', 'UserController@secrat_merchant_delete');
    Route::post('merchant-commission-update', 'UserController@merchant_commission_update');
});

// Live Search
Route::get('search_data/{keyword}', 'search\liveSearchController@SearchData');
Route::get('search_data', 'search\liveSearchController@SearchWithoutData');



// Ajax Route
 Route::get('/ajax-product-subcategory','editor\productController@getSubcategory');

Route::group(['as'=>'admin.', 'prefix'=>'admin', 'namespace'=>'Admin','middleware'=>['auth', 'admin']], function(){

    // admin dashboard
    Route::get('/dashboard', 'DashboardController@index')->name('dashboard');
    Route::post('merchant-payment/bulk-option','DashboardController@bulkpayment');
    // Nearest Zone Route 
    Route::get('/nearestzone/add','NearestzoneController@add');
    Route::post('/nearestzone/save','NearestzoneController@store');
    Route::get('/nearestzone/manage','NearestzoneController@manage');
    Route::get('/nearestzone/edit/{id}','NearestzoneController@edit');
    Route::post('/nearestzone/update','NearestzoneController@update');
    Route::post('/nearestzone/inactive','NearestzoneController@inactive');
    Route::post('/nearestzone/active','NearestzoneController@active');
    Route::post('/nearestzone/delete','NearestzoneController@destroy');

    // Delivery Charge Route 
    Route::get('/deliverycharge/add','DeliveryChargeController@add');
    Route::post('/deliverycharge/save','DeliveryChargeController@store');
    Route::get('/deliverycharge/manage','DeliveryChargeController@manage');
    Route::get('/deliverycharge/edit/{id}','DeliveryChargeController@edit');
    Route::post('/deliverycharge/update','DeliveryChargeController@update');
    Route::post('/deliverycharge/inactive','DeliveryChargeController@inactive');
    Route::post('/deliverycharge/active','DeliveryChargeController@active');
    Route::post('/deliverycharge/delete','DeliveryChargeController@destroy');
    // Cod Charge Route 
    Route::get('codcharge/add','CodChargeController@add');
    Route::post('codcharge/save','CodChargeController@store');
    Route::get('codcharge/manage','CodChargeController@manage');
    Route::get('codcharge/edit/{id}','CodChargeController@edit');
    Route::post('codcharge/update','CodChargeController@update');
    Route::post('codcharge/inactive','CodChargeController@inactive');
    Route::post('codcharge/active','CodChargeController@active');
    Route::post('codcharge/delete','CodChargeController@destroy');
    // Department Route 
    Route::get('department/add','DepartmentController@add');
    Route::post('department/save','DepartmentController@store');
    Route::get('department/manage','DepartmentController@manage');
    Route::get('department/edit/{id}','DepartmentController@edit');
    Route::post('department/update','DepartmentController@update');
    Route::post('department/inactive','DepartmentController@inactive');
    Route::post('department/active','DepartmentController@active');
    Route::post('department/delete','DepartmentController@destroy');

    // Employee Route 
    Route::get('/employee/add', 'EmployeeController@add');
    Route::post('/employee/save', 'EmployeeController@save');
    Route::get('/employee/edit/{id}', 'EmployeeController@edit');
    Route::post('/employee/update', 'EmployeeController@update');
    Route::get('/employee/manage', 'EmployeeController@manage');
    Route::post('/employee/inactive', 'EmployeeController@inactive');
    Route::post('/employee/active', 'EmployeeController@active');
    Route::post('/employee/delete', 'EmployeeController@destroy');

    // Agent Manage Route 
    Route::get('agent/add', 'AgentManageController@add');
    Route::get('Hub-report', 'AgentManageController@hubreport');
     Route::get('asing/report', 'AgentManageController@asingreport');
    Route::post('agent/save', 'AgentManageController@save');
    Route::get('agent/edit/{id}', 'AgentManageController@edit');
    Route::post('agent/update', 'AgentManageController@update');
    
    Route::get('agent/manage', 'AgentManageController@manage');
    Route::post('agent/inactive', 'AgentManageController@inactive');
    Route::post('agent/active', 'AgentManageController@active');
    Route::post('agent/delete', 'AgentManageController@destroy');
    Route::post('agent/changepassword', 'AgentManageController@changepassword');
    Route::get('thirdpartyagent/manage', 'AgentManageController@thirdpartyagent');
    Route::get('agent/transactions', 'AgentManageController@transactions');
        
      
        
        
    // Delivery Man Route 
    Route::get('deliveryman/add', 'DeliverymanManageController@add');
    Route::get('deliveryman/asign', 'DeliverymanManageController@asingreport');
    Route::post('deliveryman/save', 'DeliverymanManageController@save');
    Route::get('deliveryman/edit/{id}', 'DeliverymanManageController@edit');
    Route::post('deliveryman/update', 'DeliverymanManageController@update');
    Route::get('deliveryman/manage', 'DeliverymanManageController@manage');
    Route::get('deliveryman/pickupman', 'DeliverymanManageController@managepickupman');
    Route::post('deliveryman/inactive', 'DeliverymanManageController@inactive');
    Route::post('deliveryman/active', 'DeliverymanManageController@active');
    Route::post('deliveryman/delete', 'DeliverymanManageController@destroy');
    Route::post('deliveryman/changepassword', 'DeliverymanManageController@changepassword');
    Route::post('deliveryman/commission', 'DeliverymanManageController@commission');
    Route::post('deliveryman/defaultcommission', 'DeliverymanManageController@defaultcommission');
    
    // District route
     Route::get('/district/add','DistrictController@index');
     Route::post('/district/save','DistrictController@store');
     Route::get('/district/manage','DistrictController@manage');
     Route::get('/district/edit/{id}','DistrictController@edit');
     Route::post('/district/update','DistrictController@update');
     Route::post('/district/inactive','DistrictController@inactive');
     Route::post('/district/active','DistrictController@active');
     Route::post('/district/delete','DistrictController@destroy');
});


Route::group(['as'=>'editor.', 'prefix'=>'editor', 'namespace'=>'Editor','middleware'=>['auth', 'editor']], function(){
 // editor dashboard
 	Route::get('/dashboard', 'DashboardController@index')->name('dashboard');
 	Route::post('/bulkinvoice', 'DashboardController@bulkinvoice')->name('bulkinvoice');
 	Route::get('/reportForERP', 'DashboardController@reportForERP')->name('reportForERP');
 	Route::get('/report-collection', 'DashboardController@collectionReport');
 	Route::get('/custom-filter-data', 'DashboardController@getCustomFilterData');
 	Route::get('/getCustomFilter', 'DashboardController@getCustomFilter');
 	Route::get('/typeprepaid', 'DashboardController@typeprepaid');
 	Route::get('/postpaid', 'DashboardController@postpaid');
 	Route::get('/merchant-due-report', 'DashboardController@merchantduereport');
 	Route::get('/export', 'DashboardController@export')->name('export');
 	 // Banner route here
    Route::get('/parcel/create','ParcelManageController@create');
     Route::get('/parcel/bercode-invoice','ParcelManageController@invoiceBarcode');
        Route::get('/parcel/pickup-request','ParcelManageController@pickup_request');
        Route::get('/parcel/move-today/{id}','ParcelManageController@move_today');
    Route::get('/parcel/all-parcel','ParcelManageController@allparcel');
     Route::get('/parcel/archive-parcel','ParcelManageController@archiveparcel');
     Route::get('/parcel/archive-create','ParcelManageController@archiveparcel_create');
    Route::post('/marchant-percel','ParcelManageController@marchant_percel');
     Route::post('/parcel/partial_pay','ParcelManageController@partial_pay');
    Route::post('/parcel/store','ParcelManageController@parcelstore');
    Route::get('/parcel/edit/{id}','ParcelManageController@parceledit');
    Route::get('/parcel/restore/{id}','ParcelManageController@restore');
	Route::get('/parcel/insitedhaka-parcel','ParcelManageController@insitedhaka');
    Route::get('/parcel/outsitedhaka-parcel','ParcelManageController@outsitedhaka');
    //  bercode read
     Route::post('/parcel/track','ParcelManageController@track');
      Route::post('/parcel/invoicetrack','ParcelManageController@invoicetrack');
     Route::post('/parcel/rider','ParcelManageController@rider');
    Route::get('/parcel/extra','ParcelManageController@extra');
    Route::post('/parcel/update','ParcelManageController@parcelupdate');
    Route::get('/parcel/report','ParcelManageController@report');
    Route::get('/parcel/parcelreport','ParcelManageController@parcelreport');
 	Route::get('/nearestzone/manage','ParcelManageController@manage');
 	 Route::get('/deliverycharge/manage','ParcelManageController@dmanage');
 	 Route::get('agent/manage', 'ParcelManageController@amanage');
    //parcel manage
    Route::get('parcel/{slug}','ParcelManageController@parcel');
    Route::post('/parcel/import','ParcelManageController@import');
    Route::post('/parcel/statusimport','ParcelManageController@statusimport');
    Route::get('/processing/parcel','ParcelManageController@processing');
    Route::post('agent/asign','ParcelManageController@agentasign');
    Route::post('deliveryman/asign','ParcelManageController@deliverymanasign');
    Route::post('deliveryman/asign1','ParcelManageController@deliverymanasign1');
    Route::post('pickupman/asign','ParcelManageController@pickupmanasign');
    Route::post('/parcel/status-update','ParcelManageController@statusupdate');
     Route::post('/parcels/status-update','ParcelManageController@statusupdatetwo');
    Route::get('/parcel/invoice/{id}','ParcelManageController@invoice');
    Route::post('/parcel/delete/','ParcelManageController@deleteParcel');
    Route::get('/ohosogo/','ParcelManageController@ohosogo');
    // parcel Manage
    Route::get('/new/pickup','PickupManageController@newpickup');
    Route::get('/new/pickdrop','PickupManageController@pickdrop');
    Route::get('/pending/pickup','PickupManageController@pendingpickup');
    Route::get('/accepted/pickup','PickupManageController@acceptedpickup');
    Route::get('/cancelled/pickup','PickupManageController@cancelled');
    Route::post('pickup/agent/asign','PickupManageController@agentmanasign');
    Route::post('/pickup/status-update','PickupManageController@statusupdate');
     //  ================ website oparation =====================

     // Logo route here
    Route::get('/logo/create','LogoController@create');
    Route::post('/logo/store','LogoController@store');
    Route::get('/logo/manage','LogoController@manage');
    Route::get('/logo/edit/{id}','LogoController@edit');
    Route::post('/logo/update','LogoController@update');
    Route::post('/logo/inactive','LogoController@inactive');
    Route::post('/logo/active','LogoController@active');
    Route::post('/logo/delete','LogoController@destroy');

     // Banner route here
    Route::get('/banner/create','BannerController@create');
    Route::post('/banner/store','BannerController@store');
    Route::get('/banner/manage','BannerController@manage');
    Route::get('/banner/edit/{id}','BannerController@edit');
    Route::post('/banner/update','BannerController@update');
    Route::post('/banner/inactive','BannerController@inactive');
    Route::post('/banner/active','BannerController@active');
    Route::post('/banner/delete','BannerController@destroy');

    // Service route here
    Route::get('/service/create','ServiceController@create');
    Route::post('/service/store','ServiceController@store');
    Route::get('/service/manage','ServiceController@manage');
    Route::get('/service/edit/{id}','ServiceController@edit');
    Route::post('/service/update','ServiceController@update');
    Route::post('/service/inactive','ServiceController@inactive');
    Route::post('/service/active','ServiceController@active');
    Route::post('/service/delete','ServiceController@destroy');
    
    
         //Branch Manage Route 
    Route::get('/branch/add', 'BranchController@add');
    Route::post('/branch/save', 'BranchController@save');
    Route::get('/branch/edit/{id}', 'BranchController@edit');
    Route::post('/branch/update', 'BranchController@update');
    Route::get('/branch/manage', 'BranchController@manage');
    Route::post('/branch/inactive', 'BranchController@inactive');
    Route::post('/branch/active', 'BranchController@active');
    Route::post('/branch/delete', 'BranchController@destroy');

    // Feature Operation
    Route::get('/feature/create','FeatureController@create');
    Route::post('/feature/store','FeatureController@store');
    Route::get('/feature/manage','FeatureController@manage');
    Route::get('/feature/edit/{id}','FeatureController@edit');
    Route::post('/feature/update','FeatureController@update');
    Route::post('/feature/inactive','FeatureController@inactive');
    Route::post('/feature/active','FeatureController@active');
    Route::post('/feature/delete','FeatureController@destroy');

    // Price route here
    Route::get('price/create','PriceController@create');
    Route::post('price/store','PriceController@store');
    Route::get('price/manage','PriceController@manage');
    Route::get('price/edit/{id}','PriceController@edit');
    Route::post('price/update','PriceController@update');
    Route::post('price/inactive','PriceController@inactive');
    Route::post('price/active','PriceController@active');
    Route::post('price/delete','PriceController@destroy');

     Route::get('/social-media/add','SocialController@index');
     Route::post('/social-media/save','SocialController@store');
     Route::get('/social-media/manage','SocialController@manage');
     Route::get('/social-media/edit/{id}','SocialController@edit');
     Route::post('/social-media/update','SocialController@update');
     Route::post('/social-media/unpublished','SocialController@unpublished');
     Route::post('/social-media/published','SocialController@published');
     Route::post('/social-media/delete','SocialController@destroy');

    // merchant operation
    Route::get('/merchant-request/manage','MerchantOperationController@merchantrequest');
    Route::get('/merchant/manage','MerchantOperationController@manage');
     Route::get('/merchant/manage_offer','MerchantOperationController@manage_offer');
     Route::get('/merchant/merchantComplain','MerchantOperationController@merchantComplain');
     Route::get('/merchant/notifications','MerchantOperationController@notifications');
    Route::get('/merchant/edit/{id}','MerchantOperationController@profileedit');
    Route::post('merchant/profile/edit', 'MerchantOperationController@profileUpdate');
    Route::post('merchant/inactive','MerchantOperationController@inactive');
    Route::post('merchant/active','MerchantOperationController@active');
        Route::get('merchant/unpaid','MerchantOperationController@unpaid');

    Route::get('merchant/view/{id}','MerchantOperationController@view');
    Route::get('merchant/allparcel/{id}','MerchantOperationController@allparcelview');
    Route::get('merchant/abaileblance/{id}','MerchantOperationController@abaileblance');
    Route::post('merchant/discount','MerchantOperationController@discount');
    Route::post('merchant/merchantType','MerchantOperationController@merchantType');
    Route::post('merchant/merchantCod','MerchantOperationController@merchantCod');

    Route::post('merchant/create_offer','MerchantOperationController@create_offer');
    Route::post('merchant/create_nofification','MerchantOperationController@create_nofification');
    Route::get('merchant/changeStatus', 'MerchantOperationController@changeStatus')->name('changeStatus');
    Route::get('merchant/discount/delete/{id}','MerchantOperationController@discount_delete');
    Route::get('merchant/offer/delete/{id}','MerchantOperationController@offer_delete');
    Route::get('merchant/notification/delete/{id}','MerchantOperationController@notification_delete');
    Route::get('merchant/dis/{id}','MerchantOperationController@dis');
    Route::post('merchant/get/payment','MerchantOperationController@payment');
    Route::get('/merchant/payment/invoice/{id}','MerchantOperationController@paymentinvoice');
    Route::get('/merchant/payment/invoice-details/{id}','MerchantOperationController@inovicedetails');
    Route::get('/merchant/payment/dailyinvoice','MerchantOperationController@dailyinvoice');
    Route::post('/merchant/reply-complain', 'MerchantOperationController@replyComplain');
    
   // About route here
    Route::get('/about/create','AboutController@create');
    Route::post('/about/store','AboutController@store');
    Route::get('/about/manage','AboutController@manage');
    Route::get('/about/edit/{id}','AboutController@edit');
    Route::post('/about/update','AboutController@update');
    Route::post('/about/inactive','AboutController@inactive');
    Route::post('/about/active','AboutController@active');
    Route::post('/about/delete','AboutController@destroy');


    Route::get('/clientfeedback/create','ClientfeedbackController@create');
    Route::post('/clientfeedback/store','ClientfeedbackController@store');
    Route::get('/clientfeedback/manage','ClientfeedbackController@manage');
    Route::get('/clientfeedback/edit/{id}','ClientfeedbackController@edit');
    Route::post('/clientfeedback/update','ClientfeedbackController@update');
    Route::post('/clientfeedback/inactive','ClientfeedbackController@inactive');
    Route::post('/clientfeedback/active','ClientfeedbackController@active');
    Route::post('/clientfeedback/delete','ClientfeedbackController@destroy');

    // career
    Route::get('career/create','CareerController@create');
    Route::post('career/store','CareerController@store');
    Route::get('career/manage','CareerController@manage');
    Route::get('career/edit/{id}','CareerController@edit');
    Route::post('career/update','CareerController@update');
    Route::post('career/inactive','CareerController@inactive');
    Route::post('career/active','CareerController@active');
    Route::post('career/delete','CareerController@destroy');  

    // notice
    Route::get('notice/create','NoticeController@create');
    Route::post('notice/store','NoticeController@store');
    Route::get('notice/manage','NoticeController@manage');
    Route::get('notice/edit/{id}','NoticeController@edit');
    Route::post('notice/update','NoticeController@update');
    Route::post('notice/inactive','NoticeController@inactive');
    Route::post('notice/active','NoticeController@active');
    Route::post('notice/delete','NoticeController@destroy');

    // Gallery
    Route::get('gallery/create','GalleryController@create');
    Route::post('gallery/store','GalleryController@store');
    Route::get('gallery/manage','GalleryController@manage');
    Route::get('gallery/edit/{id}','GalleryController@edit');
    Route::post('gallery/update','GalleryController@update');
    Route::post('gallery/inactive','GalleryController@inactive');
    Route::post('gallery/active','GalleryController@active');
    Route::post('gallery/delete','GalleryController@destroy');

});

 Route::group(['as'=>'author.', 'prefix'=>'author', 'namespace'=>'Author','middleware'=>['auth', 'author']], function(){
 Route::get('/dashboard', 'DashboardController@index')->name('dashboard');
 Route::get('hub/report', 'DashboardController@hubreport');
 Route::get('asign/report', 'DashboardController@asingreport');
  Route::get('parcel/report', 'DashboardController@parcelreport');
    Route::get('marchent/report', 'DashboardController@report');
});

Route::group(['as'=>'sagent.', 'prefix'=>'sagent', 'namespace'=>'sagent','middleware'=>['auth', 'sagent']], function(){  
    Route::get('/dashboard', 'SecratAgentController@index')->name('dashboard');
    Route::get('/merchant', 'SecratAgentController@marchant')->name('merchant');
    Route::get('/withdrawal', 'SecratAgentController@withdrawal')->name('withdrawal');
    Route::post('/withdrawal-request', 'SecratAgentController@withdrawal_request');

   });

// Route::get('agent/report', 'AgentController@report');
// Route::get('hub/report', 'AgentManageController@hubreport');
Route::get('password/change', 'ChangePassController@index');
Route::post('password/updated', 'ChangePassController@updated');
});










Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
