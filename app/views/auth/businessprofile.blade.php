@extends('dashboard.manage')

@section('dashboard-content')

<div class="ui modal">
  <i class="close icon"></i>
  <div class="header">
   Director
  </div>
  <div class="image content">
      <form class="ui form" method="post" enctype = "multipart/form-data" action="{{ route('post.add.Directors') }} ">
        
        <input type="hidden" name="CustomerId" value="{{ Session::get('customer')->CustomerID }}">
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

    <form class="ui form" action="{{ route('update.business.profile') }}" method="post" enctype="multipart/form-data">

        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <input type="hidden" name="customer_id" value="{{ (Session::get('customer')->CustomerID) }}">
        <h3 class="ui dividing header" style="margin-top: 0;">Update Business Profile</h3>

        <div class="ui top attached tabular menu">
          <a class="item active" data-tab="first">Business Profile</a>
          <a class="item" data-tab="second">Business Attachements</a>
          <a class="item" data-tab="third">Directors</a>
          @if(!$DirectorAttachements === false)
           <a class="item" data-tab="fourth">Director's Work Permits</a>

          @endif

        </div>

        <div class="ui bottom attached tab segment active" data-tab="first">
          <div class="ui basic segment">
            @foreach ($form->sections() as $section )
                @if ($section->Show)
                    @if ( count($section->columns()) > 0 )
                        <div class="ui attached segment">
                            <h5 class=" ui dividing header">{{$section}} </h5>
                            <div class="ui basic segment">
                                @foreach($section->columns() as $col)
                                  @if( isset($application[$col->id()]) )
                                    <?php 
                                    $disabled='';                                   
                                     if($col->id()==4176){
                                      $disabled="disabled";
                                     }?>
                                    {{Api::CustomFormInput($col->id(), $application[$col->id()],$disabled)}}
                                  @else
                                    {{Api::CustomFormField($col->id())}}
                                  @endif
                                @endforeach
                            </div>
  
                        </div>
                        <div class="ui hidden divider"></div>
                    @endif
                @endif
            @endforeach
            <div class="ui section divider"></div>
            <button class="ui fluid green button"> Submit </button>
          </div>
        </div>

        <div class="ui bottom attached tab segment" data-tab="second">

          <table class="ui table">
              <thead>
                <tr>
                  <th>Document Type</th>
                  <th>Document</th>
                  <th>Action</th>
                </tr>
              </thead>
            
            @foreach ($BusinessAttacheMents as $BusinessAttacheMent )
            <tr>
              <td>{{$BusinessAttacheMent->DocumentName}}</td>
              <td><a href='documentdownload.php?BusinessDocId={{$BusinessAttacheMent->BusinessRegistationDocID}}' target='_blank' >View Document </a>
              </td>
              <td><a href='documentdownload.php?BusinessDocId={{$BusinessAttacheMent->BusinessRegistationDocID}}' target='_blank' >Edit </a>
              </td>
            </tr>

            @endforeach
          </table>

        </div>

        <div class="ui bottom attached tab segment" data-tab="third">
          <table class="ui compact celled definition table">
            <thead>
              <tr>
                <th></th>
                <th>FirstName</th>
                <th>LastName</th>
                <th>KRAPIN</th>
                <th>IDNO</th>
                <th>Creation Date</th>
                <th>Action</th>

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
                    <td>
                      <a class="ui green icon button" href="">
                        <i class="pencil icon"></i>
                      </a>
                    </td> 
                    <td>
                    <a class="ui red icon button" href="/removeDirector/{{$Director->DirectorsID}}">
                        <i class="trash icon"></i>
                      </a>
                    </td> 
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

        </div>

        @if(!$DirectorAttachements === false)
          <div class="ui bottom attached tab segment" data-tab="fourth">
            <table class="ui table">
              <thead>
                <tr>
                  <th>Document Name</th>
                  <th>Document</th>
                  <th>Action</th>
                </tr>
              </thead>
            
            @foreach ($DirectorAttachements as $DirectorAttachement )
            <tr>
              <td>{{$DirectorAttachement->FileName}}</td>
              <td><a href='documentdownload.php?BusinessDocId={{$DirectorAttachement->AttachementId}}' target='_blank' >View Document </a>
              </td>
              <td><a href='documentdownload.php?BusinessDocId={{$DirectorAttachement->AttachementId}}' target='_blank' >Edit </a>
              </td>
            </tr>

            @endforeach
          </table>

          </div>
        @endif
        
       
    </form>
@endsection

@section('script')
  <script type="text/javascript">
      $( document ).ready(function() {
        $('.menu .item').tab();
        
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


      });
  </script>
@endsection
