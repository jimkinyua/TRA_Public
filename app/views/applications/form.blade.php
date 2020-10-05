@extends('dashboard.services')


@section('dashboard-content')
  {{--
    <h3 class="ui left aligned dividing  header">
      @if(isset($services[0]))
        {{$services[0]->group }}
        &nbsp; >> &nbsp;
        {{$services[0]->category }}
      @endif
    </h3>
  --}}

  <div class="ui ignored info message">
    <p>
      The fields marked with * are required
    </p>
  </div>


    <form class="ui form"  action="{{ route('submit.application') }}" method="post" enctype="multipart/form-data">
      <input type="hidden" name="form_id" value="{{$form->id()}}" >
      <input type="hidden" name="CategoryNumber" value="{{$categoryID}}" >


      @if( $form->id() == 214 )
        <div class="ui attached segment">
            <div class="required field">
                <label>Business Category </label>
                <!-- <input type="text" name="ServiceStatusID" value="{{$ServiceStatusID}}" > -->
                <!-- $ServicesStatusID = $ServiceStatusID; -->

                @if($ServiceStatusID == 1)

                <div class="ui ignored info message">
                <p style="color:red;">
                 Please Note: Your licence needs to be approved for you to continue with this section.
                </p>
              </div>
                @else
                 <div class="ui ignored info message">
                  <p>
                  Your Business Licence is Approved, You Can Make This Application.
                </p>
              </div>
                @endif
                <div class="ui fitted hidden divider"></div>
                <textarea cols="50" rows="4" disabled="true">{{$categoryName}}</textarea>
            </div>
            <div class="required field">
                <label>Service Applied</label>
                <div class="ui fitted hidden divider"></div>
                <select name="service_id" class="ui dropdown" id="service">
                    <option value="0"> Select Activity </option>
                    @foreach($services as $service)
                        <option value="{{$service->ServiceID}}"> <strong> {{$service->ServiceCode}} </strong> {{$service->ServiceName}} </option>
                    @endforeach
                </select>
            </div>
            <div class="required field">
                <label>Applicant</label>
                <input type="text" name="customer" value="{{Session::get('customer')->CustomerName}}" disabled>
                <input type="hidden" name="customer_id" value="{{Session::get('customer')->CustomerID}}" >
            </div>
        </div>

        <div class="ui hidden divider"></div>

        @else

        <div class="required field">
            <label>Service</label>
            <select name="service_id" class="ui dropdown" id="service">
                {{-- <option value="0"> Select Service </option> --}}
                <?php $selected = (count($services) == 1) ? "selected='selected'" : "" ?>
                @foreach($services as $service)

                    <?php $scode=90; ?>

                    <option value="{{$service->ServiceID}}" {{$selected}} > <strong> <?php $scode; ?> </strong> {{$service->ServiceName}} </option>
                @endforeach
            </select>
        </div>
        @endif

        @if( $form->id() == 12 )
          <div class="ui segment">
            <div class="field">
              <div class="ui toggle checkbox">
                <input type="checkbox" id="previous" tabindex="0" class="hidden">
                <label> I have previously registered my land with the county </label>
              </div>
            </div>
          </div>
        @endif

@section('script')
  @parent
  <script type="text/javascript">
     $( document ).ready(function() {
       $('#dashboard-menu #manage').trigger('click');
       $('#applications-table').DataTable();
     });
  </script>
@endsection


 @if( $form->id() == 21 )
        <div class="ui attached segment">
            <!-- <div class="required field">
                <label>Business Category </label> -->
               <!--  <input type="text" name="ServiceStatusDisplay" value="{{$ServiceStatusDisplay}}" > -->
                <!-- $ServicesStatusID = $ServiceStatusID; -->

<!-- @if(count($ApplicationStatus) == 0)
  <div class="ui orange attached segment">
    <h2 class="ui center aligned icon header">
      <i class="circular hide icon"></i>
      Nothing Here!
      <div class="sub header">You have not yet applied for any service</div>
    </h2>
  </div>
  @else
    <h5 class="ui center aligned header">
      <div class="ui mini orange horizontal statistic">
        <div class="value">
         You have  {{count($ApplicationStatus)}}
        </div>
        <div class="label">
          Application(s), that need to be licenced to continue with this section.
        </div>
      </div>
    </h5>
 -->
   <!--  <table class="ui attached single line definition table portal-table" id="applications-table" >
      <thead>
        <tr>
          <th class="ten wide">Service Name</th>
          <th class="three wide">Date Submitted</th>
          <th class="two wide right aligned">Status</th>
        </tr>
      </thead>
      <tbody>
        @foreach($ApplicationStatus as $application)
          <tr>
            
            <td> <a href="{{route('view.application', [ 'id' => $application->ServiceHeaderID ])}}"> <i class="folder open outline icon"></i> </a> </td>
          
            
           
            <td>{{$application->ServiceName}}</td>
            <td>{{ \Carbon\Carbon::createFromTimeStamp(strtotime($application->Date))->toFormattedDateString() }}</td>
            <td>{{$application->ServiceStatusDisplay}}</td>
          </tr>
        @endforeach
      </tbody>
    </table>
  @endif

 -->

                @if($ServiceStatusID != 4 || count($ApplicationStatus) > 0)

                <div class="ui ignored info message">
                <p style="color:red;">
                 Please Note: <div class="value">
         You have  {{count($ApplicationStatus)}}
        </div>
        <div class="label">
          Application(s), that need to be licenced to continue with this section.
        </div>
                </p>
              </div>
                @else
                 <div class="ui ignored info message">
                  <p>
                  Your Business Licence is Approved, You Can Make This Application.
                </p>
              </div>
                @endif
                <div class="required field">
                <label>Business Category </label>
                <div class="ui fitted hidden divider"></div>
                <input type="text" disabled="true" value="{{$categoryName}}">
            </div>
            <div class="required field">
                <label>Service Applied </label>
                <div class="ui fitted hidden divider"></div>
                <input type="text" disabled="true" value="{{$service->ServiceName}}">
                <!-- <select name="service_id" class="ui dropdown" id="service">
                    <option value="0"> Select Activity </option>
                    @foreach($services as $service)
                        <option value="{{$service->ServiceID}}"> <strong> {{$service->ServiceCode}} </strong> {{$service->ServiceName}} </option>
                    @endforeach
                </select> -->
            </div>
            <div class="required field">
                <label>Applicant</label>
                <input type="text" name="customer" value="{{Session::get('customer')->CustomerName}}" disabled>
                <input type="hidden" name="customer_id" value="{{Session::get('customer')->CustomerID}}" >
            </div>
        </div>

        <div class="ui hidden divider"></div>

        @else

       <!--  <div class="required field">
            <label>Service</label>
            <select name="service_id" class="ui dropdown" id="service">
                {{-- <option value="0"> Select Service </option> --}}
                <?php $selected = (count($services) == 1) ? "selected='selected'" : "" ?>
                @foreach($services as $service)

                    <?php $scode=90; ?>

                    <option value="{{$service->ServiceID}}" {{$selected}} > <strong> <?php $scode; ?> </strong> {{$service->ServiceName}} </option>
                @endforeach
            </select>
        </div>
        @endif -->


        @foreach ($form->sections() as $section )
       
            @if ($section->Show && !$section->Optional)
                @if ( count($section->columns()) > 0 )
                    <div class="ui attached segment">
                       <?php //exit($section); ?>
                        <h4 class=" ui dividing header">{{$section->FormSectionName}} </h4>
                        <div class="ui basic segment">
                            @foreach($section->columns() as $col)                                
                                {{Api::CustomFormField($col->id())}}
                            @endforeach
                        </div>
                    </div>
                    <div class="ui hidden divider"></div>
                @endif
            @endif
            @if ($section->Optional)
                @if ( count($section->columns()) > 0 )
                    <div class="ui styled fluid accordion">
                      <div class="title">
                        <i class="icon dropdown"></i>
                       
                                            </div>
                      <div class="ui content">
                        <div class="ui basic padded segment">
                          @foreach($section->columns() as $col)
                          {{Api::CustomFormField($col->id())}}
                          @endforeach
                        </div>
                      </div>
                    </div>
                    <div class="ui hidden divider"></div>
                @endif
            @endif
        @endforeach

                  
        <div class="ui attached segment">
            <h4 class=" ui dividing header">Attachments  6465 </h4>
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
                     

                      <input type="file" id="files[]" name="files[{{$docs[$i]->DocumentID }}]" value="" required />

                    </td>
                    {{-- <td><button class="ui primary button">Upload</button></td> --}}
                  </tr>
                <?php } ?>
                
            </table>
        </div>
        <div class="ui hidden divider"></div>
      



        <div class="ui section divider"></div>

        <div id="searchresults" style="display: none;"> </div>

        <div class="ui icon message">
            <i class="red warning sign icon"></i>
            <div class="content">
                <div class="header">
                    NOTICE
                </div>
                <p>Submitting False Information Attracts Respective Penalty on the Applicant</p>
            </div>
        </div>
@if( $form->id() == 21 && $ServiceStatusID != 4 && count($ApplicationStatus) > 0 && $ServiceID = 1033 )
                        <div class="ui ignored info message">
                <p style="color:red;">
                 Please Note: Your licence needs to be approved for you to continue with this section.
                </p>
              </div>
@else
        <button id="submit" class="fluid ui positive button">Submit</button>
@endif
    </form>
@endsection

@section('script')
    <script type="text/javascript">
      var fid = <?php echo $form->id() ?>;
      var url = <?php echo json_encode(route('portal.searchland')); ?>;
        $( document ).ready(function() {
            $('.ui.dropdown').dropdown();
            $('.ui.checkbox').checkbox();

            locate();

            function locate() {
              $("#11203").change(function(){
                $.get(('/searchwards/' + $("#11203").val()), function(data) {
                  var t = '';
                  var res = JSON.parse(data);
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

            if(fid == 12) { //form id 12 is land registration
              var lrn = plotno = upn = done = undefined;

              //setupVerification();

              $('#previous').change(function() {
                console.log('do or dont', $('.ui.checkbox').checkbox('is checked'));
                if($('.ui.checkbox').checkbox('is checked')) { setupVerification() }
                //else { teardownVerification() }
              });

              function teardownVerification() {
                $("#12233").attr('disabled', ''); //disable plotno field until lrn is inserted
                $("#submit").removeClass('disabled');
              }

              function setupVerification() {
                $("#12233").attr('disabled', 'disabled'); //disable plotno field until lrn is inserted
                $("#submit").addClass('disabled');  // disable submit until we confirm plot details

                $("#12232").change(function() { // input with id 12232 picks land's lrn number
                  var _lrn
                  _lrn = $("#12232").val();
                  lrn = _lrn.replace(/\//g, '@');
                  console.log(lrn);
                  $("#12233").removeAttr('disabled'); //enable plotno field once lrn is inserted
                });

                $("#12265").change(function() { // input with id 12265 picks land's upn
                  var _upn;
                  _upn = $("#12265").val();
                  upn = _upn.replace(/\//g, '@');
                  console.log('upn', upn);
                  searchupn();
                });

                $("#12233").change(function() { // input with id 12232 picks land's lrn number
                  var _plotno;
                  _plotno = $("#12233").val();
                  plotno = _plotno.replace(/\//g, '@');
                  console.log('upn', plotno);
                  searchland();
                });
              }

              function searchupn() {
                var ep = 'https://revenue.uasingishu.go.ke/searchupn'
                var endpoint = ep + '/' + upn;
                console.log('searchupn::endpoint', endpoint);
                getRecord(endpoint);
              }

              function searchland() {
                var ep = 'https://revenue.uasingishu.go.ke/searchland'
                if(upn) {
                  var endpoint = ep + '/' + lrn + '/' + plotno + '/' + upn;
                } else {
                  var endpoint = ep + '/' + lrn + '/' + plotno;
                }
                console.log('searchland::endpoint', endpoint);
                getRecord(endpoint);
              }

              function getRecord(endpoint) {
                if(done) { return }
                $.get(endpoint, function( data ) {
                  var res = JSON.parse(data);
                  if(res.status == 'done') {

                    var message = " \
                      <div class='ui blue attached message'> \ " +
                      "<div class='content'> <h2 class='ui tiny dividing header'> " +
                      "<i class='plug icon'></i> <div class='content'> Land Record Found </div> </h2> <p>" +
                      "<table class='ui very compact small attached  table'>  <tbody>" +
                          "<tr>  <td>Plot Number  </td>  <td> " + res.data.PlotNo  + "</td>  </tr>" +
                          "<tr>  <td>Block Number  </td>  <td> " + res.data.LRN + "</td>  </tr>" +
                          "<tr>  <td>Unique Parcel Number(UPN) </td>  <td> " + res.data.LaifomsUPN  + "</td>  </tr>" +
                          "<tr>  <td>Registered to  </td>  <td> " + res.data.LaifomsOwner + "</td>  </tr>" +
                          "<tr>  <td>Rates Payable  </td>  <td> KSh. " + res.data.RatesPayable + "</td>  </tr>" +
                          "<tr>  <td>Outstanding balance  </td>  <td> KSh. " + res.data.Balance + "</td>  </tr>" +
                      "</tbody> </table>" + "<br/>" +
                      "<div class='mini left floated ui primary button' id='confirm'> Correct </div> " +
                      "<div class='mini right floated ui button' id='reject'> Incorrect </div> " + "<br/>" +
                      "</p> </div> </div> \
                    ";
                  } else {
                    var message = " \
                    <div class='ui icon red message'> \ " +
                    "<i class='remove circle outline icon'></i> " +
                    "<div class='content'> <div class='header'> Land Record Not Found </div> <p>" +
                    "The LRN Number and/or Plot Number you entered are incorrect. " + "<br/>" +
                    "Please call this number for further assistance 053-2016000 " +
                    "</p> </div> </div> \
                    ";
                  }

                  $("#searchresults").html(message);
                  $("#searchresults").css("display", "block");
                  $("#confirm").click(function() {  $("#submit").removeClass('disabled'); });
                  $("#reject").click(function() {  $("#submit").addClass('disabled'); });

                  done = true;
                });
              }

            }


        });
    </script>
    @parent
@endsection
