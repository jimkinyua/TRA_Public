<?php
class StallsController extends BaseController{

    public function index() {
      return View::make('rental');
    }

    public function register() {
        $rules = [
            'FormID'=>'required:exits:Form,FormID',
            'ServiceID'=>'required|exists:Services,ServiceID'
        ];

        $valid = Validator::make(Input::all(),$rules);
        if ($valid->fails()){
            return Redirect::route('list.departments');
        }
        $id = Input::get('FormID');

        $form = ServiceForm::findOrFail(intval($id));
        //dd($form->sections());
        //$myBusiness = Business::MyBusinesses(Auth::user()->CustomerProfileID)->lists('CustomerName','CustomerID');

        return View::make('stall.register', [ 'ServiceID'=>Input::get('ServiceID'), 'FormID'=> $id, 'form'=> $form ]);
    }

    public function submitRegistration() {
      $input = Input::all();
      //dd($input);
      //return Response::json($input);

      $rules = [
          'FormID'=>'required:exits:Form,FormID',
          'CustomerID'=>'required|exists:Customer,CustomerID',
          'ColumnID.152'=>'required|exists:Services,ServiceID',
          'ColumnID.144' => 'required|string',
          'ColumnID.145' => 'string',
          'ColumnID.146' => 'string',
          'ColumnID.149' => 'required|string',
          'ColumnID.150' => 'required|string',
          'ColumnID.151' => 'required|string',
          'ColumnID.155' => 'string'
      ];
      $msgs = [
        'ColumnID.144.required' => 'What is your Current Occupation? is required.',
        'ColumnID.144.string' => 'What is your Current Occupation? may only contain letters.',
        'ColumnID.145.string' => 'If employed, Who is your employer? may only contain letters.',
        'ColumnID.146.string' => 'If employed, What is your designation? may only contain letters.',
        'ColumnID.149.required' => 'Is the stall for yourself or for an employee? is required.',
        'ColumnID.149.string' => 'Is the stall for yourself or for an employee? may only contain letters.',
        'ColumnID.150.required' => 'Who is to pay Rent? is required.',
        'ColumnID.150.string' => 'Who is to pay Rent? may only contain letters.',
        'ColumnID.151.required' => 'How long have you lived in Uasin Gishu county? is required.',
        'ColumnID.151.string' => 'How long have you lived in Uasin Gishu county? may only contain letters.',
        'ColumnID.152.required' => 'Stall Location is required.'
      ];

      $valid = Validator::make(Input::all(),$rules, $msgs);
      if ($valid->fails()){
          return Redirect::back()
              ->withErrors($valid);
          //dd($input);
          //return Redirect::route('list.departments');
      }

      $app = new Application();
      $app->CustomerID = Input::get('CustomerID');
      $app->ServiceID = Input::get('ColumnID.152');
      $app->FormID = Input::get('FormID');
      $app->SubmissionDate = date('Y-m-d H:i:s');
      $app->ServiceStatusID = 1;
      $app->save();

      $columns = Input::get('ColumnID');

      foreach($columns as $key => $value)
      {
          $params['ServiceHeaderID'] = $app->id();
          $params['ColumnID'] = $key;
          $params['Value'] = $value;

          Api::AddFormData($params);

      }

      if (Input::hasFile('ServiceDocument')){
          $files = Input::file('ServiceDocument');
          foreach ($files as $key=> $value){
              $path =false;
              $doc = FormDocument::find($key);
              $params['file'] = $value;
              $params['path'] = Application::$url;
              $params['name'] = $doc->type;
              $path = Api::upload($value,$params);

              if ($path){
                  $upload = new ServiceDocument();
                  $upload->ServiceDocumentName = $doc->type;
                  $upload->ServiceHeaderID = $app->id();
                  $upload->FileName = $path;
                  $upload->CreatedDate = date('Y-m-d H:i:s');
                  $upload->CreatedBy = Auth::id();
                  $upload->save();
              }
          }
      }

      Session::flash('success_msg','Application sent successfully');
      return Redirect::route('list.departments');
    }

}
