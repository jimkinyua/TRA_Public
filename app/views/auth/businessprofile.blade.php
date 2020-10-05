@extends('dashboard.manage')

@section('dashboard-content')
    <form class="ui form" action="{{ route('update.business.profile') }}" method="post" enctype="multipart/form-data">

        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <input type="hidden" name="customer_id" value="{{ (Session::get('customer')->CustomerID) }}">

        <h3 class="ui dividing header" style="margin-top: 0;">Update Business Profile</h3>

        <div class="ui basic segment">
          @foreach ($form->sections() as $section )
              @if ($section->Show)
                  @if ( count($section->columns()) > 0 )
                      <div class="ui attached segment">
                          <h5 class=" ui dividing header">{{$section}} </h5>
                          <div class="ui basic segment">
                              @foreach($section->columns() as $col)
                                @if( isset($application[$col->id()]) )
                                  <?php 
                                  $disabled='';                                   
                                   if($col->id()==4176){
                                    $disabled="disabled";
                                   }?>
                                  {{Api::CustomFormInput($col->id(), $application[$col->id()],$disabled)}}
                                @else
                                  {{Api::CustomFormField($col->id())}}
                                @endif
                              @endforeach
                          </div>

                      </div>
                      <div class="ui hidden divider"></div>
                  @endif
              @endif
          @endforeach
          <div class="ui section divider"></div>
          <button class="ui fluid green button"> Submit </button>
        </div>
    </form>
@endsection

@section('script')
  <script type="text/javascript">
      $( document ).ready(function() {
      });
  </script>
@endsection
