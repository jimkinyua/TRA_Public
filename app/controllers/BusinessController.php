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

    public function ViewUpload($id){
      // exit($id);
      $Doc = BusinessAttacheMents::where('BusinessRegistationDocID', $id)->get();
      if($Doc ){
        $DocPath = $Doc[0]->DocumentPath;
        $fileName = $Doc[0]->fileName;
        $FullDocPATH = $DocPath.'/'.$fileName;
        
        // echo '<pre>';
        // print_r( $DocPath);
        // exit;
        if(file_exists($FullDocPATH) ){
          // Header content type 
          header('Content-type: application/pdf'); 
          
          header('Content-Disposition: inline; filename="' . $fileName . '"'); 
          
          header('Content-Transfer-Encoding: binary'); 
          
          header('Accept-Ranges: bytes'); 
          
          // Read the file 
          readfile($DocPath);
        }else{
              return 'File Does Not Exist';
    
        }

      }
      
      
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
            'ColumnID.15379' => 'required|exists:Counties,CountyId',
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
          'ColumnID.15379.required' => 'Please Specify The County Where The Business is Located',
        ];



        //dd(Input::all());

        $valid = Validator::make(Input::all(),$rules, $msgs);
       

        if ($valid->passes()){
          $col = Input::get('ColumnID');
          //Get Region Associated With The County Selected
          // $TraRegionCode = DB::table('Counties')
          //     ->where('CountyId', $col[15379])
          //     ->pluck('TraRegionCode');

            DB::beginTransaction();
            try{
              
              $input = Input::all();
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
                'County' => $col[15379],
                'CustomerName' => $col[4176],
                'PostalAddress' => $col[4177],
                'BusinessID' => $col[4184], //Business Permit NO (If the business has a permit)
                // 'BusinessRegistrationNumber' => $col[12247], //Certificte of Registration/ ID
                'PlotNo' => $col[12238],
                // 'PhysicalAddress' =>$col[12256],
              ]);
              
              CustomerAgent::create([
                'AgentRoleID' => 2,
                'AgentID'  => $agent->id(),
                'CustomerID' => $customer->id(),
              ]);
              
              if(!empty(Input::file())) 
              {
                $this->UploadDocuments(Input::file(),$customer->id());
              }

              DB::commit();
            }catch(\Exception $e){
              //if there is an error/exception in the above code before commit, it'll rollback
              DB::rollBack();
              return $e->getMessage();
            }

            $customers = DB::table('CustomerAgents')
                ->where('AgentID', $agent->id())
                ->join('Customer', 'Customer.CustomerID', '=', 'CustomerAgents.CustomerID')
                ->get(['Customer.CustomerName', 'Customer.CustomerID', 'Customer.Type']);
                // ECHO '<PRE>';
                // print_r(Input::all());
                // exit;
            // customers is a list of accounts that the logged in agent can represent
            Session::set('customers', $customers);
            return Redirect::route('add.Directors', [ 'id' => $customer->id() ]);
            // Session::flash('message','Business registered successfully');
            return Redirect::route('portal.home', [ 'id' =>  Session::get('customer')->BusinessTypeID ]);
        }

        return Redirect::back()->withErrors($valid)->withInput(Input::all());
    }

    public function UploadDocuments($FilesToUpload, $ApplicantId){
      
          $destinationPath = storage_path('uploads');
          // $files = $input;
          // $i=0;
  
         
          foreach ($FilesToUpload as $key => $file) 
          {
         
            if(!is_null($file))
            {
              foreach($file as $Key => $FileData){
                $DocTypeId=$Key;
                $BusinessNo= $ApplicantId; //(Session::get('customer')->CustomerID);
                $name = $FileData->guessClientExtension();
                $fileName = $FileData->getClientOriginalName();//time().'.'.$file->getClientOriginalExtension();
                $Name = $FileData->getClientOriginalName();
                 
    
                $destination=$destinationPath."/".$DocTypeId."/".$BusinessNo;

                  //C:\inetpub\wwwroot\TRA\TRA_Public\app\storage/uploads/3/336704/dummy Doc.pdf
                if (!file_exists($destination)) {
                  mkdir($destination, 0777, true);
                }
                // $DocumentPath=$destination."/".$fileName;
             
                $FileData->move($destination, $fileName);            
                $document = new BusinessAttacheMents();
                $document->BusinessNo = $ApplicantId;
                $document->DocTypeId = $DocTypeId;
                $document->DocumentPath=$destination;
                $document->FileName=$fileName;
                $document->IsCurrent =true;
                $document->save();

              }
                                    
            }
          }
      
    }

    public function removeDirector($DirectorId){
      //Ensure Director Id Passed Belongs to the Company Selected. People are Cheecky!!
      // $m =Directors::where('CompanyID', intval(Session::get('customer')
      //           ->CustomerID))
      //           ->get()
      //           ->pluck('DirectorsID');
      $m = Directors::where('DirectorsID',intval($DirectorId))
      ->pluck('CompanyID');

      if(!$m == intval(Session::get('customer')->CustomerID)){
          //Not a Director in that Company!!!
          Session::flash('message','Stop That!!!');
          return Redirect::back();
      }
      $Directors = Directors::find($DirectorId);
      $Directors->Deleted = 1;
      
      if($Directors->save()){
        //Not a Director in that Company!!!
        Session::flash('message','Director Removed Succesfully');
        return Redirect::back();
      }

      // Directors::where('id',$DirectorId)->update(['Deleted'=>true]);

      
    }

    public function postBusinessDirectors(){
    
      $rules = [
          'FirstName' => 'required',
          'LastName' => 'required',
          'PinNo' => 'required',
          'IdNo' => 'required',
          'CountryId'=>'required',
          'PhoneNumber'=>'required',
      ];


      $msgs = [
        'FirstName' => 'Business Name is required.',
        'LastName' => 'LastName is required.',
        'PinNod' => 'KRA PIN is required.',
        'IdNo' => 'IdNo is required.',
        'PhoneNumber'=>'IdNo is required.',
        'CountryId'=>'Your Country is Required '

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
          $Director->PhoneNumber = $input['PhoneNumber']; 
          $Director->CountryId = $input['CountryId']; 
          $Director->MiddleName = $input['MiddleName']; 



          if($Director->save()){
            //Save Attachements if Any
            if(Input::file()){
             

              $this->UploadBusinessDirectorDocuments(Input::file(), $Director->id());
            }
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

  public function UploadBusinessDirectorDocuments($FilesToUpload, $DirectorId){
      
    $destinationPath = storage_path('uploads');
    // $files = $input;
    // $i=0;


    foreach ($FilesToUpload as $key => $FileData) 
    {
  
           

      if(!is_null($FileData))
      {
        
          $DirectorId= $DirectorId; //(Session::get('customer')->CustomerID);
          $name = $FileData->guessClientExtension();
          $fileName = $FileData->getClientOriginalName();//time().'.'.$file->getClientOriginalExtension();
          $Name = $FileData->getClientOriginalName();
           

          $destination=$destinationPath."/".$DirectorId;

            //
          if (!file_exists($destination)) {
            mkdir($destination, 0777, true);
          }
          $DocumentPath=$destination."/".$fileName;
       
          $FileData->move($DocumentPath, $fileName);            
          $document = new ForeignBusinessOwnersAttachements();
          $document->DocTypeId = 1;
          $document->DirectorsID = $DirectorId;
          $document->DocumentPath=$DocumentPath;
          $document->FileName=$fileName;
          $document->IsCurrent =true;
          $document->save();

        
                              
      }
    }

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
            'ColumnID.15379' => 'required|exists:Counties,CountyId',

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
              'County' => 15379,
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
            return Redirect::route('add.Directors', [ 'id' => $customer->id() ]);
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

      $NumberOfDirectors  = Directors::where('CompanyID', $id)->count();
      if( $NumberOfDirectors > 0){ //AlloW Submit

        $Customer = Customer::find($id);
        if($Customer) {
          $Customer->update(['Submitted'=>1]);
          Session::flash('message','Business registered successfully');
          return Redirect::route('portal.accounts', [ 'id' =>  Session::get('customer')->CustomerID ]);
        }

        Session::flash('error_msg', 'That Record Does not exist');
        return Redirect::back();

      }     
      Session::flash('error_msg', 'Please Ensure That You have Provided The Owners or Directors of the Company');
      return Redirect::back();
    }

    
}
