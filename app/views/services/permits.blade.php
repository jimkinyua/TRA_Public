@extends('app')

@section('content')
    <label><h2>Permits</h2></label>

    <table class="table striped hovered dataTable" id="data-table">
        <thead>
        <tr>
            <th>Permit No</th>
            <th>Business</th>
            <th>Service</th>
            <th>Date</th>
            <th>Status</th>
            <th></th>
        </tr>
        </thead>
        <tbody>
        </tbody>

    </table>
@endsection

@section('page_js')

    <script>
        $(function(){
            $('#data-table').dataTable( {
                "bProcessing": true,
                "sAjaxSource": "{{route('list.permits.ajax')}}",
                "aoColumns": [
                    { "aaData": "PermitNo" },
                    { "aaData": "CustomerName" },
                    { "aaData": "ServiceName" },
                    { "aaData": "Date" },
                    { "aaData": "Status" },
                    { "aaData": "view" }
                ]
            } );
        });
    </script>

@endsection