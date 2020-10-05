@extends('app')

@section('content')

<div class="container">
  <div class="row">
    <div class="span10 offset1">
      <h3> Info </h3>
      <hr/>
      <div class="listview">
        <a href="{{ action('StallsController@register', ['FormID' => 13, 'ServiceID' => 1533 ]); }}" class="list selected">
           <div class="list-content">
             <i style="display:block" class="fa fa-2x fa-bank"> </i>
               <div class="data">
                   <span class="list-title"> Apply for a Stall </span>
               </div>
           </div>
         </a>
        <a href="{{ route('get.houserent') }}" class="list selected">
           <div class="list-content">
             <i style="display:block" class="fa fa-2x fa-bed"> </i>
               <div class="data">
                   <span class="list-title"> Apply for a House </span>
               </div>
           </div>
         </a>
       </div>
    </div>
  </div>
</div>

@endsection
