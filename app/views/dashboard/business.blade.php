@extends('dashboard.manage')

@section('dashboard-content')
    <form class="ui form" action="{{ route('post.add.business') }}" method="post" enctype="multipart/form-data">

        <input type="hidden" name="_token" value="{{ csrf_token() }}">

        <h3 class="ui dividing header" style="margin-top: 0;">Business Registration Form</h3>
        
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

        @foreach ($location->sections() as $section )
            @if ($section->Show && !$section->Optional)
                @if ( count($section->columns()) > 0 )
                    <div class="ui attached segment">
                        <h4 class=" ui dividing header">{{$section}} </h4>
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
                          
        <div class="ui attached segment">
            <h4 class=" ui dividing header">Attachments </h4>
            <table class="ui red table">
              <thead>
                  <tr>
                    <th>Attachment Name</th>
                    <th>file</th>
                    {{-- <th>Upload</th> --}}
                  </tr>
              </thead>
               <?php

               // print '<pre>';
               //  print_r($docs); exit;

                for($i=0;$i<sizeof($docs);$i++)
                { ?>
                  <tr>
                    <td>{{$docs[$i]->DocumentName}}</td>
                    <td>
                     

                      <input type="file" id="files[]" name="files[{{$docs[$i]->DocTypeID }}]" value="" required />

                    </td>
                    {{-- <td><button class="ui primary button">Upload</button></td> --}}
                  </tr>
                <?php } ?>
                
            </table>
        </div>
        <div class="ui hidden divider"></div>
      



        <div class="ui section divider"></div>

        <div id="searchresults" style="display: none;"> </div>



        <div class="ui section divider"></div>

        <button class="ui fluid green button"> Next </button>

    </form>
@endsection

@section('script')

    <script type="text/javascript">
        $( document ).ready(function() {
          console.log('dedupe');

          locate();

          function locate() {
            $("#11203").change(function(){
              $.get(('/searchwards/' + $("#11203").val()), function(data) {
                var t = '';
                var res = JSON.parse(data);
                console.log('res', res);
                if(res.status == 'done') {
                  res.data.map(function(_) {t += '<option value="'+ _.WardID +'" selected>'+ _.WardName + '</option>';});
                  $("#11204").html(t);
                  $("#11204").parent().dropdown('set text', 'Select Ward');

                  $("#11204").change(function() {
                    $.get(('/searchzones/' + $("#11204").val()), function(data) {
                      var t = '';
                      var res = JSON.parse(data);
                      if(res.status == 'done') {
                        res.data.map(function(_) {t += '<option value="'+ _.ZoneID +'" selected>'+ _.ZoneName + '</option>';});
                        $("#11202").html(t);
                        $("#11202").parent().dropdown('set text', 'Select Zone');
                      }
                    });
                  });
                }
              });

            })
          }
            $('#dashboard-menu #manage').trigger('click');
            $('.ui.accordion').accordion();
            $('.ui.dropdown').dropdown();
            $('#department-menu').accordion('open', 0);
        });
    </script>
@endsection
