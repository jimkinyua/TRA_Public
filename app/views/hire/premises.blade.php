@extends('hire.services')

@section('service')
  <form class="ui form" action="{{ route('hire.premise') }}" method="post" enctype="multipart/form-data">

    <input type="hidden" name="_token" value="{{ csrf_token() }}">

    <h4 class=" ui orange dividing header"> Application for hire fo County Premises</h4>

    <div class="required field">
      <label>Premise</label>
      <select name="service" id="service" class="ui dropdown">
        <option value="">Premise</option>
        @foreach($premises as $key => $premise)
          <option value={{$key}}> {{$premise}} </option>
        @endforeach
      </select>
    </div>

    <div class="required field">
      <label>Purpose of Hire</label>
      <input name="purpose" type="text" placeholder="Purpose of Hire">
    </div>

    <div class="two fields">
      <div class="required field">
        <label>Start Date</label>
        <input name="start" id="start" placeholder="Start Date" type="text">
      </div>
      <div class="required field">
        <label>End Date</label>
        <input name="end" id="end" placeholder="Start Date" type="text">
      </div>
    </div>

    <div class="ui section divider"></div>

    <button class="fluid ui positive button">Submit</button>

  </form>
@endsection

@section('script')
  @parent
  <script type="text/javascript">
     $( document ).ready(function() {
       $('#hire-menu #premises').trigger('click');
       $('select.dropdown').dropdown();
       $('#start, #end').dateDropper();
     });
  </script>
@endsection
