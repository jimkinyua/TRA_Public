@extends('business.services')

@section('service')
  <form class="ui form" action="{{ route('post.add.business') }}" method="post" enctype="multipart/form-data">

    <input type="hidden" name="_token" value="{{ csrf_token() }}">

      @foreach ($form->sections() as $section )
        @if ($section->Show)
          @if ( count($section->columns()) > 0 )
            <h4 class=" ui blue dividing header">{{$section}} </h4>
            @foreach($section->columns() as $col)
              {{Api::CustomFormField($col->id())}}
            @endforeach
          @endif
        @endif
      @endforeach

      <div class="ui section divider"></div>
      <button class="ui fluid green button"> Submit </button>

  </form>
@endsection

@section('script')
  @parent
  <script type="text/javascript">
     $( document ).ready(function() {
       $('#register-business').trigger('click');
       $('select').dropdown();
     });
  </script>
@endsection
