<!DOCTYPE html>
<html>
<head>
	<title>
		@section('title')
		Whois
		@show
	</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<!-- CSS are placed here -->
	{{ HTML::style('css/bootstrap.min.css') }}
	{{ HTML::style('css/header.css') }}
	{{ HTML::style('css/footer.css') }}

	<link href='//fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,600italic,700italic,800italic,400,300,600,700,800' rel='stylesheet' type='text/css'>
	<link href='//fonts.googleapis.com/css?family=Roboto:400,100,100italic,300,300italic,400italic,500,500italic,700,900,700italic,900italic' rel='stylesheet' type='text/css'>
	<link href='//fonts.googleapis.com/css?family=Roboto+Condensed:300italic,400italic,700italic,700,300,400&subset=latin,cyrillic-ext,latin-ext,cyrillic,greek-ext,greek,vietnamese' rel='stylesheet' type='text/css'>
	<!-- View Specific CSS -->
	@section('styles')

	@show
</head>
<body>
	<!-- Container -->
	<div class="container">
		<!-- Content -->
		@yield('content')
	</div>

	<!-- Footer End -->

	<!-- Scripts are placed here -->
	{{ HTML::script('js/jquery.min.js') }}
	{{ HTML::script('js/bootstrap.min.js') }}

	@section('scripts')

	@show
</body>
</html>