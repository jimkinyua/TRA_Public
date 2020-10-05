@extends('signage.services')

@section('service')
  <form class="ui form" action="{{ route('signage.submit.application') }}" method="post" enctype="multipart/form-data">

    <input type="hidden" name="_token" value="{{ csrf_token() }}">

      @foreach ($form->sections() as $section )
        @if ($section->Show)
          @if ( count($section->columns()) > 0 )
            <h4 class=" ui orange dividing header">{{$section}} </h4>
            @foreach($section->columns() as $col)
              {{Api::CustomFormField($col->id())}}
            @endforeach
          @endif
        @endif
      @endforeach

      <div class="field">
        <label>Type of Advert</label>
        <select name="service" class="ui search dropdown">
          @foreach ($services as $service)
            <option value={{$service->ServiceID}}>{{$service->ServiceName}}</option>
          @endforeach
        </select>
      </div>

      <div class="ui section divider"></div>

      <button class="fluid ui positive button">Submit</button>

  </form>
@endsection

@section('script')
  @parent
  <script type="text/javascript">
     $( document ).ready(function() {
       $('#signage-menu #application').trigger('click');
       $('select').dropdown();
     });
  </script>
@endsection
