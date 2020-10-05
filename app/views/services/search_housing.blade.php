@extends('app')

@section('content')
    <label><h2> House Details</h2></label>
    <hr/>
    @if($invoiced)
    <div class="well">
      Tenant's Name: {{$house['CustomerName']}}
      <br/>
      <br/>
      Balance Due: {{$house['Balance']}} KSh.
      <br/>

      <br/>
      <a href="{{route('my.invoice', $house['InvoiceHeaderID'])}}"><button class="warning">Pay</button></a>
    </div>
    @else
      {{ $house['warning'] }}
    @endif
@endsection
