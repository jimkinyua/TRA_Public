@extends('app')

@section('content')

                    <form class="form-horizontal span6 offset3" role="form" method="POST" action="{{route('post.setup')}}">
                        <h2>Database Setup</h2>
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">

                        <div class="form-group">
                            <label class="label">E-Mail Address</label>
                            <div class="input-control select">
                                {{Form::select('driver',array(
                                'mysql'=>'MySQL',
                                'sqlsrv'=>'SQL Server',
                                'pgsql'=>'Postgres SQL'
                                ),Input::old('driver'))}}
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="label">Host Server</label>
                            <div class="input-control text">
                                <input type="text" class="form-control" name="server" value="{{Input::old('server')}}">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="label">Database Name</label>
                            <div class="input-control text">
                                <input type="text" class="form-control" name="database"  value="{{Input::old('database')}}">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="label">Database User</label>
                            <div class="input-control text">
                                <input type="text" class="form-control" name="user"  value="{{Input::old('user')}}">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="label">User Password</label>
                            <div class="input-control text">
                                <input type="text" class="form-control" name="password"  value="{{Input::old('password')}}">
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="">
                                <button type="submit" class="button primary" style="margin-right: 15px;">
                                    Save & Run
                                </button>
                            </div>
                        </div>
                    </form>
@endsection
