@extends('app')

@section('content')
  <div class="container">
    <div class="row">
      <div class="span12">
          <div class="window flat shadow">
              <div class="caption">
                  <span class="icon"><i class="fa fa-info"></i></span>
                  <div class="title">Land Details</div>
              </div>
              <div class="content">
                  <div class="container">
                    <div class="row">
                      <div class="span8 offset1">
                        UPN: {{$land->UPN}} <hr/>
                        BlockLRNumber: {{$land->BlockLRNumber}} <hr/>
                        PlotNumber: {{$land->PlotNumber}} <hr/>
                        DocumentNumber: {{$land->DocumentNumber}} <hr/>
                        LandRates: {{$land->LandRates}} <hr/>
                        TotalAnnualAmount: {{$land->TotalAnnualAmount}} <hr/>
                        TotalArrears: {{$land->TotalArrears}} <a class="button warning" href="#">Request Invoice</a>   <hr/>
                        AccumulatedPenalty: {{$land->AccumulatedPenalty}} <hr/>
                        CurrentBalance: {{$land->CurrentBalance}} <hr/>
                        CoOwner: {{$land->CoOwner}} <hr/>
                        CustomerName: {{$land->CustomerName}} <hr/>
                        Mobile1: {{$land->Mobile1}} <hr/>
                        Mobile1: {{$land->Mobile1}} <hr/>
                        IDNO: {{$land->IDNO}} <hr/>
                      </div>
                    </div>
                  </div>
              </div>
          </div>
      </div>
    </div>
  </div>


@endsection
