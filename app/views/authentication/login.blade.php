@extends('portal')

@section('content')

  @include('partials.topmenu')

<div class="ui padded basic segment">
  <div id="window">
    <div class="ui attached segment two column divided grid">
      <div class="equal height row">
          <div class="eight wide column">

            @if(Session::has('error_msg'))
            <div class=" Test" style="color:black;text-align:center b; background-color: rgb(245, 75, 8)">
          
                <h2>{{ Session::get('error_msg') }}</h2>
                <br>
            </div>
          @endif

            
            <div class="">
             
              <form class="ui form" method="POST" action="{{route('portal.post.login')}}">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">

                <h4 class="ui dividing header">Login to access your account</h4>

                @if(Session::has('message')) @include('partials.notification') @endif

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
                   <a class="fluid ui  labeled icon button" href="{{route('get.change.password')}}">
                     <i class="signup icon"></i>
                     Reset Password
                   </a>
                 </div>
               </div>

              </form>

            </div>
          </div>

          <div id="feature" class="eight wide column" style=" overflow-y: auto;">
            <div  class="ui basic segment" style=" overflow-y: auto;">
              <h3 class="ui dividing  header"> <strong> What is TRA Self Help Portal? </strong> </h3>
              {{-- <p>
                TRA is constantly improving its service delivery to citizens and the business community.
                E-services portal is your one-stop-shop to make dealing with the company easy. Services are available 24/7 and throughout the week.
                Apply the service anywhere from the comfort of your home,
                office or cybercafé to answer your questions on a specific topic, click and read more.

                The following are some of the services available on this portal to logged in users.
              </p>
              <p>
                Apply the service anywhere from the comfort of your home, office or cybercafé
                to answer your questions on a specific topic, click and read more.
              </p> --}}

              {{-- <div class="ui basic segment">
                <p>The following are some of the services available on this portal to log in users.</p>
                <div class="ui accordion">
                  @foreach($services as $service)
                    <!-- accordion item -->
                    <div class="title">
                      <i class="dropdown icon"></i>
                      <strong class="ui small header"> {{$service->Title}} </strong>
                    </div>
                    <div class="content">
                      <p>
                        {{$service->ShortDecsription}}
                      </p>
                        <a class="ui teal" href="#"> More Information <i class="ui right double angle icon"></i>  </a>
                    </div>
                  @endforeach
                </div> --}}
              </div>


              <div class="ui basic segment">
                <div class="ui list">
                  <a class="item">
                    <i class="help icon"></i>
                    {{-- <div class="content">
                      <div class="header">How do I make payment?</div>
                      <div class="description">
                        The TRA has an MPESA pay bill number 12345 that
                        customers will use to make payments online. Details are found in the invoice that is sent to you
                        once you have filled the application(s) and is approved for payment.
                        Payments can also be made to the KCB revenue account. Details are contained in
                        the invoice
                      </div>
                    </div> --}}
                  </a>
                  <a class="item">
                    <i class="help icon"></i>
                    {{-- <div class="content">
                      <div class="header">How do I get notifications?</div>
                      <div class="description">
                        The portal is self-service meaning that you get SMS notifications and emails for your applications.
                        Whether they are rejected, approved or requested to provide more documentation.
                      </div>
                    </div> --}}
                  </a>
                  <a class="item">
                    <i class="help icon"></i>
                    {{-- <div class="content">
                      <div class="header">How do I get my Applications?</div>
                      <div class="description">
                        All documents will be send to your email. Once your applications have been approved,
                        you will get an invoice via email and once payment is made online ( MPESA or bank)
                        your permit or SBP will be generated and sent to your mail for you to print.
                      </div>
                    </div> --}}
                  </a>
                  <a class="item">
                    <i class="help icon"></i>
                    {{-- <div class="content">
                      <div class="header">If I don’t have a Computer what do I do?</div>
                      <div class="description">
                        The service will be available online. 
                      </div> --}}
                    </div>
                  </a>
                </div>
              </div>

              <div class="ui basic segment">
                Contact Us:
              The Transport Regulatory Authority
              P.O. Box 40241 - 00100
              Nairobi Kenya
              Tel: +254 (20) 2379407
              +254 (20) 2379408
              +254 (20) 2379409
              Toll free: 0800597000
              </div>

            </div>

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
<div class="ui segment">

    <h4 class="ui dividing header">
      Online Services
      <div class="sub header"> <a target="_blank" href="{{asset('uploads/bill.xlsx')}}"> View Charges </a> </div>
    </h4>
    <p>&nbsp</p>

    <p>The following are some of the services availbale on this portal to logged in users.</p>
    <div class="ui accordion">

      @foreach($services as $service)
        <!-- accordion item -->
        <div class="title">
          <i class="dropdown icon"></i>
          <strong class="ui small header"> {{$service->Title}} </strong>
        </div>
        <div class="content">
          <p>
            {{$service->ShortDecsription}}
          </p>
            <a class="ui teal" href="#"> More Information <i class="ui right double angle icon"></i>  </a>
        </div>
      @endforeach

    </div>

    <p>&nbsp<br/>&nbsp</p>
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
