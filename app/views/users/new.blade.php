@extends('app')

@section('content')
    <form class="form-horizontal" role="form" method="POST" action="{{route('add.user')}}">
        <fieldset>
            <legend>Add New User</legend>
            <div class="grid">
            <div class="row">
                <div class="span4">
                    <div class="form-group">
                        <label class="col-md-4 control-label">First Name</label>
                        <div class="span4 input-control text">
                            <input type="text" class="form-control" name="FirstName" value="{{ Input::old('FirstName') }}">
                        </div>
                    </div>
                </div>
                <div class="span4">
                    <div class="form-group">
                        <label class="col-md-4 control-label">Middle Name</label>
                        <div class="span4 input-control text">
                            <input type="text" class="form-control" name="MiddleName" value="{{ Input::old('MiddleName') }}">
                        </div>
                    </div>
                </div>
                <div class="span4">
                    <div class="form-group">
                        <label class="col-md-4 control-label">Last Name</label>
                        <div class="col-md-6 input-control text">
                            <input type="text" class="form-control" name="LastName" value="{{ Input::old('LastName') }}">
                        </div>
                    </div>
                </div>
            </div>
            </div>
            <div class="row">
                <div class="span6">
                    <div class="form-group">
                        <label class="col-md-4 control-label">ID Number</label>
                        <div class="col-md-4 input-control text">
                            <input type="text" class="form-control" name="IDNumber" value="{{ Input::old('IDNumber') }}">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-md-4 control-label">Mobile</label>
                        <div class="col-md-6 input-control text">
                            <input type="text" class="form-control" name="Mobile" value="{{ Input::old('Mobile') }}">
                        </div>
                    </div>

                </div>
                <div class="span6">
                    <div class="form-group">
                        <label class="col-md-4 control-label">E-Mail Address</label>
                        <div class="col-md-6 input-control text">
                            <input type="email" class="form-control" name="email" value="{{ Input::old('email') }}" autocomplete="off">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-md-4 control-label">Password</label>

                        <div class="span6 input-control text">
                            <div class="input-group">
                                <input id="passowrd" type="password" class="form-control" name="password">
                      <span class="input-group-btn">
                        <button id="myLink" class="button info">Generate Password</button>
                      </span>
                            </div>
                            <span id="my-display-element" ></span>
                        </div>
                    </div>
                </div>
            </div>
            <br>
            <div class="form-group btn-clear">
                <div class="col-md-6 col-md-offset-4">
                    <button type="submit" class="button primary large">
                        Add Account
                    </button>
                </div>
            </div>
        </fieldset>
        <input type="hidden" name="_token" value="{{ csrf_token() }}">


    </form>
@endsection

@section('page_js')
    <script>
        $(document).ready(function(){

            $('#myLink').pGenerator({
                'bind': 'click',
                'passwordElement': '#password',
                'displayElement': '#my-display-element',
                'passwordLength': 16,
                'uppercase': true,
                'lowercase': true,
                'numbers':   true,
                'specialChars': true,
                'onPasswordGenerated': function(generatedPassword) {
                    alert('Generated Password:  ' + generatedPassword);
                }
            });
        });
    </script>
@endsection