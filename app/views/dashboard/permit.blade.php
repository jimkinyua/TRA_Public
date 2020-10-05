@extends('dashboard.manage')

@section('dashboard-content')
  <?php $path = asset('admin/pdfdocs/sbps') . '/' . $url  . '.pdf'; ?>
  <a class="media" href="{{$path}}" style="text-align: center">Preview Permit</a>
@endsection

@section('script')
    <script type="text/javascript">
        $('a.media').media({width:700, height:400});
    </script>
@endsection
