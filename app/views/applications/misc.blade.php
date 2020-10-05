@extends('dashboard.services')


@section('dashboard-content')
  <div class="ui ignored info message">
    <p>
      The fields marked with * are required
    </p>
  </div>

  <form class="ui form"  action="{{ route('submit.miscpay') }}" method="post" enctype="multipart/form-data">
    <input type="hidden" name="form_id" value="{{$form->id()}}" >

    <div class="required field">
        <label>Service</label>
        <select name="service_id" class="ui fluid search selection dropdown" id="service">
            <option value="0"> Select Service </option>
            <?php $selected = (count($services) == 1) ? "selected='selected'" : "" ?>
            @foreach($services as $service)
                <option value="{{$service->ServiceID}}" {{$selected}} > <strong> {{$service->ServiceCode}} </strong> {{$service->ServiceName}} </option>
            @endforeach
        </select>
    </div>

      @foreach ($form->sections() as $section )
          @if ($section->Show && !$section->Optional)
              @if ( count($section->columns()) > 0 )
                  <div class="ui attached segment">
                      <h4 class=" ui dividing header">{{$section}} </h4>
                      <div class="ui basic segment">
                          @foreach($section->columns() as $col)
                              {{Api::CustomFormField($col->id())}}
                          @endforeach
                      </div>
                  </div>
                  <div class="ui hidden divider"></div>
              @endif
          @endif
      @endforeach

      <button id="submit" class="fluid ui positive button">Submit</button>
    </form>
@endsection

@section('script')
    <script type="text/javascript">
      $('.ui.dropdown').dropdown();
    </script>
    @parent
@endsection
