@extends('dashboard.manage')

@section('dashboard-content')
  <div class="ui basic padded segment">
    <form class="ui form" action="{{ route('update.user.password') }}" method="post" enctype="multipart/form-data">
      <h3 class="ui dividing header" style="mergin-bottom: 1em !important;"> Update Your Account Information </h3>

      <div class="field">
        <label>Current Password</label>
        <input type="password" name="current"  >
      </div>
      <div class="field">
        <label>New Password</label>
        <input type="password" name="password"  >
      </div>
      <div class="field">
        <label>Confirm New Password</label>
        <input type="password" name="password_confirmation"  >
      </div>

      <button class="ui primary button" type="submit">Submit</button>

    </form>
  </div>
@endsection

@section('script')
    <script type="text/javascript">
    </script>
@endsection
