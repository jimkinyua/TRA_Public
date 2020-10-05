@extends('hire.services')

@section('service')
  <form class="ui form" action="{{ route('hire.stadium') }}" method="post" enctype="multipart/form-data">

    <input type="hidden" name="_token" value="{{ csrf_token() }}">

    <h4 class=" ui orange dividing header"> Application for Hire of Stadium</h4>

    <div class="required field">
      <label>Stadium</label>
      <select name="stadium" id="stadium" class="ui dropdown">
        <option value="">Stadium</option>
        <option value="85">64 Stadium</option>
        <option value="84">Kipchoge Stadium</option>
      </select>
    </div>

    <div class="required field">
      <label>Purpose of Hire</label>
      <select name="service" id="purpose" class="ui dropdown">
        <option value="">Purpose</option>
      </select>
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
       $('#hire-menu #stadia').trigger('click');
       $('select.dropdown').dropdown();
       $('#start, #end').dateDropper();
       $('#stadium').change(function(){
         var id = $(this).val();
         $.post('{{route('hire.purposes')}}',{sci: id},function(data){
           var toAppend = '';
           $.each(data,function(i,o){
            toAppend += '<option value='+ i + '>' + o + '</option>';
          });
          $('#purpose').html(toAppend);
         });
       });
     });
  </script>
@endsection
