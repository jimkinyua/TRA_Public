<?php


class BusinessController extends BaseController{

    public function index(){
        $bill = ServiceGroup::select(['ServiceGroupName', 'ServiceGroupID'])->get();

        $customer = Session::get('customer');
        if((CustomerAgent::where('CustomerID',$customer->CustomerID)->first()->AgentRoleID == 1)) {
          $businesses = DB::table('CustomerAgents')
            ->where('CustomerAgents.AgentID', Auth::id())
            ->where('CustomerAgents.AgentRoleID', 2)
            ->join('Customer', 'Customer.CustomerID', '=', 'CustomerAgents.CustomerID')
            ->get(['Customer.Website', 'Customer.CustomerName', 'Customer.ContactPerson', 'Customer.CustomerID']);
        } else {
          $businesses = [];
        }

        return View::make('business.index', ['businesses' => $businesses, 'bill' => $bill ]);
    }

    public function getAddBusiness() {
        //$types = BusinessType::all()->lists('BusinessTypeName','BusinessTypeID');

        //return View::make('business.new',['types'=>$types]);

        $formID = 3017;
        $form = ServiceForm::findOrFail($formID);

        return View::make('business.new', ['form'=> $form ]);
    }

  
    public function postAddBusiness() {
        $rules = [
            'ColumnID.4176' => 'required|string',
            'ColumnID.4182' => 'required|string',
            'ColumnID.4184' => 'string',
            'ColumnID.4177' => 'string',
            'ColumnID.4178' => 'string',
            // 'ColumnID.4179' => 'required|string',
            'ColumnID.4180' => 'required|email',
            'ColumnID.4181' => 'string',
            // 'ColumnID.11203' => 'required|string',
            // 'ColumnID.11204' => 'required|string',
            // 'ColumnID.11202' => 'required|string'
        ];


        $msgs = [
          'ColumnID.4176.required' => 'Business Name is required.',
          'ColumnID.4176.string' => 'Business Name may only contain letters.',

          'ColumnID.4182.required' => 'KRA PIN is required.',
          'ColumnID.4182.string' => 'KRA PIN may only contain letters.',

          'ColumnID.4184.string' => 'Business Permit Registration Number may only contain numbers.',

          'ColumnID.4177.string' => 'Postal Address may only contain letters.',

          'ColumnID.4178.string' => 'Postal Code may only contain letters.',

          // 'ColumnID.4179.required' => 'Business Phone Number is required.',
          // 'ColumnID.4179.string' => 'Business Phone Number may only contain letters.',

          'ColumnID.4180.required' => 'Business Email Address is required.',
          'ColumnID.4180.email' => 'Business Email Address must be a valid email addess.',

          'ColumnID.4181.required' => 'Business Website Address is required.',

          'ColumnID.13283.required' => 'Business is Required',
          // 'ColumnID.11204.required' => 'Ward is required.',
          'ColumnID.12256.required' => 'TRA Region Office Close to the Business is required.',
        ];

        // echo '<pre>';
        // print_r(Input::all());
        // exit;


        //dd(Input::all());

        $valid = Validator::make(Input::all(),$rules, $msgs);

        if ($valid->passes()){
            $input = Input::all();
            
            //dd(Session::get('customer'));
            $col = Input::get('ColumnID');
            // echo '<pre>';
            // print_r($col);
            // exit;

           

            $agent = Auth::user();

            $customer = Customer::create([
              'PIN' => $col[4182],
              'Type' => 'business',
              //'Ward' => $col[1165],
              //'Ward' => $col[11204],
              'Email' => $col[4180],
              'Website' => $col[4181],
              //'SubCounty' => $col[4185],
              //'SubCounty' => $col[11203],
              'ContactPerson' => $agent,
              'PostalCode' => $col[4178],
              'Mobile1' => $col[12240],
              // 'Telephone1' => $col[4179],
              'BusinessTypeID' => $col[13283],
              'CustomerTypeID' => $col[13283],
              'BusinessZone' => $col[12256],
              'CustomerName' => $col[4176],
              'PostalAddress' => $col[4177],
              'BusinessID' => $col[4184], //Business Permit NO (If the business has a permit)
              // 'BusinessRegistrationNumber' => $col[12247], //Certificte of Registration/ ID
			        'PlotNo' => $col[12238],
              // 'PhysicalAddress' =>$col[12256],
            ]);
            
            // print_r($customer);
            // exit;

            CustomerAgent::create([
              'AgentRoleID' => 2,
              'AgentID'  => $agent->id(),
              'CustomerID' => $customer->id(),
            ]);

            $customers = DB::table('CustomerAgents')
                ->where('AgentID', $agent->id())
                ->join('Customer', 'Customer.CustomerID', '=', 'CustomerAgents.CustomerID')
                ->get(['Customer.CustomerName', 'Customer.CustomerID', 'Customer.Type']);

            // customers is a list of accounts that the logged in agent can represent
            Session::set('customers', $customers);
            return Redirect::route('add.Directors', [ 'id' => $customer->id() ]);
            // Session::flash('message','Business registered successfully');
            return Redirect::route('portal.home', [ 'id' =>  Session::get('customer')->BusinessTypeID ]);
        }

        return Redirect::back()->withErrors($valid)->withInput(Input::all());
    }

    public function postBusinessDirectors(){
    
      $rules = [
          'FirstName' => 'required',
          'LastName' => 'required',
          'PinNo' => 'required',
          'IdNo' => 'required',
      ];


      $msgs = [
        'FirstName' => 'Business Name is required.',
        'LastName' => 'LastName is required.',
        'PinNod' => 'KRA PIN is required.',
        'IdNo' => 'IdNo is required.',
      ];

      // echo '<pre>';
      // print_r(Input::all());
      // exit;


      //dd(Input::all());

      $valid = Validator::make(Input::all(),$rules, $msgs);

      if ($valid->passes()){
          $input = Input::all();
          $agent = Auth::user();
          //Initialise Directors
          $Director = new Directors();
          $Director->FirstName =  $input['FirstName'];
          $Director->LastName =  $input['LastName'];
          $Director->KRAPIN =  $input['PinNo'];
          $Director->IDNO = $input['IdNo'];
          $Director->CompanyID = $input['CustomerId']; 

          if($Director->save()){

            $CompanyDirectors  = Directors::select(['FirstName', 
            'LastName', 'KRAPIN', 'IDNO', 'created_at'])
              ->where('CompanyID',
                intval($input['CustomerId'])
            )->get();
  
          }
          

          return Redirect::route('add.Directors', [
             'id' =>intval($input['CustomerId']),
             'Directors'=>$CompanyDirectors
             ]);
          // Session::flash('message','Business registered successfully');
          return Redirect::route('portal.home', [ 'id' =>  Session::get('customer')->BusinessTypeID ]);
      }

      return Redirect::back()->withErrors($valid)->withInput(Input::all());
  }


  public function postUpdateBusiness() {

        // echo '<pre>';
        // print_r(Input::all());
        // exit;

      $rules = [
            // 'ColumnID.4176' => 'required|string',
            'ColumnID.4182' => 'required|string',
            'ColumnID.4184' => 'string',
            'ColumnID.4177' => 'string',
            'ColumnID.4178' => 'string',
            'ColumnID.12240' => 'required|string',
            'ColumnID.4180' => 'required|email',
            'ColumnID.4181' => 'string',
            'ColumnID.13283' => 'required',
          //  'ColumnID.4185' => 'required|string',
          //  'ColumnID.4186' => 'required|string',
          //  'ColumnID.4187' => 'required|string'
        ];

        $msgs = [
          // 'ColumnID.4176.required' => 'Business Name is required.',
          'ColumnID.4176.string' => 'Business Name may only contain letters.',

          'ColumnID.4182.required' => 'KRA PIN is required.',
          'ColumnID.4182.string' => 'KRA PIN may only contain letters.',

          'ColumnID.4184.string' => 'Business Permit Registration Number may only contain numbers.',

          'ColumnID.4177.string' => 'Postal Address may only contain letters.',

          'ColumnID.4178.string' => 'Postal Code may only contain letters.',

          'ColumnID.12240.required' => 'Business Phone Number is required.',
          'ColumnID.12240.string' => 'Business Phone Number may only contain letters.',

          'ColumnID.4180.required' => 'Business Email Address is required.',
          'ColumnID.4180.email' => 'Business Email Address may only contain letters.',

          'ColumnID.4181.required' => 'Business Website Address is required.',

          'ColumnID.13283.required' => 'Business Type is required.',
          
  
        ];

        $valid = Validator::make(Input::all(),$rules, $msgs);

        if ($valid->passes()){
            $input = Input::all();

            // dd(Session::get('customer'));
            $col = Input::get('ColumnID');

            $agent = Auth::user();

            $customer = Customer::where( 'CustomerID', intval(Input::get('customer_id')) )->first();

            $cols = Input::get('ColumnID');
            $fields = [
              'PIN' => 4182,
              //'Ward' => 4186,
              'Ward' => 11204,
              'Email' => 4180,
              'Website' => 4181,
              'SubCounty' => 11203,
              'PostalCode' => 4178,
              'Mobile1' => 2240,
              'Telephone1' => 4179,
              'CustomerTypeID' => 13283,
              'BusinessZone' => 11202,
              'CustomerName' => 4176,
              'PostalAddress' => 4177,
              'BusinessID' => 4184,
              'PlotNo' => 12238,
              'PhysicalAddress' => 12256,
              'BusinessRegistrationNumber' => 12247,
              'BusinessTypeID'=>13283

            ];

            // print_r($cols);
            // exit;

            $data = $d = [];
            foreach ($fields as $key => $value) {
              if ( array_key_exists($value, $cols) ) { array_push($data, [ $key => $cols[$value] ]); }
            }
            foreach($data as $k=>$v) { $i = key($v);  $d[$i] = $v[$i]; }



            $customer->fill($d);
            $customer['CustomerTypeID']=Input::get('ColumnID')['13283'];
            //print_r($customer);exit;    

            //$customer['CustomerTypeID'] =Input::all();

            
            //print_r(Input::all());exit;
            $customer->save();
            $customers = DB::table('CustomerAgents')
                ->where('AgentID', $agent->id())
                ->join('Customer', 'Customer.CustomerID', '=', 'CustomerAgents.CustomerID')
                ->get(['Customer.CustomerName', 'Customer.CustomerID', 'Customer.Type', 'Customer.BusinessTypeID']);

            // customers is a list of accounts that the logged in agent can represent
            Session::set('customer', $customer);
            Session::set('customers', $customers);

            Session::flash('message','Business account profile updated successfully');
            return Redirect::route('portal.home', ['id'=> Session::get('customer')->BusinessTypeID]);
        }

        return Redirect::back()->withErrors($valid)->withInput(Input::all());
    }

    public function showBusiness($id) {
      $business = DB::table('Customer')->where('CustomerID', $id)->get([
        'CustomerName', 'ContactPerson','PostalAddress', 'Town', 'Website', 'Email'
        ]);
      if($business) {
        $business = $business[0];
        return View::make('business.show', ['business' => $business ]);
      }
      Session::flash('error_msg', 'That Record Does not exist');
      return Redirect::route('portal.dashboard');
    }

    public function submitbusiness($id) {

        $Customer = Customer::find($id);
           //Get Agent ID
           $record = DB::table('CustomerAgents')->where('CustomerID', $id)
           ->get([
             'AgentID'
             ]);
             if($record){
              $AgentId = $record[0]->AgentID;

             }
      if($Customer) {
        $Customer->update(['Submitted'=>1]);

       
          //  echo '<pre>';
          //  print_r(Session::get('customer')->CustomerID);
          //  exit;

            Session::flash('message','Business registered successfully');
            return Redirect::route('portal.accounts', [ 'id' =>  Session::get('customer')->CustomerID ]);
      }
      Session::flash('error_msg', 'That Record Does not exist');
      return Redirect::route('portal.dashboard');
    }

    
}
