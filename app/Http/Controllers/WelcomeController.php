<?php

namespace App\Http\Controllers;

use App\Ward;
use Illuminate\Http\Request;

class WelcomeController extends Controller {

    public function __construct()    {
        //$this->middleware('guest');
    }

    public function wards() {
      $wards = Ward::where('subcounty_id', \Input::get('subcounty'))->get();
      return $wards;
    }

    public function index()   {
      return view('welcome');
    }

    public function bill()   {
      return view('bill');
    }

    public function services(Request $request)   {
      if( $request->get('department') ) {
        $services = \App\Service::filterByDepartment($request->get('department'))->get();
        $departments = \App\Department::where('id', $request->get('department'))->get();
      } elseif( $request->get('domain') ) {
        $domain = \App\Domain::findOrfail($request->get('domain'));
        $services = $domain->services()->get();
        $departments = $domain->departments()->get();
      } else {
        $services = \App\Service::all();
        $departments = \App\Department::all();
      //dd($request);
      }
      return view('dashboard.services', ['services' => $services, 'departments' => $departments ]);
    }

    public function service($id)   {
      $service = \App\Service::findOrFail($id);
      if (is_null($service)) {
        return redirect('dashboard/services');
      }
      return view('dashboard.service', [ 'service' => $service ]);
    }
}
