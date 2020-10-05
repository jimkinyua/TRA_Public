@extends('app')

@section('content')

<form id="houserent" action="{{route('search.housing')}}" method="post" enctype="multipart/form-data">

  <fieldset>
    <legend>Pay Or Apply For a Stall</legend>
    <div>
      <label for="house">Select Estate</label>
      {{Form::select('estate',$estates,Input::old('estate'),['class'=>'input-control','id'=>'pay'])}}
    </div>
    <br/>

    <div>
      <label for="house">Select Stall</label>
      <select name="house" form="houserent" id="payhse">
        @foreach($stalls as $stall)
          <option value="{{$stall}}">{{$stall}}</option>
        @endforeach
      </select>
    </div>

    <br/>
    <div>
      <input class="warning" type="submit"value="Pay" />
      <a href="" class="button warning" id="applyhref"> Apply </a>
    </div>
  </fieldset>

</form>

@endsection

@section('page_js')
<script>
  $('#pay').change(function(){
    var id = $(this).val();
    update(id);
  });

  function update(id){
    var link = "apply?form=5&ServiceNo=" + id;
    $('#applyhref').attr('href', link);
    getstalls(id);
  }

  function getstalls(id)
  {
    $.post('{{route('fetch.stalls')}}',{ServiceID: id},function(data){
      console.log(id, data);
      var toAppend = '';
        if (data.code == 200){
          $.each(data.stalls,function(i,o){
           toAppend += '<option value="'+o.HouseID+'">'+o.HouseNo+'</option>';
         });
        }
        $('#payhse').html(toAppend);
        $('#applyhse').html(toAppend);
    });
  }
</script>
@stop
