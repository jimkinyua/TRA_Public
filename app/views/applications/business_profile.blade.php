/**
 * Created by PhpStorm.
 * User: Attain123
 * Date: 12/11/2015
 * Time: 00:31
 */

@extends('dashboard.services')

@section('dashboard-content')
  <h3 class="ui dividing header"> Account Profile </h3>
  <table class="ui definition table">
    <thead>
      <tr>
        <th></th>
        <th> <h5 class="ui right aligned header"> Edit </h5> </th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td>Account Name</td>
        <td>{{$customer->CustomerName}}</td>
      </tr>
      <tr>
        <td>Contact Person</td>
        <td>{{$customer->ContactPerson}}</td>
      </tr>
      <tr>
        <td>Postal Address</td>
        <td>{{$customer->PostalAddress}}</td>
      </tr>
      <tr>
        <td>Postal Code</td>
        <td>{{$customer->PostalCode}}</td>
      </tr>
      <tr>
        <td>Telephone 1</td>
        <td>{{$customer->Telephone1}}</td>
      </tr>
      <tr>
        <td>Telephone 2</td>
        <td>{{$customer->Telephone2}}</td>
      </tr>
      <tr>
        <td>Mobile 1</td>
        <td>{{$customer->Mobile1}}</td>
      </tr>
      <tr>
        <td>Email</td>
        <td>{{$customer->Email}}</td>
      </tr>
      <tr>
        <td>Website</td>
        <td>{{$customer->Website}}</td>
      </tr>
      <tr>
        <td>Registered Since</td>
        <td>{{$customer->CreatedDate}}</td>
      </tr>
      <tr>
        <td>ID Number</td>
        <td>{{$customer->IDNO}}</td>
      </tr>
      <tr>
        <td>Sub County</td>
        <td>{{$customer->SubCounty}}</td>
      </tr>
      <tr>
        <td>Ward</td>
        <td>{{$customer->Ward}}</td>
      </tr>
      <tr>
        <td>Business Zone</td>
        <td>{{$customer->BusinessZone}}</td>
      </tr>
    </tbody>
  </table>
@endsection
