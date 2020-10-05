<?php

/*
|--------------------------------------------------------------------------
| Register The Laravel Class Loader
|--------------------------------------------------------------------------
|
| In addition to using Composer, you may use the Laravel class loader to
| load your controllers and models. This is useful for keeping all of
| your classes in the "global" namespace without Composer updating.
|
*/

ClassLoader::addDirectories(array(

	app_path().'/commands',
	app_path().'/controllers',
	app_path().'/models',
	app_path().'/libs',
	app_path().'/database/seeds',

));

/*
|--------------------------------------------------------------------------
| Application Error Logger
|--------------------------------------------------------------------------
|
| Here we will configure the error logger setup for the application which
| is built on top of the wonderful Monolog library. By default we will
| build a basic log file setup which creates a single file for logs.
|
*/

Log::useFiles(storage_path().'/logs/laravel.log');

/*
|--------------------------------------------------------------------------
| Application Error Handler
|--------------------------------------------------------------------------
|
| Here you may handle any errors that occur in your application, including
| logging them or displaying custom views for specific errors. You may
| even register several error handlers to handle different types of
| exceptions. If nothing is returned, the default error view is
| shown, which includes a detailed stack trace during debug.
|
*/

App::error(function(Exception $exception, $code)
{
	Log::error($exception);

	#return View::make('500');
});

App::missing(function(){
	return View::make('404');
});
/*
|--------------------------------------------------------------------------
| Maintenance Mode Handler
|--------------------------------------------------------------------------
|
| The "down" Artisan command gives you the ability to put an application
| into maintenance mode. Here, you will define what is displayed back
| to the user if maintenance mode is in effect for the application.
|
*/

App::down(function()
{
	return Response::make("Be right back!", 503);
});

/*
|--------------------------------------------------------------------------
| Require The Filters File
|--------------------------------------------------------------------------
|
| Next we will load the filters file for the application. This gives us
| a nice separate location to store our route and application filter
| definitions instead of putting them all in the main routes file.
|
*/

require app_path().'/filters.php';

Event::listen('auth.login', function($agent) {
	$id = CustomerAgent::where('AgentID', $agent->id())->where('AgentRoleID', 1)->pluck('CustomerID');
	//dd($agent);
	try {
		$user = Customer::findOrFail($id);		
	} catch (Exception $e) {		
		Auth::logout();
		Session::flash('message','Could not retrieve the account');
		return Redirect::route('portal.login');
	}finally{
		
		
	}

	//customer is the customer account the logged in agent represents
	Session::set('customer', $user);

	$customers = DB::table('CustomerAgents')
		->where('AgentID', $agent->id())
		->join('Customer', 'Customer.CustomerID', '=', 'CustomerAgents.CustomerID')
		->get(['Customer.CustomerName', 'Customer.CustomerID', 'Customer.Type']);

	// customers is a list of accounts that the logged in agent can represent
	Session::set('customers', $customers);


	$county = DB::table('UserRoles')
		->where('UserID', $agent->id())
		->get();

	//admin user agents are county employees
	if($county) {
		Session::set('county', true);
	} else {
		Session::set('county', false);
	}


});
