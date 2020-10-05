<div class="ui basic segment">
    <div class="ui raised segment">{{Session::get('message')}}</div>

    @if(Session::has('error_msg'))
        <div class="ui red segment">{{Session::get('error_msg')}}</div>
    @endif

    @if(Session::has('success_msg'))
        <div class="ui raised segment">{{Session::get('success_msg')}}</div>
    @endif

    @if(Session::has('warning_msg'))
        <div class="oaerror warning">{{Session::get('warning_msg')}}</div>
    @endif

    @if(Session::has('info_msg'))
        <div class="oaerror info">{{Session::get('info_msg')}}</div>
    @endif

    @if (count($errors) > 0)
    <div class="ui red segment">
      <div class="header">Please Correct the following error(s)</div>
      <ul class="list">
        @foreach ($errors->all() as $error)
          <li>{{ $error }}</li>
        @endforeach
      </ul>
    </div>
    @endif
</div>
