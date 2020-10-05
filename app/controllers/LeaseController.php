<?php

class LeaseController extends BaseController{

    public function requestContract() {
      #dd(['so' => 'so what?']);
      return View::make('lease.request');
    }

    // just in case
    public function index() {
        $items = ServiceGroup::all();
        return View::make('services.index',['entities'=>$items]);
    }

    public function searchLand() {
        #var_dump(Input::get('search'));
        $lrnqs = Input::get('search');

        function find($qs) {
          $sid = FormData::where('Value', $qs)->where('FormColumnID', 12)->pluck('ServiceHeaderID');
          $lrn = FormData::where('ServiceHeaderID', $sid)->where('FormColumnID', 12)->pluck('Value');

          $oid = ServiceHeader::where('ServiceHeaderID', $sid)->pluck('CustomerID');
          $cid = Customer::where('CustomerID', $oid)->pluck('CustomerID');
          $own = Customer::where('CustomerID', $oid)->first();

          $ihi = Invoice::where('CustomerID', $cid)->where('Paid', false)->pluck('InvoiceHeaderID');
          $invd = InvoiceLine::where('InvoiceHeaderID', $ihi)->pluck('Amount');
          $paid = Receipt::where('InvoiceHeaderID', $ihi)->pluck('Amount');
          $bal = (float)$invd - (float)$paid;

          $inv = InvoiceView::where('ServiceGroupID', 3)->where('CustomerID', Auth::id())->pluck('InvoiceHeaderID');

          return [ 'owner' => $own, 'due' => $bal, 'lrnumber' => $lrn, 'invoice' => $inv ];
        }

        $land = find($lrnqs);

        //dd(Auth::id());

        return View::make('services.search_land', ['land' => $land, 'owner' => $land['owner'], 'due' => $land['due'], 'inv' => $land['invoice'] ]);
    }

    public function searchHousing() {
        //dd(Input::all());
        $eqs = Input::get('estate');
        $hqs = Input::get('house');

        $info = InvoiceView::select(['CustomerName', 'Balance', 'InvoiceHeaderID'])->where('ServiceID', $eqs)->first();
        if(isset($info)) {
          $info = $info->toArray();
          $invd = true;
        } else {
          $info = [ 'warning' => 'No invoice has been issued' ];
          $invd = false;
        }

        return View::make('services.search_housing', [ 'house' => $info, 'invoiced' => $invd ]);
    }

    public function getServices($id) {
        $dep = ServiceGroup::findOrFail($id);

        $groups = Category::where('ServiceGroupID',$dep->id())->get();
        return View::make('services.list',['entities'=> $groups ]);
    }

    public function getHouserent() {
      $estates = Service::where('ServiceCategoryID', 50)->lists('ServiceName','ServiceID');
      //dd($estates);
      $houses = House::where('ServiceID', 389)->lists('HouseNo','HouseID');
      return View::make('houserent',['estates'=> $estates, 'houses' => $houses ]);
    }

    public function getStalls() {
      $estates = Service::where('ServiceCategoryID', 80)->lists('ServiceName','ServiceID');
      //dd($estates);
      $stalls = House::where('HouseTypeID', 5)->lists('HouseNo','HouseID');
      return View::make('stalls',['estates'=> $estates, 'stalls' => $stalls ]);
    }

    public function getLandRates() {

      $land = House::where('ServiceID', 389)->lists('HouseNo','HouseID');
      return View::make('landrates', [ 'land'=> $land ]);
    }

    public function update() {
      $base = 1543;
      for($x = 14; $x < 75; $x++) {
        $data = House::where('EstateID', $x)->update(['ServiceID' => $base]);
        $base = $base + 1;
      }

      //dd($data);
    }

    public function fetchEstateHouses() {
      //dd(Input::all());
      $rules = ['ServiceID'=>"required|exists:Services,ServiceID"];
      $valid = Validator::make(Input::all(),$rules);
      if ($valid->fails()){
        $response =['code'=>404,'message'=>'Estate not found'];
      }else{
        $id = Input::get('ServiceID');
        $houses = House::select(['HouseID','HouseNo'])->FromEstate($id)->get();#->toJson();
        $response =['code'=>200,'message'=>'Estate found','houses'=>$houses];
      }


      return Response::json($response);
    }

    public function fetchStalls() {
      //dd(Input::all());
      $rules = ['ServiceID'=>"required|exists:Services,ServiceID"];
      $valid = Validator::make(Input::all(),$rules);
      if ($valid->fails()){
        $response =['code'=>404,'message'=>'Estate not found'];
      }else{
        $id = Input::get('ServiceID');
        $stalls = House::where('ServiceID', $id)->FromEstate($id)->get();#->toJson();
        $response =['code'=>200,'message'=>'Estate found','stalls'=>$stalls];
      }


      return Response::json($response);
    }

    public function fetchWards() {
      //dd(Input::all());
      $rules = ['SubCountyID'=>"required|exists:SubCounty,SubCountyID"];
      $valid = Validator::make(Input::all(),$rules);
      if ($valid->fails()){
        $response =['code'=>404,'message'=>'Estate not found'];
      }else{
        $id = Input::get('SubCountyID');
        $wards = Ward::where('SubCountyID' ,$id)->get(['WardName','WardID']);#->toJson();
        $response =['code'=>200,'message'=>'Estate found','wards'=> $wards];
      }


      return Response::json($response);
    }

    public function getApplyForm() {
        $rules = [
            'form'=>'required:exits:Form,FormID',
            'ServiceNo'=>'required|exists:Services,ServiceID'
        ];
        $valid = Validator::make(Input::all(),$rules);
        if ($valid->fails()){
            return Redirect::route('list.departments');
        }
        $id = Input::get('form');


        $form = ServiceForm::findOrFail(intval($id));
        $myBusiness = Business::MyBusinesses(Auth::user()->CustomerProfileID)->lists('CustomerName','CustomerID');

        return View::make('services.apply',['form'=>$form,'businesses'=>$myBusiness,'formID'=>$id,'ServiceNo'=>Input::get('ServiceNo')]);
    }

    public function postApplyForm() {
        $input = Input::all();
        //return Response::json($input);
        //dd(Input::get('form'));
        if(Input::get('form') == 2) {
          $rules = [
              'form'=>'required:exits:Form,FormID',
              'ServiceNo'=>'required|exists:Services,ServiceID',
              'CustomerID'=>'required|exists:Customer,CustomerID',
              'ColumnID.1' => 'required|string',
              'ColumnID.2' => 'required|numeric',
              'ColumnID.3' => 'required|numeric',
              'ColumnID.4' => 'required|string',
              'ColumnID.123' => 'required|string',
              'ColumnID.132' => 'required|email',
              'ColumnID.117' => 'required|numeric',
              'ColumnID.117' => 'required|numeric'
          ];
          $msgs = [
            'ColumnID.1.required' => 'Business Activity Description is required.',
            'ColumnID.1.string' => 'Business Activity Description may only contain letters.',
            'ColumnID.2.required' => 'Business Premise Area (Square Meters) is required.',
            'ColumnID.2.numeric' => 'Business Premise Area (Square Meters) may only contain numeric digits.',
            'ColumnID.3.required' => 'No. of Employees is required.',
            'ColumnID.3.numeric' => 'No. of Employees may only contain numeric digits.',
            'ColumnID.4.required' => 'Other Business Clarification Details are required.',
            'ColumnID.4.string' => 'Other Business Clarification Details may only contain letters.',
            'ColumnID.123.required' => 'Nearest Road is required.',
            'ColumnID.123.string' => 'Nearest Road may only contain letters.',
            'ColumnID.132.required' => 'Email is required.',
            'ColumnID.132.email' => 'Email must be a valid email address.',
            'ColumnID.133.url' => 'Website must be a valid url.',
            'ColumnID.129.required' => 'Postal Address is required.',
            'ColumnID.129.numeric' => 'Postal Address may only contain numeric digits.',
            'ColumnID.117.required' => 'ID/Passport Number is required.',
            'ColumnID.117.numeric' => 'ID/Passport Number may only contain numeric digits.',
          ];
          $valid = Validator::make(Input::all(),$rules, $msgs);
          if ($valid->fails()){
              return Redirect::back()
                  ->withErrors($valid);
              //dd($input);
              //return Redirect::route('list.departments');
          }
        }

        if(Input::get('form') == 5) {
          $rules = [
              'form'=>'required:exits:Form,FormID',
              'ServiceNo'=>'required|exists:Services,ServiceID',
              'CustomerID'=>'required|exists:Customer,CustomerID',
              'ColumnID.44' => 'required|string',
              'ColumnID.45' => 'required|string',
              'ColumnID.46' => 'required|string',
              'ColumnID.47' => 'required|string',
              'ColumnID.48' => 'required|numeric',
              'ColumnID.50' => 'required|string',
              'ColumnID.51' => 'required|numeric'
          ];
          $msgs = [
            'ColumnID.44.required' => 'What is your Occupation now? is required.',
            'ColumnID.44.string' => 'What is your Occupation now? may only contain letters.',
            'ColumnID.45.required' => 'Who is your employer? is required.',
            'ColumnID.45.string' => 'Who is your employer? may only contain letters.',
            'ColumnID.46.required' => 'If employed what is your designation? is required.',
            'ColumnID.46.string' => 'If employed what is your designation? may only contain letters.',
            'ColumnID.47.required' => 'Where is your Accomodation Now? is required.',
            'ColumnID.47.string' => 'Where is your Accomodation Now? may only contain letters.',
            'ColumnID.48.required' => 'What rent are you paying per Month is required.',
            'ColumnID.48.numeric' => 'What rent are you paying per Month may only contain numeric digits.',
            'ColumnID.50.required' => 'Who is to pay Rent? is required.',
            'ColumnID.50.string' => 'Who is to pay Rent? may only contain letters.',
            'ColumnID.51.required' => 'How long have you lived in Uasin Gishu county? is required.',
            'ColumnID.51.numeric' => 'How long have you lived in Uasin Gishu county? may only contain numeric digits.'
          ];
          $valid = Validator::make(Input::all(),$rules, $msgs);
          if ($valid->fails()){
              return Redirect::back()
                  ->withErrors($valid);
              //dd($input);
              //return Redirect::route('list.departments');
          }
        }

        $app = new Application();
        $app->CustomerID = Input::get('CustomerID');
        $app->ServiceID = Input::get('ServiceNo');
        $app->FormID = Input::get('form');
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
