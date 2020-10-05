@extends('app')

@section('content')


<form action="{{route('land.search')}}" method="post" enctype="multipart/form-data">

  <fieldset>
    <legend>Search for land</legend>
    <div>
      <input style="width:60%" name="UPN" id="UPN" type="text" placeholder="Enter Unique Parcel Number or LR/Block Number" class="form-control" />
    </div>

    <br/>
    <div>
      <input class="warning" type="submit" value="Search" />
    </div>
  </fieldset>

</form>

@endsection
