<?php

# refactory
Route::group([], function() 
{
	//Route::get('zones',['as'=>'service.charges','uses'=> 'DashboardController@zones']);
	Route::get('charges',['as'=>'service.charges','uses'=> 'DashboardController@charges']);
	Route::get('login',['as'=>'portal.login','uses'=> 'AuthenticationController@login']);
	Route::get('logout', ['as' => 'portal.logout', 'uses' => 'AuthenticationController@logout']);
	Route::post('login', ['as' => 'portal.post.login', 'uses' => 'AuthenticationController@postLogin']);
	Route::get('register', ['as' => 'portal.get.register', 'uses' => 'AuthenticationController@getRegister']);
	Route::post('register', ['as'=>'portal.post.register', 'uses' => 'AuthenticationController@postRegister']);
	Route::get('activate/{code}', [ 'as'=> 'activate.portal.user', 'uses'=>'AuthenticationController@activate']);
	Route::get('financebill', ['as' => 'portal.services', 'uses' => 'DashboardController@services']);
	Route::get('servicecategory/{cat}', ['as' => 'portal.category', 'uses' => 'DashboardController@category']);

	Route::get('searchupn/{upn}', ['as' => 'portal.searchupn', 'uses' => 'DashboardController@searchupn']);
	Route::get('searchland/{lrn}/{pno}/{upn?}', ['as' => 'portal.searchland', 'uses' => 'DashboardController@searchland']);
	Route::get('searchinvoice/{id}', ['as' => 'portal.searchinvoice', 'uses' => 'DashboardController@searchinvoice']);
	Route::get('searchwards/{subcounty}', ['as' => 'portal.searchwards', 'uses' => 'DashboardController@searchwards']);
	Route::get('searchzones/{ward}', ['as' => 'portal.searchzones', 'uses' => 'DashboardController@searchzones']);

//	Route::get('showgroups', ['as' => 'portal.showgroups', 'uses' => 'DashboardController@showgroups']);
	Route::get('showservices', ['as' => 'portal.showservices', 'uses' => 'DashboardController@showservices']);
//	Route::get('showcategories', ['as' => 'portal.showcategories', 'uses' => 'DashboardController@showcategories']);


	Route::group(['before'=>'auth'],function(){

		Route::get('miscpay', [ 'as' => 'get.miscpay', 'uses' => 'DashboardController@getmiscpay']);
		Route::post('miscpay', [ 'as' => 'submit.miscpay', 'uses' => 'DashboardController@postmiscpay']);

		Route::get('/', ['as' => 'portal.home', 'uses' => 'DashboardController@home']);
		Route::get('viewpermit/{id}', ['as' => 'permits.view', 'uses' => 'DashboardController@viewpermit']);
		Route::get('viewreceipt/{ihid}', ['as' => 'receipt.view', 'uses' => 'DashboardController@viewreceipt']);
		
		Route::get('viewreceipt_2/{rid}/{hid}', ['as' => 'receipt_2.view', 'uses' => 'DashboardController@viewreceipt_2']);
		Route::get('viewreceipt_2/{rid}/{hid}', ['as' => 'renewalreceipt_2.view', 'uses' => 'DashboardController@viewrenewalreceipt_2']);

		Route::get('viewreceipts/{cid}', ['as' => 'receipts.view', 'uses' => 'DashboardController@viewreceipts']);

		Route::get('payment/{inv}', ['as' => 'portal.payment', 'uses' => 'DashboardController@payment']);
		Route::post('payment', ['as' => 'portal.post.payment', 'uses' => 'DashboardController@postPayment']);
		Route::get('payments', ['as' => 'aggregate.payments', 'uses' => 'DashboardController@aggregatePayment']);
		Route::get('landreports', ['as' => 'portal.reports', 'uses' => 'DashboardController@reports']);
		Route::get('landreport/{id}', ['as' => 'portal.report', 'uses' => 'DashboardController@report']);

		Route::get('receipt/{id}', ['as' => 'portal.receipt', 'uses' => 'DashboardController@receipt']);
		Route::post('receipt', ['as' => 'portal.post.receipt', 'uses' => 'DashboardController@postReceipt']);

		Route::get('categoryservices/{cat}', ['as' => 'portal.category.services', 'uses' => 'DashboardController@categoryservices']);

		Route::get('userprofile', [ 'as' => 'user.profile','uses'=>'UsersController@userProfile']);
		Route::post('userprofile', [ 'as' => 'update.user.profile','uses'=>'UsersController@updateUserProfile']);
		Route::get('updatepassword', [ 'as' => 'user.password','uses'=>'UsersController@userPassword']);
		Route::post('updatepassword', [ 'as' => 'update.user.password','uses'=>'UsersController@updateUserPassword']);

		Route::get('accounts/{cid}', ['as' => 'portal.accounts', 'uses' => 'DashboardController@accounts']);
		Route::post('accounts/{cid}', ['as' => 'portal.get.accounts', 'uses' => 'DashboardController@accounts_search']);

		Route::get('account', ['as' => 'portal.account', 'uses' => 'SettingsController@account']);
		Route::get('profile',['as'=>'my.profile','uses'=>'UsersController@showMyProfile']);
		Route::get('businessprofile/{cid}',['as'=>'business.profile','uses'=>'UsersController@businessProfile']);
		Route::post('businessprofile',['as'=>'update.business.profile','uses'=>'BusinessController@postUpdateBusiness']);
		# dashboard menu
		Route::get('home', ['as' => 'portal.home', 'uses' => 'DashboardController@home']);
		Route::get('manage', ['as' => 'portal.manage', 'uses' => 'DashboardController@manage']);
		Route::get('support', ['as' => 'portal.support', 'uses' => 'DashboardController@support']);
		Route::get('dashboard', ['as' => 'portal.dashboard', 'uses' => 'DashboardController@home']);
		Route::get('settings', ['as' => 'portal.settings', 'uses' => 'DashboardController@settings']);
		Route::get('switch/{id}', ['as' => 'switch.account', 'uses' => 'DashboardController@swap']);
		Route::get('backend', ['as' => 'redirect.away', 'uses' => 'DashboardController@backend']);
		Route::get('businesses', ['as'=>'list.businesses','uses'=>'DashboardController@businesses']);

		Route::get('applications', ['as' => 'all.applications', 'uses' => 'ApplicationsController@all']);
		Route::get('renewals', ['as' => 'allrenewals.applications', 'uses' => 'ApplicationsController@allrenewals']);
		//
		Route::get('sbp/{id}', ['as' => 'view.permit', 'uses' => 'ApplicationsController@sbp']);
		Route::get('application/{id}', ['as' => 'view.application', 'uses' => 'ApplicationsController@show']);
		Route::get('statement/{lrn}/{plotno}/{authority}/{upn}', ['as' => 'view.statement', 'uses' => 'ApplicationsController@statement']);
		Route::get('applications/{cat}', [ 'as' => 'grouped.applications', 'uses' => 'ApplicationsController@grouped']);
		Route::post('application', [ 'as' => 'update.application', 'uses' => 'ApplicationsController@update']);
		Route::post('application', ['as' => 'submit.application', 'uses' => 'ApplicationsController@apply']);
		Route::post('applications', ['as' => 'submit.renewal', 'uses' => 'ApplicationsController@renew']);
		Route::get('applicationform/{cat}', [ 'as' => 'application.form', 'uses' => 'DashboardController@applicationform']);
		Route::get('licences/{cat}', [ 'as' => 'grouped.licences', 'uses' => 'ApplicationsController@licences']);
		Route::get('renewlicence/{cat}', [ 'as' => 'grouped.renewal', 'uses' => 'ApplicationsController@renewlicence']);
		Route::get('viewlicence/{cat}', [ 'as' => 'view.licence', 'uses' => 'ApplicationsController@viewlicence']);
		Route::get('viewrenewal/{cat}', [ 'as' => 'view.renewal', 'uses' => 'ApplicationsController@viewrenewal']);

		//
		Route::get('registerbusiness', ['as' => 'dashboard.business', 'uses' => 'DashboardController@registerBusiness']);
		Route::post('registerbusiness',['as'=>'post.add.business','uses'=>'BusinessController@postAddBusiness']);
		Route::post('addvehicle/{id}',['as'=>'post.add.vehicle','uses'=>'BusinessController@postAddVehicle']);
		Route::get('viewbusiness/{id}', ['as' => 'dashboard.view.business', 'uses' => 'DashboardController@viewBusiness']);

		Route::get('invoice/{hid}', ['as' => 'application.invoice', 'uses' => 'ApplicationsController@invoice']);
		Route::get('viewinvoice/{hid}', ['as' => 'application.viewinvoice', 'uses' => 'ApplicationsController@viewinvoice']);
		Route::get('renewalinvoice/{hid}', ['as' => 'application.renewalinvoice', 'uses' => 'ApplicationsController@renewalinvoice']);

		//
		Route::get('invoicepdf/{hid}', ['as' => 'application.invoicepdf', 'uses' => 'ApplicationsController@invoicepdf']);
		Route::get('licencerenewalinvoicepdf/{hid}', ['as' => 'application.licencerenewalinvoicepdf', 'uses' => 'ApplicationsController@licencerenewalinvoicepdf']);

		Route::get('invoices', ['as' => 'application.invoices', 'uses' => 'ApplicationsController@invoices']);
		Route::get('renewalinvoices', ['as' => 'application.renewalinvoices', 'uses' => 'ApplicationsController@renewalinvoices']);
		Route::get('receipts/{hid}', ['as' => 'application.receipts', 'uses' => 'ApplicationsController@receipts']);
		Route::get('renewalreceipts/{hid}', ['as' => 'application.renewalreceipts', 'uses' => 'ApplicationsController@renewalreceipts']);

		Route::get('agent',['as'=>'get.business.agent','uses'=>'BusinessController@getAgent']);
		Route::post('agent',['as'=>'post.business.agent','uses'=>'BusinessController@postAgent']);

		Route::group(['prefix'=>'businesses'],function(){
			Route::get('',['as'=>'list.businesses','uses'=>'DashboardController@businesses']);
			Route::get('add',['as'=>'get.add.business','uses'=>'BusinessController@getAddBusiness']);
			Route::get('show/{id}', [ 'as'=>'view.business','uses' => 'BusinessController@showBusiness']);
			Route::get('show/{id}', [ 'as'=>'view.business','uses' => 'DashboardController@viewBusiness']);
		});

		Route::group(['prefix'=>'business'],function(){
			//Route::get('',['as'=>'list.businesses','uses'=>'BusinessController@list']);
			Route::get('',['as'=>'list.businesses','uses'=>'BusinessController@index']);
			//Route::post('add',['as'=>'add.business','uses'=>'BusinessController@postAddBusiness']);
			Route::get('{id}/show',['as'=>'view.biz','uses'=>'BusinessController@showBusiness']);
		});

	});

});

Route::group(['prefix' => 'v1'], function() {
	Route::get('install',['as'=>'get.setup','uses'=>'HomeController@launchSetup']);
	Route::post('install',['as'=>'post.setup','uses'=>'HomeController@saveSetup']);
	Route::get('login',['as'=>'get.login','uses'=>'UsersController@getLoginForm']);
	Route::post('login',['as'=>'post.login','uses'=>'UsersController@postLogin']);
	Route::get('register',['as'=>'get.register','uses'=>'UsersController@getRegistrationForm']);
	Route::post('register',['as'=>'post.register','uses'=>'UsersController@postRegister']);
	Route::get('security/{activationCode}/activate',['as'=>'activate.user','uses'=>'UsersController@activate'] );
	Route::get('security/change',['as'=>'get.change.password','uses'=>'AuthenticationController@getChangePassword'] );
	Route::post('security/change',['as'=>'post.change.password','uses'=>'AuthenticationController@changePassword'] );
	Route::any('logout',['as'=>'logout','uses'=>'UsersController@logout']);

	Route::get('demo', ['as' => 'baringo.demo', 'uses' => 'DemoController@baringo']);

	Route::group(['before'=>'auth'],function(){
		Route::get('/',['as'=>'home','uses'=>'HomeController@index']);
		Route::get('/dash',['as'=>'dash','uses'=>'HomeController@index']);

		Route::group(['prefix'=>'account'],function(){
			Route::get('users',['as'=>'list.users','uses'=>'UsersController@getUsersList']);
			Route::get('add',['as'=>'get.add.user','uses'=>'UsersController@getAddAccount']);
			Route::post('add',['as'=>'add.user','uses'=>'UsersController@postAddAccount']);
			//Route::get('profile',['as'=>'my.profile','uses'=>'UsersController@showMyProfile']);
			Route::get('{id}/profile',['as'=>'view.profile','uses'=>'UsersController@showUserProfile']);
		});

		Route::group(['prefix'=>'services'],function(){
			Route::get('',['as'=>'list.departments','uses'=>'ServicesController@index']);
			Route::get('{id}/list',['as'=>'list.services','uses'=>'ServicesController@getServices']);
			Route::get('apply',['as'=>'get.apply.service','uses'=>'ServicesController@getApplyForm']);
			Route::get('houserent',['as'=>'get.houserent','uses'=>'ServicesController@getHouserent']);
			Route::get('stalls',['as'=>'get.stalls','uses'=>'ServicesController@getStalls']);
			Route::post('stalls',['as'=>'fetch.stalls','uses'=>'ServicesController@fetchStalls']);
			Route::get('landrates',['as'=>'get.landrates','uses'=>'ServicesController@getLandrates']);
			Route::post('apply',['as'=>'post.apply.service','uses'=>'ServicesController@postApplyForm']);
			Route::post('search_land',['as'=>'search.land','uses'=>'ServicesController@searchLand']);
			Route::post('search_housing',['as'=>'search.housing','uses'=>'ServicesController@searchHousing']);
		});

		Route::group(['prefix'=>'applications'],function(){
			//Route::get('',['as'=>'my.applications','uses'=>'HomeController@showApplications']);
			Route::get('current',['as'=>'approved.applications','uses'=>'HomeController@approvedApplications']);
		});

		Route::group(['prefix'=>'bills'],function(){
			Route::get('',['as'=>'my.bills','uses'=>'HomeController@showBills']);
			Route::get('{id}/invoice',['as'=>'my.invoice','uses'=>'HomeController@showInvoice']);
		});

		Route::group(['prefix'=>'rentals'],function(){
			Route::get('',['as'=>'rental','uses'=>'StallsController@index']);
			Route::get('stalls',['as'=>'stall.registration','uses'=>'StallsController@register']);
			Route::post('stalls',['as'=>'stall.submit.registration','uses'=>'StallsController@submitRegistration']);
		});

		Route::group(['prefix' => 'zones'], function(){
			Route::get('list', ['as' => 'zones_list', 'uses' => 'ZonesController@getList' ]);
			Route::get('', ['as' => 'zones', 'uses' => 'AuthenticationController@index' ]);
		});


		//ajax table actions
		Route::group(['prefix'=>'api'],function()
		{
			Route::get('users',['as'=>'list.users.ajax','uses'=>'AjaxController@getUsers']);
			Route::get('biz',['as'=>'list.businesses.ajax','uses'=>'AjaxController@getBusinesses']);
			Route::get('apps',['as'=>'list.applications.ajax','uses'=>'AjaxController@getApplications']);
			Route::get('current',['as'=>'list.approved_applications.ajax','uses'=>'AjaxController@approvedApplications']);
			Route::get('bills',['as'=>'list.bills.ajax','uses'=>'AjaxController@getInvoices']);
			Route::get('invoice/{id}/pay',['as'=>'pay.invoice','uses'=>'PaymentController@getPaymentStatus']);
			Route::any('estate/house',['as'=>'get.houses','uses'=>'ServicesController@fetchEstateHouses']);
			Route::any('subcounty/wards',['as'=>'get.wards','uses'=>'ServicesController@fetchWards']);
			Route::any('ui/filter',['as'=>'filter.select','uses'=>'ServicesController@filterSelect']);
			Route::any('',['as'=>'update','uses'=>'ServicesController@update']);
		});
	});

});


# land rates services
Route::group(['prefix' => 'land'], function() {
	Route::get('pay', ['as' => 'land.pay', 'uses' => 'LandController@pay']);
	Route::get('plots', ['as' => 'land.plots', 'uses' => 'LandController@plots']);
	Route::get('', ['as' => 'land.services', 'uses' => 'LandController@services']);
	Route::get('search', ['as' => 'land.search', 'uses' => 'LandController@search']);
	Route::get('invoice/{id}', ['as' => 'land.invoice', 'uses' => 'LandController@invoice']);
	Route::get('register', ['as' => 'land.registration', 'uses' => 'LandController@register']);
	Route::post('search', ['as' => 'land.post.search', 'uses' => 'LandController@submitSearch']);
	Route::post('register', ['as' => 'land.submit.registration', 'uses' => 'LandController@submitRegistration']);
});

# signage services
Route::group(['prefix' => 'signage'], function() {
	//Route:get('apply', ['as' => 'signage.apply', 'uses' => 'SignageController@apply']);
	Route::get('', ['as' => 'signage.services', 'uses'  =>  'SignageController@services']);
	Route::get('charges', ['as' => 'signage.charges', 'uses' => 'SignageController@charges']);
	Route::get('applications', ['as' => 'signage.applications', 'uses' => 'SignageController@applications']);
	Route::post('application', ['as' => 'signage.submit.application', 'uses' => 'SignageController@submitApplication']);
});

# building services
Route::group(['prefix' => 'building'], function() {
	Route::get('', ['as' => 'building.services', 'uses'  =>  'BuildingController@services']);
	Route::get('fencing', ['as' => 'building.fencing', 'uses' => 'BuildingController@fencing']);
	Route::get('approval', ['as' => 'building.approval', 'uses' => 'BuildingController@approval']);
	Route::get('occupation', ['as' => 'building.occupation', 'uses' => 'BuildingController@occupation']);
	Route::post('approval', ['as' => 'building.submit.approval', 'uses' => 'BuildingController@submitApproval']);
});

# housing services
Route::group(['prefix' => 'housing'], function() {
	Route::get('home', ['as' => 'housing.home', 'uses' => 'HousingController@home']);
	Route::get('stall', ['as' => 'housing.stall', 'uses' => 'HousingController@stall']);
	Route::get('', ['as' => 'housing.services', 'uses'  =>  'HousingController@services']);
	Route::get('applications', ['as' => 'housing.applications', 'uses' => 'HousingController@applications']);
	Route::post('house', ['as' => 'housing.house.application', 'uses' => 'HousingController@houseApplication']);
	Route::post('stall', ['as' => 'housing.stall.application', 'uses' => 'HousingController@stallApplication']);
});

# settings
Route::group(['prefix' => 'settings'], function() {
	Route::get('', ['as' => 'settings.services', 'uses' => 'SettingsController@services']);
	Route::get('account', ['as' => 'settings.account', 'uses' => 'SettingsController@account']);
});

# hire
Route::group(['prefix' => 'hire'], function() {
	Route::get('', ['as' => 'hire.services', 'uses' => 'HireController@services']);
	Route::get('stadia', ['as' => 'hire.stadia', 'uses' => 'HireController@stadia']);
	Route::post('stadium', ['as' => 'hire.stadium', 'uses' => 'HireController@stadium']);
	Route::post('premise', ['as' => 'hire.premise', 'uses' => 'HireController@premise']);
	Route::post('article', ['as' => 'hire.article', 'uses' => 'HireController@article']);
	Route::get('premises', ['as' => 'hire.premises', 'uses' => 'HireController@premises']);
	Route::post('purposes', ['as' => 'hire.purposes', 'uses' => 'HireController@purposes']);
	Route::get('equipment', ['as' => 'hire.equipment', 'uses' => 'HireController@equipment']);
	Route::get('applications', ['as' => 'hire.applications', 'uses' => 'HireController@applications']);
});

# permits
Route::group(['prefix' => 'permits'], function() {
	Route::get('apply', ['as' => 'permits.apply', 'uses' => 'PermitsController@apply']);
	Route::get('index', ['as' => 'permits.index', 'uses' => 'PermitsController@index']);
	Route::get('renew', ['as' => 'permits.renew', 'uses' => 'PermitsController@renew']);
	Route::get('', ['as' => 'permits.services', 'uses' => 'PermitsController@services']);
	Route::get('extend/{id}', ['as' => 'permits.extend', 'uses' => 'PermitsController@extend']);
	Route::post('application', ['as' => 'permits.submit.application', 'uses' => 'PermitsController@submitApplication']);
});

Route::group(['prefix' => 'lease'], function() {
	Route::get('', ['as' => 'request.lease.contract', 'uses' => 'LeaseController@requestContract']);
	Route::post('', ['as' => 'submit.lease.contract', 'uses' => 'LeaseController@submitContract']);
});



# weights
Route::group(['prefix' => 'weights'], function() {
	Route::get('', ['as' => 'weights.services', 'uses' => 'WeightsController@services']);
	Route::get('apply', ['as' => 'weights.apply', 'uses' => 'WeightsController@apply']);
	Route::get('charges', ['as' => 'weights.charges', 'uses' => 'WeightsController@charges']);
	Route::get('applications', ['as' => 'weights.applications', 'uses' => 'WeightsController@applications']);
	Route::post('application', ['as' => 'weights.submit.application', 'uses' => 'WeightsController@submitApplication']);
});



Route::group(['prefix'=>'api'],function(){
	Route::get('departments', [ 'as' => 'api.departments', 'uses' => 'ApiController@departments' ]);
	Route::get('categories/{department}', [ 'as' => 'api.categories', 'uses'=>'ApiController@categories']);
});
