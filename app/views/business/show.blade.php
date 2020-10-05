@extends('business.services')

@section('service')
<div class="ui top attached tabular menu">
  <a class="active item" data-tab="details">Business Details</a>
  <a class="item" data-tab="employees">Business Employees</a>
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
      <tr>
        <td class="collapsing">
          <div class="ui fitted slider checkbox">
            <input type="checkbox"> <label></label>
          </div>
        </td>
        <td>John Lilki</td>
        <td>September 14, 2013</td>
        <td>27689065</td>
      </tr>
      <tr>
        <td class="collapsing">
          <div class="ui fitted slider checkbox">
            <input type="checkbox"> <label></label>
          </div>
        </td>
        <td>Jamie Harington</td>
        <td>January 11, 2014</td>
        <td>27364893</td>
      </tr>
      <tr>
        <td class="collapsing">
          <div class="ui fitted slider checkbox">
            <input type="checkbox"> <label></label>
          </div>
        </td>
        <td>Jill Lewis</td>
        <td>May 11, 2014</td>
        <td>38562349</td>
      </tr>
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


@endsection

@section('script')
  @parent
  <script type="text/javascript">
     $( document ).ready(function() {
       $('#registered-businesses').trigger('click');
       $('.menu .item').tab();
     });
  </script>
@endsection
