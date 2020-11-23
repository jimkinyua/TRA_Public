@extends('dashboard.manage')

@section('dashboard-content')
    {{ Form::open(['url' => 'addvehicle', 'class' => 'ui form', 'enctype' => 'multipart/form-data']) }}

        <input type="hidden" name="_token" value="{{ csrf_token() }}">

        <h3 class="ui dividing header" style="margin-top: 0;">Fleet Registration Form</h3>
        <div class="ui icon message">
          <i class="pink warning sign icon"></i>
          <div class="content">
            <div class="header">
                Please Note
            </div>
              <p>Only Holders of a Tours and Travel, Tourism Vehicle Hire Licences are allowed to Register Fleets</p> 
              <p>Submitting False Information Attracts Respective Penalty on the Applicant</p> 
          </div>
      </div>
        <div class="ui hidden divider"></div>
        <div class="ui basic segment">
            <div class="required field">
              {{ Form::label('RegNo', 'Vehicle Registration Number') }}
              {{ Form::text('RegNo') }}
            </div>
            <div class="required field">
              {{ Form::label('LicenceNo', 'Licence Number') }}
              {{ Form::text('LicenceNo') }}
            </div>
        </div>
          {{-- <div class="ui icon message">
            <i class="red warning sign icon"></i>
            <div class="content">
                <div class="header">
                    NOTICE
                </div>
                <p>Submitting False Information Attracts Respective Penalty on the Applicant</p>
            </div>
        </div> --}}
            {{ Form::submit('Submit', ['class' => 'ui fluid purple button']) }}
        {{ Form::close() }}



        <div class="ui section divider"></div>

        <table class="ui compact celled definition table">
         
          <thead>
            <tr>
              <th></th>
              <th>RegNo</th>
              <th>OwnerName</th>
              <th>Make</th>
              <th>Model</th>
              <th>Creation Date</th>
            </tr>
          </thead>
          <tbody>
       
          @foreach($Fleets as $Fleet)
          <tr>
              <td class="collapsing">
                  <td>{{$Fleet->RegNo}}</td>
                  <td>{{$Fleet->OwnerName}}</td>
                  <td>{{$Fleet->Make}}</td>
                  <td>{{$Fleet->Model}}</td> 
                  <td>{{$Fleet->created_at}}</td> 

              </td>
          </tr>
          @endforeach


          </tbody>
          {{-- <tfoot class="full-width">
            <tr>
              <th></th>
              <th colspan="4">
                <div class="ui right floated small primary labeled icon button">
                  <i class="user icon" id ="AddFleet"></i> Add Fleet
                </div>

              </th>
            </tr>
          </tfoot> --}}
        </table>


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
