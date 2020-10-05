@extends('emails.template')

@section('content')
    <h1>Hello {{$LastName.', '.$FirstName.' '.$MiddleName}}</h1>
    <p>Your account has been created with the following login credentials:
        <strong>Email : </strong>{{$email}}<br>
        <strong>Password : </strong>{{$password}}
    </p>

    <p><a href="{{route('activate.portal.user',$confirm_token)}}" class="btn btn-success btn-lg">Click Here to Activate Account</a> </p>
@endsection
