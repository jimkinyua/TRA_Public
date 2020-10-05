<form class="form" action="" method="post" enctype="multipart/form-data">
    <div class="form-group">
        <label>{{$form}}</label>
        <div class="input-control select">
            {{Form::select('CustomerID',$businesses,Input::old('CustomerID'),['data-transform'=>'input-control'])}}
        </div>
    </div>
    @foreach ($form->sections() as $section )
        @if ($section->Show)
        <div class="panel">
            <div class="panel-header">{{$section}}</div>
            <div class="panel-content">
                @foreach($section->columns() as $col)
                    {{Api::CreateFormField($col->id())}}
                @endforeach

            </div>
        </div>
        @endif
    @endforeach
    @if ($form->documents->count() > 0)
        <div class="panel">
            <div class="panel-header">Attachments</div>
            <div class="panel-content">
                @foreach($form->documents as $doc)
                    <div class='form-group'>
                        <label class='label'>{{$doc->type}}</label>
                        <?php if ($doc->Mandatory) $mandatory = ['required'=>'required']; else $mandatory =[]; ?>
                        <div class='input-control file'>
                            {{Form::file('ServiceDocument['.$doc->id().']',$mandatory)}}
                            <button class="btn-file"></button>
                        </div>
                    </div>
                @endforeach

            </div>
        </div>
    @endif
        <div class="form-actions">
            <button type="button" class="button info">Save</button>
            <button type="submit" class="button primary">Submit</button>
        </div>
</form>

@section('page_js')
<script>
  $("[name='ColumnID[120]']").change(function(){
    var id = $(this).val();
    console.log(id);
    getWards(id);
  });

  function getWards(id)
  {
    $.post('{{route('get.wards')}}',{SubCountyID: id},function(data){
      //console.log(id, data);
      var toAppend = '';
        if (data.code == 200){
          $.each(data.wards,function(i,o){
           toAppend += '<option value="'+o.WardID+'">'+o.WardName+'</option>';
         });
        }
        $("[name='ColumnID[8]']").html(toAppend);
    });
  }
</script>
@endsection
