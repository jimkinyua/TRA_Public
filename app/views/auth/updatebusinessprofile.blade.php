@extends('dashboard.manage')

@section('dashboard-content')


    <form class="ui form" action="{{ route('update.business.profile') }}" method="post" enctype="multipart/form-data">

        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <input type="hidden" name="customer_id" value="{{ (Session::get('customer')->CustomerID) }}">
        <h3 class="ui dividing header" style="margin-top: 0;">Update Business Profile</h3>

        <div class="ui top attached tabular menu">
          <a class="item active" data-tab="first">Business Profile</a>     

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
                                    ?>
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
              <td><a href="/ViewUpload/{{$BusinessAttacheMent->BusinessRegistationDocID}}" >View Document </a>
              {{-- </td>
              <td><a href='documentdownload.php?BusinessDocId={{$BusinessAttacheMent->BusinessRegistationDocID}}' >Delete </a>
              </td> --}}
            </tr>

            @endforeach
          </table>

            <div class="ui section divider"></div>
            <button class="ui fluid green button"> Save Changes </button>
          </div>
        </div>

      
         

        


        
       
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
