<?php

class DashboardController extends Controller {

  public function home() {
    if(Session::get('customer')->Type == 'business'){
      $customerType = DB::table('Customer')
      ->select(['Customer.BusinessTypeID',
                'Customer.CustomerName'])  
      ->where('Customer.CustomerID',Session::get('customer')->CustomerID)
      ->pluck('BusinessTypeID');      
      // exit($customerType);
    }
    // return 9099;
    //return View::make('welcome');
    
   
    $bill = ServiceGroup::select(['ServiceGroupName', 'ServiceGroupID'])
    ->where('ServiceGroupID', $customerType)
    ->orWhere(function($query)
            {
                $query->orwhere('ServiceGroupID', '=', 11)
                      ->orwhere('ServiceGroupID', '=', 12);
            })
    ->get();

   
  
    $data = DB::table('ServiceHeader')
        ->select(['ServiceHeader.ServiceHeaderID',
            'ServiceHeader.PermitNo as No',
            'Services.ServiceName',
            'ServiceHeader.CreatedDate as Date',
            'ServiceStatus.ServiceStatusDisplay'])
        ->where('ServiceHeader.CustomerID', Session::get('customer')->CustomerID)
        ->join('Customer','Customer.CustomerID','=','ServiceHeader.CustomerID')
        ->join('Services','Services.ServiceID','=','ServiceHeader.ServiceID')
        ->join('ServiceStatus','ServiceStatus.ServiceStatusID','=','ServiceHeader.ServiceStatusID')
        ->get();

    // echo '<pre>';
    // print_r($bill);
    // exit;

  
    return View::make('dashboard.home', [ 'applications'=> $data, 'bill' => $bill, ]);
  }
  
  

  public function individualservices($id) {

    // exi('');
    //return View::make('welcome');
    
    $customerType = DB::table('Customer')
      ->select(['Customer.BusinessTypeID',
                'Customer.CustomerName'])  
      ->where('Customer.CustomerID',Session::get('customer')->CustomerID)
      ->pluck('BusinessTypeID');      

    $bill = ServiceGroup::select(['ServiceGroupName', 'ServiceGroupID'])
    //->where('ServiceGroupID', $customerType)
    // ->orWhere(function($query)
    //         {
    //             $query->orwhere('ServiceGroupID', '=', 11)
    //                   ->orwhere('ServiceGroupID', '=', 12);
    //         })
    ->get();

   
  
    $data = DB::table('ServiceHeader')
        ->select(['ServiceHeader.ServiceHeaderID',
            'ServiceHeader.PermitNo as No',
            'Services.ServiceName',
            'ServiceHeader.CreatedDate as Date',
            'ServiceStatus.ServiceStatusDisplay'])
        ->where('ServiceHeader.CustomerID', Session::get('customer')->CustomerID)
        ->join('Customer','Customer.CustomerID','=','ServiceHeader.CustomerID')
        ->join('Services','Services.ServiceID','=','ServiceHeader.ServiceID')
        ->join('ServiceStatus','ServiceStatus.ServiceStatusID','=','ServiceHeader.ServiceStatusID')
        ->get();

    // echo '<pre>';
    // print_r($bill);
    // exit;

  
    return View::make('dashboard.home', [ 'applications'=> $data, 'bill' => $bill, ]);
  }

  public function data(Request $request){

    if($request->has('cat_id')){
        $CountyId = $request->get('cat_id');
        $data = KenyanSubCounties::where('countyCode',$CountyId)->get();
        return ['success'=>true,'data'=>$data];
    }

}

  public function accounts($cid) {
    $aid = CustomerAgent::where('CustomerID', $cid)->where('AgentRoleID', 1)->pluck('AgentID');
    $bill = ServiceGroup::select(['ServiceGroupName', 'ServiceGroupID'])->get();
    $customers = DB::table('CustomerAgents')
      ->where('AgentID', $aid)
      ->where('Submitted', 1)
      ->orderBy("Customer.CustomerID","desc")
      ->take(100)
      ->leftjoin('Customer', 'Customer.CustomerID', '=', 'CustomerAgents.CustomerID')
      ->get(['Customer.CustomerName', 'Customer.CustomerID', 'Customer.Mobile1', 'Customer.Email', 'Customer.IDNO', 'Customer.Type']);

      //dd($customers);

    return View::make('dashboard.accounts', [ 'customers'=> $customers, 'bill' => $bill, ]);

  }

  public function unsubaccounts($cid) {
    $aid = CustomerAgent::where('CustomerID', $cid)->where('AgentRoleID', 1)->pluck('AgentID');
    $bill = ServiceGroup::select(['ServiceGroupName', 'ServiceGroupID'])->get();
    $customers = DB::table('CustomerAgents')
      ->where('AgentID', $aid)
      ->where('Submitted', 0)
      ->where('Type', 'business')
      ->orderBy("Customer.CustomerID","desc")
      ->take(100)
      ->leftjoin('Customer', 'Customer.CustomerID', '=', 'CustomerAgents.CustomerID')
      ->get(['Customer.CustomerName', 'Customer.CustomerID', 'Customer.Mobile1', 'Customer.Email', 'Customer.IDNO', 'Customer.Type']);

      //dd($customers);

    return View::make('dashboard.unsubmittedaccounts', [ 'customers'=> $customers, 'bill' => $bill, ]);

  }

  

  public function accounts_search($cid) {
    $cid = Session::get('customer')->CustomerID;
    $data = [];
    $data = (array) Input::all();
    
    $accname=$data['accname'];
    $idno=$data['IDNO'];
    $mobileno=$data['mobileno'];
    $email=$data['email'];

    // if($idno!==''){
    //   dd("iko");
    // }else
    // {
    //   dd("hakuna");
    // }


    $aid = CustomerAgent::where('CustomerID', $cid)->where('AgentRoleID', 1)->pluck('AgentID');
    $bill = ServiceGroup::select(['ServiceGroupName', 'ServiceGroupID'])->get();
    $customers = DB::table('CustomerAgents')
      ->where('CustomerAgents.AgentID', $aid)
      ->where("Customer.CustomerName","like", "%$accname%")
      ->take(100)
      ->join("Customer", "Customer.CustomerID", "=", "CustomerAgents.CustomerID")
      ->orderBy("Customer.CustomerID","desc")
      ->get(["Customer.CustomerName", "Customer.CustomerID", "Customer.Mobile1", "Customer.Email", "Customer.IDNO", "Customer.Type"])
      ;

      // $customers=DB::select('select top 10 c.CustomerName', 'c.CustomerID', 'c.Mobile1', 'c.Email', 'c.IDNO','c.Type
      //               from Customer c join CustomerAgents ca on c.CustomerID=ca.CustomerID 
      //               where ca.AgentID='.$aid);  

     // dd($customers);

    return View::make('dashboard.accounts', [ 'customers'=> $customers, 'bill' => $bill, ]);

  }

  public function postmiscpay() {
    $rules = [ 'service_id' => 'required|exists:Services,ServiceID' ];
    $valid = Validator::make(Input::all(), $rules);
    $input = Input::get('ColumnID');

    if ($valid->fails()){ return Redirect::back()->withErrors($valid)->withInput(Input::all()); }

    $app = new Application();
    $app->FormID = 2;
    $app->ServiceID = 303;
    $app->ServiceStatusID = 1012;
    $app->SubmissionDate = date('Y-m-d H:i:s');
    $app->CustomerID = Session::get('customer')->CustomerID;
    $app->save();

    $shid = $app->id();

    $misc = new MiscApplication();
    $misc->ServiceHeaderID = $shid;
    $misc->Amount = $input['12263'];
    $misc->Description = $input['12262'];
    $misc->CustomerName = $input['12261'];
    $misc->CreatedDate = date('Y-m-d H:i:s');
    $misc->CreatedBy = Session::get('customer')->CustomerID;
    $misc->save();

    $inv = new Invoice();
    $inv->Paid = false;
    $inv->InvoiceDate = date('Y-m-d H:i:s');
    $inv->CreatedDate = date('Y-m-d H:i:s');
    $inv->CreatedBy = Session::get('customer')->CustomerID;
    $inv->CustomerID = Session::get('customer')->CustomerID;
    //$inv->invoiceNo
    $inv->save();

    $ihid = $inv->id();

    $line = new InvoiceLine();
    $line->ServiceHeaderID = $shid;
    $line->InvoiceHeaderID = $ihid;
    $line->Amount = $input['12263'];
    $line->createdDate = date('Y-m-d H:i:s');
    $line->ServiceID = Input::get('service_id');
    $line->CreatedBy = Session::get('customer')->CustomerID;
    //$line->ReceiptID
    $line->save();

    //dd(Input::all());
    Session::flash('message', 'Invoive Issued');
    return Redirect::route('portal.dashboard');
  }

  public function getmiscpay() {
    $cid = Session::get('customer')->CustomerID;
    $aid = CustomerAgent::where('CustomerID', $cid)->where('AgentRoleID', 1)->pluck('AgentID');
    $rid = DB::table('UserRoles')->where('UserID', $aid)->pluck('RoleCenterID');

    if(!is_null($rid)) {
      $role = DB::Table('RoleCenters')->where('RoleCenterID', $rid)->first();
      if(!is_null($role)) {
        if($role->isAdmin) {
          $bill = ServiceGroup::select(['ServiceGroupName', 'ServiceGroupID'])->get();
          $form = ServiceForm::findOrFail(5043);
          $services = Service::all();
          return View::make('applications.misc', ['bill' => $bill, 'services' => $services, 'form' => $form ]);
        }
      }
    }

    Session::flash('message', 'You are not allowed to access that feature.');
    return Redirect::route('portal.dashboard');
  }

  public function showgroups() {
    $d = ServiceGroup::select(['ServiceGroupName as name', 'ServiceGroupID as pk'])->get();
    return json_encode($d);
  }

  public function showservices() {
    //show services
    $d = Service::with('currentCharges')
                ->select(['ServiceName as name', 'ServiceID as pk', 'ServiceCategoryID as fk'])
                ->get();
    //show charges
    $currentYear = DB::table('FinancialYear')->where('isCurrentYear', 1)->pluck('FinancialYearId');
    $d2 = ServiceCharge::where('FinancialYearId', $currentYear)
                        ->select('ChargeID as pk', 'ServiceID as fk', 'SubSystemId as subsys', 'Amount as amount', 'ChargeTypeID as type')
                        ->get();
    //show forms
    $d3 = ServiceForm::select('FormID as pk', 'FormName as name', 'ServiceHeaderType as type')
                        ->get();
    //show formsections
    $d4 = FormSection::where('Show', 1)
                        ->select('FormSectionID as pk', 'FormSectionName as name', 'FormID as fk', 'Priority as priority')
                        ->get();
    //show formcolumns
    $d5 = FormColumns::select('FormColumnID as pk', 'FormColumnName as name', 'FormSectionID as fk', 'Priority as priority', 'ColumnDataTypeID as type', 'Mandatory as required')
                        ->get();

    $d = Category::where('PrimaryService', 1)
                ->select(['CategoryName as name', 'ServiceCategoryID as pk', 'ServiceGroupID as fk', 'FormID as fid'])
                ->get();

    return json_encode($d);
  }

  public function showcategories() 
  {
    $d = Category::where('PrimaryService', 1)
    ->select(['CategoryName as name', 'ServiceCategoryID as pk', 'ServiceGroupID as fk', 'FormID as fid'])
    ->get();
    return json_encode($d);
  }

  public function applicationform($cat) { //Category ID as PARAM
    if($cat == 0) {

    }

    
    // echo '<pre>';
    // print_r(Session::get('customer'));
    // exit;



    //  echo '<pre>';
    // print_r($InspectionOfficers);
    // exit;
        
      $ServiceStatusID = DB::table('ServiceHeader')
      ->select(['ServiceHeader.ServiceStatusID',
            'ServiceHeader.ServiceHeaderID',
            'ServiceHeader.PermitNo as No',
            'Services.ServiceName',
            'ServiceHeader.CreatedDate as Date',
            'ServiceStatus.ServiceStatusDisplay'])
      ->where('ServiceHeader.CustomerID', Session::get('customer')->CustomerID)
      ->join('Customer','Customer.CustomerID','=','ServiceHeader.CustomerID')
      ->join('Services','Services.ServiceID','=','ServiceHeader.ServiceID')
      ->join('ServiceStatus','ServiceStatus.ServiceStatusID','=','ServiceHeader.ServiceStatusID')
      ->orderBy('ServiceHeader.CreatedDate', 'desc')
      ->pluck(['ServiceStatusID']);

      $appliedService = DB::table('ServiceHeader')
      ->select(['Services.ServiceID',
            'ServiceHeader.ServiceStatusID',
            'ServiceHeader.ServiceHeaderID',
            'ServiceHeader.PermitNo as No',
            'ServiceHeader.CreatedDate as Date',
            'ServiceStatus.ServiceStatusDisplay'])
      ->where('ServiceHeader.CustomerID', Session::get('customer')->CustomerID)
      ->where('ServiceCategory.ServiceGroupID','!=',11)
      ->where('ServiceCategory.ServiceGroupID','!=',12)
      ->join('Customer','Customer.CustomerID','=','ServiceHeader.CustomerID')
      ->join('Services','Services.ServiceID','=','ServiceHeader.ServiceID')
      ->join('ServiceCategory','ServiceCategory.ServiceCategoryID','=','ServiceHeader.ServiceCategoryID')
      ->join('ServiceStatus','ServiceStatus.ServiceStatusID','=','ServiceHeader.ServiceStatusID')
      ->orderBy('ServiceHeader.CreatedDate', 'desc')
      ->pluck(['ServiceStatusID']);
      // echo '<pre>';
      // print_r($appliedService);
      // exit;

      $rejectedService = DB::table('ServiceHeader')
      ->select([
            'Services.ServiceID',
            'ServiceHeader.ServiceHeaderID',
            'ServiceHeader.ServiceStatusID',
            'ServiceHeader.PermitNo as No',
            'ServiceHeader.CreatedDate as Date',
            'ServiceStatus.ServiceStatusDisplay'])
      ->where('ServiceHeader.CustomerID', Session::get('customer')->CustomerID)
      ->where('ServiceCategory.ServiceGroupID','!=',11)
      ->where('ServiceCategory.ServiceGroupID','!=',12)
      ->where('ServiceHeader.ServiceStatusID','=',6)
      ->join('Customer','Customer.CustomerID','=','ServiceHeader.CustomerID')
      ->join('Services','Services.ServiceID','=','ServiceHeader.ServiceID')
      ->join('ServiceCategory','ServiceCategory.ServiceCategoryID','=','ServiceHeader.ServiceCategoryID')
      ->join('ServiceStatus','ServiceStatus.ServiceStatusID','=','ServiceHeader.ServiceStatusID')
      ->orderBy('ServiceHeader.CreatedDate', 'desc')
      ->pluck(['ServiceStatusID']);
    
      $appliedClassification = DB::table('ServiceHeader')
      ->select(['ServiceHeader.ServiceCategoryID',
            'Services.ServiceID',
            'ServiceHeader.ServiceStatusID',
            'ServiceHeader.ServiceHeaderID',
            'ServiceHeader.PermitNo as No',
            'ServiceHeader.CreatedDate as Date',
            'ServiceStatus.ServiceStatusDisplay'])
      ->where('ServiceHeader.CustomerID', Session::get('customer')->CustomerID)
      ->where('ServiceCategory.ServiceGroupID','=',11)
      ->join('Customer','Customer.CustomerID','=','ServiceHeader.CustomerID')
      ->join('Services','Services.ServiceID','=','ServiceHeader.ServiceID')
      ->join('ServiceCategory','ServiceCategory.ServiceCategoryID','=','ServiceHeader.ServiceCategoryID')
      ->join('ServiceStatus','ServiceStatus.ServiceStatusID','=','ServiceHeader.ServiceStatusID')
      ->orderBy('ServiceHeader.CreatedDate', 'desc')
      ->pluck(['ServiceStatusID']);
      



      $ServiceStatusDisplay = DB::table('ServiceHeader')
      ->select([ 'ServiceStatus.ServiceStatusDisplay',
            'ServiceHeader.ServiceStatusID',
            'ServiceHeader.ServiceHeaderID',
            'ServiceHeader.PermitNo as No',
            'Services.ServiceName',
            'ServiceHeader.CreatedDate as Date'])
      ->where('ServiceHeader.CustomerID', Session::get('customer')->CustomerID)
      ->join('Customer','Customer.CustomerID','=','ServiceHeader.CustomerID')
      ->join('Services','Services.ServiceID','=','ServiceHeader.ServiceID')
      ->join('ServiceStatus','ServiceStatus.ServiceStatusID','=','ServiceHeader.ServiceStatusID')
      ->orderBy('ServiceHeader.CreatedDate', 'desc')
      ->pluck(['ServiceStatusDisplay']);
      

     $ApplicationStatus = DB::table('ServiceHeader')
      ->select(['ServiceHeader.ServiceHeaderID',
            'ServiceHeader.PermitNo as No',
            'Services.ServiceName',
            'Services.ServiceID',
            'ServiceHeader.CreatedDate as Date',
            'ServiceStatus.ServiceStatusDisplay'])
      ->where('ServiceHeader.CustomerID', Session::get('customer')->CustomerID)
      ->where('ServiceStatus.ServiceStatusID','!=',4)
      ->join('Customer','Customer.CustomerID','=','ServiceHeader.CustomerID')
      ->join('Services','Services.ServiceID','=','ServiceHeader.ServiceID')
      ->join('ServiceStatus','ServiceStatus.ServiceStatusID','=','ServiceHeader.ServiceStatusID')
      ->orderBy('ServiceHeader.CreatedDate', 'desc')
      ->get();

      $theStatus = DB::table('ServiceHeader')
      ->select(['ServiceCategory.ServiceGroupID',
            'ServiceHeader.ServiceHeaderID',
            'ServiceHeader.PermitNo as No',
            'Services.ServiceName',
            'Services.ServiceID',
            'ServiceHeader.ServiceStatusID',
            'ServiceHeader.CreatedDate as Date',
            'ServiceStatus.ServiceStatusDisplay'])
      ->where('ServiceHeader.CustomerID', Session::get('customer')->CustomerID)
      ->where('ServiceStatus.ServiceStatusID','!=',4)
      ->where('ServiceCategory.ServiceGroupID','!=',11)
      ->where('ServiceCategory.ServiceGroupID','!=',12)
      ->join('Customer','Customer.CustomerID','=','ServiceHeader.CustomerID')
      ->join('Services','Services.ServiceID','=','ServiceHeader.ServiceID')
      ->join('ServiceCategory','ServiceCategory.ServiceCategoryID','=','ServiceHeader.ServiceCategoryID')
      ->join('ServiceStatus','ServiceStatus.ServiceStatusID','=','ServiceHeader.ServiceStatusID')
      ->orderBy('ServiceHeader.CreatedDate', 'desc')
      ->pluck('ServiceGroupID');
  
      $ApplicationsMade = DB::table('ServiceHeader')
      ->select(['Services.ServiceID',
            'ServiceHeader.ServiceHeaderID',
            'ServiceHeader.PermitNo as No',
            'Services.ServiceName',
            'ServiceHeader.CreatedDate as Date',
            'ServiceStatus.ServiceStatusDisplay'])
      ->where('ServiceHeader.CustomerID', Session::get('customer')->CustomerID)
      ->where('ServiceStatus.ServiceStatusID','!=',4)
      ->join('Customer','Customer.CustomerID','=','ServiceHeader.CustomerID')
      ->join('Services','Services.ServiceID','=','ServiceHeader.ServiceID')
      ->join('ServiceStatus','ServiceStatus.ServiceStatusID','=','ServiceHeader.ServiceStatusID')
      ->orderBy('ServiceHeader.CreatedDate', 'desc')
      ->pluck('ServiceID'); 

      $appdata = DB::table('ServiceHeader')
      ->select(['ServiceHeader.ServiceHeaderID',
            'ServiceHeader.PermitNo as No',
            'Services.ServiceName',
            'Services.ServiceID',
            'ServiceCategory.ServiceCategoryID',
            'ServiceHeader.CreatedDate as Date',
            'ServiceStatus.ServiceStatusDisplay'])
      ->where('ServiceHeader.CustomerID', Session::get('customer')->CustomerID)
      ->join('Customer','Customer.CustomerID','=','ServiceHeader.CustomerID')
      ->join('Services','Services.ServiceID','=','ServiceHeader.ServiceID')
      ->join('ServiceStatus','ServiceStatus.ServiceStatusID','=','ServiceHeader.ServiceStatusID')
      ->join('ServiceCategory','ServiceCategory.ServiceCategoryID','=','Services.ServiceCategoryID')
      ->orderBy('ServiceHeader.CreatedDate', 'desc')
      ->get();
      
    $ServiceCategoryID = DB::table('ServiceCategory')->where('ServiceCategoryID', intval($cat))->pluck('ServiceCategoryID');

    
      //->pluck(['ServiceStatusDisplay']);

      // print_r($ApplicationStatus);exit;

    $ServiceCategoryID = DB::table('ServiceCategory')
    ->where('ServiceCategoryID', intval($cat))
    ->pluck('ServiceCategoryID');

    $bill = ServiceGroup::select(['ServiceGroupName', 'ServiceGroupID'])
    ->where('ServiceGroupID', Session::get('customer')->BusinessTypeID)
    ->orWhere(function($query)
            {
                $query->orwhere('ServiceGroupID', '=', 11)
                      ->orwhere('ServiceGroupID', '=', 12);
            })
    ->get();
    $services = Service::select(['ServiceName', 'ServiceID'])
    ->where('ServiceCategoryID', intval($cat))
    ->get();
    

    $services = Service::where('ServiceCategoryID', intval($cat))->get();
    
    //Get Form Associaed with the Service Category
    $FormID = DB::table('ServiceCategory')->where('ServiceCategoryID', intval($cat))->pluck('FormID');

    $ServiceGroupID = DB::table('ServiceCategory')->where('ServiceCategoryID', intval($cat))->pluck('ServiceGroupID');


    
    $categoryName = DB::table('ServiceCategory')->where('ServiceCategoryID', intval($cat))->pluck('CategoryName');
    $categoryID = DB::table('ServiceCategory')->where('ServiceCategoryID', intval($cat))->pluck('ServiceCategoryID');

    // if(Session::get('customer')->Type == 'individual' && $FormID == 2) 
    // {
    //   Session::flash('message', 'Switch to a business account to apply for a Licence');
    //   return Redirect::route('portal.dashboard');
    // }

    $form = ServiceForm::findOrFail($FormID);
   
    $docs=DB::select('select * from vwRequiredDocuments where ServiceCategoryID='.$cat); 
   

    return View::make('applications/form', ['ServiceStatusID' => $ServiceStatusID,
     'ServiceStatusDisplay' => $ServiceStatusDisplay, 
     'bill' => $bill, 'services' => $services, 'form' => $form,
     'categoryName'=>$categoryName,
     'ServiceID'=>$ServiceCategoryID,
     'docs'=>$docs, 'categoryID'=>$categoryID, 
     'ServiceCategoryID'=>$ServiceCategoryID, 
     'ServiceGroupID'=>$ServiceGroupID,
     'ApplicationStatus' => $ApplicationStatus,
     'appliedService'=>$appliedService, 
     'appliedClassification'=>$appliedClassification,
     'ApplicationsMade' => $ApplicationsMade,
     'rejectedService' => $rejectedService,
     'theStatus'=>$theStatus, 
     'appdata'=>$appdata]);
  }

  public function registerBusiness() {
    $bill = ServiceGroup::select(['ServiceGroupName', 'ServiceGroupID'])->get();
    $form = ServiceForm::findOrFail(1);
    $locform = ServiceForm::findOrFail(3);
    $docs=DB::select('select * from BusinessRegistrationDocumentTypes'); //where ServiceCategoryID=2039'); 
    // ECHO '<PRE>';
    // print_r($docs);
    // exit;

    return View::make('dashboard.business', ['bill' => $bill, 'location' => $locform, 'form'=> $form, 'docs'=>$docs]);
  }

  public function addBusinessDirectors($id) {
    $bill = ServiceGroup::select(['ServiceGroupName', 'ServiceGroupID'])->get();
      $CompanyDirectors  = Directors::select(['FirstName', 
        'LastName', 'KRAPIN', 'IDNO', 'created_at'])
       ->where('CompanyID',
        intval($id)
      )->get();

      $Countries = Countries::select(['Id', 'Name'])->get();
    return View::make('dashboard.director', [
      'bill' => $bill,
      'CustomerId'=>$id,
      'Directors'=>$CompanyDirectors,
      'Countries'=>$Countries

     ]);
  }

  

  public function registerFleet() {
    $bill = ServiceGroup::select(['ServiceGroupName', 'ServiceGroupID'])
    ->get();
    $CustomerId = Session::get('customer')->CustomerID;

    //Get Any Fleets Registered Under the Company If ANy
    $Fleets = Fleets::select(['FleetId', 
        'RegNo', 'OwnerName', 'Make', 'Model', 'created_at'])
        ->where('CustomerId',
      intval($CustomerId )
    )->get();

    return View::make('dashboard.fleet', ['bill' => $bill, 
    'Fleets'=>$Fleets,
    'CustomerId'=>$CustomerId]);
  }
  public  function  SubmitFleet(){
    $col = Input::all();
    $messages = [
      'RegNo' => 'Please specify the RegNo',
      'LicenceNo' => 'Please specify the Licence Number',
      'LicenceNo.exists' => 'There is no Such Licence Under Your Business Profile',

    ];
    $rules = [
      'LicenceNo'=> 'required|Integer|exists:Permits,id',
      'RegNo'=> 'required',

    ];

    $validator = Validator::make(Input::all(), $rules, $messages);
    if ($validator->fails()) { return Redirect::back()->withErrors($validator);  }

    $IsAllowed = $this->IsLicenceAllowedToRegisterFleets($col['LicenceNo']);
    if( $IsAllowed === true){
          //Remove All White Spaces and TABS
         $RegNo =  strtoupper(preg_replace('/\s/', '', $col['RegNo']));

         $DataOfVehicle  = $this->GetVehicleInfoFromNTSA($RegNo);

        if(empty($DataOfVehicle['error'])){ //Vehicle is in NTSA System
      
          //Extract PIN No of the Owner
          $OwnerData = (object)$DataOfVehicle['owner'][0];
          $OurDatabaseResult = $this->IsVehicleRegisteredInOurDatabase($RegNo);
          if($OurDatabaseResult === false){ //Not In Our Db
            $DirectorResult = $this->IsVehicleOwnedByCompanyDirector($OwnerData->pin);
            $VehicleResult = $this->IsVehicleOwnedByCompany($OwnerData->pin);
      
            //If Vehicle is Owned By Director or Org. Register It
            if($DirectorResult === true || $VehicleResult === true){
              // Register the Damn Vehicle
              $fleet = new CustomerFleet();
              $fleet->RegNo=$RegNo;
              $fleet->OwnerPIN=Session::get('customer')->PIN;
              // $fleet->ChasisNo=Input::get('ChasisNo');
              $fleet->CustomerId=Session::get('customer')->CustomerID;
              $fleet->OwnerName=$OwnerData->names;
              $fleet->Make=$DataOfVehicle['make'];
              $fleet->Model=$DataOfVehicle['model'];
      
              if($fleet->save()){
                 //Get Any Fleets Registered Under the Company If ANy
        
                Session::flash('message','Fleet registered successfully');
                    return Redirect::back();
              }
            }else{
              Session::flash('message', 'Please Ensure The Vehicle is Owned by the Organization or either of Company Directors');
              return Redirect::route('dashboard.fleet');
            }
          }else{
            Session::flash('message', 'The Vehicle is already Registered In Our System');
            return Redirect::route('dashboard.fleet');
          }
        }

        Session::flash('message', 'Vehicle is Not Registered With NTSA');
        return Redirect::route('dashboard.fleet');
    }

    Session::flash('message', $IsAllowed);
        return Redirect::route('dashboard.fleet');
      

  } 
  function IsLicenceAllowedToRegisterFleets($LicenceNo){

   

    $PermitInfo =Permits::with('Service')
                      ->where('id', $LicenceNo)->get();
    // $Data = null;              
    foreach($PermitInfo as $Info){
      $Data = $Info->Service;              
    }
    if(is_object($Data)){
      $ExpiryDate = new DateTime($Data->ExpiryDate);
      $TodayDate = new DateTime("now");

      if($Data->ServiceID == 46 || $Data->ServiceID ==45 ){

          //Check If Licence Belongs to the Customer
          
            if(!$Data->CustomerID ==  Session::get('customer')->CustomerID ){
              return $msg ='This Licence is not Linked to Your Profile';
            }
            //Look at it's  Expiry Date
            if($TodayDate > $ExpiryDate ){
              return $msg ='Your Licence is Exprired, Kindly renew and Add the Vehicle';
            }
          //Licence Passed All Condition
          return true;
      }
      else{
      return $msg ='Only Tours and Travel, and Tourism Vehicle Hire are Allowed to Add Vehicles';

      }
    }
    //Licence is Not Linked to a Service
    return $msg ='Your Licence Seems to Have Been Deleted! Ooops....';
    
  }
  function IsVehicleOwnedByCompanyDirector($PinToCheck){
    // var_dump($PinToCheck);
    $BusinessId=Session::get('customer')->CustomerID;
   
    //Get Company Director/Directors
    $Directors  = Directors::where('CompanyID', $BusinessId)
    ->Select('KRAPIN')->get()->toArray();
    
    if($Directors){
      foreach($Directors as $key => $Director) {
        $m =strcmp($Director['KRAPIN'],$PinToCheck);

        if($m == 0) { // Director Owns Vehicle!!
          return true;
        }

    } //TODO Tell User Compnay Has no Directors!!
 
  }

  }

  
  function IsVehicleOwnedByCompany($PinToCheck){
     
      $BusinessPIN=Session::get('customer')->PIN;
      $m =strcmp($BusinessPIN,$PinToCheck);

      if($m == 0) { // Director Owns Vehicle!!
        return true;
      }
      else{
        return false;
      }

  }

  function IsVehicleRegisteredInOurDatabase($VehicleRegNo){
      //Get Any Fleets Registered Under the Company If ANy
      $Fleets = Fleets::select(['FleetId'])
      ->where('RegNo',
      $VehicleRegNo )->get()->count();

      // echo '<pre>';
      // print_r($Fleets);
      // exit;

      if($Fleets > 0){
        return true;
      }else{
        return false;
      }
  }
  function GetVehicleInfoFromNTSA($regno)
  { 
            // echo $regno;
            //https://portal.ntsa.go.ke/rsl_online/index.php
    $url=    "https://portal.ntsa.go.ke/rsl_online/_getVehicle_details.php";
    $username="tra";
    $password="tra@123";
    $param="regno=$regno";
    $ch=curl_init();     
    curl_setopt($ch,CURLOPT_URL,$url);
    curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
    curl_setopt($ch, CURLOPT_USERPWD, $username . ":" . $password);
    curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
    curl_setopt($ch,CURLOPT_POSTFIELDS,$param);
    // tell cURL to graciously not TO accept an SSL certificate 
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER,false);

    $result=curl_exec($ch); 
    if($result === FALSE) {
      die(curl_error($ch));
  }

    $data=json_decode($result,true);
    return $data;
  }



  public function payment($inv) {
    $invs = explode(',' , $inv);
    array_shift($invs);

    $invoices = Invoice::find($invs);
    //dd($invoices);

    $bill = ServiceGroup::select(['ServiceGroupName', 'ServiceGroupID'])->get();
    $banks = DB::table('banks')->lists('BankName', 'BankID');
    //dd($banks);
    return View::make('dashboard.payment', ['bill' => $bill, 'banks' => $banks, 'invoices' => $invoices ]);
  }

  public function reports() {
    $customer = Session::get('customer')->CustomerID;
    $applications = Application::where('CustomerID', $customer)
      ->join('LandApplication', 'LandApplication.ServiceHeaderID', '=', 'ServiceHeader.ServiceHeaderID')
      ->get(['LandApplication.LRN', 'LandApplication.PlotNo'])
      ->toArray();

    $data = $d = $plots = [];
    foreach ($applications as $key => $value) {
      $plot = DB::table('Land')
        ->where('LRN', $value['LRN'])->where('PlotNo', $value['PlotNo'])
        ->join('LandReceipts', 'Land.LaifomsUPN', '=', 'LandReceipts.LaifomsUPN')
        ->first();
      if(!is_null($plot)) { array_push($data, [ $plot ]); }
    }
    foreach($data as $i => $a) { foreach ($a as $k => $v) { $plots[$i] = $v; } }
    $bill = ServiceGroup::select(['ServiceGroupName', 'ServiceGroupID'])->get();
    return View::make('dashboard.reports', ['bill' => $bill, 'reports' => $plots ]);
  }

  public function report($id) {
    $reports = DB::table('LandReceipts')->where('LaifomsUPN', $id)->get();
    if(empty($reports)) {
      Session::flash('message', 'Land Record not found!');
      return Redirect::route('portal.home');
    }

    $bill = ServiceGroup::select(['ServiceGroupName', 'ServiceGroupID'])->get();

    return View::make('dashboard.report', ['bill' => $bill, 'reports' => $reports ]);
  }

  public function aggregatePayment() {
    $customer = Session::get('customer')->CustomerID;
    $invoices = Invoice::where('CustomerID', $customer)->get();

    $banks = DB::table('banks')->where('ShowPublic',1)->lists('BankName', 'BankID');
    $bill = ServiceGroup::select(['ServiceGroupName', 'ServiceGroupID'])->get();

    return View::make('dashboard.payments', ['bill' => $bill, 'banks' => $banks, 'invoices' => $invoices ]);
  }

  public function searchinvoice($id) {
    try {
      $inv = Invoice::findOrFail(intval($id));
      

      $sh = InvoiceLine::where('InvoiceHeaderID', $id)->first();
      if(!is_null($sh)) 
      {
        $shid = $sh->ServiceHeaderID;
      }

      $applic = Application::findOrFail(intval($shid));
      

      $cust = Customer::find(intval($applic->CustomerID));
      //dd($cust);

      $invoice = [
        'invoice' => $id,
        'status' => 'done',
        'balance' => $inv->balance(),
        'issued_to' => $cust->CustomerName,
      ];    
      return json_encode($invoice);
    } catch (\Exception $e) {
    
      return json_encode([ 'status' => 'error', 'error' => 'invoice not found' ]);
    }

    return View::make('dashboard.payments', ['bill' => $bill, 'banks' => $banks, 'invoices' => $invoices,'CustomerName'=>$cust->CustomerName]);
  }

  public function searchupn($upn) 
  {  
    $record = DB::table('Land')->where('LaifomsUPN', $upn)->first();
    if(empty($record)) {
      return json_encode([ 'status' => 'error', 'error' => 'land record not found' ]);
    }
    return json_encode([ 'status' => 'done', 'data' => $record ]);
  }

  public function searchland($_lrn, $_pno, $upn = null) {
    $lrn = str_replace("@", "/", $_lrn);
    $pno = str_replace("@", "/", $_pno);

    if(is_null($upn)) {
      $record = DB::table('Land')->where('LRN', $lrn)->where('PlotNo', $pno)->first();
    } else {
      $record = DB::table('Land')->where('LRN', $lrn)->where('PlotNo', $pno)->where('LaifomsUPN', $upn)->first();
    }
    if(empty($record)) {
      return json_encode([ 'status' => 'error', 'error' => 'land record not found' ]);
    }
    return json_encode([ 'status' => 'done', 'data' => $record ]);
  }

  public function searchwards($subcounty) {
    $record = DB::table('Wards')->where('SubCountyID', $subcounty)->get();
    if(empty($record)) {
      return json_encode([ 'status' => 'error', 'error' => 'unknown subcounty' ]);
    }
    return json_encode([ 'status' => 'done', 'data' => $record ]);
  }

  public function searchzones($ward) {
    $record = DB::table('BusinessZones')->where('WardID', $ward)->get();
    if(empty($record)) {
      return json_encode([ 'status' => 'error', 'error' => 'unknown ward' ]);
    }
    return json_encode([ 'status' => 'done', 'data' => $record ]);
  }

  public function receipt($id) {
    $receipt = Receipt::where('InvoiceHeaderID', $id)->first();
    $invoice = Invoice::where('InvoiceHeaderID', $id)->first();

    $banks = DB::table('banks')->lists('BankName', 'BankID');
    $bill = ServiceGroup::select(['ServiceGroupName', 'ServiceGroupID'])->get();

    if( is_null($receipt) || is_null($invoice) ) {
      Session::flash('message', 'Record not found!');
      return Redirect::route('portal.home');
    }

    if($invoice->CustomerID == Session::get('customer')->id()) {
      return View::make('dashboard.receipt', [ 'bill' => $bill, 'receipt' => $receipt, 'banks' => $banks ]);
    }

    Session::flash('message', 'You can not edit this receipt');
    return Redirect::route('portal.home');
  }

  public function viewreceipt($id) 
  {
    $invoice = Invoice::where('InvoiceHeaderID', $id)->first();
    if(is_null($invoice)) 
  {
      Session::flash('message', 'Could not find an invoice with that Reference Number');
      return Redirect::route('portal.home');
    }
    $bill = ServiceGroup::select(['ServiceGroupName', 'ServiceGroupID'])->get();
    return View::make('dashboard.receipt', [ 'invoice' => $invoice, 'bill' => $bill, 'customer' => Session::get('customer') ]);
  }
  
  public function viewreceipt_2($rid,$hid) 
  {
    $bill = ServiceGroup::select(['ServiceGroupName', 'ServiceGroupID'])->get();
      $receipt = Receipt::where('ReceiptID', $rid)->first();
    $invoice = Invoice::where('InvoiceHeaderID',$hid)->first();
    //dd($hid);
    if(is_null($receipt)) 
    {
      Session::flash('message', 'Could not find an receipt with that Reference Number');
      return Redirect::route('portal.home');
    }
  
    $ILine=InvoiceLine::where('InvoiceHeaderID',$hid)->first();
    $Description='';
    $ServiceName='';
    if(!is_null($ILine)){
      $Description=$ILine->Description;
      $ServiceID=$ILine->ServiceID;   
    }

    $ServiceName=Service::where('ServiceID',$ServiceID)->pluck('ServiceName');
    
    $ServiceName.=$Description;
    $Description=$ServiceName;
    $iType='';
    $InvoiceType=DB::select('select ServiceHeaderType from vwInvoiceType where InvoiceHeaderID='.$hid); 
    foreach($InvoiceType as $key =>$value)    
    {
      $iType=$value->ServiceHeaderType;
    }
    $Balance=0;
    if($iType=='1')//land
    {
      $LandDetail=DB::select('select li.upn,l.Balance 
                  from LandInvoices li join Land l on li.upn=l.upn  
                  where li.InvoiceHeaderID='.$hid);
      foreach($LandDetail as $key =>$value)    
      {     
        $Balance=$value->Balance;
      }   
    }else if($iType=='2')//house
    {
      $HouseDetail=DB::select('select hi.EstateID,hi.HouseNumber,tn.Balance,hr.Balance RBalance 
                  from HouseInvoices hi
                  join ReceiptLines rl on rl.InvoiceHeaderID=hi.InvoiceHeaderID
                  join Receipts r on rl.ReceiptID=r.ReceiptID 
                  join Tenancy tn on hi.HouseNumber=tn.HouseNumber and hi.EstateID=tn.EstateID 
                  left join HouseReceipts hr on hr.DocumentNo=r.referencenumber and hr.HouseNumber=hi.HouseNumber 
                  where hi.InvoiceHeaderID='.$hid.' and r.ReceiptID='.$rid);
      foreach($HouseDetail as $key =>$value)    
      {
        $HouseNumber=$value->HouseNumber;
        $EstateID=$value->EstateID;
        $Balance=$value->RBalance;
      }
    }
    
    /* if ($iType=='2')
    {
      return View::make('dashboard.receipt_house', [ 'receipt' => $receipt, 'bill' => $bill, 'customer' => Session::get('customer'),'InvoiceNo'=>$hid,'Description'=>$Description,'invoice'=>$invoice,'Servicename'=>$ServiceName,'Balance'=>$Balance]);
    }else{ */
      return View::make('dashboard.receipt_2', [ 'receipt' => $receipt, 'bill' => $bill, 'customer' => Session::get('customer'),'InvoiceNo'=>$hid,'Description'=>$Description,'invoice'=>$invoice,'Servicename'=>$ServiceName,'Balance'=>$Balance]);
    //}
      
  }

  public function viewrenewalreceipt_2($rid,$hid) 
  {
    $bill = ServiceGroup::select(['ServiceGroupName', 'ServiceGroupID'])->get();
    $receipt = LicenceRenewalReceipt::where('LicenceRenewalReceiptID', $rid)->first();
    $invoice = LiceneRenewaInvoice::where('LicenceRenewalInvoiceHeaderID',$hid)->first();
    if(is_null($receipt)) 
    {
      Session::flash('message', 'Could not find an receipt with that Reference Number');
      return Redirect::route('portal.home');
    }
  
    $ILine=LicenceRenewalnvoiceLines::where('InvoiceHeaderID',$hid)->first();
    $Description='';
    $ServiceName='';
    if(!is_null($ILine)){
      $Description=$ILine->Description;
      $ServiceID=$ILine->ServiceID;   
    }

    $ServiceName=Service::where('ServiceID',$ServiceID)->pluck('ServiceName');
    
    $ServiceName.=$Description;
    $Description=$ServiceName;
    $iType='';
    $InvoiceType=DB::select('select ServiceHeaderType from vwInvoiceType where InvoiceHeaderID='.$hid); 
    foreach($InvoiceType as $key =>$value)    
    {
      $iType=$value->ServiceHeaderType;
    }
    $Balance=0;
    if($iType=='1')//land
    {
      $LandDetail=DB::select('select li.upn,l.Balance 
                  from LandInvoices li join Land l on li.upn=l.upn  
                  where li.InvoiceHeaderID='.$hid);
      foreach($LandDetail as $key =>$value)    
      {     
        $Balance=$value->Balance;
      }   
    }else if($iType=='2')//house
    {
      $HouseDetail=DB::select('select hi.EstateID,hi.HouseNumber,tn.Balance,hr.Balance RBalance 
                  from HouseInvoices hi
                  join ReceiptLines rl on rl.InvoiceHeaderID=hi.InvoiceHeaderID
                  join Receipts r on rl.ReceiptID=r.ReceiptID 
                  join Tenancy tn on hi.HouseNumber=tn.HouseNumber and hi.EstateID=tn.EstateID 
                  left join HouseReceipts hr on hr.DocumentNo=r.referencenumber and hr.HouseNumber=hi.HouseNumber 
                  where hi.InvoiceHeaderID='.$hid.' and r.ReceiptID='.$rid);
      foreach($HouseDetail as $key =>$value)    
      {
        $HouseNumber=$value->HouseNumber;
        $EstateID=$value->EstateID;
        $Balance=$value->RBalance;
      }
    }
    
    /* if ($iType=='2')
    {
      return View::make('dashboard.receipt_house', [ 'receipt' => $receipt, 'bill' => $bill, 'customer' => Session::get('customer'),'InvoiceNo'=>$hid,'Description'=>$Description,'invoice'=>$invoice,'Servicename'=>$ServiceName,'Balance'=>$Balance]);
    }else{ */
      return View::make('dashboard.licence_renewal_receipt_2', [ 'receipt' => $receipt, 'bill' => $bill, 'customer' => Session::get('customer'),'InvoiceNo'=>$hid,'Description'=>$Description,'invoice'=>$invoice,'Servicename'=>$ServiceName,'Balance'=>$Balance]);
    //}
      
  }
  
  public function viewreceipts($cid) 
  {
    $bill = ServiceGroup::select(['ServiceGroupName', 'ServiceGroupID'])->get();

    // $invoices = DB::table('Invoiceheader')
    //         ->where('ServiceHeader.CustomerID', intval($cid))
    //         ->join('ServiceHeader', 'ServiceHeader.ServiceHeaderID', '=', 'Invoiceheader.ServiceHeaderID')
    //         //->select(['Permits.PermitNo', 'Permits.ServiceHeaderID' ])
    //         ->get();

    //print_r($invoices); exit;


    $invoices = Invoice::where('CustomerID', $cid)
    ->orderBy('InvoiceHeaderID','desc')->take(100)->get();
    
    //dd($invoices->first()->toArray());

    return View::make('dashboard.receipts', ['invoices' => $invoices, 'bill' => $bill, 'customer' => Customer::findOrFail(intval($cid))  ]);
  }

  public function viewpermit($id) {
    
    $permits = DB::table('ServiceHeader')
            ->where('ServiceHeader.ServiceHeaderID', intval($id))
            ->join('Permits', 'ServiceHeader.ServiceHeaderID', '=', 'Permits.ServiceHeaderID')
            ->select(['Permits.PermitNo', 'Permits.ServiceHeaderID' ])
            ->get();

    if(count($permits) == 0) {
      Session::flash('message', 'Could not find any permit for this account');
      return Redirect::route('portal.home');
    }

    $trimed = ($permits[0]->PermitNo);
    //dd($permits);
    //$permit = public_path().'/admin/pdfdocs/sbps/'.$trimed.'.pdf';
    $permit = public_path().'/admin/pdfdocs/sbps/'.$trimed.'.pdf';
    //dd($permit);

    if ( !File::exists($permit) || empty($trimed)) {
      Session::flash('message', 'The permit with ID ' . $trimed . ' could not be found!');
      return Redirect::route('portal.home');
    }

    //$headers = [ 'Content-Type: application/pdf' ];
    //return Response::download($permit, 'permit.pdf', $headers);

    $bill = ServiceGroup::select(['ServiceGroupName', 'ServiceGroupID'])->get();
    return View::make('dashboard.permit', [ 'bill' => $bill, 'url' => $trimed ]);
  }

  public function postPayment() {
    $data = [];

    $cid = Session::get('customer')->CustomerID;
    $aid = CustomerAgent::where('CustomerID', $cid)
    ->where('AgentRoleID', 1)->pluck('AgentID');


    $validUser=DB::select('select 1 Valid from Users u
                      join UserRoles r on u.AgentID=r.UserID
                      where r.RoleCenterID=2009 and AgentID='.$aid);

    if (empty($validUser)){
      return Redirect::back()->withErrors('You are not Authorized to make payment. Check with the County');  
    }

    $messages = [
      'bank.required_if' => 'Please specify the issuing bank',
      'slip_number.required_if' => 'Please specify the payslip number',
    ];
    $rules = [
      'amount'=> 'required',
      'method'=> 'required',
      'bank'=> 'required_if:method,3',
      'date'=> 'required|date|before:tomorrow',
      //'invoice'=> 'required|integer|exists:InvoiceHeader,InvoiceHeaderID',
      'slip_number'=> 'required_if:method,3|unique:Receipts,ReferenceNumber',
    ];
    $validator = Validator::make(Input::all(), $rules, $messages);
    if ($validator->fails()) { return Redirect::back()->withErrors($validator);  }

    $data = (array) Input::all();
    $date = date_create_from_format('d/M/Y', $data['date']);

    $receipted = $invoiced = 0;
    $invoice_rules = $invoice_msgs = $d = $m = [];
    foreach (Input::get('invoice') as $key => $value) {
      $receipted += $value;
      $invoiced += Invoice::find(intval($key))->balance();
      array_push($d, [ $key => 'required|integer']);
      $m1 = [ $key.'.required' => 'The Amount Receipted for invoice ' . $key . ' is required!' ];
      array_push($m, $m1);
      $m2 = [ $key.'.integer' => 'The Amount Receipted for invoice ' . $key . '  must be a number!' ];
      array_push($m, $m2);
    }

    if(intval($receipted) > Input::get('amount')) {
      Session::flash('message','The Amount Receipted exceeds the amount paid!');
      return Redirect::back();
    }

    foreach($d as $k=>$v) { $i = key($v);  $invoice_rules[$i] = $v[$i]; }
    foreach($m as $k=>$v) { $i = key($v);  $invoice_msgs[$i] = $v[$i]; }

    $validator = Validator::make(Input::get('invoice'), $invoice_rules, $invoice_msgs);
    if ($validator->fails()) { return Redirect::back()->withErrors($validator);  }

    $receipt = Receipt::create([
      'ReceiptStatusID' => 1,
      'BankID' => $data['bank'],
      'Amount' => $data['amount'],
      'ReceiptDate' => date('Y-m-d', strtotime($data['date'])),
      'ReceiptMethodID' => $data['method'],
      'CreatedBy'=>$aid,
      'ReferenceNumber' => $data['slip_number'] ,
    ]);

    //dd($receipt);

    foreach (Input::get('invoice') as $key => $value) {
      ReceiptLine::create([
        'CreatedBy' => 1,
        'Amount' => $value,
        'InvoiceHeaderID' => $key,
        'ReceiptID' => $receipt->ReceiptID,
        'CreatedBy'=>$aid,
        'CreatedDate' => date('Y-m-d H:i:s'),
      ]);
    }

    Session::flash('message', 'Your payment has been receipted');
    return Redirect::route('portal.home');
  }

  public function postReceipt() {
    $data = [];

    $cid = Session::get('customer')->CustomerID;
    $aid = CustomerAgent::where('CustomerID', $cid)->where('AgentRoleID', 1)->pluck('AgentID');

    $messages = [
      'bank.required_if' => 'Please specify the issuing bank',
      'slip_number.required_if' => 'Please specify the payslip number',
    ];
    $rules = [
      'amount'=> 'required',
      'method'=> 'required',
      'bank'=> 'required_if:method,3',
      'date'=> 'required|date|before:tomorrow',
      'slip_number'=> 'required_if:method,3',
      'invoice'=> 'required|integer|exists:InvoiceHeader,InvoiceHeaderID'
    ];

    $validator = Validator::make(Input::all(), $rules, $messages);
    if ($validator->fails()) { return Redirect::back()->withErrors($validator);  }

    $data = (array) Input::all();
    $date = date_create_from_format('M/d/Y', $data['date']);

    $receipt = Receipt::find($data['receipt']);
    $invoice = Invoice::find($data['invoice']);
    if( is_null($receipt) || is_null($invoice) ) {
      Session::flash('message', 'Record not found!');
      return Redirect::route('portal.home');
    }

    $receipt->delete();
    //dd(strtotime(date(DATE_RFC2822))); TODO

    Receipt::create([
      'ReceiptStatusID' => 0,
      'BankID' => $data['bank'],
      'Amount' => $data['amount'],
      'ReceiptDate' => $data['date'],
      'ReceiptMethodID' => $data['method'],
      'CreatedBy'=>$aid,
      'InvoiceHeaderID' => $data['invoice'],
      'ReferenceNumber' => $data['slip_number'] ,
    ]);

    Session::flash('message', 'Your receipt has been updated');
    return Redirect::route('portal.home');
  }

  public function viewBusiness($id) {
    $bill = ServiceGroup::select(['ServiceGroupName', 'ServiceGroupID'])->get();
    $business = DB::table('Customer')->where('CustomerID', $id)->select([
        'CustomerName', 'ContactPerson','PostalAddress', 'Town', 'Website', 'Email'
    ])->first();

    $vehicles=DB::select('select CV.RegNo,BP.ParkName,sc.SittingCapacity,MT.RouteName,cv.Status from CustomerVehicles cv 
                  join BusParks bp on bp.ParkID=cv.BusParkID 
                  join MatatuRoutes mt on cv.Route=mt.routeid
                  join SittingCapacity sc on cv.SittingCapacity=sc.ID
                  where cv.CustomerID='.$id);

    $BusParks=DB::table('BusParks')->lists('ParkName', 'ParkID');

    $capacities=DB::table('SittingCapacity')->lists('SittingCapacity','ID');

    $routes=DB::table('MatatuRoutes')->lists('RouteName', 'RouteID');

    //dd($BusParks);

    if($business) {
      return View::make('dashboard.viewbusiness', ['bill' => $bill, 'business' => $business,'vehicles'=>$vehicles,'id'=>$id,'parks'=>$BusParks,'routes'=>$routes,'capacities'=>$capacities]);
    }
    Session::flash('message', 'That Record Does not exist');
    return Redirect::route('portal.dashboard');
  }

  public function manage() {
    //return View::make('dashboard.manage');
    return Redirect::route('all.applications');
  }

  public function businesses() {
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
    //dd($businesses);
    return View::make('dashboard.businesses', ['bill' => $bill, 'applications' => $businesses ]);
  }

  public function services() {
    $bill = ServiceGroup::select(['ServiceGroupName', 'ServiceGroupID'])->get();

    return View::make('dashboard.financebill',['bill' => $bill]);
    //return Redirect::route('all.applications');
  }

  public function category($cat) {
    $bill = ServiceGroup::select(['ServiceGroupName', 'ServiceGroupID'])->get();
    $services = Service::where('ServicecategoryID',$cat)->get();
    // dd($services->applicationCharges()->get());
    // $service->applicationCharges
    return View::make('dashboard.category',[ 'bill' => $bill, 'services' => $services ]);
  }

  public function settings() {
    return Redirect::route('settings.account');
  }

  public function support() {
    return View::make('dashboard.support');
  }

  public function charges() {
    return Response::download(public_path().'/uploads/5-National-ID.pdf');
  }

  public function categoryservices($cat) {
    $services = Service::where('ServiceCategoryID', intval($cat))->select(['ServiceName', 'ServiceID', 'ServiceCode'])->get();
    return [
      'code' => 200,
      'status' => 'ok',
      'data' => $services,
    ];
  }

  public function backend() {
    $post_data = ['uname' => Auth::user()->Email, 'passwd' => Session::get('password') ];

    $login_url = 'http://revenue.uasingishu.go.ke/admin/index.php';

    //Create a curl object
    $ch = curl_init();

    //Set the URL
    curl_setopt($ch, CURLOPT_URL, $login_url );
    //This is a POST query
    curl_setopt($ch, CURLOPT_POST, true );
    //Set the post data
    curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
    //We want the content after the query
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HEADER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Expect:'));
    curl_setopt($ch, CURLOPT_HEADER, true);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_COOKIEFILE, 'public_html/cookie.txt');
    curl_setopt($ch, CURLOPT_COOKIEJAR, 'public_html/cookie.txt');

    $result = curl_exec ($ch);
    $info = curl_getinfo($ch);
    curl_close ($ch);

    return Redirect::away($login_url);
  }

  public function swap($cid) {
    $id = CustomerAgent::where('AgentID', Auth::id())
      ->where('CustomerID', $cid)
      ->pluck('CustomerID');

    if($id) {
      $customer = Customer::find($id);
      Session::set('customer', $customer);
      // echo'<pre>';
      // print_r(Session::get('customer'));
      // exit;

      $msg = 'You are now logged in as '.$customer;
      Session::flash('message', $msg);
      return Redirect::route('all.applications');
    }

    Session::flash('message','Invalid account details');
    return Redirect::route('portal.dashboard');
  }

}
