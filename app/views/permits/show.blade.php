@extends('dashboard.manage')

@section('dashboard-content')

  <div class="ui orange attached segment">
    <h4 class="ui center aligned icon header">
      <i class="circular info icon"></i>
      Single Business Permit for {{$service->ServiceName}}
      <div class="sub header">
        Issued: {{$application->SubmissionDate}} <br/>
        <?php $link = 'admin/pdfdocs/sbps/'.$application->ServiceHeaderID.'.pdf'; ?>
        <a href="{{asset($link)}}">download</a>
      </div>
    </h4>

  </div>

@endsection

@section('script')
  @parent
  <script type="text/javascript">
     $( document ).ready(function() {
       $('#dashboard-menu #manage').trigger('click');
     });
  </script>
@endsection
