@extends('building.services')

@section('service')
  <form class="ui form" action="{{ route('building.submit.approval') }}" method="post" enctype="multipart/form-data">

    <input type="hidden" name="_token" value="{{ csrf_token() }}">

      @foreach ($form->sections() as $section )
        @if ($section->Show)
          @if ( count($section->columns()) > 0 )
            <h4 class=" ui red dividing header">{{$section}} </h4>
            @foreach($section->columns() as $col)
              {{Api::CustomFormField($col->id())}}
            @endforeach
          @endif
        @endif
      @endforeach

      <div class="ui section divider"></div>

      <div class="ui buttons">
        <button class="ui button">Save</button>
        <div class="or"></div>
        <button class="ui positive button">Submit</button>
      </div>

  </form>
@endsection

@section('script')
  @parent
  <script type="text/javascript">
     $( document ).ready(function() {
       $('#building-menu #approval').trigger('click');
     });
  </script>
@endsection
