@extends('app')

@section('content')
    <label><h2> Land Details</h2></label>
    <table class="table stripped hovered">
        <thead>
          @if($due)
            <tr style="background: #1abc9c; padding: 1em; color: #fff">
                <td>Land Rates Due: </td>
                <td>{{$due}}</td>
                <td><a href="#"><button class="btn btn-primary">Request Invoice &nbsp;&nbsp;&nbsp; <i class="fa fa-send-o"></i> </button></a></td>
            </tr>
          @endif
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
