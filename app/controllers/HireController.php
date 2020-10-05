<?php

class HireController extends Controller {

  public function services() {
    return View::make('hire.index');
  }

  public function stadia() {
    return View::make('hire.stadia');
  }

  public function premises() {
    $premises = DB::table('Services')->where('ServiceCategoryID', 86)->lists('ServiceLabel','ServiceID');
    return View::make('hire.premises', ['premises' => $premises ]);
  }

  public function equipment() {
    $equipment = DB::table('Services')->where('ServiceCategoryID', 87)->lists('ServiceLabel','ServiceID');
    return View::make('hire.equipment', ['equipment' => $equipment ]);
  }

  # sci >> service category id
  public function purposes() {
    $id = Input::get('sci');
    $purposes =  DB::table('Services')->where('ServiceCategoryID', $id)->lists('ServiceLabel', 'ServiceId');
    return  $purposes;
  }
  # receive application for hiring stadium
  public function stadium() {

    $app = new Application();

    $app->FormID = 7;
    $app->ServiceStatusID = 1;
    $app->CustomerID = Session::get('customer')->CustomerID;
    $app->ServiceID = Input::get('service');
    $app->SubmissionDate = date('Y-m-d H:i:s');
    $app->save();

    $columns = [
      104 => Input::get('stadium'),
      105 => Input::get('service'),
      106 => Input::get('start'),
      107 => Input::get('end'),
    ];

    foreach($columns as $key => $value) {
        $params['ServiceHeaderID'] = $app->id();
        $params['ColumnID'] = $key;
        $params['Value'] = $value;

        Api::AddFormData($params);
    }

    Session::flash('success_msg','Application sent successfully');
    return Redirect::route('portal.dashboard');
  }
  # receive application for hiring premises
  public function premise() {

    $app = new Application();

    $app->FormID = 7;
    $app->ServiceStatusID = 1;
    $app->CustomerID = Session::get('customer')->CustomerID;
    $app->ServiceID = Input::get('service');
    $app->SubmissionDate = date('Y-m-d H:i:s');
    $app->save();

    $columns = [
      104 => Input::get('service'),
      105 => Input::get('purpose'),
      106 => Input::get('start'),
      107 => Input::get('end'),
    ];

    foreach($columns as $key => $value) {
        $params['ServiceHeaderID'] = $app->id();
        $params['ColumnID'] = $key;
        $params['Value'] = $value;

        Api::AddFormData($params);
    }

    Session::flash('success_msg','Application sent successfully!');
    return Redirect::route('portal.dashboard');
  }
  # receive application for hiring equipment
  public function article() {

    $app = new Application();

    $app->FormID = 7;
    $app->ServiceStatusID = 1;
    $app->CustomerID = Session::get('customer')->CustomerID;
    $app->ServiceID = Input::get('article');
    $app->SubmissionDate = date('Y-m-d H:i:s');
    $app->save();

    $columns = [
      104 => Input::get('article'),
      106 => Input::get('start'),
      107 => Input::get('end'),
    ];

    foreach($columns as $key => $value) {
        $params['ServiceHeaderID'] = $app->id();
        $params['ColumnID'] = $key;
        $params['Value'] = $value;

        Api::AddFormData($params);
    }

    Session::flash('success_msg','Application sent successfully!');
    return Redirect::route('portal.dashboard');
  }

  public function applications() {
    $data = DB::table('ServiceHeader')
      ->select(['ServiceHeader.ServiceHeaderID',
            'Services.ServiceName',
            'ServiceHeader.CreatedDate as Date',
            'ServiceStatus.ServiceStatusDisplay'
            ])
      ->where('ServiceHeader.CustomerID', intval(Session::get('customer')->CustomerID))
      ->where('Services.ServiceGroupID', 21)
      //->join('Customer','Customer.CustomerID','=','ServiceHeader.CustomerID')
      ->join('Services','Services.ServiceID','=','ServiceHeader.ServiceID')
      ->join('ServiceStatus','ServiceStatus.ServiceStatusID','=','ServiceHeader.ServiceStatusID')
      ->get();
    return View::make('hire.applications', ['applications'=> $data]);

  }

}
