<?php

class ApplicationsController extends Controller {

  public function all() 
  {
    $bill = ServiceGroup::select(['ServiceGroupName', 'ServiceGroupID'])->get();
	 //dd(Session::get('customer'));
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
      ->orderBy('ServiceHeader.CreatedDate', 'desc')
      ->get();

	  
    return View::make('applications.all', ['applications'=> $data, 'bill' => $bill ]);
  }

  public function licences() //Only Those That Are Damn Approved
  {
    $bill = ServiceGroup::select(['ServiceGroupName', 'ServiceGroupID'])->get();
	 //dd(Session::get('customer'));
    $data = DB::table('ServiceHeader')
      ->select(['ServiceHeader.ServiceHeaderID',
            'ServiceHeader.PermitNo as No',
            'Services.ServiceName',
            'ServiceHeader.CreatedDate as Date',
            'ServiceHeader.ExpiryDate',
            'ServiceHeader.IssuedDate',
            'ServiceHeader.PermitNo',
            'ServiceStatus.ServiceStatusDisplay'])
      ->where('ServiceHeader.CustomerID', Session::get('customer')->CustomerID)
      ->where('ServiceHeader.ServiceStatusID', 4)
      ->join('Customer','Customer.CustomerID','=','ServiceHeader.CustomerID')
      ->join('Services','Services.ServiceID','=','ServiceHeader.ServiceID')
      ->join('ServiceStatus','ServiceStatus.ServiceStatusID','=','ServiceHeader.ServiceStatusID')
      ->orderBy('ServiceHeader.CreatedDate', 'desc')
      ->get();
      // echo '<pre>';
      // print_r($data);
      // exit;

	  
    return View::make('applications.mylicences', ['applications'=> $data, 'bill' => $bill ]);
  }

  public function renew(){
    $rules = [ 'service_id' => 'required|exists:Services,ServiceID',
              'PermitNo'=>'required|exists:ServiceHeader,PermitNo' ];
    $valid = Validator::make(Input::all(), $rules);
    $cols = Input::get('ColumnID'); //Get dATA
    // echo '<pre>';
    // print_r(Input::all());
    // exit;

    if ($valid->fails()){
      return Redirect::back()->withErrors($valid)->withInput($cols);
     }
   
    return $this->persistLicenceRenewalApplication($cols);
  }

  public function renewlicence($ServiceHeaderID) //
  {
    // exit($ServiceHeaderID);
    $bill = ServiceGroup::select(['ServiceGroupName', 'ServiceGroupID'])->get();
	 //dd(Session::get('customer'));
    $data = DB::table('ServiceHeader')
      ->select(['*'])
      ->where('ServiceHeader.CustomerID', Session::get('customer')->CustomerID)
      ->where('ServiceHeader.ServiceStatusID', 4)
      ->where('ServiceHeader.ServiceHeaderID', $ServiceHeaderID)
      ->join('Customer','Customer.CustomerID','=','ServiceHeader.CustomerID')
      ->join('Services','Services.ServiceID','=','ServiceHeader.ServiceID')
      ->join('ServiceStatus','ServiceStatus.ServiceStatusID','=','ServiceHeader.ServiceStatusID')
      ->orderBy('ServiceHeader.CreatedDate', 'desc')
      ->get();

    
      
      //Get the Renewal Form
      $form = LicenceRenewalForm::findOrFail(2); //2 is For Renewal
      $docs=DB::select('select * from vwRequiredDocuments where ServiceCategoryID=9'); 

      $StandardRenewalFee = 10000;


      //Check If Period is Less than 3 Months
      $ExpiryDate = $data[0]->ExpiryDate;
			
      $TimeToExpiry = ((strtotime($ExpiryDate) - time() ) / (60*60*24));
      $TimeToExpiry = ceil($TimeToExpiry);
     
      $AllowRenew = 0;
      //Remember to Check if a Liceence can be renewed. A user may be suspended

      //print_r($LicenceRenewal);  exit;
      // if($TimeToExpiry <= 91 && !$LicenceRenewal){
        //Check For Penalties

        //Check if the Day they Are Renewing is Past 31st January
        $DateToday = new DateTime();
        $day = 31; $month =01; $year = date("Y");
        $d=mktime(00, 00, 00, $month,$day, $year);
        $EndOfWaiverDate = date("Y-m-d h:i:sa", $d);
        //exit($ExpiryDate);
       
       if ($DateToday > $EndOfWaiverDate) { // Past The Waiver Date
           //Calculate Intrest
           $PenaltyAmountToPay  = $this->CalculateSimpleInterest($StandardRenewalFee, ($TimeToExpiry/30), 10 );
           $Penalty = true;
            // exit($PenaltyAmountToPay);
       }else{//Don't Charge Interest
        $PenaltyAmountToPay = 0;
        $Penalty = false;
       }

      // $PayableAmount =  $PenaltyAmountToPay + $StandardRenewalFee;
      // print_r($PayableAmount );
      // exit;


      // }else{
        // Session::flash('error_msg','Licences Can Only be renewed with Only 3 Months Remaining');
        // return Redirect::route('grouped.licences');
        // return View::make('applications.mylicences', ['applications'=> $data, 'bill' => $bill ]);

      // }
	  
    return View::make('applications.licencerenewal_form', ['applications'=> $data, 'bill' => $bill, 'Penalty'=>$Penalty,  'PenaltyAmountToPay'=>$PenaltyAmountToPay, 'StandardRenewalFee'=>$StandardRenewalFee,  'form'=>$form, 'docs'=>$docs ]);
  }

  
  function CalculateSimpleInterest($principal, $number_of_periods, $interest_rate)
  {
    //variable and initializations
    $Interest = 0.0;

    //calculate simple interest
    $Interest = ($principal * $number_of_periods * $interest_rate)/100;
    $TotalAmountPayable = $Interest + $principal;
    //return the value
    return $Interest;
  }

  public function view($ServiceHeaderID) {

    $columns = DB::table('FormData')
      ->where('FormData.ServiceHeaderID', $ServiceHeaderID)
      ->lists('Value', 'FormColumnID');

    $bill = ServiceGroup::select(['ServiceGroupName', 'ServiceGroupID'])->get();


    $app = FormData::where('ServiceHeaderID', $ServiceHeaderID)
      ->lists('FormColumnID', 'Value');
      //->toArray();

    

    foreach($app as $key => $val) {
      $app[$key] = 'ColumnID['.$val.']';
    }
    $app = array_flip($app);

    $model = new FormModel;
    $model->fill($app);


    $header = DB::table('ServiceHeader as S')
      //->where('S.CustomerID', $custId)
      ->where('S.ServiceHeaderID', $ServiceHeaderID)
      //->join('FormData as D', 'D.ServiceHeaderID', '=', 'S.ServiceHeaderID')
      ->get();

    $formID = $header[0]->FormID;
    $form = ServiceForm::findOrFail($formID);
    //dd(array_keys($model->toArray()));

    return View::make('applications.view', ['form' => $form, 'model' => $model, 'bill' => $bill ]);
  }

  public function show($ServiceHeaderID) {

    $bill = ServiceGroup::select(['ServiceGroupName', 'ServiceGroupID'])->get();
    $app = FormData::where('ServiceHeaderID', $ServiceHeaderID)->lists('Value', 'FormColumnID');
  
    $formID = ServiceHeader::where('ServiceHeaderID', $ServiceHeaderID)->pluck('FormID');
    $ApplicationStatus = ServiceHeader::where('ServiceHeaderID', $ServiceHeaderID)->pluck('ServiceStatusID');
    $serviceID = ServiceHeader::where('ServiceHeaderID', $ServiceHeaderID)->pluck('ServiceID');
    $ServiceCategoryId = ServiceHeader::where('ServiceHeaderID', $ServiceHeaderID)->pluck('ServiceCategoryId');
    $categoryName = DB::table('ServiceCategory')->where('ServiceCategoryID', intval($ServiceCategoryId))->pluck('CategoryName');

    if(is_null($formID)) {
      Session::flash('message','Application not found');
      return Redirect::route('portal.home');
    }
    $form = ServiceForm::findOrFail($formID);
    
    $service = Service::find($serviceID);
  

    // $SavedServiceName =  Service::find($serviceID)->pluck('ServiceName');
 

    $services = Service::where('ServiceCategoryID', intval($service->ServiceCategoryID))->get();
    $SavedServiceName = Service::where('ServiceCategoryID', intval($ServiceCategoryId))->pluck('ServiceName');
    $SavedServiceID = Service::where('ServiceCategoryID', intval($ServiceCategoryId))->pluck('ServiceID');
 
    // echo '<pre>';
    // print_r( $app);
    // exit;

    // //dd($app);

    return View::make('applications.show', [
      'application' => $app, 'form' => $form, 'Status'=>$ApplicationStatus, 'SavedServiceName'=> $SavedServiceName,  'SavedServiceID'=> $SavedServiceID, 'categoryName'=>$categoryName, 'bill' => $bill, 'service' => $service , 'services' => $services, 'header' => $ServiceHeaderID
    ]);
  }

  public function statement($lrn,$plotno,$authority,$upn) 
  {
    $stmt = DB::select("select lr.DateReceived,lr.DocumentNo,lr.Description,lr.Amount,lr.Balance
  	from LAND l join LANDRECEIPTS lr on lr.upn=l.upn
  	where l.PlotNo='".$plotno."' 
  	and l.LRN='".$lrn."' 
  	and lr.LocalAuthorityID=".$authority." 
  	and l.upn=".$upn." 
  	order by lr.LandReceiptsId");
  	
    //dd($stmt);

    return View::make('land.statement', ['stmt' => $stmt]);
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
    $app->CustomerID = Auth::user()->customerID();

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

  public function apply()
  {     
    // exit('Nding');

      // echo '<pre>';
      // print_r(Input::all());
      // exit;

    $rules = [ 'service_id' => 'required|exists:Services,ServiceID' ];
    $valid = Validator::make(Input::all(), $rules);
    $cols = Input::get('ColumnID'); //Get dATA
    

    if ($valid->fails()){
      return Redirect::back()->withErrors($valid)->withInput($cols);
     }
   
    return $this->persistApplication($cols);
  }

  // public function update(){
  //   $rules = [ 'service_id' => 'required|exists:Services,ServiceID' ];
  //   $valid = Validator::make(Input::all(), $rules);
  //   $cols = Input::get('ColumnID'); //Get dATA
  //   // echo '<pre>';
  //   // print_r(Input::all());
  //   // exit;

  //   if ($valid->fails()){
  //     return Redirect::back()->withErrors($valid)->withInput($cols);
  //    }
   
  //   return $this->persistApplicationForSubmit($cols);
  // }
      public function update()  {

          //inputs
          $input = Input::all();
        
          $msgs = [];
          $rules = [
            'service_id' => 'required|exists:Services,ServiceID',
            'service_header_id' => 'required|exists:ServiceHeader,ServiceHeaderID'
          ];

          $valid = Validator::make($input, $rules, $msgs);
          if ($valid->fails()){ 
            return  Redirect::back()->withErrors($valid)->withInput(Input::all()); 

          }
          
          //application type
          // echo '<pre>';
          // print_r( $input);
          // exit;
          //Service::find(intval(Input::get('service_id'))->where('ServiceID',Input::get('service_id')))
          $m = Service::find(intval(Input::get('service_id')))->category()->first(); 
          if(is_null( $m)){
            Session::flash('message','NUll Error!!!');
            return Redirect::back();
          }
          $type = $m->id();

          // exit('Hapa');
          $service = Service::find(Input::get('service_id'));
          $cat = $service->ServiceCategoryID;
          $formID = DB::table('ServiceCategory')->where('ServiceCategoryID', $cat)->pluck('FormID');
          // echo '<pre>';
          // print_r( $formID);
          // exit;

          $form = ServiceForm::findOrFail($formID);

          foreach($form->sections() as $section){
              foreach($section->columns() as $field) {

                if($field->Mandatory) {
                  $rules['ColumnID.'.$field->id()] = 'required|string';
                  $msgs['ColumnID.'.$field->id().'.required'] = $field.' is required.';
                }
                $rules['ColumnID.'.$field->id()] = 'string';
                $msgs['ColumnID.'.$field->id().'.string'] = $field.' may only contain letters.';

                //$msgs['ColumnID.'.$field->id().'.required'] = $field.' is required.';
                //$rules['ColumnID.'.$field->id()] = ($field->Rules) ? ($field->Rules) : 'string';

              }
          }

          $valid = Validator::make($input, $rules, $msgs);
          if ($valid->fails()){ return Redirect::back()->withErrors($valid)->withInput(Input::all()); }



          $app = Application::find(Input::get('service_header_id'));

          // $app->ServiceHeaderType = $this->getType($type);
          $app->CustomerID = (Session::get('customer')->CustomerID);
          $app->ServiceID = Input::get('service_id');

          $app->save();

          $info = $this->extractSpecific(($input['ColumnID']), $type);
          if( is_null($info) ) {
            $columns = Input::get('ColumnID');
          } else {
            $columns = Input::get('ColumnID');
            $entity = $this->persistEntity(($info['particular']), $type, $app->id());
          }

          foreach($columns as $key => $value)    {
              $params['ServiceHeaderID'] = $app->id();
              $params['ColumnID'] = $key;
              $params['Value'] = trim($value);

              Api::UpdateFormData($params);

          }

          Session::flash('message','Application updated successfully');
          return Redirect::back();
      }

  public function grouped($gid) {
      $applicant = Session::get('customer')->CustomerID;
      $group = Service::where('ServiceGroupID', $gid)->lists('ServiceID');

      // $apps = ServiceHeader::where('CustomerID', $applicant)
      //   ->whereIn('ServiceHeader.ServiceID', $group)
      //   ->join('Services','Services.ServiceID','=','ServiceHeader.ServiceID')
      //   ->join('ServiceStatus','ServiceStatus.ServiceStatusID','=','ServiceHeader.ServiceStatusID')
      //   ->select(['ServiceHeader.ServiceHeaderID',
      //       'ServiceHeader.PermitNo as No',
      //       'Services.ServiceName',
      //       'ServiceHeader.CreatedDate as Date',
      //       'ServiceStatus.ServiceStatusDisplay'])
      //   ->get();

        $apps=DB::select('select Distinct sh.ServiceHeaderID,s.ServiceName,sh.CreatedDate [Date],ss.ServiceStatusDisplay
          from ServiceHeader sh
          join services s on sh.ServiceID=s.ServiceID
          join ServiceStatus ss on sh.ServiceStatusID=ss.ServiceStatusID
          join Permits p on p.ServiceHeaderID=sh.ServiceHeaderID
          where sh.CustomerID ='.$applicant.' and sh.ServiceID in (select ServiceID from Services where ServiceGroupID='.$gid.' and year(sh.CreatedDate)>2017)');

        

      if($gid == 1) { return View::make('permits.index', [ 'applications' => $apps]); }
  	  if($gid == 3) 
  	  {
  		  $apps = DB::select('select l.LocalAuthorityID,l.LRN,l.PlotNo,l.LaifomsUPN,L.UPN from LandApplication la
				join ServiceHeader sh on la.ServiceHeaderID=sh.ServiceHeaderID
				join land l on la.LRN=l.LRN and la.PlotNo=l.PlotNo
				join Customer c on sh.CustomerID=c.CustomerID
				where c.CustomerID='.$applicant);
  		 ///dd($apps);
  		  return View::make('land.property', [ 'property' => $apps]); 
  	  }

      return View::make('applications.all', [ 'applications' => $apps]);
  }

  public function invoice($ihid) {
	$Details="";
	$bill = ServiceGroup::select(['ServiceGroupName', 'ServiceGroupID'])->get();
	$invoice = Invoice::findOrFail($ihid);
		
	$sg=DB::select('SELECT sg.ServiceGroupID,sc.ServiceCategoryID FROM 
		InvoiceLines IL 
		join Services S ON il.ServiceID=s.ServiceID
		join ServiceCategory sc on s.ServiceCategoryID=sc.ServiceCategoryID
		join ServiceGroup sg on sc.ServiceGroupID=sg.ServiceGroupID
		where il.InvoiceHeaderID='.$ihid);
		
	$sGroup=$sg[0]->ServiceGroupID;
	$sCategory=$sg[0]->ServiceCategoryID;

	if($sGroup=="20"){
		$Details=DB::select('select li.InvoiceHeaderID,li.HouseInvoiceID InvoiceLineID,tn.HouseNumber ServiceName,tn.MonthlyRent Amount,
				dbo.fnMonthName([Month])+\'-\'+convert(nvarchar(20),[year]) [Description]
				,tn.balance-tn.monthlyrent Arrears
				from HouseInvoices li
				left join Tenancy tn on li.HouseNumber=tn.HouseNumber				
				left join invoicelines il on li.InvoiceHeaderID=il.InvoiceHeaderID
				left join services s on il.ServiceID=s.ServiceID 
				where il.InvoiceHeaderID='.$ihid);
	}else {
		$Details=DB::select('Select 0 Arrears');
	}

	return View::make('applications.invoice', [ 'invoice' => $invoice, 'bill' => $bill, 'customer' => Session::get('customer'),'Details'=>$Details ]);
  }

  public function viewinvoice($ihid) {
      $bill = ServiceGroup::select(['ServiceGroupName', 'ServiceGroupID'])->get();
      $invoice = Invoice::findOrFail($ihid);

      return View::make('dashboard.pdf', [ 'bill' => $bill, 'id' => $invoice->id()  ]);
  }

  public function invoicepdf($ihid) {
	$bill = ServiceGroup::select(['ServiceGroupName', 'ServiceGroupID'])->get();
	$invoice = Invoice::findOrFail($ihid);

	$sg=DB::select('SELECT sg.ServiceGroupID,sc.ServiceCategoryID FROM 
		InvoiceLines IL 
		join Services S ON il.ServiceID=s.ServiceID
		join ServiceCategory sc on s.ServiceCategoryID=sc.ServiceCategoryID
		join ServiceGroup sg on sc.ServiceGroupID=sg.ServiceGroupID
		where il.InvoiceHeaderID='.$ihid);

	$sGroup=$sg[0]->ServiceGroupID;
	$sCategory=$sg[0]->ServiceCategoryID;

	if($sGroup=="20"){
		$Details=DB::select('select li.InvoiceHeaderID,li.HouseInvoiceID InvoiceLineID,tn.HouseNumber ServiceName,tn.MonthlyRent Amount,
				\'Rent For \'+tn.HouseNumber+\' For \'+dbo.fnMonthName([Month])+\'-\'+convert(nvarchar(20),[year]) [Description]
				,tn.balance-tn.monthlyrent Arrears
				from HouseInvoices li
				left join Tenancy tn on li.HouseNumber=tn.HouseNumber				
				left join invoicelines il on li.InvoiceHeaderID=il.InvoiceHeaderID
				left join services s on il.ServiceID=s.ServiceID 
				where il.InvoiceHeaderID='.$ihid);
	}else {
		$Details=DB::select('Select 0 Arrears');
  }
  // echo '<pre>';
  // print_r($invoice);
  // exit;

      return View::make('applications.invoicepdf', [ 'invoice' => $invoice, 'bill' => $bill, 'customer' => Session::get('customer'),'Details'=>$Details  ]);
  }

  public function sbp($id) {

    $bill = ServiceGroup::select(['ServiceGroupName', 'ServiceGroupID'])->get();

    $application = ServiceHeader::findOrFail($id);
    $service = Service::findOrFail($application->ServiceID);
    //dd($application);
    if($application->ServiceStatusID == 5) {
      return View::make('permits.show', ['bill' => $bill, 'service' => $service, 'application' => $application ]);
    }

    Session::flash('message', 'Your SBP application is still being processed! Check back soon.');
    return Redirect::route('portal.dashboard');
  }

  public function invoices() 
  {


      $bill = ServiceGroup::select(['ServiceGroupName', 'ServiceGroupID'])->get();

      $customer_id=Session::get('customer')->CustomerID;

      $apps = Application::where('CustomerID', $customer_id)->Select('ServiceHeaderID')->get()->toArray();



      $invoices = Invoice::whereIn('ServiceHeaderID',$apps)->orderBy('InvoiceHeaderID','desc')->get();
  
      return View::make('applications.invoices', [ 'invoices' => $invoices, 'bill' => $bill ]);
  }
  
  public function receipts($hid) {	
	$bill = ServiceGroup::select(['ServiceGroupName', 'ServiceGroupID'])->get();
    $Receipts = ReceiptLine::where('InvoiceHeaderID', $hid)->get();
    $Desc=InvoiceLine::where('InvoiceHeaderID',$hid)->first();
	if(!is_null($Desc)){
		$Description=$Desc->Description;
	}

    return View::make('dashboard.receipts_2', [ 'receipts' => $Receipts, 'bill' => $bill,'InvoiceNo'=>$hid,'Description'=>$Description]);
  }

  protected  function invalid_service()    {
      $rules=['service_id' => 'required|exists:Services,ServiceID',];
      $msgs='Select the service to apply';

      $valid = Validator::make(Input::all(), $rules, $msgs);

      //dd($valid);

      return Redirect::back()
          ->withErrors($valid)
          ->withInput(Input::all());
  }

  protected function getMap() {
    $hire = [
      11200 => 'ToDate',
      11201 => 'FromDate',
    ];
    $house = [
      1167 => 'EstateID',
      1168 => 'HouseNumber',
    ];
    $land = [ // 81: land registration
      12232 => 'LRN',
      134 => 'TitleNo',
      12233 => 'PlotNo',
      12234 => 'MPlotNo',
    ];
    return [
      81 => $land,

      50 => $house, // 50: House application
      51 => $house, // 51: School House application
      80 => $house, // 51: Stall application

      84 => $hire,
      85 => $hire,
      86 => $hire,
      87 => $hire,
    ];
  }


  protected function getSpecs($k) {
    $specs = $this->getMap();
    return array_key_exists($k, $specs) ? $specs[$k] : null;
  }

  protected function getType ($KeyToCheck) {
    //application types
    $types = [
      81 => 1,
    ];

    return array_key_exists($KeyToCheck, $types) ? $types[$KeyToCheck] : 0;
  }

  protected function getFields($input, $type, $d) {
    $data = [];
    $specs = $this->getSpecs($type);
    foreach ($input as $key => $value) {
      if ( array_key_exists($key, $specs) ) {
        array_push($data, [ $specs[$key] => $value ]);
      }
    }
    foreach($data as $k=>$v) { $i = key($v);  $d[$i] = $v[$i]; }
    return $d;
  }

  protected function persistEntity (array $input, $type, $sh) {
    $d = [
      'ServiceHeaderID' => $sh,
      'CreatedDate' => date('Y-m-d H:i:s'),
    ];
    switch ($type) {
      case 81:
        $data = $this->getFields($input, $type, $d);
        $landapplication = LandApplication::firstOrNew(['ServiceHeaderID' => $sh]);
        $landapplication->fill($data);
        $landapplication->save();
        //dd(LandApplication::all()->count());
        break;
      case 50:
      case 51:
      case 80:
        $data = $this->getFields($input, $type, $d);
        $houseapplication = HouseApplication::firstOrNew(['ServiceHeaderID' => $sh]);
        $houseapplication->fill($data);
        $houseapplication->save();
        break;
      case 84:
      case 85:
      case 86:
      case 87:
        $data = $this->getFields($input, $type, $d);
        $houseapplication = HireApplication::firstOrNew(['ServiceHeaderID' => $sh]);
        $houseapplication->fill($data);
        $houseapplication->save();
        break;

      default:
        dd('no entity');
        break;
    }
    return 0;
    //return array_key_exists($k, $types) ? $types[$k] : 0;
  }


  protected function extractSpecific($input, $type) {
    //static form fields
    $specs = $this->getMap();
    // echo '<pre>';
    // print_r($specs);
    // print_r($type);
    // exit;

    if(!array_key_exists($type, $specs)) { 
      return;
     }

    $general = [];
    $particular = [];

    foreach ($input as $key => $value) {
      if ( array_key_exists($key, $specs[$type]) ) {
        array_push($particular, [ $key => $value ]);
      } else {
        array_push($general, [ $key => $value ]);
      }
    }

    $g = $p = [];
    foreach($general as $k=>$v) { $i = key($v);  $g[$i] = $v[$i]; }
    foreach($particular as $k=>$v) { $i = key($v);  $p[$i] = $v[$i]; }

    return [ 'general' => $input, 'particular' => $p ];

  }

  protected function createInvoice($sh,$sid){
    //get the application fee
    $charges=DB::select('select * from [dbo].[fnApplicationFee] ('.$sid.')'); 

    $Amount=0;
    if(sizeof($charges)>0){
      $Amount=$charges[0]->Amount;
    }

    DB::beginTransaction();

      //create invoice
      $invoice = new Invoice();    
      $invoice->ServiceHeaderID = $sh;
      $invoice->Amount = $Amount;
      $invoice->CreatedBy = Auth::id();

      if($invoice->save()){
        //create line
        $line = new InvoiceLine();    
        $line->InvoiceHeaderID = $invoice->InvoiceHeaderID;
        $line->ServiceID=$sid;
        $line->Amount = $Amount;
        $line->CreatedBy = Auth::id();
        if($line->save()){
          DB::commit();
          return $line->InvoiceHeaderID;
        }
      }
      DB::rollBack();
  }

  
  protected function persistLicenceRenewalApplication($input) {
    
    $msgs = $rules = [];
    
    $CategoryID = Service::find(intval(Input::get('service_id')))->category()->first()->id();
    

    $service = Service::find(Input::get('service_id'));
   
    // echo '<pre>';
    // print_r(Input::get());
    // exit;


    // $cat = $service->ServiceCategoryID;
 

    $formID = 2; //DB::table('ServiceCategory')->where('ServiceCategoryID', $CategoryID)->pluck('FormID');

    $form = LicenceRenewalForm::findOrFail($formID);
    foreach($form->sections() as $section){
      foreach($section->columns() as $field) {
        if($field->id() == 11202 || $field->id() == 11203 || $field->id() == 11203) {  }
        else {
          if($field->Mandatory == 1) {
            $rules[$field->id()] = 'required|string';
            $msgs[$field->id() . '.required' ] = $field . ' is required.';
          } else {
            $rules[$field->id()] = 'string';
            $msgs[$field->id() . '.string'] = $field . ' may only contain letters.';
          }
        }
      }
    }

    $valid = Validator::make($input, $rules, $msgs);
    if ($valid->fails()){
       return Redirect::back()->withErrors($valid)->withInput($input);
       }
    //dd($record);
    $LicenceRenewals = new LicenceRenewals();
  
    $LicenceRenewals->LicenceNo = Input::get('PermitNo');
    $LicenceRenewals->RenewalFormId = Input::get('form_id');
    $LicenceRenewals->SubmissionDate = date('Y-m-d H:i:s');
    $LicenceRenewals->LicenceRenewalStatusId =1; // Input::get('service_id');
    $LicenceRenewals->ServiceId =  Input::get('service_id');
    // $LicenceRenewals->ServiceCategoryId = Input::get('CategoryNumber');
    $LicenceRenewals->ServiceCategoryId =4; //(Input::get('CategoryNumber'))?Input::get('CategoryNumber'):2;

    $LicenceRenewals->CustomerID = (Session::get('customer')->CustomerID);
    // $LicenceRenewals->ServiceHeaderType = (is_null($form->ServiceHeaderType) ? 4 : $form->ServiceHeaderType);
    

    $LicenceRenewals->save();

    $LicenceId = $LicenceRenewals->id();
    


    $info = $this->extractSpecific($input, $CategoryID);
    // echo '<pre>';
    // print_r(Input::all());
    // exit;

    if( !is_null($info) ) {
      $this->persistEntity(($info['particular']), $type, $sh);
    }
   
    $columns = Input::get('ColumnID');
    foreach($columns as $key => $value)    {
        $params['LicenceId'] = $LicenceId;
        $params['ColumnID'] = $key;
        $params['Value'] = $value;
        Api::AddLicenceRenewalFormData($params);
    }

    $input = Input::file()['files'];
    //
    $x=$this->uploadFiles($LicenceId,$input);
    //get the application Fee

    //$AppFeeServiceID = DB::table('ServicePlus')->where('ServiceID', $app->ServiceID)->pluck('service_add');

    $InvoiceHeaderID=$this->createInvoice($LicenceRenewals->LicenceId,Input::get('service_id'));
    Session::flash('message','Application Created successfully. Invoice Number: '.$InvoiceHeaderID.' for Application fee have been Generated. Please therefore proceed to pay to complete your Application');
    return Redirect::route('portal.home');
  }

  protected function persistApplication($input) {
    
    $msgs = $rules = [];
    
    $CategoryID = Service::find(intval(Input::get('service_id')))->category()->first()->id();
    

    $service = Service::find(Input::get('service_id'));
   

    // $cat = $service->ServiceCategoryID;
 

    $formID = DB::table('ServiceCategory')->where('ServiceCategoryID', $CategoryID)->pluck('FormID');

    $form = ServiceForm::findOrFail($formID);
    foreach($form->sections() as $section){
      foreach($section->columns() as $field) {
        if($field->id() == 11202 || $field->id() == 11203 || $field->id() == 11203) {  }
        else {
          if($field->Mandatory == 1) {
            $rules[$field->id()] = 'required|string';
            $msgs[$field->id() . '.required' ] = $field . ' is required.';
          } else {
            $rules[$field->id()] = 'string';
            $msgs[$field->id() . '.string'] = $field . ' may only contain letters.';
          }
        }
      }
    }

    $valid = Validator::make($input, $rules, $msgs);
    if ($valid->fails()){
       return Redirect::back()->withErrors($valid)->withInput($input);
       }
    //dd($record);
    $app = new Application(); 
  
    $app->ServiceStatusID = 1;
    $app->FormID = $formID; //Input::get('form_id');
    $app->SubmissionDate = date('Y-m-d H:i:s');
    $app->ServiceID = Input::get('service_id');
    $app->ServiceHeaderType = $this->getType($CategoryID);
    // $app->ServiceCategoryId = Input::get('CategoryNumber');
    $app->ServiceCategoryId = (Input::get('CategoryNumber'))?Input::get('CategoryNumber'):0;
    $app->CustomerID = (Session::get('customer')->CustomerID);
    $app->ServiceHeaderType = (is_null($form->ServiceHeaderType) ? 4 : $form->ServiceHeaderType);
    

    $app->save();

    $HeaderId = $app->id();
    


    $info = $this->extractSpecific($input, $CategoryID);
    // echo '<pre>';
    // print_r(Input::all());
    // exit;

    if( !is_null($info) ) {
      $this->persistEntity(($info['particular']), $type, $sh);
    }
   
    $columns = Input::get('ColumnID');
    foreach($columns as $key => $value)    {
        $params['ServiceHeaderID'] = $HeaderId;
        $params['ColumnID'] = $key;
        $params['Value'] = $value;
        Api::AddFormData($params);
    }

    $input = isset(Input::file()['files'])?Input::file()['files']:null;
    //
    $x=$this->uploadFiles($HeaderId,$input);
    //get the application Fee

    //$AppFeeServiceID = DB::table('ServicePlus')->where('ServiceID', $app->ServiceID)->pluck('service_add');

    $InvoiceHeaderID=$this->createInvoice($app->ServiceHeaderID,$app->ServiceID);
    Session::flash('message','Application Created successfully. Invoice Number: '.$InvoiceHeaderID.' for Application fee have been Generated. Please therefore proceed to pay to complete your Application');
    return Redirect::route('portal.home');
  }

  protected function persistApplicationForSubmit($input) {
    
    $msgs = $rules = [];
    
    $CategoryID = Service::find(intval(Input::get('service_id')))->category()->first()->id();
  //   $m = DB::table('Services')->where('ServiceID', Input::get('service_id'))->pluck('ServiceCategoryID');
  //  echo '<pre>';
  //   print_r(Input::all() );
  //   exit;


    $service = Service::find(Input::get('service_id'));
   

    // $cat = $service->ServiceCategoryID;
 

    $formID = DB::table('ServiceCategory')->where('ServiceCategoryID', $CategoryID)->pluck('FormID');

    $form = ServiceForm::findOrFail($formID);
    foreach($form->sections() as $section){
      foreach($section->columns() as $field) {
        if($field->id() == 11202 || $field->id() == 11203 || $field->id() == 11203) {  }
        else {
          if($field->Mandatory == 1) {
            $rules[$field->id()] = 'required|string';
            $msgs[$field->id() . '.required' ] = $field . ' is required.';
          } else {
            $rules[$field->id()] = 'string';
            $msgs[$field->id() . '.string'] = $field . ' may only contain letters.';
          }
        }
      }
    }

    $valid = Validator::make($input, $rules, $msgs);
    if ($valid->fails()){ return Redirect::back()->withErrors($valid)->withInput($input); }
    //dd($record);
    $app = new Application();
  
    $app->ServiceStatusID = 1;
    $app->FormID = Input::get('form_id');
    $app->SubmissionDate = date('Y-m-d H:i:s');
    $app->ServiceID = Input::get('service_id');
    $app->ServiceHeaderType = $this->getType($CategoryID);
    $app->ServiceCategoryId = (Input::get('CategoryNumber'))?Input::get('CategoryNumber'):0;
    $app->CustomerID = (Session::get('customer')->CustomerID);
    $app->ServiceHeaderType = (is_null($form->ServiceHeaderType) ? 4 : $form->ServiceHeaderType);
    

    $app->save();

    $HeaderId = $app->id();
    


    $info = $this->extractSpecific($input, $CategoryID);
    // echo '<pre>';
    // print_r(Input::all());
    // exit;

    if( !is_null($info) ) {
      $this->persistEntity(($info['particular']), $type, $sh);
    }
   
    $columns = Input::get('ColumnID');
    foreach($columns as $key => $value)    {
        $params['ServiceHeaderID'] = $HeaderId;
        $params['ColumnID'] = $key;
        $params['Value'] = $value;
        Api::AddFormData($params);
    }

    $input = isset(Input::file()['files'])?Input::file()['files']:'';
    //
    $x=$this->uploadFiles($HeaderId,$input);
    //get the application Fee

    //$AppFeeServiceID = DB::table('ServicePlus')->where('ServiceID', $app->ServiceID)->pluck('service_add');

    $InvoiceHeaderID=$this->createInvoice($app->ServiceHeaderID,$app->ServiceID);
    Session::flash('message','Application Updated successfully. Invoice Number: '.$InvoiceHeaderID.' for Application fee have been Generated. Please therefore proceed to pay to complete your Application');
    return Redirect::route('portal.home');
  }

  protected function uploadFiles($appID,$input){
    if(!empty(Input::file())) 
    {
        $destinationPath = storage_path('uploads');
        $files = $input;
        $i=0;

        // print('<pre>');
        // print_r($files); exit;

        foreach ($files as $key => $file) 
        {
          // print('<pre>');
          // print_r($file); exit;

          if(!is_null($file))
          {
            $DocumentID=$key;
            $CustomerID=(Session::get('customer')->CustomerID);
            $name = $file->guessClientExtension();
            $fileName = time().'.'.$file->getClientOriginalExtension();

            $destination=$destinationPath."/".$DocumentID."/".$CustomerID."/".$appID;

            //
            if (!file_exists($destination)) {
                mkdir($destination, 0777, true);
            }

            $filePath=$destination."/".$fileName;
            

            $file->move($destination, $fileName);            
            $document = new Attachments();
            $document->ApplicationNo = $appID;
            $document->DocumentID = $DocumentID;
            $document->FilePath=$filePath;
            $document->save();                      
          }
        }
    }
  }

}
