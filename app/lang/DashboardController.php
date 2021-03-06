<?php

class DashboardController extends Controller {

  public function home() {
    //return View::make('welcome');
    $bill = ServiceGroup::select(['ServiceGroupName', 'ServiceGroupID'])->get();
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
    return View::make('dashboard.home', [ 'applications'=> $data, 'bill' => $bill, ]);
  }

  public function applicationform($cat) {
    if($cat == 0) {

    }

    $bill = ServiceGroup::select(['ServiceGroupName', 'ServiceGroupID'])->get();
    $services = Service::where('ServiceCategoryID', intval($cat))->get();
    $fid = DB::table('ServiceCategory')->where('ServiceCategoryID', intval($cat))->pluck('FormID');

    if(Session::get('customer')->Type == 'individual' && $fid == 2) {
      Session::flash('message', 'Switch to a business account to apply for a permit');
      return Redirect::route('portal.dashboard');
    }
    $form = ServiceForm::findOrFail($fid);
    //dd($fid);
    //$locform = ServiceForm::findOrFail(5020);
    $locform = ServiceForm::findOrFail(5022);
    //dd($services);
    return View::make('applications.form', ['bill' => $bill, 'services' => $services, 'location' => $locform, 'form' => $form ]);
  }

  public function registerBusiness() {
    $bill = ServiceGroup::select(['ServiceGroupName', 'ServiceGroupID'])->get();
    $form = ServiceForm::findOrFail(3017);
    return View::make('dashboard.business', ['bill' => $bill, 'form'=> $form]);
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
        //->where('LRN', $value['LRN'])->where('PlotNo', $value['PlotNo'])
        ->join('LandReceipts', 'Land.UPN', '=', 'LandReceipts.UPN')
        ->get();
      if(!is_null($plot)) { array_push($data, [ $plot ]); }
    }
    foreach($data as $k=>$v) { $i = key($v);  $d[$i] = $v[$i]; }
    foreach($d as $i => $a) { foreach ($a as $k => $v) { $plots[$k] = $v; }; }

    $bill = ServiceGroup::select(['ServiceGroupName', 'ServiceGroupID'])->get();
dd($plots);
    return View::make('dashboard.reports', ['bill' => $bill, 'reports' => $plots ]);
  }

  public function report($id) {
    $reports = DB::table('LandReceipts')->where('UPN', intval($id))->get();
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

    $banks = DB::table('banks')->lists('BankName', 'BankID');
    $bill = ServiceGroup::select(['ServiceGroupName', 'ServiceGroupID'])->get();

    return View::make('dashboard.payments', ['bill' => $bill, 'banks' => $banks, 'invoices' => $invoices ]);
  }

  public function searchinvoice($id) {
    try {
      $inv = Invoice::findOrFail(intval($id));
      $cust = Customer::find(intval($inv->CustomerID));
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

    return View::make('dashboard.payments', ['bill' => $bill, 'banks' => $banks, 'invoices' => $invoices ]);
  }

  public function searchland($lrn, $pno) {
    //dd($pno);
    $record = DB::table('Land')->where('LRN', $lrn)->where('PlotNo', $pno)->first();
    if(empty($record)) {
      return json_encode([ 'status' => 'error', 'error' => 'land record not found' ]);
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

  public function viewreceipt($cid) {
    $bill = ServiceGroup::select(['ServiceGroupName', 'ServiceGroupID'])->get();
    $invoices = Invoice::where('CustomerID', $cid)->get();

    return View::make('dashboard.receipts', [ 'invoices' => $invoices, 'bill' => $bill, 'customer' => Customer::findOrFail(intval($cid))  ]);
  }

  public function viewpermit($cid) {
    //$applications = ServiceHeader::where('CustomerID', intval($cid))->lists('ServiceHeaderID');
    $permits = DB::table('ServiceHeader')
            //->where('ServiceHeader.ServiceStatusID', 7)
            ->where('ServiceHeader.CustomerID', intval($cid))
            ->join('Permits', 'ServiceHeader.ServiceHeaderID', '=', 'Permits.ServiceHeaderID')
            ->select(['Permits.PermitNo', 'Permits.ServiceHeaderID' ])
            ->get();

    if(count($permits) == 0) {
      Session::flash('message', 'Could not find any permit for this account');
      return Redirect::route('portal.home');
    }

    $trimed = ($permits[0]->PermitNo);
    //dd($permits);
    $permit = public_path().'/admin/pdfdocs/sbps/'.$trimed.'.pdf';

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
    $date = date_create_from_format('M/d/Y', $data['date']);

    //dd(strtotime(date(DATE_RFC2822))); TODO

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

    if(intval($receipted) > intval($invoiced)) {
      Session::flash('message','The Amount Paid exceeds the invoiced amount!');
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
      'ReceiptDate' => $data['date'],
      'ReceiptMethodID' => $data['method'],
      'ReferenceNumber' => $data['slip_number'] ,
    ]);

    foreach (Input::get('invoice') as $key => $value) {
      ReceiptLine::create([
        'CreatedBy' => 1,
        'Amount' => $value,
        'InvoiceHeaderID' => $key,
        'ReceiptID' => $receipt->ReceiptID,
        'CreatedDate' => date('Y-m-d H:i:s'),
      ]);
    }

    Session::flash('message', 'Your payment has been receipted');
    return Redirect::route('portal.home');
  }

  public function postReceipt() {
    $data = [];
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
      'InvoiceHeaderID' => $data['invoice'],
      'ReferenceNumber' => $data['slip_number'] ,
    ]);

    Session::flash('message', 'Your receipt has been updated');
    return Redirect::route('portal.home');
  }
/*
  public function zones() {
    $subcounties = Subcounty::get(['SubCountyID', 'SubCountyName'])->toArray();
    $zones = [];
    foreach ($subcounties as $subcounty) {
      $wards = Ward::where('SubCountyID', intval($subcounty["SubCountyID"]))->get(['WardID', 'WardName'])->toArray();
      foreach ($wards as $ward) {
        $ward['zones'] = ['ZoneID' => $ward['WardID'], 'ZoneName' => $ward['WardName']];
      }
      $subcounty['wards'] = $wards;
      array_push($zones, $subcounty);
    }
    return Response::json($zones);
  }
  */

  public function viewBusiness($id) {
    $bill = ServiceGroup::select(['ServiceGroupName', 'ServiceGroupID'])->get();
    $business = DB::table('Customer')->where('CustomerID', $id)->select([
        'CustomerName', 'ContactPerson','PostalAddress', 'Town', 'Website', 'Email'
    ])->first();
    if($business) {
      return View::make('dashboard.viewbusiness', ['bill' => $bill, 'business' => $business  ]);
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
    //dd($services[2]->currentCharges()->get());
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
      $msg = 'You are now logged in as '.$customer;
      Session::flash('message', $msg);
      return Redirect::route('all.applications');
    }

    Session::flash('message','Invalid account details');
    return Redirect::route('portal.dashboard');
  }

}
