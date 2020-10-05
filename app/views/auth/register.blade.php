@extends('app')



@section('content')
	<form class="form-horizontal" role="form" method="POST" action="{{route('post.register')}}">
		<input type="hidden" name="_token" value="{{ csrf_token() }}">

			<div class="span7">
				<div class="panel">
					<div class="panel-header"> User Details </div>
					<div class="panel-content">
						<div class="container">
							<div class="row">
									<div class="row">
										<div class="span6">
											<div class="form-group">
												<label class="label">First Name</label>
												<div class="input-control text">
													<input type="text" class="form-control" name="FirstName" value="{{ Input::old('FirstName') }}">
												</div>
											</div>
										</div>
									</div>

									<div class="row">
										<div class="span3">
											<div class="form-group">
												<label class="label">Middle Name</label>
												<div class="input-control text">
													<input type="text" class="form-control" name="MiddleName" value="{{ Input::old('MiddleName') }}">
												</div>
											</div>
										</div>

										<div class="span3">
											<div class="form-group">
												<label class="label">Last Name</label>
												<div class="input-control text">
													<input type="text" class="form-control" name="LastName" value="{{ Input::old('LastName') }}">
												</div>
											</div>
										</div>
									</div>

									<div class="row">
										<div class="span6">
											<div class="form-group">
												<label class="label">ID Number</label>
												<div class="input-control number">
													<input type="text" class="form-control" name="IDNumber" value="{{ Input::old('IDNumber') }}">
												</div>
											</div>
										</div>
									</div>

									<div class="row">
										<div class="span6">
											<div class="form-group">
												<label class="label">Mobile</label>
												<div class="input-control number">
													<input type="number" class="form-control" name="Mobile" value="{{ Input::old('Mobile') }}">
												</div>
											</div>
										</div>
									</div>

									<div class="row">
										<div class="span3">
											<div class="form-group">
												<label class="label">E-Mail Address</label>
												<div class="input-control email">
													<input type="email" class="form-control" name="email" value="{{ Input::old('email') }}">
												</div>
											</div>
										</div>

										<div class="span3">
											<div class="form-group">
												<label class="label">Confirm E-Mail Address</label>
												<div class="input-control email">
													<input type="email" class="form-control" name="email_confirmation" value="{{ Input::old('email_confirmation') }}">
												</div>
											</div>
										</div>
									</div>

									<div class="row">
										<div class="span3">
											<div class="form-group">
												<label class="label">Password</label>
												<div class="input-control password">
													<input type="password" class="form-control" name="password">
												</div>
											</div>
										</div>

										<div class="span3">
											<div class="form-group">
												<label class="label">Confirm Password</label>
												<div class="input-control password">
													<input type="password" class="form-control" name="password_confirmation">
												</div>
											</div>
										</div>
									</div>

									<div class="row">
										<div class="span3">
											<div class="text-center">
												<h4> captcha </h4>
											</div>
										</div>

										<div class="span3">
											<div class="text-center">
												<button type="submit" class="button primary large">
													Register
												</button>
											</div>
										</div>
									</div>


							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="span5">
				<div class="panel">
					<div class="panel-header">Application Details</div>
					<div class="panel-content">
						<div class="row">
							<div class="balloon bottom">
								<div class="padding10">
									<p> Fill in the revevant section bellow if you either have a business permit, live a in County House or Own Land in Uasin Gishu County. </p>
								</div>
							</div>
							<div class="accordion with-marker span4 place-left margin10" data-role="accordion" data-closeany="false">
			          <div class="accordion-frame active">
			              <a class="heading" href="#">Business Permits</a>
			              <div class="content">
											<div class="form-group">
												<label class="label">You Business Permit Number (SBP) </label>
												<div class="input-control text">
													<input type="text" class="form-control" name="SBPNumber" value="{{ Input::old('SBPNumber') }}">
												</div>
											</div>
			              </div>
			          </div>
			          <div class="accordion-frame active">
			              <a class="heading" href="#">County House or Stall</a>
			              <div class="content">
											<div class="form-group">
												<label class="label">Your House/Stall Number (UHN) </label>
												<div class="input-control text">
													<input type="text" class="form-control" name="UHN" value="{{ Input::old('UHN') }}">
												</div>
											</div>
			              </div>
			          </div>
			          <div class="accordion-frame active">
			              <a class="heading" href="#">Land Rates</a>
			              <div class="content">
											<div class="form-group">
												<label class="label">Your Land's UPN</label>
												<div class="input-control text">
													<input type="text" class="form-control" name="UPN" value="{{ Input::old('UPN') }}">
												</div>
											</div>
			              </div>
			          </div>
			      </div>

						<div class="row">
							<div class="span6">
								&nbsp;
								&nbsp;
								&nbsp;
							</div>
						</div>

						</div>
					</div>
				</div>
			</div>

	</form>
@endsection
