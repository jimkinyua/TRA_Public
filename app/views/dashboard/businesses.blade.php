@extends('dashboard.manage')

@section('dashboard-content')
    @if(count($applications) == 0)
        <div class="ui orange attached segment">
            <h2 class="ui center aligned icon header">
                <i class="circular hide icon"></i>
                Select a service from your left!
                <div class="sub header">You have not yet applied for any service</div>
            </h2>
        </div>
    @else
        <h5 class="ui center aligned header">
            <div class="ui mini orange horizontal statistic">
                <div class="value">
                    {{count($applications)}}
                </div>
                <div class="label">
                    Registered Business(es).
                </div>
            </div>
        </h5>

        <table class="ui selectable celled definition table">
            <thead>
                <tr>
                    <th></th>
                    <th>Business Name</th>
                    <th>Website</th>
                    <th>Contact Person</th>
                </tr>
            </thead>
            <tbody>
            @foreach($applications as $application)
                <tr>
                    <td> <a href="{{route('dashboard.view.business', $application->CustomerID)}}"> <i class="folder open outline circle icon"></i> </a> </td>
                    <td>{{$application->CustomerName}}</td>
                    <td>{{$application->Website}}</td>
                    <td>{{$application->ContactPerson}}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    @endif

@endsection

@section('script')
    @parent
    <script type="text/javascript">
        $( document ).ready(function() {
            $('#dashboard-menu #manage').trigger('click');
            $('.ui.accordion').accordion('open', 0);
        });
    </script>
@endsection
