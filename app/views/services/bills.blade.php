@extends('app')

@section('content')
    <label><h2>Invoices</h2></label>

        <table class="table striped hovered dataTable" id="data-table">
            <thead>
            <tr>
                <th># No</th>
                <th>Business</th>
                <th>Invoice Date</th>
                <th>Amount</th>
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
                "sAjaxSource": "{{route('list.bills.ajax')}}",
                "aoColumns": [
                    { "aaData": "ID" },
                    { "aaData": "Customer" },
                    { "aaData": "Date" },
                    { "aaData": "Total" },
                    { "aaData": "view" }
                ]
            } );
        });
    </script>

@endsection