<h3>Select Department</h3>
@foreach($entities as $entity)

    <a class="tile double bg-{{Api::RandomBackgroundColor()}}" href="{{route('list.services',$entity->id())}}">
        <div class="tile-content icon">
            <i class="icon-folder"></i>
        </div>
        <div class="tile-status">
            <span class="name">{{$entity}}</span>
        </div>
    </a>

 @endforeach