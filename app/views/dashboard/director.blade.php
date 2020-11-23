@extends('dashboard.manage')

@section('dashboard-content')

    <div class="ui modal">
        <i class="close icon"></i>
        <div class="header">
         Director
        </div>
        <div class="image content">
            <form class="ui form" method="post" enctype = "multipart/form-data" action="{{ route('post.add.Directors') }} ">
              
              <input type="hidden" name="CustomerId" value="{{ $CustomerId }}">
              <div class="ui four column  grid">

                  <div class="two column">
                    <div class="required  field">
                      <label>First Name</label>
                      <input type="text" name="FirstName" 
                      placeholder="First Name" class="row">
                      </div>
                </div>

                  <div class="two column">
                    <div class="  field">
                      <label>Middle Name</label>
                      <input type="text" name="MiddleName"
                      placeholder="Middle Name">
                    </div>
                </div>

                <div class="column">
                  <div class="required  field">
                    <label>Last Name</label>
                    <input type="text" name="LastName"
                    placeholder="Last Name">
                  </div>
                </div>


              </div>
               
              <div class="ui four column  grid">

                  <div class="two column">
                    
                    <div class="required  field">
                      <label>KRA Pin No</label>
                      <input type="text" name="PinNo"
                       placeholder="KRA Pin No">
                    </div>

                  </div>

                  <div class="two column">
                    <div class="required  field">
                      <label>ID/Passport/Huduma Number</label>
                      <input type="text" name="IdNo" 
                      placeholder="ID/Passport/Huduma Number">
                    </div>
                  </div>

                  <div class=" two column">
                    <div class="required  field">
                      <label>PhoneNumber</label>
                      <input type="number" name="PhoneNumber" 
                      placeholder="Phone Number">
                    </div>
                  </div>


              </div>

              <div class="ui  column  grid">

                <div class=" column">
                  
                  <div class="required  field">
                    <label>Nationality</label>
                    <div class="ui fitted hidden divider"></div>
                    <select name="CountryId" class="ui  dropdown" id="CountryId" >
                        <option value="0" > Select Country </option>
                        @foreach($Countries as $index => $Country)
                            <option value="{{$Country->id()}}" > <strong> {{$Country->Name}}
                        @endforeach
                    </select>
                  </div>

                </div>

              </div>

              <div class="ui  column  grid">
                <div class=" column"> 
                  <div class="ui attached segment">       
                    <div class="required DirectorAttachements  field">
                      <div class="  field">
                        <label>Work Permit</label>
                        <input type="file" name="WorkPermit"
                        >
                      </div>
                    </div>
                  </div>
                </div>

              </div>
              

              



                   
        </div>
        <div class="actions">
        <button class="ui button green" type="submit">Submit</button>
        <div class="ui button red">Cancel</div>
        </div>
    </form> 
    </div>
    
    {{ Form::open(['route' => array('application.submitbusiness', $CustomerId, 
                                    ),
                    'class' => 'ui form', 
                    'enctype' => 'multipart/form-data'])
                 }}


        <h3 class="ui dividing header" style="margin-top: 0;">Company Directors Form</h3>
                          
        <div class="ui hidden divider"></div>
        <table class="ui compact celled definition table">
            <thead>
              <tr>
                <th></th>
                <th>FirstName</th>
                <th>LastName</th>
                <th>KRAPIN</th>
                <th>IDNO</th>
                <th>Creation Date</th>
              </tr>
            </thead>
            <tbody>

            @foreach($Directors as $Director)
            <tr>
                <td class="collapsing">
                    <td>{{$Director->FirstName}}</td>
                    <td>{{$Director->LastName}}</td>
                    <td>{{$Director->KRAPIN}}</td>
                    <td>{{$Director->IDNO}}</td> 
                    <td>{{$Director->created_at}}</td> 

                </td>
            </tr>
            @endforeach


            </tbody>
            <tfoot class="full-width">
              <tr>
                <th></th>
                <th colspan="4">
                  <div class="ui right floated small primary labeled icon button" id ="AddDirector">
                    <i class="user icon"></i> Add Director
                  </div>
  
                </th>
              </tr>
            </tfoot>
          </table>
      
          <div class="ui icon message">
            <i class="red warning sign icon"></i>
            <div class="content">
                <div class="header">
                    NOTICE
                </div>
                <p>Submitting False Information Attracts Respective Penalty on the Applicant</p>
            </div>
        </div>
            {{ Form::submit('Submit', ['class' => 'ui fluid purple button']) }}
    {{ Form::close() }}



        <div class="ui section divider"></div>


@endsection

@section('script')
    <script type="text/javascript">
        $( document ).ready(function() {
          $('.DirectorAttachements').hide();
          $('#AddDirector').click(function(){
            $('.ui.modal').modal({
                centered: true
            }).modal('show');

            this.preventDefault();
          });
        
        $('#CountryId').change(function(){
            // alert($('#CountryId').val());
            val = $('#CountryId').val();
            if(val == 110){
              $('.DirectorAttachements').hide();
              return false;
            }
            $('.DirectorAttachements').show();

        });

            $('#dashboard-menu #manage').trigger('click');
            $('.ui.accordion').accordion();
            $('.ui.dropdown').dropdown();
            $('#department-menu').accordion('open', 0);
        });
        
        
    </script>``
@endsection