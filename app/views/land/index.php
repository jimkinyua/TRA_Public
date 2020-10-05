@extends('app')

@section('content')

<h3> Land Management </h3>
<div class="panel">
  <div class="panel-header bg-lightBlue fg-white"> Registration </div>
  <div calss="panel-conte"
<form action="{{route('search.land')}}" method="post" enctype="multipart/form-data">

  <fieldset>
    <legend>Search for land</legend>
    <div>
      <input style="width:60%" name="search" id="search" type="text" placeholder="Enter Unique Parcel Number or LR/Block Number" class="form-control" />
    </div>

    <br/>
    <div>
      <input class="warning" type="submit" value="Search" />
    </div>
  </fieldset>

</form>

@endsection
