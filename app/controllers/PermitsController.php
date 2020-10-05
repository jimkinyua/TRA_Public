<?php

class PermitsController extends Controller {

  public function services() {
    return View::make('permits.index');
  }

  public function apply() {

    $formID = 2;
    $serviceID = 303;
    $form = ServiceForm::findOrFail($formID);

    /*
        $id = 1;
        $cat = Service::where('ServiceGroupID',$id)->lists('ServiceName', 'ServiceID');

        DB::table('FormColumns')->insert([
          'FormColumnName' => 'Business Type',
          'FormID' => 2,
          'ColumnDataTypeID' => 4,
          'Priority' => 3,
          'FormSectionID' => 2,
          'ColumnSize' => 4,
          'CreatedBy' => 1,
          'Notes' => 'SELECT ServiceID, ServiceName FROM Services WHERE ServiceGroupID = 1',
          'Mandatory' => 1
          ]);

        $form = DB::table('FormColumns')
        ->where('FormID', 2)
        ->get();
        dd($form);
        */

    return View::make('permits.apply', [ 'ServiceID'=> $serviceID, 'form'=> $form ]);
  }

  public function submitApplication() {
    $input = Input::all();

    $rules = [
        'customer_id' => 'required|exists:Customer,CustomerID',
        'service_id' => 'required|exists:Services,ServiceID',
        'ColumnID.2' => 'required|numeric',
        'ColumnID.3' => 'required|numeric',
        'ColumnID.5' => 'required|string',
        'ColumnID.117' => 'required|string',
        'ColumnID.123' => 'required|string',
        'ColumnID.157' => 'required|string',
        'ColumnID.158' => 'required|string',
    ];

    $msgs = [
      'ColumnID.2.required' => 'Business Premise Area (Square Meters) is required.',
      'ColumnID.2.numeric' => 'Business Premise Area (Square Meters) may only contain digits.',

      'ColumnID.3.required' => 'Number of Employees is required.',
      'ColumnID.3.numeric' => 'Number of Employees may only contain digits.',

      'ColumnID.5.required' => 'Business Activity  is required.',
      'ColumnID.5.string' => 'Business Activity may only contain letters.',

      'ColumnID.117.required' => 'Registrants ID/Passport Number is required.',
      'ColumnID.117.string' => 'Registrants ID/Passport Number may only contain letters.',

      'ColumnID.123.required' => 'Nearest Road is required.',
      'ColumnID.123.string' => 'Nearest Road may only contain letters.',


      'ColumnID.157.required' => 'Registrants Age is required.',
      'ColumnID.157.string' => 'Registrants Age may only contain letters.',

      'ColumnID.158.required' => 'Registrants Gender is required.',
      'ColumnID.158.string' => 'Registrants Gender may only contain letters.',
    ];

    $valid = Validator::make(Input::all(), $rules, $msgs);

      //dd($input);

    if ($valid->fails()){
        return Redirect::back()
        ->withErrors($valid)
        ->withInput(Input::all());
    }

    $app = new Application();

    $app->FormID = 2;
    $app->ServiceStatusID = 1;
    $app->SubmissionDate = date('Y-m-d H:i:s');
    $app->CustomerID = Input::get('customer_id');
    $app->ServiceID = Input::get('service_id');

    $app->save();

    $columns = Input::get('ColumnID');

    foreach($columns as $key => $value)
    {
        $params['ServiceHeaderID'] = $app->id();
        $params['ColumnID'] = $key;
        $params['Value'] = $value;

        Api::AddFormData($params);

    }

    Session::flash('message','Application submitted successfully');
    return Redirect::route('portal.dashboard');

  }

  public function index() {

    $customer = Session::get('customer');
    $id = intval($customer->CustomerID);

    $data = DB::table('ServiceHeader')
        ->select(['ServiceHeader.ServiceHeaderID',
              'ServiceHeader.PermitNo as No',
              'Services.ServiceName',
              'ServiceHeader.CreatedDate as Date',
              'ServiceStatus.ServiceStatusDisplay'])
        ->where('ServiceHeader.CustomerID', $id)
      //->where('Services.ServiceCategoryID', 2)
      ->join('Customer','Customer.CustomerID','=','ServiceHeader.CustomerID')
      ->join('Services','Services.ServiceID','=','ServiceHeader.ServiceID')
      ->join('ServiceStatus','ServiceStatus.ServiceStatusID','=','ServiceHeader.ServiceStatusID')
      ->get();
      //dd($data);

    return View::make('permits.applications', ['applications'=> $data]);
  }

  public function renew() {
    $bill = ServiceGroup::select(['ServiceGroupName', 'ServiceGroupID'])->get();

    $data = DB::table('ServiceHeader')
      ->select(['ServiceHeader.ServiceHeaderID',
            'ServiceHeader.PermitNo as No',
            'Services.ServiceName',
            'ServiceHeader.CreatedDate as Date',
            'ServiceStatus.ServiceStatusDisplay'])
      ->where('ServiceHeader.CustomerID', Session::get('customer')->customerID)
      ->where('ServiceStatus.ServiceStatusDisplay','Expired Awaiting Renewal')
      ->where('Services.ServiceCategoryID', 2)
      ->join('Customer','Customer.CustomerID','=','ServiceHeader.CustomerID')
      ->join('Services','Services.ServiceID','=','ServiceHeader.ServiceID')
      ->join('ServiceStatus','ServiceStatus.ServiceStatusID','=','ServiceHeader.ServiceStatusID')
      ->get();
    return View::make('permits.renew', [ 'applications'=> $data, 'bill' => $bill ]);
  }

  public function extend($ServiceHeaderID) {
    $columns = DB::table('FormData')
      ->where('FormData.ServiceHeaderID', $ServiceHeaderID)
      ->lists('Value', 'FormColumnID');

    $app = new Application();

    $app->FormID = 2;
    $app->ServiceID = 303;
    $app->ServiceStatusID = 1012;
    $app->SubmissionDate = date('Y-m-d H:i:s');
    $app->CustomerID = Session::get('customer')->customerID;

    $app->save();

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

}
