<div id="department-menu" class="ui vertical accordion menu" style="width: 100%">
  <div class="header item">Services</div>

  @foreach($bill as $index => $group)
    <div class="item" data-group-id="{{$group->ServiceGroupID}}" data-offset-id="{{$index}}">
      <a class="active title">
        <i class="dropdown icon"></i>
        {{$group->ServiceGroupName}}
      </a>
      <div class="content">
        <div class="menu">
          @foreach($group->primaryCategories as $cat)
            <a class="item" href="{{route('application.form', $cat->ServiceCategoryID)}}" data-category-id="{{$cat->ServiceCategoryID}}">
              {{$cat->CategoryName}} </a>
          @endforeach
        </div>
      </div>
    </div>
  @endforeach

</div>
