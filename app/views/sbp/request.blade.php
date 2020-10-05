@extends('layout')

@section('section')
<div class="ui stacked segment">

  <form class="ui form" action="" method="post" enctype="multipart/form-data">
      @foreach ($form->sections() as $section )
          @if ($section->Show)
            <h3 class="ui dividing header">{{$section}}</h4>
            @foreach($section->columns() as $col)
                {{Api::CustomFormField($col->id())}}
            @endforeach
          @endif
      @endforeach

      @if ($form->documents->count() > 0)
        <div class="ui bottom attached segment">
          <h3 class="ui dividing header">Attachments</h3>
          <div class="grouped fields">
            @foreach($form->documents as $doc)
              <div class="field">
                <label for="alone">{{$doc->type}}</label>
                <?php if ($doc->Mandatory) $mandatory = ['required'=>'required']; else $mandatory =[]; ?>
                {{Form::file('ServiceDocument['.$doc->id().']',$mandatory)}}
              </div>
              <div class="ui hidden divider"></div>
            @endforeach
          </div>
        </div>
      @endif

      <div class="ui buttons">
        <div class="ui button">Save</div>
        <div class="or"></div>
        <div class="ui positive button">Submit</div>
      </div>
  </form>


    <p>&nbsp<br/>&nbsp</p>
</div>
@endsection


@section('script')
<script type="text/javascript">
  $( document ).ready(function() {
    $('.ui.dropdown').dropdown();
  });
</script>
@endsection
