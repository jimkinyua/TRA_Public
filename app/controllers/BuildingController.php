<?php
class BuildingController extends BaseController{

    public function services() {
      return Redirect::route('building.approval');
    }

    public function fencing() {

      $formID = 6;
      $form = ServiceForm::findOrFail($formID);
      return View::make('building.plan', ['form'=> $form ]);
    }

    public function approval() {

      $formID = 6;
      $form = ServiceForm::findOrFail($formID);
      return View::make('building.plan', ['form'=> $form ]);
    }

    public function occupation() {

      $formID = 6;
      $form = ServiceForm::findOrFail($formID);
      return View::make('building.plan', ['form'=> $form ]);
    }

    public function submitApproval() {
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
      $app->ServiceID = 510;
      $app->ServiceStatusID = 1;
      $app->CustomerID = Auth::id();
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
      return Redirect::route('list.departments');
    }

}
