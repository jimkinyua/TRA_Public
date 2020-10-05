@extends('finance')

@section('bill-left')
  @widget('dockets')
@endsection

@section('bill-content')
  <div class="ui padded center aligned segment">
    <h4 class="ui center aligned dividing header">
      <img src="/images/logo.png" class="ui small centered image">
    </h4>
    <h4 class="ui olive center aligned icon header">
      THE COUNTY GOVERNMENT OF UASIN GISHU
    </h4>
    <h5 class="ui header">
      VISION
      <div class="sub header">A Prosperous and Attractive County in Kenya and Beyond</div>
    </h5>
    <h5 class="ui header">
      <div class="content">
        MISSION
        <div class="sub header">To Serve And Improve People's Livelihood Through Good Leadership, Innovative Technology and Efficient Infrastructure</div>
      </div>
    </h5>

  </div>
@endsection

@section('bill-right')
  @widget('departments')
@endsection
