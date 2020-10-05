<?php

class WeightsController extends Controller {

  public function services() {
    return Redirect::route('weights.apply');
  }

  public function apply() {

    $formID = 11;
    $serviceID = 1348;
    $form = ServiceForm::findOrFail($formID);

    return View::make('weights.apply', [ 'ServiceID'=> $serviceID, 'form'=> $form ]);
  }

  public function submitApplication() {
    $input = Input::all();

    $rules = [
        'ColumnID.125' => 'required|string',
        'ColumnID.159' => 'required|string',
        'ColumnID.126' => 'required|string',
        'ColumnID.160' => 'required|string',
        'ColumnID.128' => 'required|string'
    ];
    $msgs = [
      'ColumnID.125.required' => 'Type of Equipment is required.',
      'ColumnID.125.string' => 'Type of Equipment may contain only letters.',

      'ColumnID.159.required' => 'Capacity of the equipment is required.',
      'ColumnID.159.string' => 'Capacity of the equipment may only contain letters.',

      'ColumnID.126.required' => "Applicant's Name is required.",
      'ColumnID.126.string' => "Applicant's Name may only contain letters.",

      'ColumnID.160.required' => "Owner's Nameis required.",
      'ColumnID.160.string' => "Owner's Name may only contain letters.",

      'ColumnID.128.required' => 'Business Location Nameis required.',
      'ColumnID.128.string' => 'Business Location Name may only contain letters.',
    ];

    $valid = Validator::make(Input::all(),$rules, $msgs);
    if ($valid->fails()){
        return Redirect::back()->withErrors($valid);
    }

    $app = new Application();

    $app->FormID = 10;
    $app->ServiceStatusID = 1;
    $app->CustomerID = Session::get('customer')->CustomerID;
    //$app->ServiceID = Input::get('service');
    $app->ServiceID = 1192;
    $app->SubmissionDate = date('Y-m-d H:i:s');

    $app->save();

    $columns = Input::get('ColumnID');

    foreach($columns as $key => $value)
    {
        $params['ServiceHeaderID'] = $app->id();
        $params['ColumnID'] = $key;
        $params['Value'] = $value;

        Api::AddFormData($params);

    }

    Session::flash('success_msg','Application sent successfully');
    return Redirect::route('portal.dashboard');

  }

  public function applications() {
    $data = DB::table('ServiceHeader')
      ->select(['ServiceHeader.ServiceHeaderID',
            'ServiceHeader.PermitNo as No',
            'Services.ServiceName',
            'ServiceHeader.CreatedDate as Date',
            'ServiceStatus.ServiceStatusDisplay'])
      ->where('ServiceHeader.CustomerID', Auth::id())
      ->where('Services.ServiceCategoryID', 2)
      ->join('Customer','Customer.CustomerID','=','ServiceHeader.CustomerID')
      ->join('Services','Services.ServiceID','=','ServiceHeader.ServiceID')
      ->join('ServiceStatus','ServiceStatus.ServiceStatusID','=','ServiceHeader.ServiceStatusID')
      ->get();
    return View::make('weights.applications', ['applications'=> $data]);
  }
  public function charges() {
    $srvcs = DB::select(DB::raw(
      "select services.ServiceName, ServiceCharges.Amount
        from Services 
        left join ServiceCharges 
        on ServiceCharges.ServiceID = Services.ServiceID  
        where services.ServiceName like '%weight%'"
      ));
    return View::make('signage.charges', ['services' => $srvcs ]);
  }

}
