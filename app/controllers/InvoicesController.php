<?php
class InvoicesController extends BaseController{

    public function permits() {

      $property = DB::table('ServiceHeader as H')
        ->where('H.CustomerID', Auth::user()->CustomerID())
        ->where('H.ServiceID', '=', 1530)
        ->orWhere('H.ServiceID', '=', 1564)
        ->join('Customer as U', 'U.CustomerID', '=', 'H.CustomerID')
        ->join('Services as SS', 'SS.ServiceID', '=', 'H.ServiceID')
        ->join('ServiceStatus as S', 'S.ServiceStatusID', '=', 'H.ServiceStatusID')
        ->get(['H.CustomerID', 'SS.ServiceName', 'S.ServiceStatusDisplay', 'H.CreatedDate', 'U.CustomerName', 'U.Email', 'U.Mobile1', 'U.IDNO']);
        //->get();
      return View::make('housing.applications', ['applications'=> $property]);
    }

    public function stallApplication() {
      $input = Input::all();

      $rules = [
          'ColumnID.149' => 'required|string',
          'ColumnID.150' => 'required|string',
          'ColumnID.152' => 'required|string',
          'ColumnID.155' => 'required|numeric'
      ];
      $msgs = [
        'ColumnID.149.required' => 'Is the stall for yourself or for an employee? is required.',
        'ColumnID.149.string' => 'Is the stall for yourself or for an employee? may only contain letters.',
        'ColumnID.150.required' => 'Who is to pay Rent? is required.',
        'ColumnID.150.string' => 'Who is to pay Rent? may only contain letters.',
        'ColumnID.152.required' => 'Stall Location is required.',
        'ColumnID.152.string' => 'Stall Location may only contain letters.',
        'ColumnID.155.required' => 'Stall Number (UHN) is required.',
        'ColumnID.155.string' => 'Stall Number (UHN) may only contain numbers.'
      ];

      $valid = Validator::make(Input::all(),$rules, $msgs);
      if ($valid->fails()){
          return Redirect::back()->withErrors($valid);
      }

      $app = new Application();

      $app->FormID = 13;
      $app->ServiceID = 1564;
      $app->ServiceStatusID = 1;
      $app->SubmissionDate = date('Y-m-d H:i:s');
      $app->CustomerID = Auth::user()->customerID();

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
      return Redirect::route('portal.home');
    }

    public function houseApplication() {
      $input = Input::all();

      $app = new Application();

      $app->FormID = 5;
      $app->ServiceID = 1530;
      $app->ServiceStatusID = 1;
      $app->SubmissionDate = date('Y-m-d H:i:s');
      $app->CustomerID = Auth::user()->customerID();

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
      return Redirect::route('portal.home');
    }

}
