@extends('dashboard')

@section('content')
<div class="ui basic segment">
  <div id="bottom" class="ui basic clearing segment">
    <h5 class="ui right floated header">
      <a href="{{ route('plot.create') }}" class="ui olive button"> Register plot </a>
    </h5>
    <h5 class="ui left floated header">
      <div class="ui basic index button"> {{$plots->count()}} Registered Plot(s)  </div>
    </h5>
  </div>

  @if ($plots->count() > 0)
  <div class="index ui fluid small borderless vertical menu">
    @foreach ($plots as $plot)
      <a class="link item" href="{{ route('account.edit', $plot->id) }}">
        <div class="content">
          <div class="header">
            {{ $plot->number }}
            <div class="subheader"> {{ $plot->area }} </div>
            <div class="subheader"> {{ $plot->landuse }} </div>
          </div>
        </div>
      </a>
    @endforeach
  </div>
  @else
  <div class="ui basic segment">
    <p>
      <strong> You currently have no registered land records. <strong>  </br>
      If you had submitted a registration application, the record will appear here once your application is processed.  </br>
      Click the link above to access land registration application form. </br>
      Note that your application will be reviewed. Therefore remain truthful with the information you provide.
    </p>
    <p>
      Once your application is approved, you will be able to pay land rates through the portal.
    </p>
  </div>
  @endif

</div>
@endsection
