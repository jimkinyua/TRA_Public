@extends('app')

@section('content')
    <div class="grid">
        <div class="row">
                <div class="panel">
                    <div class="panel-header">Profile User Accounts</div>

                    <div class="panel-content">
                        <a href="{{route('get.add.user')}}" class="btn btn-primary"><i class="glyphicon glyphicon-plus"></i> Add User</a>
                        <table class="table striped hovered dataTable" id="users-table">
                            <thead>
                            <tr>
                                <th>First Name</th>
                                <th>Middle Name</th>
                                <th>Last Name</th>
                                <th>Email</th>
                                <th>ID No.</th>
                                <th>Mobile No.</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>
                            </tbody>


                        </table>
                    </div>
                </div>
        </div>
    </div>
@endsection

@section('page_js')
    <script>
        $(function(){
            $('#users-table').dataTable( {
                "bProcessing": true,
                "sAjaxSource": "{{route('list.users.ajax')}}",
                "aoColumns": [
                    { "aaData": "FirstName" },
                    { "aaData": "MiddleName" },
                    { "aaData": "LastName" },
                    { "aaData": "Email" },
                    { "aaData": "IDNumber" },
                    { "aaData": "Mobile" },
                    { "aaData": "view" }
                ]
            } );
        });
    </script>
@endsection