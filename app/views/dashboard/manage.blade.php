@extends('dashboard.index')

@section('dashboard-aside')
    <div id="department-menu" class="ui vertical accordion menu" style="width: 100%">
        <!-- <div class="header item">Manage</div> -->

        <div class="item"> </div>
        @if(Session::get('customer')->Type !== 'business')
        <a class="item" href="{{route('portal.accounts', [ 'cid' => Session::get('customer')->id() ])}}"> My Business Accounts </a>        
        @else
        <?php $xxxx = CustomerAgent::where('AgentID', Auth::User()->id())->where('AgentRoleID', 1)->pluck('CustomerID'); ?>
        <a class="item" href="{{route('switch.account', [ 'cid' => $xxxx ])}}"> Switch to Individual Account </a>        

        @endif
        <div class="item"> </div>
        <!-- <div class="item" id="all-applications"> <a href="{{route('all.applications')}}"> Applications </a> </div> -->

        
        <!-- @if(Session::get('customer')->Type == 'business')
        <div class="item"> </div>
        <a class="item" href="{{route('grouped.licences', [ 'cid' => Session::get('customer')->id() ])}}"> My Licences </a>
        
        @endif -->

        @if(Session::get('customer')->Type == 'business')
            <div class="item" id="permits">
                <a class="title">
                    <i class="dropdown icon"></i>
                    Licence Management
                </a>
                <div class="content">
                    <div class="menu">
                         <?php $cid = Session::get('customer')->id() ?>

                        <a class="item" href="{{route('grouped.licences', [ 'cid' => Session::get('customer')->id() ])}}">My Licences</a>
                        <a class="item" href="{{route('all.applications')}}">My Licence Applications</a>
                        <a class="item" href="{{route('allrenewals.applications')}}">Licence Renewal Applications</a>
                        <!-- <a class="item" href="{{route('application.invoices')}}">Licence Renewal Invoices</a>
                        <a class="item" href="{{route('receipts.view', [ 'cid' => $cid ] )}}">Licence Renewal Receipts</a> -->

                        <!-- <a class="item" href="{{route('permits.view', [ 'cid' => $cid ] )}}"> View Permit</a> -->
                    </div>
                </div>
            </div>

            <div class="item" id="invoice">
                <a class="title">
                    <i class="dropdown icon"></i>
                    Invoice Management Management
                </a>
                <div class="content">
                    <div class="menu">
                         <?php $cid = Session::get('customer')->id() ?>
                        <a class="item" href="{{route('application.invoices')}}">Licence Application Invoices</a>
                        <a class="item" href="{{route('receipts.view', [ 'cid' => $cid ] )}}">Licence Application Receipts</a>
                        <!-- <a class="item" href="{{route('allrenewals.applications')}}">Licence Renewal Applications</a> -->
                        <a class="item" href="{{route('application.renewalinvoices')}}">Licence Renewal Invoices</a>
                        <a class="item" href="{{route('receipts.view', [ 'cid' => $cid ] )}}">Licence Renewal Receipts</a>

                        <!-- <a class="item" href="{{route('permits.view', [ 'cid' => $cid ] )}}"> View Permit</a> -->
                    </div>
                </div>
            </div>
        @else
            <div class="item" id="businesses">
                <a class="active title">
                    <i class="dropdown icon"></i>
                    Business/Personal Accounts
                </a>
                <div class="content">
                    <div class="menu">
                        <a id="register-business" class="item" href="{{route('dashboard.business')}}">Register Businness</a>
                        <a id="registered-businesses" class="item" href="{{route('list.businesses')}}">View Registered Accounts</a>
                    </div>
                </div>
            </div>
        @endif

        <!-- <div class="item" id="invoices"> <a href="{{route('application.invoices')}}"> Invoices </a> </div> -->
        <?php $cid = Session::get('customer')->id() ?>
        <!-- <a class="item" href="{{route('receipts.view', [ 'cid' => $cid ] )}}"> Receipts </a> -->
        <!-- <a class="item" href="{{route('aggregate.payments')}}"> Pay </a> -->
        <!-- <a class="item" href="{{route('get.miscpay')}}"> Miscellaneous Payments </a> -->

    </div>
@endsection

@section('dashboard-content')

@endsection

@section('script')
  @parent()
  <script type="text/javascript">
    $( document ).ready(function() {
      $('#dashboard-menu #manage').trigger('click');
    });
  </script>

@endsection
