@extends('dashboard.manage')

@section('dashboard-content')
  <?php $path = asset('admin/pdfdocs/invoices') . '/' . $id  . '.pdf'; ?>
  <a class="media" href="{{$path}}" style="text-align: center">Preview Invoice</a>
@endsection

@section('script')
    <script type="text/javascript">
        $('a.media').media({width:700, height:400});
    </script>
@endsection
