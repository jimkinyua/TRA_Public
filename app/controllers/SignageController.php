<?php
class SignageController extends BaseController{

    public function services() {
      return View::make('signage.index');
    }


    public function charges() {
      $srvcs = DB::select(DB::raw("SELECT DISTINCT S.ServiceName, C.Amount FROM ServiceCharges AS C LEFT OUTER JOIN   Services AS S ON C.ServiceID = S.ServiceID WHERE  S.ServiceCategoryID = 55"));
      return View::make('signage.charges', ['services' => $srvcs ]);
    }

    public function apply() {

      $formID = 10;
      $serviceID = 508;
      $form = ServiceForm::findOrFail($formID);
      $services = DB::table('services')->where('ServiceCategoryID', 55)->get(['ServiceName','ServiceID']);

      return View::make('signage.apply', [ 'ServiceID'=> $serviceID, 'form'=> $form , 'services' => $services ]);
    }

    public function applications() {
      $data = DB::table('ServiceHeader')
        ->select(['ServiceHeader.ServiceHeaderID',
              'ServiceHeader.PermitNo as No',
              'Services.ServiceName',
              'ServiceHeader.CreatedDate as Date',
              'ServiceStatus.ServiceStatusDisplay'])
        ->where('ServiceHeader.CustomerID', Session::get('customer')->CustomerID)
        ->where('Services.ServiceCategoryID', 55)
        ->join('Customer','Customer.CustomerID','=','ServiceHeader.CustomerID')
        ->join('Services','Services.ServiceID','=','ServiceHeader.ServiceID')
        ->join('ServiceStatus','ServiceStatus.ServiceStatusID','=','ServiceHeader.ServiceStatusID')
        ->get();
      return View::make('signage.applications', ['applications'=> $data]);
    }


    public function submitApplication() {
      $input = Input::all();

      $rules = [
          'ColumnID.103' => 'required|string',
          'ColumnID.114' => 'required|string',
          'ColumnID.115' => 'required|string',
          'ColumnID.116' => 'required|string'
      ];
      $msgs = [
        'ColumnID.103.required' => 'Banner Size is required.',
        'ColumnID.103.string' => 'Banner Size may only contain letters.',

        'ColumnID.114.required' => 'Town is required.',
        'ColumnID.114.string' => 'Town may only contain letters.',

        'ColumnID.115.required' => 'Location is required.',
        'ColumnID.115.string' => 'Location may only contain letters.',

        'ColumnID.116.required' => 'Street is required.',
        'ColumnID.116.string' => 'Street may only contain letters.',
      ];
      $valid = Validator::make(Input::all(),$rules, $msgs);
      if ($valid->fails()){
          return Redirect::back()->withErrors($valid);
      }

      $app = new Application();

      $app->FormID = 10;
      $app->ServiceStatusID = 1;
      $app->ServiceID = Input::get('service');
      $app->SubmissionDate = date('Y-m-d H:i:s');
      $app->CustomerID = Session::get('customer')->CustomerID;

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

}
