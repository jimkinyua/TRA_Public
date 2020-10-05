@extends('dashboard.manage')

@section('dashboard-content')
  <div class="ui basic padded segment">
    <form class="ui form" action="{{ route('update.user.profile') }}" method="post" enctype="multipart/form-data">
      <h3 class="ui dividing header" style="mergin-bottom: 1em !important;"> Update Your Account Information </h3>

      <div class="field">
        <label>First Name</label>
        <input type="text" name="FirstName" placeholder="First Name" value={{$user->FirstName}} >
      </div>
      <div class="field">
        <label>Middle Name</label>
        <input type="text" name="MiddleName" placeholder="Middle Name" value={{$user->MiddleName}}>
      </div>
      <div class="field">
        <label>Last Name </label>
        <input type="text" name="LastName" placeholder="Last Name" value={{$user->LastName}}>
      </div>
      <div class="field">
        <label>ID or Passport Number </label>
        <input type="text" name="IDNO" placeholder="ID or Passport Number" value={{$user->IDNO}}>
      </div>
      <div class="field">
        <label>Mobile Phone Number </label>
        <input type="text" name="Mobile" placeholder="Mobile Phone Number" value={{$user->Mobile}}>
      </div>
      <div class="field">
        <label>Email Address </label>
        <input type="text" name="Email" placeholder="Email Address" value={{$user->Email}}>
      </div>


      <div class="ui grid">
        <div class="three column row">
          <div class="left floated column">
            <button class="ui primary button" type="submit">Submit</button>
          </div>
          <div class="right floated column">
            <a href="{{route('user.password')}}" class="ui basic fluid button"> Change Password </a>
          </div>
        </div>
      </div>

    </form>
  </div>
@endsection

@section('script')
    <script type="text/javascript">
    </script>
@endsection
