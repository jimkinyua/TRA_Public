@extends('dashboard.manage')

@section('dashboard-content')
    <form class="ui form" action="{{ route('post.add.business') }}" method="post" enctype="multipart/form-data">

        <input type="hidden" name="_token" value="{{ csrf_token() }}">

        <h3 class="ui dividing header" style="margin-top: 0;">Business Registration</h3>

        @foreach ($form->sections() as $section )
            @if ($section->Show)
                @if ( count($section->columns()) > 0 )
                    <div class="ui attached segment">
                        <h5 class=" ui dividing header">{{$section}} </h5>
                        <div class="ui basic segment">
                            @foreach($section->columns() as $col)
                                {{Api::CustomFormField($col->id())}}
                            @endforeach
                        </div>
                    </div>
                    <div class="ui hidden divider"></div>
                @endif
            @endif
        @endforeach

        <div class="ui section divider"></div>

        <button class="ui fluid green button"> Submit </button>

    </form>
@endsection

@section('script')
    
    <script type="text/javascript">
        $( document ).ready(function() {
            $('#dashboard-menu #manage').trigger('click');
            $('.ui.accordion').accordion();
            $('.ui.dropdown').dropdown();
            $('#department-menu').accordion('open', 0);
        });
    </script>
@endsection
