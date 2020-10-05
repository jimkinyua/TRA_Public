@extends('app')

@section('content')
    <h2> Land Details</h2>
    <hr/>

    <div class="well">
      @if($owner)
        Registered to: {{$owner->CustomerName}}
        <br/>
      @endif
      <br/>
      @if($due)
        Land Rates Balance Due: KSh. {{$due}}
      @endif
      @if($inv)
        <div>
          <br/>
          <a href="{{ URL::route('my.invoice', $inv) }}"><button class="warning"> </button></a>
        </div>
      @else
        <div class="alert alert-danger">
          <br/>
          You have not been issued with an invoice
        </div>
      @endif
      <br/>
    </div>

    <table class="table stripped hovered">
        <thead>
          <td> <h4> Summery </h4> </td>
          <td></td>
        </thead>
          <tbody>
            @if($owner)
            <tr>
                <td>Land Owner </td>
                <td>{{$owner->CustomerName}}</td>
            </tr>
            <tr>
                <td>Land Registration Number </td>
                <td>{{$owner->RegistrationNumber}}</td>
            </tr>
            <tr>
                <td>Contact Person Name </td>
                <td>{{$owner->ContactPerson}} </td>
            </tr>
            <tr>
                <td>Contact Person Designation </td>
                <td>{{$owner->Designation}} </td>
            </tr>
            <tr>
                <td>Contact Postal Address </td>
                <td>P.O Box {{$owner->PostalCode}}, {{$owner->Town}} </td>
            </tr>
            <tr>
                <td>Contact Telephone </td>
                <td>{{$owner->Telephone1}}, {{$owner->Telephone2}} </td>
            </tr>
            <tr>
                <td>Contact Email </td>
                <td>{{$owner->Email}} </td>
            </tr>
            <tr>
                <td>Contact Url </td>
                <td>{{$owner->Url}} </td>
            </tr>
          </tbody>
        @else
          <h3> Not Found </h3>
        @endif
    </table>
@endsection
