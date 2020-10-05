@extends('finance')

@section('bill-left')
  @widget('dockets')
@endsection

@section('bill-content')
  <div class="ui middle aligned selection list">
    @foreach ($services as $service)
    <div class="item">
      <div class="content">
        <div class=" relaxed header">
          <a class="link item" href="{{ action('WelcomeController@service', $service->id) }}">
            {{ $service->name }}
          </a>
        </div>
      </div>
    </div>
    @endforeach
  </div>
@endsection

@section('bill-right')
  @widget('departments')
@endsection
