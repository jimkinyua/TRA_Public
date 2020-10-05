@extends('dashboard.services')

@section('dashboard-content')

       <!--
        <table class="ui striped called table">
            <tr>
                <th class="six wide column">First Name</th>
                <td class="ten wide column">{{$entity->FirstName}}</td>
            </tr>
        </table>
        -->

            <table class="ui striped celled table">
                <thead>
                    <th colspan="2">{{$entity->LastName}} {{$entity->MiddleName}} {{$entity->FirstName}}</th>
                </thead>
                <tbody>
                    <tr>
                        <th class="collapsing six wide column">First Name</th>
                        <td >{{$entity->FirstName}}</td>
                    </tr>
                    <tr>
                        <th>Middle Name</th>
                        <td>{{$entity->MiddleName}}</td>
                    </tr>
                    <tr>
                        <th>Last Name</th>
                        <td>{{$entity->LastName}}</td>
                    </tr>
                    <tr>
                        <th>IDNumber</th>
                        <td>{{$entity->IdNumber}}</td>
                    </tr>
                    <tr>
                        <th>Phone Number</th>
                        <td>{{$entity->Mobile}}</td>
                    </tr>

                    <tr>
                        <th>Email Address</th>
                        <!-- <td><a href="mailto:{{$entity->Email}}">{{$entity->Email}}</a></td> -->
                        <td>{{$entity->Email}}</td>
                    </tr>
                </tbody>
            </table>

            <hr/>
            <a href="#">Change Password </a>




@endsection
