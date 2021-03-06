@extends('dashboard.index')

@section('dashboard-aside')
<div id="department-menu" class="ui vertical accordion menu" style="width: 100%">
  <div class="header item">Services</div>
    <?php //echo '<pre>'; print_r( $bill); exit; ?>

    @if(Session::get('customer')->Type == 'individual')
      @foreach($bill as $index => $group)
        <?php //echo '<pre>'; print_r( $group); exit; ?>
        <div class="item" data-group-id="{{$group->ServiceGroupID}}" data-offset-id="{{$index}}">
              <a class="active title">
                <i class="dropdown icon"></i>
                {{$group->ServiceGroupName}}
              </a>
          
      
          <div class="content">
            <div class="menu">
              @foreach($group->primaryCategories as $cat)
                <?php //echo '<pre>'; print_r( $cat->services); exit; ?>
                @foreach ($cat->services as $service)
                  @if($service->IsAppliedByIndividuals== 1)
                    <a class="item" href="{{route('application.form', $cat->ServiceCategoryID)}}" data-category-id="{{$cat->ServiceCategoryID}}">
                    <strong> {{$cat->ServiceCode}} </strong>   {{$service->ServiceName}} </a>
                  @endif
                @endforeach
              @endforeach
            </div>
          </div>  
        
        </div>
      @endforeach
    @endif

    @if(Session::get('customer')->Type == 'business')
      @foreach($bill as $index => $group)
        <?php //echo '<pre>'; print_r( $group); exit; ?>
        <div class="item" data-group-id="{{$group->ServiceGroupID}}" data-offset-id="{{$index}}">
              <a class="active title">
                <i class="dropdown icon"></i>
                {{$group->ServiceGroupName}}
              </a>
          
      
          <div class="content">
            <div class="menu">
              @foreach($group->primaryCategories as $cat)
                <?php //echo '<pre>'; print_r( $cat->services); exit; ?>
                    <a class="item" href="{{route('application.form', $cat->ServiceCategoryID)}}" data-category-id="{{$cat->ServiceCategoryID}}">
                    <strong> {{$cat->ServiceCode}} </strong>   {{$cat->CategoryName}} </a>
              @endforeach
            </div>
          </div>  
        
        </div>
      @endforeach
    @endif

  </div>

@endsection

@section('script')
  @parent
  <script type="text/javascript">
     $( document ).ready(function() {
       $('#dashboard-menu #services').trigger('click');
       $('.ui.accordion').accordion();
     });
  </script>
@endsection
