<hr/>

<form class="form input-group" action="{{route('search.housing')}}" method="post" enctype="multipart/form-data">
    <input style="width: 40%; display: inline-block" name="estate" id="estate" type="text" placeholder="Estate Name [Pioneer Estate I]" class="form-control" />
    <input style="width: 40%; display: inline-block" name="house" id="house" type="text" placeholder="House number [102]" class="form-control" />
    <div style="display: inline !important"> <button style="padding: 3px 2em" class="warning btn btn-lg" type="submit">Search</button> </div>
</form>
