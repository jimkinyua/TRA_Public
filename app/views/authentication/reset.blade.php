@extends('portal')

@section('content')
  <div class="ui padded basic segment">
    <div id="window">
      <div class="ui segment centered grid">
          <div class="eight wide column">

            <div class="">
                @if (count($errors) > 0)
                  <div class="ui pink segment">
                    <strong>Whoops!</strong> There were some problems with your input.<br><br>
                    <ul class="ui selection list">
                      @foreach ($errors->all() as $error)
                        <li class="item">{{ $error }}</li>
                      @endforeach
                    </ul>
                  </div>
                @endif
              <form action="@if (!isset($change_password_token)){{route('post.reset')}}
                              @else {{route('post.change.password')}}
                            @endif"  method="POST" class="ui basic segment form">
                  <input type="hidden" name="_token" value="{{ csrf_token() }}">

                  @if (!isset($user__)) <!-- No User Found -->
                      <div class="required field">
                        <label>E-Mail Address 55</label>
                        <div class="ui icon input">
                          <input type="email" placeholder="Email Address" name="email" value="{{ Input::old('email') }}">
                          <i class="user icon"></i>
                        </div>
                      </div>
                  @else
                      <input type="hidden" name="user__" value="{{$user__}}">
                      <input type="hidden" name="change_password_token" value="{{Session::get('change_password_token')}}"> 
                      <div class="required field">
                        <label>Current Password</label>
                        <div class="ui icon input">
                          <input type="password" name="current">
                          <i class="lock icon"></i>
                        </div>
                      </div> 

                      <div class="required field">
                        <label>New Password</label>
                        <div class="ui icon input">
                          <input type="password" name="password">
                          <i class="lock icon"></i>
                        </div>
                      </div>

                      <div class="required field">
                          <label>Confirm Password</label>
                          <div class="ui icon input">
                            <input type="password" name="password_confirmation">
                            <i class="lock icon"></i>
                          </div>
                      </div> 
                    
                  @endif

                  <button type="submit" class="ui fluid teal button">
                    Reset Password
                  </button>

              </form>
              
            </div>

          </div>
      </div>
    </div>
  </div>

@endsection

@section('section')
<div class="ui segment">
  <form class="ui form" method="POST" action="{{route('portal.post.login')}}">
    <input type="hidden" name="_token" value="{{ csrf_token() }}">

    <h4 class="ui dividing header">Login to access your account</h4>

    @include('partials.notification')

    <div class="required field">
      <label>Email</label>
      <div class="ui icon input">
        <input type="email" placeholder="Username" name="email" value="{{ Input::old('email') }}">
        <i class="user icon"></i>
      </div>
    </div>
    <div class="required field">
      <label>Password</label>
      <div class="ui icon input">
        <input type="password" name="password">
        <i class="lock icon"></i>
      </div>
    </div>

    <div class="ui form segment">
      <div class="inline field">
        <div class="ui checkbox">
          <input type="checkbox" id="remember" name="remember">
          <label for="remember"> Remember me </label>
        </div>
      </div>
    </div>

    <button class="fluid ui orange submit button"> Sign In</button>

    <div class="ui horizontal divider">
      Can't Login?
    </div>

    <div class="ui two column middle aligned relaxed fitted stackable grid">
      <div class="column">
        <a class="fluid ui green labeled icon button" href="{{route('portal.get.register')}}">
          Register
          <i class="add icon"></i>
        </a>
      </div>

     <div class="center aligned column">
       <div class="fluid ui  labeled icon button">
         <i class="signup icon"></i>
         Reset Password
       </div>
     </div>
   </div>

  </form>
</div>

@endsection

@section('script')
<script type="text/javascript">
  $( document ).ready(function() {
      console.log( "ready!" );
      $('.ui.accordion').accordion();
  });
</script>
@endsection
