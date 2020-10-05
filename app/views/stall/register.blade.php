@extends('app')

@section('content')
<form class="form" action="{{ route('stall.submit.registration') }}" method="post" enctype="multipart/form-data">

  <div class="container">
    <div class="row">
      <div class="span8">

            <div class="form-group">
                {{Form::hidden('FormID',$FormID,Input::old('FormID'),['data-transform'=>'input-control'])}}
                {{Form::hidden('ServiceID',$ServiceID,Input::old('ServiceID'),['data-transform'=>'input-control'])}}
                {{Form::hidden('CustomerID',Auth::id(),Input::old('CustomerID'),['data-transform'=>'input-control'])}}
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

      </div>
      <div class="span4">
        <h3>Instructions </h3>
        <hr/>
        <p>
          Specify a stall number if that stall had been registered to you <br/> <br/>
          Otherwise for new applicants, leave that field blank.
        </p>
      </div>
    </div>
  </div>

</form>
@endsection
