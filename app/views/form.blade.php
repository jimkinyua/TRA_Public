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

      @if( $form->id() == 2 )
        <div class="ui attached segment">
            <div class="required field">
                <label>Business Category</label>
                <div class="ui fitted hidden divider"></div>
                <select name="service_id" class="ui  dropdown" id="category" onchange="filterActivity()">
                    <option value="0" > Select Category </option>
                    @foreach($bill as $index => $group)
                      @foreach($group->primaryCategories as $cat)
                        <!-- <option value="{{$cat->id()}}" > {{$cat->CategoryName}} </option> -->
                        <option value="{{$cat->id()}}" > <strong> {{$cat->ServiceCode}} </strong> {{$cat->CategoryName}} </option>
                      @endforeach
                    @endforeach
                </select>
            </div>
            <div class="required field">
                <label>Business Activity</label>
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
                <option value="0"> Select Service </option>
                <?php $selected = (count($services) == 1) ? "selected='selected'" : "" ?>
                @foreach($services as $service)
                    <option value="{{$service->ServiceID}}" {{$selected}} > <strong> {{$service->ServiceCode}} </strong> {{$service->ServiceName}} </option>
                @endforeach
            </select>
        </div>
        @endif

        @if( $form->id() == 12 )
        <!--
          <div class="ui segment">
            <div class="field">
              <div class="ui toggle checkbox">
                <input type="checkbox" id="previous" tabindex="0" class="hidden">
                <label> I have previously registered my land with the county </label>
              </div>
            </div>
          </div>
        -->
        @endif

        @foreach ($form->sections() as $section )
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
            @if ($section->Optional)
                @if ( count($section->columns()) > 0 )
                    <div class="ui styled fluid accordion">
                      <div class="title">
                        <i class="icon dropdown"></i>
                        {{$section}}
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

        <button id="submit" class="fluid ui positive button">Submit</button>
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
              $("#11204").parent().parent().css("visibility", "none");
              $("#11202").parent().parent().css("visibility", "none");
              $("#11203").change(function(){
                $("#11204").parent().parent().css("visibility", "visible");
                console.log('filter location', $("#11203").val());
                var endpoint = '/searchwards/' + $("#11203").val();
                console.log('url>>>>>>>>>', endpoint);
                $.get(endpoint, function(data) {
                  var toAppend = '';
                  var res = JSON.parse(data);
                  if(res.status == 'done') {

                    res.data.map(function(_) {
                      toAppend += '<option value="'+ _.WardID +'" selected>'+ _.WardName + '</option>';
                    });
                    console.log('fetched', toAppend);
                    $("#11204").html(toAppend);
                    $("#11204").parent().dropdown('set text', 'Select Ward');
                  }
                });

              })
            }

            if(fid == 12) { //form id 12 is land registration
              var lrn = plotno = undefined;
              setupVerification();
/*
              $('#previous').change(function() {
                console.log('do or dont', $('.ui.checkbox').checkbox('is checked'));
                if($('.ui.checkbox').checkbox('is checked')) { setupVerification() }
                else { teardownVerification() }
              });
*/
              function teardownVerification() {
                $("#12233").attr('disabled', ''); //disable plotno field until lrn is inserted
                $("#submit").removeClass('disabled');
              }

              function setupVerification() {
                $("#12233").attr('disabled', 'disabled'); //disable plotno field until lrn is inserted
                $("#submit").addClass('disabled');  // disable submit until we confirm plot details

                $("#12232").change(function() { // input with id 12232 picks land's lrn number
                  lrn = $("#12232").val();
                  $("#12233").removeAttr('disabled'); //enable plotno field once lrn is inserted
                });

                $("#12233").change(function() { // input with id 12232 picks land's lrn number
                  plotno = $("#12233").val();
                  searchland();
                });
              }

              function searchland() {
                var endpoint = url.slice(0, -20) + '/' + lrn + '/' + plotno;
                console.log('endpoint', endpoint);
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
                          "<tr>  <td>Outstanding balance  </td>  <td> KSh. " + res.data.PrincipalBalance + "</td>  </tr>" +
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

                });
              }

            }


        });
    </script>
    @parent
@endsection
