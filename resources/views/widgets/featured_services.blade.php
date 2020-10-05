@foreach($services as $service)
  <!-- accordion item -->
  <div class="title">
    <i class="dropdown icon"></i>
    <span class="ui small header"> {{$service->Title}} </span>
  </div>
  <div class="content">
    <p>
      {{$service->ShortDecsription}}
    </p>
      <a class="ui teal" href="#"> More Information <i class="ui right double angle icon"></i>  </a>
  </div>
@endforeach
