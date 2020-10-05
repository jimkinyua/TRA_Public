@extends('dashboard.settings')


@section('dashboard-content')
<div class="ui raised segment">
  <h3 class="ui header">
    <i class="circular user icon"></i>
    <div class="content">
      {{$user->FirstName}} {{$user->MiddleName}} {{$user->LastName}}
      <div class="sub header">Manage your preferences</div>
    </div>
  </h3>
</div>
<div class="row">
    <div class="span3" align="center">
      <img alt="User Pic" src="https://lh5.googleusercontent.com/-b0-k99FZlyE/AAAAAAAAAAI/AAAAAAAAAAA/eu7opA4byxI/photo.jpg?sz=100" class="img-circle">
    </div>
    <!--<div class="col-xs-10 col-sm-10 hidden-md hidden-lg"> <br>
      <dl>
        <dt>DEPARTMENT:</dt>
        <dd>Administrator</dd>
        <dt>HIRE DATE</dt>
        <dd>11/12/2013</dd>
        <dt>DATE OF BIRTH</dt>
           <dd>11/12/2013</dd>
        <dt>GENDER</dt>
        <dd>Male</dd>
      </dl>
    </div> -->
    <div class="span9">
        <table class="table table-user-information">
            <tbody>
            <tr>
                <td>First Name</td>
                <td>{{$user->FirstName}}</td>
            </tr>
            <tr>
                <td>Middle Name</td>
                <td>{{$user->MiddleName}}</td>
            </tr>
            <tr>
                <td>Last Name</td>
                <td>{{$user->LastName}}</td>
            </tr>
            <tr>
                <td>IDNumber</td>
                <td>{{$user->IdNumber}}</td>
            </tr>
            <tr>
                <td>Phone Number</td>
                <td>{{$user->Mobile}}</td>
            </tr>

            <tr>
                <td>Email Address</td>
                <!-- <td><a href="mailto:{{$user->Email}}">{{$user->Email}}</a></td> -->
                <td>{{$user->Email}}</td>
            </tr>

            </tbody>
        </table>

        <hr/>
        <a href="#">Change Password </a>

    </div>
</div>
@endsection

@section('script')
  @parent
  <script type="text/javascript">
     $( document ).ready(function() {
       $('#settings-menu #account').trigger('click');
     });
  </script>
@endsection
