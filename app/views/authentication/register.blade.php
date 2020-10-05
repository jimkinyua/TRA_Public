@extends('layout')

@section('section')
<div class="ui raised segment">
  <form class="ui form" method="POST" action="{{route('portal.post.register')}}">
    <input type="hidden" name="_token" value="{{ csrf_token() }}">

    <h4 class="ui horizontal header divider"> Personal Information </h4>

    <div class="ui hidden divider"></div>
    <div class="field">
      <label>Name</label>
      <div class="three fields">
        <div class="field">
          <input type="text" name="FirstName" placeholder="First Name" value="{{ Input::old('FirstName') }}">
        </div>
        <div class="field">
          <input type="text" name="MiddleName" placeholder="Middle Name" value="{{ Input::old('MiddleName') }}">
        </div>
        <div class="field">
          <input type="text" name="LastName" placeholder="Last Name" value="{{ Input::old('LastName') }}">
        </div>
      </div>
    </div>

    <div class="ui hidden divider"></div>
    <div class="two fields">
      <div class="required field">
        <label>ID/Passport Number</label>
        <input type="text" name="IDNumber" placeholder="National ID Number" value="{{ Input::old('IDNumber') }}">
      </div>
      <div class="required field">
        <label>Mobile Number</label>
        <input type="number" name="Mobile" placeholder="Mobile Phone Number" value="{{ Input::old('Mobile') }}">
      </div>
    </div>

    <div class="ui hidden divider"></div>
    <h4 class="ui horizontal header divider"> Account Information </h4>

    <div class="two fields">
      <div class="required field">
        <label>Email</label>
        <div class="ui icon input">
          <input type="text" name="email" placeholder="Email Address" value="{{ Input::old('email') }}">
          <i class="email icon"></i>
        </div>
      </div>
      <div class="required field">
        <label>Email Confirmation</label>
        <div class="ui icon input">
          <input type="text" name="email_confirmation" placeholder="Confirm Email Address" value="{{ Input::old('email_confirmation') }}">
          <i class="email icon"></i>
        </div>
      </div>
    </div>

    <div class="two fields">
      <div class="required field">
        <label>Password</label>
        <div class="ui icon input">
          <input type="password" name="password">
          <i class="lock icon"></i>
        </div>
      </div>
      <div class="required field">
        <label>Password Confirmation</label>
        <div class="ui icon input">
          <input type="password" name="password_confirmation">
          <i class="lock icon"></i>
        </div>
      </div>
    </div>

    <div class="ui section divider"></div>

    <div class="ui error message">
      <div class="header">We noticed some issues</div>
    </div>

    <button class="fluid ui orange submit button"> Register </button>
    <div class="ui horizontal divider">
      Already have an account?
    </div>
    <a class="ui center aligned" href="{{route('portal.login')}}"> Login </a>

  </form>
</div>
@endsection

@section('aside')
  @include('partials.notification')
@endsection

@section('script')
<script type="text/javascript">
  $( document ).ready(function() {
      console.log( "ready!" );
      $('.ui.dropdown').dropdown();
  });
</script>
@endsection
