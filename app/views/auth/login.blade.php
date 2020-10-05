@extends('app')

@section('content')

	<form class="form-horizontal span6 offset3" role="form" method="POST" action="{{route('post.login')}}">
		<h2>User Login</h2>
		<input type="hidden" name="_token" value="{{ csrf_token() }}">

		<div class="form-group">
			<label class="col-md-4 control-label">E-Mail Address</label>
			<div class="col-md-6 input-control text">
				<input type="email" class="form-control" name="email" value="{{ Input::old('email') }}">
			</div>
		</div>

		<div class="form-group">
			<label class="col-md-4 control-label">Password</label>
			<div class="col-md-6 input-control password">
				<input type="password" class="form-control" name="password">
			</div>
		</div>

		<div class="form-group">
			<div class="col-md-6 col-md-offset-4">
				<div class="checkbox">
					<label>
						<input type="checkbox" name="remember"> Remember Me
					</label>
				</div>
			</div>
		</div>

		<div class="form-group">
			<div class="col-md-6 col-md-offset-4">
				<button type="submit" class="button primary" style="margin-right: 15px;">
					Login
				</button>

				<a href="/password/email">Forgot Your Password?</a>
			</div>
		</div>
	</form>
@endsection
