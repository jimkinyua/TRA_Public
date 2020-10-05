@extends('portal')

@section('content')

  @if(Auth::user())
    @include('partials.dashboardheader')
  @else
    @include('partials.topmenu')
  @endif

  <div id="dahboard" class="ui grid">
      <div class="five wide column">

        <div id="department-menu" class="ui vertical accordion menu" style="width: 100%">
            <div class="header item">Services </div>

            @foreach($bill as $index => $group)
              <div class="item" data-group-id="{{$group->ServiceGroupID}}" data-offset-id="{{$index}}">
                  <a class="active title">
                      <i class="dropdown icon"></i>
                      {{$group->ServiceGroupName}}
                  </a>
                  <div class="content">
                      <div class="menu">
                          @foreach($group->primaryCategories as $cat)
                              <a class="item" href="{{route('portal.category', $cat->ServiceCategoryID)}}" data-category-id="{{$cat->ServiceCategoryID}}">
                                  {{$cat->CategoryName}} </a>
                          @endforeach
                      </div>
                  </div>
              </div>
            @endforeach
        </div>

      </div>

      <div class="eleven wide column">
        <div class="ui segment">
          @yield('dashboard-content')
        </div>
      </div>
  </div>

@endsection

@section('script')
  @parent
  <script type="text/javascript">
     $( document ).ready(function() {
       $('#topmenu #bill').trigger('click');
       $('.ui.accordion').accordion();
     });
  </script>
@endsection
