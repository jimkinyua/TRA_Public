<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
	<title>County Revenue</title>

	<link href="{{asset('css/metro-bootstrap.css')}}" rel="stylesheet">
	<link href="{{asset('css/metro-bootstrap-responsive.css')}}" rel="stylesheet">
	<link href="{{asset('css/iconFont.css')}}" rel="stylesheet">
	<link href="{{asset('css/docs.css')}}" rel="stylesheet">
	<link href="{{asset('js/prettify/prettify.css')}}" rel="stylesheet">

	<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">

	<!-- Load JavaScript Libraries -->
	<script src="{{asset('js/jquery.min.js')}}"></script>
	<script src="{{asset('js/jquery/jquery.widget.min.js')}}"></script>
	<script src="{{asset('js/jquery/jquery.mousewheel.js')}}"></script>
	<script src="{{asset('js/prettify/prettify.js')}}"></script>
	<script src="{{asset('js/holder/holder.js')}}"></script>
	<script src="{{asset('js/jquery/jquery.dataTables.js')}}"></script>

	<!-- Metro UI CSS JavaScript plugins -->
	<script src="{{asset('js/metro.min.js')}}"></script>



	<!-- HTML5 shim and Respond.js')}}" for IE8 support of HTML5 elements and media queries -->
	<!-- WARNING: Respond.js')}}" doesn't work if you view the page via file:// -->
	<!--[if lt IE 9]>
		<script src="{{asset('https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js')}}"></script>
		<script src="{{asset('https://oss.maxcdn.com/respond/1.4.2/respond.min.js')}}"></script>
	<![endif]-->
	@yield('head')
</head>
<body class="metro">
<div class="container">

	@include('partials.header')
	<div class="grid">
		<div class="row">
			<div class="example">
				@include('partials.notification')
				@yield('content')
			</div>
		</div>
	<div>



	@include('partials.footer')
	<!-- Scripts -->
	<script src="{{asset('js/pGenerator.jquery.js')}}"></script>
	@yield('page_js')
</div>
</body>
</html>
