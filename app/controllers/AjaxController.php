<?php

class AjaxController extends BaseController { //ut

    public function getUsers()
    {
        $users = User::select([ 
            'UserProfileID as id',
            'FirstName',
            'MiddleName',
            'LastName',
            'Email',
            'IDNumber',
            'Mobile'
        ])
        ->where('CustomerProfileID',Auth::user()->CustomerProfileID);

        return Datatables::of($users)
            ->remove_column('id')
            ->add_column('view','<a href="{{route(\'view.profile\',$id)}}" >Show</a>')
            ->make();
    }


    public function getBusinesses()
    {
        $biz = Business::select([
            'CustomerID as id',
            'CustomerName',
            #'BusinessTypeName as type',
            'RegistrationNumber',
            'PIN',
            'VATNumber',
            'Customer.Telephone1',
            'Customer.Mobile1',
            'CountyName as country'
        ])
            ->where('CustomerProfileID',Auth::user()->CustomerProfileID)
        ->join('CountyDetails','CountyDetails.CountyID','=','Customer.CountyID');
        #->join('BusinessType','BusinessType.BusinessTypeID','=','Customer.BusinessTypeID')
        #->get();
        #dd($biz);
        return Datatables::of($biz)
            ->remove_column('id')
            #->add_column('view','<a href="{{route(\'view.biz\',$id)}}" >Show</a>')
            ->add_column('view','<a href="#" >Show</a>')
            ->make();
    }

    public function getApplications()
    {
      $data = DB::table('ServiceHeader')
        ->select(['ServiceHeader.ServiceHeaderID as ID',
              'ServiceHeader.PermitNo as No',
              'Customer.CustomerName',
              'Services.ServiceName',
              'ServiceHeader.CreatedDate as Date',
              'ServiceStatus.ServiceStatusDisplay'])
        ->where('ServiceHeader.CustomerID', Auth::id())
        ->join('Customer','Customer.CustomerID','=','ServiceHeader.CustomerID')
        ->join('Services','Services.ServiceID','=','ServiceHeader.ServiceID')
        ->join('ServiceStatus','ServiceStatus.ServiceStatusID','=','ServiceHeader.ServiceStatusID');
        /*->get();
        dd($path);
        $permit = DB::table('serviceheader')
          ->where('CustomerID', Auth::id())
          ->where('FormID', 2)
          ->pluck('PermitNo');
        $apps = Application::select([
            'ServiceHeader.ServiceHeaderID as ID',
            'Customer.CustomerName',
            'Services.ServiceName',
            'ServiceHeader.CreatedDate as Date',
            'ServiceStatus.ServiceStatusDisplay as Status'
        ])
            //->where('Customer.CustomerProfileID',Auth::user()->CustomerProfileID)
            ->join('Customer','Customer.CustomerID','=','ServiceHeader.CustomerID')
            ->join('Services','Services.ServiceID','=','ServiceHeader.ServiceID')
            ->join('ServiceStatus','ServiceStatus.ServiceStatusID','=','ServiceHeader.ServiceStatusID');
            */
        return Datatables::of($data)
            ->remove_column('ID')
            ->remove_column('No')
            //->add_column('view','<a href="{{route(\'view.biz\',$ID)}}" >Show</a>')
            //->add_column('view','<a href="./uploads/9-Registration-Certificate.pdf" >Show</a>')
            ->add_column('view', function($model) {
              return ($model->No) ? '<a href="'.(asset('revenueadmin/pdfdocs/sbps')).'/'.($model->No).'.pdf"> Show </a>' : '';
            })
            ->make();
    }

    public function approvedApplications()
    {
        $apps = Application::select([
            'ServiceHeader.ServiceHeaderID as ID',
            'Customer.CustomerName',
            'Services.ServiceName',
            'ServiceHeader.CreatedDate as Date',
            'ServiceStatus.ServiceStatusDisplay as Status'
        ])
            ->where('Customer.CustomerProfileID', Auth::user()->CustomerProfileID)
            ->where('ServiceStatus.ServiceStatusID', 5)
            ->join('Customer','Customer.CustomerID','=','ServiceHeader.CustomerID')
            ->join('Services','Services.ServiceID','=','ServiceHeader.ServiceID')
            ->join('ServiceStatus','ServiceStatus.ServiceStatusID','=','ServiceHeader.ServiceStatusID');

        return Datatables::of($apps)
            ->remove_column('ID')
            //->add_column('view','<a href="{{route(\'view.biz\',$ID)}}" >Show</a>')
            ->add_column('view','<a href="./uploads/9-Registration-Certificate.pdf" >Show</a>')
            ->make();
    }


    public function getPermits()
    {
        $apps = Application::select([
            'ServiceHeader.PermitNo',
            'ServiceHeader.ServiceHeaderID as ID',
            'Customer.CustomerName',
            'Services.ServiceName',
            'ServiceHeader.CreatedDate as Date',
            'ServiceStatus.ServiceStatusDisplay as Status'
        ])
            ->Permits()
            ->where('Customer.CustomerProfileID',Auth::user()->CustomerProfileID)
            ->join('Customer','Customer.CustomerID','=','ServiceHeader.CustomerID')
            ->join('Services','Services.ServiceID','=','ServiceHeader.ServiceID')
            ->join('ServiceStatus','ServiceStatus.ServiceStatusID','=','ServiceHeader.ServiceStatusID');

        return Datatables::of($apps)
            ->remove_column('ID')
            ->add_column('view','<a href="{{route(\'download.permit\',$ID)}}" >Download</a>')
            ->make();
    }

    public function getInvoices()
    {
        $items = Invoice::select([
            'InvoiceHeader.InvoiceHeaderID as ID',
            'Customer.CustomerName as Customer',
            'InvoiceHeader.InvoiceDate as Date',
            DB::raw('sum(Amount) as Total')
        ])


            ->join('Customer','Customer.CustomerID','=','InvoiceHeader.CustomerID')
            ->join('InvoiceLines','InvoiceLines.InvoiceHeaderID','=','InvoiceHeader.InvoiceHeaderID')
            ->where('Customer.CustomerProfileID','=',Auth::user()->CustomerProfileID)
            ->groupBy('CustomerName')
            ->groupBy('InvoiceHeader.InvoiceHeaderID')
            ->groupBy('InvoiceDate');

        return Datatables::of($items)
            ->add_column('view','<a href="{{route(\'my.invoice\',$ID)}}" >Show</a>')
            ->make();
    }

    public function getDatatable()
    {
        return Datatable::collection(User::all(array('id','name')))
            ->showColumns('id', 'name')
            ->searchColumns('name')
            ->orderColumns('id','name')
            ->make();
    }
}
