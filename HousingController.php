<?php
class HousingController extends BaseController{

    public function services() {
      return View::make('housing.index');
    }

    public function home() {

      $estates = Service::where('ServiceCategoryID', 50)->lists('ServiceName','ServiceID');
      $houses = House::where('HouseTypeID', 1)->get(['HouseNo','HouseID']);
      //dd($houses);

      $formID = 5;
      $form = ServiceForm::findOrFail($formID);
      return View::make('housing.home', ['form'=> $form, 'estates'=> $estates, 'houses' => $houses ]);
    }

    public function stall() {

      $formID = 13;
      $form = ServiceForm::findOrFail($formID);

      return View::make('housing.stall', ['form'=> $form ]);
    }

    public function applications() {

      $property = DB::table('ServiceHeader as H')
        ->where('H.CustomerID', Session::get('customer')->CustomerID)
        ->where('H.ServiceID', '=', 1530)
        //->orWhere('H.ServiceID', '=', 1564)
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
      $app->ServiceID = Input::get('ColumnID[152]');
      //$app->ServiceID = 1564;
      $app->ServiceStatusID = 1;
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
      return Redirect::route('portal.home');
    }

    public function houseApplication() {
      $input = Input::all();
      /*
      $rules = [
          'ColumnID.49' => 'required|string',
          'ColumnID.50' => 'required|string',
          'ColumnID.53' => 'required|string'
      ];
      $msgs = [
        'ColumnID.49.required' => 'Is house asked for Personal or Employee? is required.',
        'ColumnID.49.string' => 'Is house asked for Personal or Employee? may only contain letters.',
        'ColumnID.50.required' => 'Who is to pay Rent? is required.',
        'ColumnID.50.string' => 'Who is to pay Rent? may only contain letters.',
        'ColumnID.53.required' => 'State whether you prefer - is required.',
        'ColumnID.53.string' => 'State whether you prefer - may only contain letters.'
      ];

      $valid = Validator::make(Input::all(),$rules, $msgs);
      if ($valid->fails()){
          return Redirect::back()->withErrors($valid);
      }
      */

      $app = new Application();

      $app->FormID = 5;
      $app->ServiceID = 1530;
      $app->ServiceStatusID = 1;
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
      return Redirect::route('portal.home');
    }

}
