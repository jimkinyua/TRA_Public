@extends('dashboard.manage')

@section('dashboard-content')
<form class="ui form" action="{{ route('post.add.vehicle',$id) }}" method="post" enctype="multipart/form-data" id="add-vehicle">
    <div class="ui basic very padded segment">
      <div class="ui top attached tabular menu">
          <a class="active item" data-tab="details">Business Details</a>
          <a class="item" data-tab="employees">Business Employees</a>
          <a class="item" data-tab="vehicles">Company Vehicles</a>
      </div>

      <div class="ui bottom attached active tab segment" data-tab="details">
          <table class="ui compact celled definition table">
              <thead>
              <tr>
                  <th></th>
                  <th>Business Name</th>
                  <th>Business Website</th>
              </tr>
              </thead>
              <tbody>
              @foreach($business as $key => $val)
                  <tr>
                      <td>
                          <i class="pin icon"></i>
                      </td>
                      <td>{{$key}}</td>
                      <td>{{$val}}</td>
                  </tr>
              @endforeach
              </tbody>
          </table>
      </div>

      <div class="ui bottom attached tab segment" data-tab="employees">
          <h4 class="ui dividing header"> Account Users </h4>
          <table class="ui compact celled definition table">
              <thead>
              <tr>
                  <th></th>
                  <th>Name</th>
                  <th>Registration Date</th>
                  <th>ID/Passport Number</th>
              </tr>
              </thead>
              <tbody>
                
              </tbody>
              <tfoot class="full-width">
              <tr>
                  <th></th>
                  <th colspan="4">
                      <div class="ui right floated small primary labeled icon button">
                          <i class="user icon"></i> Add User
                      </div>
                      <div class="ui small button">
                          Approve
                      </div>
                      <div class="ui small  disabled button">
                          Approve All
                      </div>
                  </th>
              </tr>
              </tfoot>
          </table>

      </div>
      <div class="ui bottom attached tab segment" data-tab="vehicles">
          <table class="ui celled padded table">
            <thead>
              <tr>
                <th>Registration No</th>
                <th>Bus Park</th>
                <th>Sitting Capacity</th>
                <th>Route</th>
                <th>Status</th>
              </tr>
            </thead>
            <tbody>
              @foreach($vehicles as $mat)
                <tr>
                  <td>{{$mat->RegNo}}</td>
                  <td>{{$mat->ParkName}}</td>
                  <td>{{$mat->SittingCapacity}}</td>
                  <td>{{$mat->RouteName}}</td>
                  <td>{{$mat->Status}}</td>

                </tr>
              @endforeach
            </tbody>
            <tfoot class="full-width">            
              <tr>
                  <td>
                    <div class="ui input">
                      <input type="text" name="RegNumber" id="RegNumber" placeholder="Reg Number..">
                    </div>
                  </td>
                  <td>
                    <select class="ui search dropdown" name="BusPark" id="BusPark">
                      <option value="">Bus Park</option>
                      @foreach ($parks as $key=>$park)
                        <option value={{$key}} >{{$park}}</option>
                      @endforeach
                    </select>
                  </td>
                  <td>
                    <div class="ui input">
                      <select class="ui search dropdown" name="sittingcapacity" id="sittingcapacity">
                      <option value="">Sitting Capacity</option>
                      @foreach ($capacities as $key=>$capacity)
                        <option value={{$key}} >{{$capacity}}</option>
                      @endforeach
                    </select>
                    </div>
                  </td>
                  <td>
                    <select class="ui search dropdown" name="Route" id="Route">
                      <option value="">Route</option>
                      @foreach ($routes as $key=>$route)
                        <option value={{$key}} >{{$route}}</option>
                      @endforeach
                    </select>
                  </td>
                  <td>
                    <input class="ui button" type="submit" value="Add Vehicle"/>                  
                  </td>
                                           
              </tr>
            </tfoot>
          </table>
      </div>
    </div>
</form>
@endsection

@section('script')
    @parent
    <script type="text/javascript">
        $( document ).ready(function() {
            $('#registered-businesses').trigger('click');
            $('.menu .item').tab();
            $('.ui.checkbox').checkbox();
            $('.ui.accordion').accordion('open', 0);

            $('#add-vehicle').submit(function(ev) {
              // to stop the form from submitting
              ev.preventDefault(); 
              //SUBMIT FORM via AJAX
              $.ajax({
                  url     : $(this).attr('action'),
                  type    : $(this).attr('method'),
                  data    : $(this).serialize(),
                  success : function( data ) {
                              // alert('Submitted');
                               // console.log(data)
                               $('#add-vehicle')[0].reset()
                               setDOM(document, data)
                               $('a[data-tab="vehicles"]').trigger('click');
                  },
                  error   : function( xhr, err ) {
                      console.log(err)
                               alert('Error');     
                  }
              }); 
            });
        });
    </script>
@endsection
