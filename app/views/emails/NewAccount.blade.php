@extends('emails.template')

@section('content')
    <h1>Hello {{$LastName.', '.$FirstName.' '.$MiddleName}}</h1>
    <p>Your account has been created with the following login credentials:<br>
    <strong>Email : </strong>{{$email}}<br>
    <strong>Password : </strong>{{$password}}
    </p>

    <p><a href="{{route('get.login')}}" class="btn btn-success btn-lg">Click Here View Your Profile</a> </p>
@endsection
