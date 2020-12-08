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

    // print_r($data);exit;
$ServiceCategoryID = DB::table('ServiceHeader')
      ->select(['ServiceCategory.ServiceCategoryID',
            'ServiceHeader.ServiceHeaderID',
            'ServiceHeader.PermitNo as No',
            'Services.ServiceName',
            'ServiceHeader.CreatedDate as Date',
            'ServiceStatus.ServiceStatusDisplay'])
      ->where('ServiceHeader.CustomerID', Session::get('customer')->CustomerID)
      ->join('Customer','Customer.CustomerID','=','ServiceHeader.CustomerID')
      ->join('Services','Services.ServiceID','=','ServiceHeader.ServiceID')
      ->join('ServiceStatus','ServiceStatus.ServiceStatusID','=','ServiceHeader.ServiceStatusID')
      ->join('ServiceCategory','ServiceCategory.ServiceCategoryID','=','Services.ServiceCategoryID')
      ->orderBy('ServiceHeader.CreatedDate', 'desc')
      ->pluck('ServiceCategoryID');
// print_r($ServiceCategoryID);exit;
	  
    return View::make('applications.all', ['applications'=> $data, 'bill' => $bill, 'ServiceCategoryID'=> $ServiceCategoryID ]);
  }

  public function allrenewals() 
  {
    $bill = ServiceGroup::select(['ServiceGroupName', 'ServiceGroupID'])->get();
	 //dd(Session::get('customer'));
    $data = DB::table('LicenceRenewals')
      ->select(['LicenceRenewals.ServiceHeaderID',
            'LicenceRenewals.LicenceNo as No',
            'LicenceRenewals.LicenceRenewalDate',
            'Services.ServiceName',
            'LicenceRenewals.SubmissionDate as Date',
            'LicenceRenewalStatus.StatusName as ServiceStatusDisplay'])
      ->where('LicenceRenewals.CustomerID', Session::get('customer')->CustomerID)
      ->where('LicenceRenewals.Renewed', 1)
      ->join('Customer','Customer.CustomerID','=','LicenceRenewals.CustomerID')
      ->join('Services','Services.ServiceID','=','LicenceRenewals.ServiceID')
      ->join('LicenceRenewalStatus','LicenceRenewalStatus.Id','=','LicenceRenewals.LicenceRenewalStatusId')
      ->orderBy('LicenceRenewals.SubmissionDate', 'desc')
      ->get();

      // echo '<pre>';
      // print_r($data);exit;

	  
    return View::make('applications.allrenewals', ['applications'=> $data, 'bill' => $bill ]);
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

      $ServiceId =DB::table('ServiceHeader')
      ->select(['ServicecategoryID'])
      ->where('ServiceHeader.ServiceHeaderID', $ServiceHeaderID)
      // ->get();
      ->pluck('ServiceHeader.ServicecategoryID');

      $services = Service::where('ServicecategoryID',$ServiceId)->get();
      // echo '<pre>';
      // print_r($services );
      // exit;
      // print_r($ServiceId);
      // exit;

      //Check if Licence Renewal Application has been Made
      $Li =DB::table('LicenceRenewals')
                        ->select(['*'])
                        ->where('LicenceRenewals.ServiceId', $ServiceId)
                        ->where('LicenceRenewals.Renewed', 0)
                        ->get();
                        // -/>pluck('Amount');
      if(empty($Li)){
                  //Get the Renewal Form
          $form = LicenceRenewalForm::findOrFail(2); //2 is For Renewal
          $docs=DB::select('select * from vwRequiredDocuments where ServiceCategoryID=9'); 
          $StandardRenewalFee =

          DB::table('ServiceCharges')
                            ->select(['Amount'])
                            ->where('ServiceCharges.ServiceID', $ServiceId)
                            // ->get();
                            ->pluck('Amount');

          $ExpiryDate = new DateTime($data[0]->ExpiryDate);
          $IssuedDate = new DateTime($data[0]->IssuedDate);
          $currentDate = new DateTime("now");
          // $MonthsOverdue = $interval->m;
          $day = 31; $month =01; $year = date("Y")+1;
          $d=mktime(00, 00, 00, $month,$day, $year);
          $M = date("d-m-Y", $d);
          $EndOfWaiverDate = new DateTime($M);
          
          $date = strtotime($data[0]->IssuedDate);
          $YearLicenceWasIssued = date("Y", $date);
          
          $TodayYear = date("Y");
          $DaysRemainingToRenewalDate = $currentDate->diff($EndOfWaiverDate)->days;

          if($DaysRemainingToRenewalDate > 900){
            $AllowRenew=false;
            Session::flash('message','Licences are Renewed With 3 Months Remaining to End of Waiver Date');
            return Redirect::route('grouped.licences');
          }else{
            $AllowRenew=true;
          }
          if($YearLicenceWasIssued == $TodayYear){ //Licence Was Isssued This Year. No Interest Charged
              $PenaltyAmountToPay = 0;
              $Penalty = false;
          }else{
            if($currentDate > $EndOfWaiverDate){ //Past End Of Waiver Date
              $interval = $currentDate->diff($EndOfWaiverDate); 
              $DiffrenceBetweenEndOfWaiverAndLicenceExpiryDate = $ExpiryDate->diff($EndOfWaiverDate)->m;
              $Penalty = true;
            }else{//Get Diffence Between Expiry Date and Waiver Date
              $DaysInBetween = $ExpiryDate->diff($EndOfWaiverDate)->days;
              if($DaysInBetween > 31){
                $ISPastEndOfWaiver = true;
                $Penalty = true;
                $Diff = $ExpiryDate->diff($currentDate);
                $MonthsPast = (($Diff->y) * 12) + ($Diff->m);
                // echo '<pre>';
                // print_r( $Diff);

                // print_r( $ExpiryDate);
                // print_r( $EndOfWaiverDate);

                // print_r( $MonthsPast);
                // exit;

              }else{
                $ISPastEndOfWaiver = false;
                $Penalty = false;
                $MonthsPast = 0;
              }
              $DiffrenceBetweenEndOfWaiverAndLicenceExpiryDate = $MonthsPast;
              // echo '<pre>';
              // print_r($ExpiryDate);
              // print_r($EndOfWaiverDate);
              // print_r($DiffrenceBetweenEndOfWaiverAndLicenceExpiryDate);
              // exit;

            }
         
            $PenaltyAmountToPay  = $this->CalculateSimpleInterest($StandardRenewalFee, $DiffrenceBetweenEndOfWaiverAndLicenceExpiryDate, 10 );
          }
       
      }else{//Already they Have Made an Application

        Session::flash('message','You Have Already Submitted A Renewal Application for this Licence');
        return Redirect::route('grouped.licences');

      }
	  
    return View::make('applications.licencerenewal_form', ['applications'=> $data,
        'bill' => $bill, 'Penalty'=>$Penalty, 
        'PenaltyAmountToPay'=>$PenaltyAmountToPay, 
        'StandardRenewalFee'=>$StandardRenewalFee, 
        'form'=>$form,
        'ServiceHeaderID'=>$ServiceHeaderID,
        'docs'=>$docs ]);
  }

  
  function CalculateSimpleInterest($principal, $number_of_periods, $interest_rate)
  {
    //variable and initializations
    $Interest = 0.0;
    // $Months = floor(($number_of_periods)/31);
    // echo '<pre>';
    // print_r($number_of_periods);
    // exit;
    //calculate simple interest
    $Interest = ($principal * $number_of_periods )*($interest_rate/100);
    $TotalAmountPayable = $Interest + $principal;
    // exit($number_of_periods);
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
      'application' => $app, 'form' => $form, 'AllowRenew'=>false, 'Status'=>$ApplicationStatus, 'SavedServiceName'=> $SavedServiceName,  'SavedServiceID'=> $SavedServiceID, 'categoryName'=>$categoryName, 'bill' => $bill, 'service' => $service , 'services' => $services, 'header' => $ServiceHeaderID
    ]);
  }

  public function viewlicence($ServiceHeaderID) {

    $bill = ServiceGroup::select(['ServiceGroupName', 'ServiceGroupID'])->get();
    $app = FormData::where('ServiceHeaderID', $ServiceHeaderID)->lists('Value', 'FormColumnID');
  
    $formID = ServiceHeader::where('ServiceHeaderID', $ServiceHeaderID)->pluck('FormID');
    $ApplicationStatus = ServiceHeader::where('ServiceHeaderID', $ServiceHeaderID)->pluck('ServiceStatusID');
    $serviceID = ServiceHeader::where('ServiceHeaderID', $ServiceHeaderID)->pluck('ServiceID');
    $ServiceCategoryId = ServiceHeader::where('ServiceHeaderID', $ServiceHeaderID)->pluck('ServiceCategoryId');
    $categoryName = DB::table('ServiceCategory')->where('ServiceCategoryID', intval($ServiceCategoryId))->pluck('CategoryName');
    $LicenceData = ServiceHeader::where('ServiceHeaderID', $ServiceHeaderID)->get();
    $PermitsData = Permits::where('ServiceHeaderID',3116)->get();
    // echo '<pre>';
    // print_r($PermitsData);
    // exit;

    if(is_null($formID)) {
      Session::flash('message','Application not found');
      return Redirect::route('portal.home');
    }
    $form = ServiceForm::findOrFail($formID);
    $service = Service::find($serviceID);
    $currentDate = new DateTime("now");
    $day = 31; $month =01; $year = date("Y")+1;
    $d=mktime(00, 00, 00, $month,$day, $year);
    //Get Diffrence in Months Between Day Licence Was issued and Waiver Period End Date
    $M = date("d-m-Y", $d);
    $EndOfWaiverDate = new DateTime($M);
    //Get diffrence between Epirery Date and End of Waiver in Months.
    //If greater than 3,They are Late!!!
    $ExpiryDate = new DateTime($LicenceData[0]['ExpiryDate']);
    $DaysInBetween = $ExpiryDate->diff($EndOfWaiverDate)->days;
      
    if($DaysInBetween > 31){
      $ISPastEndOfWaiver = true;
      $DaysPast = $DaysInBetween;
    }else{
      $ISPastEndOfWaiver = false;
      $DaysPast = 0;
    }

    //Renewing Of Licences is Only Allowed 3 months Before End of Year
    $currentDate = new DateTime("now");
    $DaysRemainingToRenewalDate = $ExpiryDate->diff($currentDate)->days;

    if($DaysRemainingToRenewalDate > 90){
      $AllowRenew=true;
    }else{
      $AllowRenew=true;
    }


    $services = Service::where('ServiceCategoryID', intval($service->ServiceCategoryID))->get();
    $SavedServiceName = Service::where('ServiceCategoryID', intval($ServiceCategoryId))->pluck('ServiceName');
    $SavedServiceID = Service::where('ServiceCategoryID', intval($ServiceCategoryId))->pluck('ServiceID');
 


    // //dd($app);

    return View::make('applications.show', [
      'application' => $app, 'form' => $form, 'AllowRenew'=>$AllowRenew, 
      'DaysRemainingToRenewalDate'=>$DaysRemainingToRenewalDate, 
      'LicenceData'=>$LicenceData, 'Status'=>$ApplicationStatus, 
      'SavedServiceName'=> $SavedServiceName,  
      'SavedServiceID'=> $SavedServiceID, 
      'categoryName'=>$categoryName, 'bill' => $bill, 
      'service' => $service , 'services' => $services, 
      'header' => $ServiceHeaderID,
      'ISPastEndOfWaiver'=>$ISPastEndOfWaiver,
      'DaysPast'=>$DaysPast,
      'PermitsData'=>$PermitsData
    ]);
  }

  public function viewrenewal($ServiceHeaderID) {

    $bill = ServiceGroup::select(['ServiceGroupName', 'ServiceGroupID'])->get();
    $app = FormData::where('ServiceHeaderID', $ServiceHeaderID)->lists('Value', 'FormColumnID');
      // echo '<pre>';
      // print_r($app);
      // exit;

    $formID =2;// ServiceHeader::where('ServiceHeaderID', $ServiceHeaderID)->pluck('FormID');
    $ApplicationStatus = ServiceHeader::where('ServiceHeaderID', $ServiceHeaderID)->pluck('ServiceStatusID');
    $serviceID = ServiceHeader::where('ServiceHeaderID', $ServiceHeaderID)->pluck('ServiceID');
    $ServiceCategoryId = ServiceHeader::where('ServiceHeaderID', $ServiceHeaderID)->pluck('ServiceCategoryId');
    $categoryName = DB::table('ServiceCategory')->where('ServiceCategoryID', intval($ServiceCategoryId))->pluck('CategoryName');
    $LicenceData = ServiceHeader::where('ServiceHeaderID', $ServiceHeaderID)->get();
    if(is_null($formID)) {
      Session::flash('message','Application not found');
      return Redirect::route('portal.home');
    }
    $form = ServiceForm::findOrFail($formID);
    $service = Service::find($serviceID);

    //Get Diff Between Expirer Date and Today's Date. If Less than 3 Month Don't Allow Renewal
    $ExpiryDate = new DateTime($LicenceData[0]['ExpiryDate']);
    $currentDate = new DateTime("now");
    $DaysRemainingToRenewalDate = $ExpiryDate->diff($currentDate)->days;
    if($DaysRemainingToRenewalDate > 90){
      $AllowRenew=false;
    }else{
      $AllowRenew=true;
    }


    $services = Service::where('ServiceCategoryID', intval($service->ServiceCategoryID))->get();
    $SavedServiceName = Service::where('ServiceCategoryID', intval($ServiceCategoryId))->pluck('ServiceName');
    $SavedServiceID = Service::where('ServiceCategoryID', intval($ServiceCategoryId))->pluck('ServiceID');
 
    // //dd($app);

    return View::make('applications.show', [
      'application' => $app, 'form' => $form, 'AllowRenew'=>$AllowRenew, 'DaysRemainingToRenewalDate'=>$DaysRemainingToRenewalDate, 'LicenceData'=>$LicenceData, 'Status'=>$ApplicationStatus, 'SavedServiceName'=> $SavedServiceName,  'SavedServiceID'=> $SavedServiceID, 'categoryName'=>$categoryName, 'bill' => $bill, 'service' => $service , 'services' => $services, 'header' => $ServiceHeaderID
    ]);
  }

  //

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

    $rules = [ 'service_id' => 'required' ];
    // ECHO '<PRE>';
    // print_r(Input::all());
    // exit;

    $rules = [ 'service_id' => 'required|exists:Services,ServiceID' ];
    $valid = Validator::make(Input::all(), $rules);
    $cols = Input::get('ColumnID'); //Get dATA
    // print_r(Input::all());exit;


    if ($valid->fails()){
      return Redirect::back()->withErrors($valid)->withInput($cols);
     }
   
    return $this->persistApplication($cols);
  }

  // public function update(){
  //   $rules = [ 'service_id' => 'required|exists:Services,ServiceID' ];
  //   $valid = Validator::make(Input::all(), $rules);
  //   $cols = Input::get('ColumnID'); //Get dATA
    // echo '<pre>';
    // print_r(Input::all());
    // exit;

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

  public function renewalinvoice($ihid) {
	  $Details="";
	  $bill = ServiceGroup::select(['ServiceGroupName', 'ServiceGroupID'])->get();
	  $invoice = LiceneRenewaInvoice::find($ihid);
  
    // echo '<pre>';
    // print_r($invoice->paid());
    // exit;

    // $sg=DB::select('SELECT sg.ServiceGroupID,sc.ServiceCategoryID FROM 
    //   InvoiceLines IL 
    //   join Services S ON il.ServiceID=s.ServiceID
    //   join ServiceCategory sc on s.ServiceCategoryID=sc.ServiceCategoryID
    //   join ServiceGroup sg on sc.ServiceGroupID=sg.ServiceGroupID
    //   where il.InvoiceHeaderID='.$ihid);

        // echo '<pre>';
        // print_r($invoice);
        // exit;
        
        // $sGroup=$sg[0]->ServiceGroupID;
        // $sCategory=$sg[0]->ServiceCategoryID;

      // if($sGroup=="20"){
      //   $Details=DB::select('select li.InvoiceHeaderID,li.HouseInvoiceID InvoiceLineID,tn.HouseNumber ServiceName,tn.MonthlyRent Amount,
      //       dbo.fnMonthName([Month])+\'-\'+convert(nvarchar(20),[year]) [Description]
      //       ,tn.balance-tn.monthlyrent Arrears
      //       from HouseInvoices li
      //       left join Tenancy tn on li.HouseNumber=tn.HouseNumber				
      //       left join invoicelines il on li.InvoiceHeaderID=il.InvoiceHeaderID
      //       left join services s on il.ServiceID=s.ServiceID 
      //       where il.InvoiceHeaderID='.$ihid);
      // }else {
      $Details=DB::select('Select 0 Arrears');
    // }

	  return View::make('applications.renewalinvoice', [ 'invoice' => $invoice, 'bill' => $bill, 'customer' => Session::get('customer'),'Details'=>$Details ]);
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


  public function licencerenewalinvoicepdf($ihid) {
    $bill = ServiceGroup::select(['ServiceGroupName', 'ServiceGroupID'])->get();
    $invoice = LiceneRenewaInvoice::findOrFail($ihid);
    // ECHO '<PRE>';
    // print_r($invoice);
    // exit;


		$Details=DB::select('Select 0 Arrears');


    return View::make('applications.licencerenewalinvoicepdf', [ 'invoice' => $invoice, 'bill' => $bill, 'customer' => Session::get('customer'),'Details'=>$Details  ]);
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

      // echo '<pre>';
      // print_r($invoices);
      // exit;
      return View::make('applications.invoices', [ 'invoices' => $invoices, 'bill' => $bill ]);
  }

  public function renewalinvoices() 
  {


      $bill = ServiceGroup::select(['ServiceGroupName', 'ServiceGroupID'])->get();

      $customer_id=Session::get('customer')->CustomerID;

      $apps = LicenceRenewals::where('CustomerID', $customer_id)
                                ->where('Renewed', 1)
      ->Select('LicenceId')->get()->toArray();
      
      $invoices = LiceneRenewaInvoice::whereIn('LicenceRenewalid',$apps)->orderBy('LicenceRenewalInvoiceHeaderID','desc')->get();
      // echo '<pre>';
      // print_r($invoices);
      // exit;


      return View::make('applications.renewalinvoices', [ 'invoices' => $invoices, 'bill' => $bill ]);
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

  public function renewalreceipts($hid) {	
    $bill = ServiceGroup::select(['ServiceGroupName', 'ServiceGroupID'])->get();
      $Receipts = LicenceRenewaReceiptLines::where('InvoiceHeaderID', $hid)->get();
      $Desc=LicenceRenewalnvoiceLines::where('InvoiceHeaderID',$hid)->first();
      if(!is_null($Desc)){
        $Description=$Desc->Description;
      }

      return View::make('dashboard.receipts_2', [ 'receipts' => $Receipts, 'bill' => $bill,'InvoiceNo'=>$hid,'Description'=>$Description, 'RenewalReceipt'=>true]);
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
    $LicenceRenewals->ServiceHeaderId = Input::get('ServiceHeaderID');
    $LicenceRenewals->ServiceCategoryId =4; //(Input::get('CategoryNumber'))?Input::get('CategoryNumber'):2;
    $LicenceRenewals->CustomerID = (Session::get('customer')->CustomerID);
    $LicenceRenewals->RenewalFee = (int)Input::get('LicenceFee');
    // $LicenceRenewals->ServiceHeaderType = (is_null($form->ServiceHeaderType) ? 4 : $form->ServiceHeaderType);
    $LicenceRenewals->save();

    $LicenceId = $LicenceRenewals->id();
    if(Input::get('LateRenewalCharges')){ //Late Payment Detected
      $Penalties = new Penalties();
      $Penalties->ServiceHeaderID = Input::get('ServiceHeaderID');
      $Penalties->Description = 'Late Renewal Charge';
      $Penalties->CreateDate = date('Y-m-d H:i:s');
      $Penalties->Amount = Input::get('LateRenewalCharges');
      $Penalties->save();

    }else{
      exit('7347');
    }


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

    // $InvoiceHeaderID=$this->createInvoice($LicenceRenewals->LicenceId,Input::get('service_id'));
    Session::flash('message','Application Submitted successfully.');
    return Redirect::route('portal.home');
  }

  protected function persistApplication($input) {
    
    $msgs = $rules = [];
    //  echo '<pre>';
    // print_r(Input::all() );
    // exit;

    $CategoryID = Service::find(intval(Input::get('service_id')))->category()->first()->id();
    

    $service = Service::find(Input::get('service_id'));
   

    // $cat = $service->ServiceCategoryID;
 

    $formID = DB::table('ServiceCategory')->where('ServiceCategoryID', Input::get('CategoryNumber'))->pluck('FormID');
    // exit($formID);
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
    
    $TraRegionCode = DB::table('Counties')
              ->where('CountyId', Session::get('customer')->BusinessZone)
              ->pluck('TraRegionCode');
  
    $app->ServiceStatusID = 1;
    $app->FormID = $formID; //Input::get('form_id');
    $app->SubmissionDate = date('Y-m-d H:i:s');
    $app->ServiceID = Input::get('service_id');
    
    if(Session::get('customer')->Type == 'individual'){
      $app->SubSystemID = $TraRegionCode;
      $app->ServiceHeaderType = 5; //Individual Licence Applications
      //$app->SubSystemID =(Session::get('customer')->BusinessZone);
      $app->ServiceCategoryId = (Input::get('CategoryNumber'))?Input::get('CategoryNumber'):0;
      $app->CustomerID = (Session::get('customer')->CustomerID);
     
    }else{
      $app->SubSystemID = $TraRegionCode;
      $app->ServiceHeaderType = $this->getType($CategoryID);
      $app->ServiceCategoryId = (Input::get('CategoryNumber'))?Input::get('CategoryNumber'):0;
      $app->CustomerID = (Session::get('customer')->CustomerID);
      $app->ServiceHeaderType = (is_null($form->ServiceHeaderType)
                                   ? 4 : $form->ServiceHeaderType
                                  );
    }

 
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


    //No Inspection for Individuals
    if(Session::get('customer')->Type == 'business'){
      //Assign Application to Inspectors Where The User Region Is
      $InspectionOfficers = DB::table('Users')
      ->select(['Users.Email',
                'Users.UserID',
                'Agents.AgentID',
                'Agents.FirstName',
                'Agents.Middlename',
                'Agents.LastName',
                ])
      ->where('Users.RegionID', Session::get('customer')->BusinessZone)
      ->join('Agents','Users.AgentID','=','Agents.AgentID')
      ->get();

        //Get A random One
      $InspectionOfficer=$InspectionOfficers[array_rand($InspectionOfficers)]; 

      $Inspections = new Inspections(); 
    
      $Inspections->ServiceHeaderID = $HeaderId;
      $Inspections->UserID = $InspectionOfficer->AgentID; //Input::get('form_id');
      $Inspections->InspectionStatusID = 1;
      $Inspections->CreatedDate = date('Y-m-d H:i:s');
      $Inspections->save();
        
    }

       
    
    //get the application Fee
    // $AppFeeServiceID = DB::table('ServicePlus')->where('ServiceID', $app->ServiceID)->pluck('service_add');
    // $InvoiceHeaderID=$this->createInvoice($app->ServiceHeaderID,$app->ServiceID);
    Session::flash('message','Application Submitted successfully.');
    if(Session::get('customer')->Type == 'business'){
      return Redirect::route('portal.home', [ 'id' =>  Session::get('customer')->BusinessTypeID ]);

    }
    return Redirect::route('portal.individual', [ 'id' =>  Session::get('customer')->CustomerID ]);
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
       
          if(!is_null($file))
          {
            $DocumentID=$key;
            $CustomerID=(Session::get('customer')->CustomerID);
            $name = $file->guessClientExtension();
            $fileName = $file->getClientOriginalName();//time().'.'.$file->getClientOriginalExtension();
            $Name = $file->getClientOriginalName();
              //  print('<pre>');
              //   print_r($Name); exit;


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
            $document->AttachmentName=$Name;
            $document->save();                      
          }
        }
    }
  }

}
