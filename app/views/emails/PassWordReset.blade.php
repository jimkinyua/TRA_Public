@extends('emails.template')

@section('content')
    
    <h1>Password Reset</h1>

    <h2>Hello {{$LastName.', '.$FirstName.' '.$MiddleName}}</h2>
    <p>

    </p>
    <p>Your TRA Account Password has been Rest:
    </p>

    <p><a href="{{route('reset.portal.user',$confirm_token)}}" 
        class="btn btn-success btn-lg">Click Here to Confrim It's You Trying 
        to Reset the Password</a> 
    </p>
@endsection
