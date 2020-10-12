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
  <?php
            if ($Penalty === true){
             $LicenceFee = number_format($PenaltyAmountToPay +$StandardRenewalFee, 2) ;
            }else{
                  $LicenceFee = $StandardRenewalFee;
            }

  ?>

    <form class="ui form"  action="{{ route('submit.renewal') }}" method="post" enctype="multipart/form-data">
      <input type="hidden" name="form_id" value="{{$form->id()}}" >
      <input type="hidden" name="PermitNo" value="{{$applications[0]->PermitNo}}" >
      <input type="hidden" name="CategoryNumber" value="{{2}}" >
      <input type="hidden" name="LicenceFee" value="{{ $LicenceFee}}" >
      <input type="hidden" name="ServiceHeaderID" value="{{ $ServiceHeaderID}}" >



      @if( $form->id() )
        <div class="ui attached segment">
            <div class="required field">
                {{-- <label>Business Category </label> --}}
                <!-- <input type="text" name="ServiceStatusID" value="{{$applications[0]->ServiceStatusID}}" > -->
                <!-- $ServicesStatusID = $ServiceStatusID; -->

                @if($applications[0]->ServiceStatusID == 1)

                    <div class="ui ignored info message">
                        <p style="color:red;">
                        Please Note: Your licence needs to be approved for you to continue with this section.
                        </p>
                    </div>

                @else
                    <div class="ui ignored info message">
                        {{-- <p>
                        Your Business Licence is Approved, You Can Make This Application.
                        </p> --}}
                        <hr>
                        <table class="ui green table">
                          <thead>
                              <tr>
                                <th>Description </th>
                                <th>Charge</th>
                                {{-- <th>Upload</th> --}}
                              </tr>
                          </thead>
                           
                              <tr>
                                <td>Standard Licence Renewal Fees</td>
                                <td>
                                 <?php //exit($StandardRenewalFee)?>
              
                                  <input type="text"  value={{number_format($StandardRenewalFee)}}  readonly/>
            
                                 
                                </td>                               
                                  
                              </tr>

                              @if ($Penalty === true)
                              <tr>
                                <td>Late Renewal Charges</td>
                                <td> <input type="text"  value={{number_format($PenaltyAmountToPay, 2)}}  readonly /> </td>

                              </tr>

                              @endif

                              <tr>
                                
                                <td>Total Amount Payable</td>
                                <td> <input type="text"  value={{number_format($PenaltyAmountToPay+$StandardRenewalFee, 2)}} readonly  /> </td>

                              </tr>
                              
                           
                            
                        </table>
                    </div>
                @endif
    
                <div class="required field">
                    <label>Licence Being Renewed</label>
                    <div class="ui fitted hidden divider"></div>
                    <select name="service_id" class="ui dropdown" id="service">
                        {{-- <option value="0"> Select Activity </option> --}}
                        @foreach($applications as $service)
                            <option value="{{$service->ServiceID}}"> <strong> {{$service->ServiceName}} </strong> {{$service->ServiceName}} </option>
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
      
        @foreach ($form->sections() as $section )
       
      
            @if (!$section->Optional)
            <?php //exit('hAPA');?>
                @if ( count($section->columns()) > 0 )
                    <div class="ui attached segment">
                       <?php //exit($section); ?>
                        <h4 class=" ui dividing header">{{$section->FormSectionName}} </h4>
                        <div class="ui basic segment">
                            @foreach($section->columns() as $col) 
                            <?php
                            // echo '<pre>';
                            //  print_r($col);
                            //  exit;
                      
                            ?>                               
                                {{Api::RenewalCustomFormField($col->id())}}
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

                  
        
        <div class="ui hidden divider"></div>
        <div class="ui attached segment">
          <h4 class=" ui dividing header">Attachments  </h4>
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



