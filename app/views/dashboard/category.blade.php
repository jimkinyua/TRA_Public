@extends('dashboard.financebill')

@section('dashboard-content')
    <h3 class="ui left aligned dividing  header">
      @if(isset($services[0]))
        {{$services[0]->group }}
        &nbsp; >> &nbsp;
        {{$services[0]->category }}
      @endif
    </h3>

    <table class="ui  striped celled structured table">
        <thead>
            <tr>
                <th rowspan="2" class="three wide column">Service Code</th>
                <th rowspan="2" class="eight wide column">Service Name</th>
                <th colspan="2" class="five wide column center aligned">Charges (KSh.)</th>
            </tr>
            <tr>
                <th>License Fees</th>
                {{-- <th>Sub County</th> --}}
            </tr>
        </thead>
        <tbody>
            @foreach($services as $service)
                <tr>
                    <td>{{$service->ServiceID}}</td>
                    <td>{{$service->ServiceName}}</td>
                    @foreach($service->currentCharges as $charge)
                     
                        <td>{{number_format($charge->Amount)}}</td>
                    @endforeach
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
