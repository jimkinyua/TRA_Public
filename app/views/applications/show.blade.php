@extends('permits.services')

@section('service')
    <h3 class="ui left aligned dividing  header">  {{$service->ServiceName}}  </h3>

    <form class="ui form"  action="{{ route('update.application') }}" method="post" enctype="multipart/form-data">

        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <input type="hidden" name="service_header_id" value="{{$header}}" >
        <input type="hidden" name="CategoryNumber" value="<?= isset($categoryID)?$categoryID:'' ?>" >
        <input type="hidden" name="customer_id" value="{{Session::get('customer')->CustomerID}}" >
        <?php  ?>
        @if( $form->id() == 2 )
          <div class="ui attached segment">
            <div class="required field">
                <label>Business Category</label>
                <div class="ui fitted hidden divider"></div>
                <textarea cols="50" rows="4" disabled="true">{{$categoryName}}</textarea>
            </div>

              {{-- <div class="required field"> --}}
                  
                  {{-- <label>Business Category</label> --}}
                  {{-- <div class="ui fitted hidden divider"></div> --}}
                  {{-- <select class="ui  dropdown" id="category" onchange="filterActivity()">
                      <option value="0" > Select Category </option>
                      @foreach($bill as $index => $group)
    
                        @foreach($group->primaryCategories as $cat)
                       
                            @if($cat->id() == $CategoryId)
                              <option value="{{$cat->id()}}" > <strong> {{$cat->ServiceCode}} </strong> {{$cat->CategoryName}} </option>
                            @endif()
                          <!-- <option value="{{$cat->id()}}" > {{$cat->CategoryName}} </option> -->
                          <option value="{{$cat->id()}}" > <strong> {{$cat->ServiceCode}} </strong> {{$cat->CategoryName}} </option>
                        @endforeach
                      @endforeach
                  </select> --}}
              {{-- </div> --}}

              <div class="required field">
                  <label>Service Applied</label>
                  <div class="ui fitted hidden divider"></div>
                  <select name="service_id" class="ui dropdown" id="service" disabled>
                  <option value="{{$SavedServiceID}}" selected="true"> {{$SavedServiceName}} </option>
                      @foreach($services as $service)
                          <option value="{{$service->ServiceID}}"> <strong> {{$service->ServiceCode}} </strong> {{$service->ServiceName}} </option>
                      @endforeach
                  </select>
              </div>
              <div class="required field">
                  <label>Applicant</label>
                  <input type="text" name="customer" value="{{Session::get('customer')->CustomerName}}" disabled>
                  <b> <input type="hidden" name="customer_id" value="{{Session::get('customer')->CustomerID}}" > </b>
              </div>
          </div>

          <div class="ui hidden divider"></div>

          @else

          <div class="required field">
              <label>Service</label>
              <div class="ui fitted hidden divider"></div>
              <select name="service_id" class="ui dropdown" id="service" disabled>
                  <option value="0"> Select Service </option>
                  <?php $selected = (count($services) == 1) ? "selected='selected'" : "" ?>
                  @foreach($services as $service)
                      <option value="{{$service->ServiceID}}" {{$selected}} > <strong> {{$service->ServiceCode}} </strong> {{$service->ServiceName}} </option>
                  @endforeach
              </select>
          </div>
          @endif

        @foreach ($form->sections() as $section )
            @if ($section->Show)
                @if ( count($section->columns()) > 0 )
                    <div class="ui attached segment">
                        <h4 class=" ui dividing header">{{$section}} </h4>
                        <div class="ui basic segment">
                          @foreach($section->columns() as $column )
                              @if( isset($application[$column->id()]) )
                                @if (!$Status == 0)
                                    {{Api::CustomFormInputReadOnly($column->id(), $application[$column->id()])}}
                                    
                                    @endif
                                    
                                @endif
                              
                          @endforeach
                        </div>
                    </div>
                    <div class="ui hidden divider"></div>
                @endif
            @endif
        @endforeach

        @if ($Status == 0)
        <button class="fluid ui positive button">Update</button>
        
        @endif
        

    </form>
@endsection

@section('script')
    @parent
    <script type="text/javascript">
        // $( document ).ready(function() {
        //     $('#department-menu #permits').trigger('click');
        //     $('.ui.dropdown').dropdown();

        //     {{--
        //     var slct = "[data-group-id=" + {{ $service->group->ServiceGroupID }} + "]";
        //     var index = $(slct).data().offsetId;
        //     $('#department-menu').accordion('open', index);

        //     var slct = "[data-category-id=" + {{ $service->category->ServiceCategoryID }} + "]";
        //     $(slct).addClass('activ');
        //     --}}
        // });
    </script>
@endsection
