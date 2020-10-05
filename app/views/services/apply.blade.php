@extends('app')

@section('content')
    <label><h2>Services</h2></label>
    @if($ServiceNo == 508)
      <br/>
      <a class="button warning" href="{{{ asset('uploads/signage.pdf') }}}">View Signage Prices  </a>
      <hr/>
    @endif
    <div class="panel-content">
        @include('services.partials.form')
    </div>
@endsection
