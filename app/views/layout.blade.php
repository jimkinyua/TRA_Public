@extends('portal')

@section('content')
<section class="row">
  <div class="nine wide column">
    @yield('section')
  </div>

  <div class="seven wide column">
    @yield('aside')
  </div>
</section>
@endsection
