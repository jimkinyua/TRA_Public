<?php

class HomeController extends BaseController {

	/*
	|--------------------------------------------------------------------------
	| Default Home Controller
	|--------------------------------------------------------------------------
	|
	| You may wish to use controllers instead of, or in addition to, Closure
	| based routes. That's great! Here is an example controller method to
	| get you started. To route to this controller, just add the route:
	|
	|	Route::get('/', 'HomeController@showWelcome');
	|
	*/

	public function showWelcome()
	{
		return View::make('hello');
	}

	public function index(){
		//return Redirect::route('get.setup');
		return View::make('home');
	}

	public function launchSetup()
	{
		return View::make('setup');
	}

	public function saveSetup(){
		$rules = [
			'driver'=>'required',
			'server'=>'required',
			'database'=>'required',
			'user'=>'required',
		];
		$valid = Validator::make(Input::all(),$rules);
		if ($valid->passes()){
			Config::write('install.driver',Input::get('driver'));
			Config::write('install.server',Input::get('server'));
			Config::write('install.database',Input::get('database'));
			Config::write('install.user',Input::get('user'));
			Config::write('install.password',Input::get('password'));

			try{
				DB::connection()->getDatabaseName();
				Session::flash('success_msg','Database connection successful');
				return Redirect::to('/login');
			}catch(Exception $e){
				Session::flash('error_msg',$e->getMessage());
				return Redirect::route('get.setup');
			}


		}
		
		return Redirect::route('get.setup')
			->withErrors($valid);
	}

	public function showApplications(){

		return View::make('services.applications');
	}

	public function approvedApplications(){

		return View::make('services.approved_applications');
	}

	public function showPermits(){
		return View::make('services.permits');
	}

	public function showBills()
	{
		return View::make('services.bills');
	}

	public function showInvoice($id)
	{
		//dd($id);
		$invoice = Invoice::findOrFail($id);

		return View::make('services.invoice',['invoice'=>$invoice]);
	}

	public function downloadPermit($id)
	{
		$permit = Application::findOrFail($id);
		$file = HTML::link('http://localhost/documents/'.$permit->PermitNo.'.pdf');
		return Response::download($file);
	}

}
