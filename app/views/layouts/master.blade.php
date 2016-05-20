<?php
$activeMenuMap = array();
$activeMenuMap['home'] = array('/');
$activeMenuMap['products_services'] = array('whois-api-doc.php','domain-availability-api.php','reverse-whois.php',/*'reverse-ip-api.php',*/'brand-alert-api.php','registrant-alert-api.php','whois-api-software.php','registrar-whois-services.php','reverse-whois.php','bulk-whois-lookup.php',/*'reverse-ip.php',*/'whois-database-download.php','newly-registered-domains.php','domain-ip-database.php');
$activeMenuMap['solutions'] = array('whitepapers.php');
$activeMenuMap['contact_us'] = array('whois-api-contact.php');
$activeMenuMap['support'] = array('support.php');
$activeMenuMap['blog'] = array('blog');
?>
<!DOCTYPE html>
<html>
<head>
 <title>@yield('title')</title>
<meta name="description" content="@yield('description')">

	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">

	<link rel="shortcut icon" type="image/x-icon" href="{{ asset('images/Whois_Favicon.ico') }}"/>

	<!-- CSS are placed here -->
	<link href='//fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,600italic,700italic,800italic,400,300,600,700,800' rel='stylesheet' type='text/css'>
	<link href='//fonts.googleapis.com/css?family=Roboto:400,100,100italic,300,300italic,400italic,500,500italic,700,900,700italic,900italic' rel='stylesheet' type='text/css'>
	<link href='//fonts.googleapis.com/css?family=Roboto+Condensed:300italic,400italic,700italic,700,300,400&subset=latin,cyrillic-ext,latin-ext,cyrillic,greek-ext,greek,vietnamese' rel='stylesheet' type='text/css'>

	{{ HTML::style('css/bootstrap.min.css') }}
	{{ HTML::style('css/jquery-ui-1.8.8.custom.css') }}
	{{ HTML::style('css/ui.jqgrid.css') }}
	{{ HTML::style('css/header.css') }}
	{{ HTML::style('css/footer.css') }}
	{{ HTML::style('css/popup.css') }}
	{{ HTML::style('css/xml_tree.css') }}
	{{ HTML::style('css/whoislookup.css') }}


	<!-- View Specific CSS -->
	@section('styles')

	@show

	<!-- Scripts are placed here -->
	{{ HTML::script('js/jquery.min.js') }}
	{{ HTML::script('js/bootstrap.min.js') }}
	{{ HTML::script('js/jquery-ui.min.js') }}
	{{ HTML::script('js/i18n/grid.locale-en.js') }}
	{{ HTML::script('js/jquery.jqGrid.min.js') }}
	{{ HTML::script('js/common.js') }}
	{{ HTML::script('js/ajax.js') }}
	{{ HTML::script('js/home.js') }}
	{{ HTML::script('js/menu.js') }}
	{{ HTML::script('js/xml_tree.js') }}
	{{ HTML::script('js/lookup_result.js') }}

	<script type="text/javascript">
		var BASE_URL = '{{ URL::to("/") }}/';
	</script>

	@section('scripts')
	@show
<meta name="google-site-verification" content="el_PyrNuofsynT869O0KtZeNFkHpX7ZLDcg2cSteMyc" />
<meta name="google-site-verification" content="Xqs9TsPiRK8gFXH6okzP-NkkNhSw__9NCQnSeh4emKg" />
</head>

<body>
	<!-- Header Start -->
	<div class="container-fluid">
		<div class="row wa-header-border-top">
			<div class="wa-auto-margin">
				<div>
					<div class="wa-col-whoisLogo pull-left">
						<a href="{{ URL::to('/') }}">{{ HTML::image('images/logo.png', 'Responsive image', array('class'=>'img-responsive wa-img-logo')) }}</a>
					</div>
					<div class="wa-clientName-mediaIcons">
						<div class="wa-name-icons">
							@if (count(Auth::user()))
							<ul class="nav wa-nav" role="tablist">
								<li class="dropdown wa-dropdown wa-dropdown-aftrlogin">
									<a class="dropdown-toggle wa-dropdown-toggle-welcome-user" data-toggle="dropdown" href="#">
										<?php
										$welcomeName = (empty(Auth::user()->firstname) && empty(Auth::user()->lastname)) ? Auth::user()->username :  Auth::user()->firstname.' '.Auth::user()->lastname;
										?>
										{{ Lang::get('whois.welcome', array('username' => $welcomeName)) }}
										{{ HTML::image('images/downArrow.png', 'Responsive image', array('class'=>'img-responsive wa-img-downArrow')) }}
									</a>
									<ul class="dropdown-menu wa-dropdown-menu-client" role="menu">
										<div class="row">
											<div class="col-xs-12 text-center">
												<div class="tp-btn-header-dropdown-border">
													<div><a href="{{ URL::to('user/management.php') }}"><button type="button" class="btn btn-default wa-btn-form-action wa-btn-header-dropdown wa-btn-header-as" id="wa-btn-header-as">My ACCOUNT</button></a></div>
													<div><a href="{{ URL::to('logout.php') }}"><button type="button" class="btn btn-default wa-btn-form-action wa-btn-header-dropdown wa-header-btn-signOut" id="wa-btn-header-so">SIGN OUT</button></a></div>
												</div>
											</div>
										</div>
									</ul>
								</li>
							</ul>
							@else
							{{ Form::open(array('url' => 'login.php')) }}
							<ul class="nav wa-nav" role="tablist">
								<li class="dropdown wa-dropdown wa-dropdown-form-header">
									<a class="dropdown-toggle wa-dropdown-toggle-login" data-toggle="dropdown" href="#">
										<span>Sign In / Sign Up</span>
										{{ HTML::image('images/downArrow.png', 'Responsive image', array('class'=>'img-responsive wa-img-downArrow')) }}
									</a>
									<ul class="dropdown-menu  wa-dropdown-form " role="menu">
										<div class="form-group wa-form-group{{{ $errors->has('username') ? 'error' : '' }}}">
											{{ Form::label('username', 'Username', array('class' => 'wa-lbl-form-header wa-lbl-username-header')) }}

											<div class="controls">
												{{ Form::text('username', Input::old('username'), array('class' => 'form-control wa-form-control wa-form-control-header wa-form-control-uname')) }}
												{{ $errors->first('username') }}
											</div>
										</div>
										<div class="form-group wa-form-group{{{ $errors->has('password') ? 'error' : '' }}}">
											{{ Form::label('password', 'Password', array('class' => 'wa-lbl-form-header wa-lbl-pwd-header')) }}

											<div class="controls">
												{{ Form::password('password',array('class' => 'form-control wa-form-control wa-form-control-header wa-form-control-pwd')) }}
											</div>
										</div>
										<div class="row">
											<div class="col-xs-12">
												<div class="wa-lbl-form-header pull-right wa-lbl-forgot-pwd-header">{{ HTML::link('password/remind.php', 'Forgot Username/Password ?') }}</div>
											</div>
										</div>
										<div class="row wa-row-border-form">
											<div class="col-xs-12">
												<div class="wa-lbl-form-header wa-lbl-newUser">{{ HTML::link('/user/create.php', 'New User ?') }}</div>
												<a href="{{ URL::to('/user/create.php') }}"><button type="submit" class="btn btn-default pull-right wa-btn-form-action wa-btn-header-dropdown wa-header-btn-newuser" id="wa-btn-login">LOGIN</button></a>
											</div>
										</div>
									</ul>
								</li>
							</ul>
							{{ Form::close() }}
							<!-- <span class="wa-lbl-register wa-cursor"><a href="{{ URL::to('/user/create.php') }}">New User</a></span> -->
							@endif
							<a href="{{ URL::to('https://twitter.com/whoisxmlapi') }}">{{ HTML::image('images/twitter-icon.png', 'Responsive image', array('class'=>'img-responsive wa-img-mediaIcons','id'=>'wa-mediaIcons')) }}</a>
							<a href="{{ URL::to('https://www.facebook.com/pages/Whois-API-LLC/106782642810963') }}">{{ HTML::image('images/facebook-icon.png', 'Responsive image', array('class'=>'img-responsive wa-img-mediaIcons','id'=>'wa-mediaIcons')) }}</a>
							<a href="{{ URL::to('https://www.linkedin.com/pub/whois-api/88/573/6b2') }}">{{ HTML::image('images/linkedin-icon.png', 'Responsive image', array('class'=>'img-responsive wa-img-mediaIcons','id'=>'wa-mediaIcons')) }}</a>
							<a href="{{ URL::to('https://plus.google.com/110739836659192632708/posts') }}">{{ HTML::image('images/google-plus.png', 'Responsive image', array('class'=>'img-responsive wa-img-mediaIcons','id'=>'wa-mediaIcons')) }}</a>
						</div>
					</div>
					<div class="wa-menu-bar">
						<div>
							<div class="wa-menubar">
								<nav class="navbar navbar-default wa-navbar" role="navigation">
									<div class="navbar-header wa-navbar-header">
										<button type="button" class="navbar-toggle collapsed wa-navbar-toggle" data-toggle="collapse" data-target="#wa-menu">
											<span class="caret wa-caret"></span>
										</button>
										{{ HTML::link('/', 'HOME', array('class' => 'navbar-brand visible-xs')) }}
									</div>
									<div class="collapse navbar-collapse wa-navbar-collapse" id="wa-menu">
										<ul class="nav navbar-nav wa-navbar-nav-header">
											<li class="{{ in_array(Request::path(),$activeMenuMap['home']) ? 'active' : ''; }} wa-menu"><a href="{{ URL::to('/') }}"><span class="wa-lbl-menu" id="wa-headerMenu-home">HOME</span></a>
											</li>
											<li class="wa-line hidden-xs">|</li>
											<li class="{{ in_array(Request::path(),$activeMenuMap['products_services']) ? 'active' : ''; }} dropdown wa-menu">
												<a class="dropdown-toggle wa-dropdownToggle" data-toggle="dropdown" href="#">
													PRODUCTS & SERVICES
												</a>
												<ul class="dropdown-menu wa-dropdown-menu wa-dropdown-menu-header" role="menu">
													<div class="row wa-submneu-row">
														<div class="col-sm-6">
															<li class="wa-list-header">APIs</li>
															<li class="wa-menu-list"><a href={{ URL::to('whois-api-doc.php') }}>{{ HTML::image('images/servicesArrow.png', 'Responsive image', array('class'=>'img-responsive wa-img-servicesarrow')) }}Whois API</a>
															</li>
															<li class="wa-menu-list wa-cursor"><a href={{ URL::to('domain-availability-api.php') }}>{{ HTML::image('images/servicesArrow.png', 'Responsive image', array('class'=>'img-responsive wa-img-servicesarrow')) }}Domain Availability API</a>
															</li>

															<li class="wa-menu-list wa-cursor"><a href={{ URL::to('reverse-whois-api.php') }}>{{ HTML::image('images/servicesArrow.png', 'Responsive image', array('class'=>'img-responsive wa-img-servicesarrow')) }}Reverse Whois API</a>
															</li>
															<!--  <li class="wa-menu-list wa-cursor"><a href={{ URL::to('reverse-ip-api.php') }}>{{ HTML::image('images/servicesArrow.png', 'Responsive image', array('class'=>'img-responsive wa-img-servicesarrow')) }}Reverse IP API</a>
															</li>
															-->
															<li class="wa-menu-list wa-cursor"><a href={{ URL::to('brand-alert-api.php') }}>{{ HTML::image('images/servicesArrow.png', 'Responsive image', array('class'=>'img-responsive wa-img-servicesarrow')) }}Brand Alert API</a>
															</li>
															<li class="wa-menu-list wa-cursor"><a href={{ URL::to('registrant-alert-api.php') }}>{{ HTML::image('images/servicesArrow.png', 'Responsive image', array('class'=>'img-responsive wa-img-servicesarrow')) }}Registrant Alert API</a>
															</li>

															<li class="wa-list-header ">CUSTOM SOLUTIONS
															</li>
															<li class="wa-menu-list wa-cursor"><a href={{ URL::to('whois-api-software.php') }}>{{ HTML::image('images/servicesArrow.png', 'Responsive image', array('class'=>'img-responsive wa-img-servicesarrow')) }}Whois API Software Package</a>
															</li>
															<li class="wa-menu-list wa-cursor"><a href={{ URL::to('registrar-whois-services.php') }}>{{ HTML::image('images/servicesArrow.png', 'Responsive image', array('class'=>'img-responsive wa-img-servicesarrow')) }}Registrar Whois Service</a>
															</li>
														</div>
														<div class="col-sm-6">
															<li class="wa-list-header ">DOMAIN RESEARCH</li>
															<li class="wa-menu-list wa-cursor"><a href={{ URL::to('http://whois.whoisxmlapi.com/') }}>{{ HTML::image('images/servicesArrow.png', 'Responsive image', array('class'=>'img-responsive wa-img-servicesarrow')) }}Whois Lookup</a>
															</li>
															<li class="wa-menu-list wa-cursor"><a href={{ URL::to('reverse-whois.php') }}>{{ HTML::image('images/servicesArrow.png', 'Responsive image', array('class'=>'img-responsive wa-img-servicesarrow')) }}Reverse Whois/Registrant Search</a>
															</li>
															<li class="wa-menu-list wa-cursor"><a href={{ URL::to('bulk-whois-lookup.php') }}>{{ HTML::image('images/servicesArrow.png', 'Responsive image', array('class'=>'img-responsive wa-img-servicesarrow')) }}Bulk Whois Lookup</a>
															</li>
															<!--
															<li class="wa-menu-list wa-cursor"><a href={{ URL::to('reverse-ip.php') }}>{{ HTML::image('images/servicesArrow.png', 'Responsive image', array('class'=>'img-responsive wa-img-servicesarrow')) }}Reverse IP</a>
															</li>-->

															<li class="wa-list-header ">DATA FEEDS</li>

															<li class="wa-menu-list wa-cursor"><a href={{ URL::to('whois-database-download.php') }}>{{ HTML::image('images/servicesArrow.png', 'Responsive image', array('class'=>'img-responsive wa-img-servicesarrow')) }}Whois Database Download</a>
															</li>
															<li class="wa-menu-list wa-cursor"><a href={{ URL::to('newly-registered-domains.php') }}>{{ HTML::image('images/servicesArrow.png', 'Responsive image', array('class'=>'img-responsive wa-img-servicesarrow')) }}Newly Registered Domains</a>
															</li>
															<li class="wa-menu-list wa-cursor"><a href={{ URL::to('domain-ip-database.php') }}>{{ HTML::image('images/servicesArrow.png', 'Responsive image', array('class'=>'img-responsive wa-img-servicesarrow')) }}Domain IP Database</a>
															</li>
														</div>
													</div>
												</ul>
											</li>
											<li class="wa-line hidden-xs">|</li>
											<li class="{{ in_array(Request::path(),$activeMenuMap['solutions']) ? 'active' : ''; }} dropdown wa-menu">
												<a class="dropdown-toggle wa-dropdownToggle" data-toggle="dropdown" href="#">
													SOLUTIONS
												</a>
												<ul class="dropdown-menu wa-dropdown-menu wa-dropdown-menu-header wa-dropdown-menusolution" role="menu">
													<div class="row wa-submneu-row">
														<div class="col-sm-6">
															<li class="wa-menu-list wa-cursor"><a href={{ URL::to('whitepapers.php') }}>{{ HTML::image('images/servicesArrow.png', 'Responsive image', array('class'=>'img-responsive wa-img-servicesarrow')) }}White Papers</a>
															</li>
														</div>
													</div>
													<div class="row wa-submneu-row">
														<div class="col-sm-12">
															<li class="wa-menu-list wa-cursor"><a href={{ URL::to('cybersecurity-data-solution.php') }}>{{ HTML::image('images/servicesArrow.png', 'Responsive image', array('class'=>'img-responsive wa-img-servicesarrow')) }}Cyber-security Data Solution</a>
															</li>
														</div>
													</div>
												</ul>
											</li>
											<li class="wa-line hidden-xs">|</li>
											<li class="{{ in_array(Request::path(),$activeMenuMap['contact_us']) ? 'active' : ''; }} wa-menu"><a href={{ URL::to('whois-api-contact.php') }}><span class="wa-lbl-menu" id="wa-headerMenu-contactUs">CONTACT US</span></a>
											</li>
											<li class="wa-line hidden-xs">|</li>
											<li class="{{ in_array(Request::path(),$activeMenuMap['support']) ? 'active' : ''; }} wa-menu"><a href={{ URL::to('support.php') }}><span class="wa-lbl-menu" id="wa-headerMenu-contactUs">SUPPORT</span></a>
											</li>
											<li class="wa-line hidden-xs">|</li>
<!-- 											<li class=" wa-menu"><a href=https://www.whoisxmlapi.com/blog><span class="wa-lbl-menu" id="wa-headerMenu-blog">BLOG</span></a>
											</li> -->
											<li class="{{ in_array(Request::path(),$activeMenuMap['blog']) ? 'active' : ''; }} wa-menu">
												<a href={{ URL::to('/blog') }}>
													<span class="wa-lbl-menu" id="wa-headerMenu-blog">BLOG</span>
												</a>
											</li>
										</ul>
									</div>
								</nav>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- Header End -->

	<!-- Container Start -->
	<div class="container-fluid">
		<!-- Success-Messages Start -->
		<?php
		$message = ($errors->has('success')) ? $errors->first('success') : Session::get('success');
		$successType = ($errors->has('successType')) ? $errors->first('successType') : Session::get('successType');
		?>
		@if ($message)
		@if ($successType)
		<div class="alert alert-{{$successType}} alert-dismissible" role="alert" id="wa-session-message">
			@else
			<div class="alert alert-info alert-dismissible" role="alert" id="wa-session-message">
				@endif
				<button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
				{{ $message }}
			</div>
			@endif
			<!-- Success-Messages End-->

			<!-- Content -->
			@yield('content')

		</div>
	</div>
	<!-- Container End -->

	<!-- Footer Start -->
	<div class="container-fluid">
		<div class="row wa-footer-3">
			<div class="col-xs-12 wa-auto-margin wa-footer3-col-margin">
				<div class="row">
					<div class="col-sm-4 col-xs-12 wa-trustedCustomer-footer">
						<div class="wa-trustedUser-text">TRUSTED BY OVER</div>
						<div class="wa-amount-text">50,000</div>
						<div class="wa-customers-text">CUSTOMERS</div>
					</div>
					<div id="carousel-example-generic-footer" class="carousel slide carousel-slide-footer" data-ride="carousel">
						<!-- Wrapper for slides -->

						<div class="carousel-inner" role="listbox">
							<!-- <div class="col-sm-8 col-xs-12 wa-services-images wa-service-images-whois"> -->
							<div class="item active">
								<div class="row" style="margin-left:0px;margin-right:0px;">
									<div class="col-sm-12">
										{{ HTML::image('images/qlialicomm.png', 'Responsive image', array('class'=>'img-responsive wa-img-myServices wa-img-QcommLogo','id'=>'wa-QcommLogo')) }}
										{{ HTML::image('images/fsecure.png', 'Responsive image', array('class'=>'img-responsive wa-img-myServices wa-img-FsecureLogo','id'=>'wa-FsecureLogo')) }}
										{{ HTML::image('images/cisco.png', 'Responsive image', array('class'=>'img-responsive wa-img-myServices wa-img-Ciscologo','id'=>'wa-Ciscologo')) }}
										{{ HTML::image('images/duck.png', 'Responsive image', array('class'=>'img-responsive wa-img-myServices wa-img-DuckLogo','id'=>'wa-DuckLogo')) }}
									</div>
								</div>
								<div class="clearfix hidden-xs"></div>
								<div class="row"  style="margin-left:0px;margin-right:0px;">
									<div class="col-sm-12">
										{{ HTML::image('images/at&t.png', 'Responsive image', array('class'=>'img-responsive wa-img-myServices wa-img-atLogo','id'=>'wa-atLogo')) }}
										{{ HTML::image('images/ebay.png', 'Responsive image', array('class'=>'img-responsive wa-img-myServices wa-img-ebayLogo','id'=>'wa-ebayLogo')) }}
										{{ HTML::image('images/rsa.png', 'Responsive image', array('class'=>'img-responsive wa-img-myServices wa-img-rsaLogo','id'=>'wa-rsaLogo')) }}
										{{ HTML::image('images/American.png', 'Responsive image', array('class'=>'img-responsive wa-img-myServices wa-img-AmericanLogo','id'=>'wa-AmericanLogo')) }}
									</div>
								</div>
								<div class="row"  style="margin-left:0px;margin-right:0px;">
									<div class="col-sm-12">
										{{ HTML::image('images/apple.png', 'Responsive image', array('class'=>'img-responsive wa-img-myServices wa-img-atLogo','id'=>'wa-atLogo')) }}
										{{ HTML::image('images/IBM.png', 'Responsive image', array('class'=>'img-responsive wa-img-myServices wa-img-ebayLogo','id'=>'wa-ebayLogo')) }}
										{{ HTML::image('images/Symentech.png', 'Responsive image', array('class'=>'img-responsive wa-img-myServices wa-img-rsaLogo','id'=>'wa-rsaLogo')) }}
										{{ HTML::image('images/Verisign.png', 'Responsive image', array('class'=>'img-responsive wa-img-myServices wa-img-AmericanLogo','id'=>'wa-AmericanLogo')) }}
									</div>
								</div>
							</div>
							<!-- </div> -->
						</div>
						<!-- Controls -->
<!-- 						<a class="left carousel-control wa-img-left-slider-footer hidden-md hidden-xs" href="#carousel-example-generic-footer" role="button" data-slide="prev">
							{{ HTML::image('images/left-slider1.png', 'Responsive image', array('class'=>'img-responsive wa-left-slider','id'=>'wa-img-left-slider')) }}
						</a>
						<a class="right carousel-control wa-img-right-slider-footer hidden-md hidden-xs" href="#carousel-example-generic-footer" role="button" data-slide="next">
							{{ HTML::image('images/f-right-slider.png', 'Responsive image', array('class'=>'img-responsive wa-right-slider','id'=>'wa-img-right-slider')) }}
						</a> -->
					</div>

<!-- 					<div class="col-xs-12 wa-newLogo">
							<div>
								<div class="wa-dsaf">
									{{ HTML::image('images/qlialicomm.png', 'Responsive image', array('class'=>'img-responsive wa-img-myServices wa-img-QcommLogo','id'=>'wa-QcommLogo')) }}
									{{ HTML::image('images/fsecure.png', 'Responsive image', array('class'=>'img-responsive wa-img-myServices wa-img-FsecureLogo','id'=>'wa-FsecureLogo')) }}
									{{ HTML::image('images/cisco.png', 'Responsive image', array('class'=>'img-responsive wa-img-myServices wa-img-Ciscologo','id'=>'wa-Ciscologo')) }}
									{{ HTML::image('images/duck.png', 'Responsive image', array('class'=>'img-responsive wa-img-myServices wa-img-DuckLogo','id'=>'wa-DuckLogo')) }}

								</div>
							</div>
							<div>
								<div class="wa-dsaf">
									{{ HTML::image('images/at&t.png', 'Responsive image', array('class'=>'img-responsive wa-img-myServices wa-img-atLogo','id'=>'wa-atLogo')) }}
									{{ HTML::image('images/ebay.png', 'Responsive image', array('class'=>'img-responsive wa-img-myServices wa-img-ebayLogo','id'=>'wa-ebayLogo')) }}
									{{ HTML::image('images/rsa.png', 'Responsive image', array('class'=>'img-responsive wa-img-myServices wa-img-rsaLogo','id'=>'wa-rsaLogo')) }}
									{{ HTML::image('images/American.png', 'Responsive image', array('class'=>'img-responsive wa-img-myServices wa-img-AmericanLogo','id'=>'wa-AmericanLogo')) }}
								</div>
							</div>
							<div>
								<div class="wa-dsaf">
									{{ HTML::image('images/apple.png', 'Responsive image', array('class'=>'img-responsive wa-img-myServices wa-img-atLogo','id'=>'wa-atLogo')) }}
									{{ HTML::image('images/IBM.png', 'Responsive image', array('class'=>'img-responsive wa-img-myServices wa-img-ebayLogo','id'=>'wa-ebayLogo')) }}
									{{ HTML::image('images/Symentech.png', 'Responsive image', array('class'=>'img-responsive wa-img-myServices wa-img-rsaLogo','id'=>'wa-rsaLogo')) }}
									{{ HTML::image('images/Verisign.png', 'Responsive image', array('class'=>'img-responsive wa-img-myServices wa-img-AmericanLogo','id'=>'wa-AmericanLogo')) }}
								</div>
							</div>
					</div> -->


				</div>
			</div>
		</div>
		<div class="row wa-footer-2">
			<div class="col-xs-12  wa-auto-margin">
				<div class="row">
					<div class="col-sm-8 col-xs-12 ">
						<div class="row">
							<div class="col-xs-12"><span class="wa-field-title wa-field-title-footer wa-lbl-text" id="wa-services-footer">SERVICES</span></div>
						</div>
						<div class="row">
							<div class="col-sm-6 col-xs-12">
								<div class="wa-lbl-services wa-serices-margin">APIs</div>
								<ul class="list-unstyled wa-content-text wa-content-spacing">
									<li class="wa-lbl-li-services wa-li-services-footer wa-cursor">{{ HTML::image('images/servicesArrow.png', 'Responsive image', array('class'=>'img-responsive wa-img-servicesarrow')) }}
										<a href="{{ URL::to('whois-api-doc.php') }}">Whois Api</a>
									</li>
									<li class="wa-lbl-li-services wa-li-services-footer wa-cursor">{{ HTML::image('images/servicesArrow.png', 'Responsive image', array('class'=>'img-responsive wa-img-servicesarrow')) }}
										<a href="{{ URL::to('domain-availability-api.php') }}">Domain Availabilty API</a>
									</li>
									<li class="wa-lbl-li-services wa-li-services-footer wa-cursor">{{ HTML::image('images/servicesArrow.png', 'Responsive image', array('class'=>'img-responsive wa-img-servicesarrow')) }}
										<a href="{{ URL::to('reverse-whois.php') }}">Reverse Whois Api</a>
									</li>
									<!--
									<li class="wa-lbl-li-services wa-li-services-footer wa-cursor">{{ HTML::image('images/servicesArrow.png', 'Responsive image', array('class'=>'img-responsive wa-img-servicesarrow')) }}
										<a href="{{ URL::to('reverse-ip-api.php') }}">Reverse IP Api</a>
									</li>
									-->
									<li class="wa-lbl-li-services wa-li-services-footer wa-cursor">{{ HTML::image('images/servicesArrow.png', 'Responsive image', array('class'=>'img-responsive wa-img-servicesarrow')) }}
										<a href="{{ URL::to('brand-alert-api.php') }}">Brand Alert Api</a>
									</li>
									<li class="wa-li-services-footer wa-cursor">{{ HTML::image('images/servicesArrow.png', 'Responsive image', array('class'=>'img-responsive wa-img-servicesarrow')) }}
										<a href="{{ URL::to('registrant-alert-api.php') }}">Registrant Alert Api</a>
									</li>

								</ul>
								<div class="wa-lbl-services wa-serices-margin">Custom Solutions</div>
								<ul class="list-unstyled wa-content-text wa-content-spacing">
									<li class="wa-lbl-li-services wa-li-services-footer wa-cursor">{{ HTML::image('images/servicesArrow.png', 'Responsive image', array('class'=>'img-responsive wa-img-servicesarrow')) }}
										<a href="{{ URL::to('whois-api-software.php') }}">Whois Api Software Package</a>
									</li>
									<li class="wa-li-services-footer wa-cursor">{{ HTML::image('images/servicesArrow.png', 'Responsive image', array('class'=>'img-responsive wa-img-servicesarrow')) }}
										<a href="{{ URL::to('registrar-whois-services.php') }}">Reverse Whois Service</a>
									</li>
								</ul>
							</div>
							<div class="col-sm-6 col-xs-12">
								<div class="wa-lbl-services wa-serices-margin">Domain Research</div>
								<ul class="list-unstyled wa-content-text wa-content-spacing">
									<li class="wa-lbl-li-services wa-li-services-footer wa-cursor">{{ HTML::image('images/servicesArrow.png', 'Responsive image', array('class'=>'img-responsive wa-img-servicesarrow')) }}
										<a href="{{ URL::to('http://whois.whoisxmlapi.com/') }}">Whois lookup</a>
									</li>
									<li class="wa-lbl-li-services wa-li-services-footer wa-cursor">{{ HTML::image('images/servicesArrow.png', 'Responsive image', array('class'=>'img-responsive wa-img-servicesarrow')) }}
										<a href="{{ URL::to('reverse-whois.php') }}">Reverse whois/Registrant Search</a>
									</li>
									<li class="wa-lbl-li-services wa-li-services-footer wa-cursor">{{ HTML::image('images/servicesArrow.png', 'Responsive image', array('class'=>'img-responsive wa-img-servicesarrow')) }}
										<a href="{{ URL::to('bulk-whois-lookup.php') }}">Bulk Whois Lookup</a>
									</li>
									<!--
									<li class="wa-li-services-footer wa-cursor">{{ HTML::image('images/servicesArrow.png', 'Responsive image', array('class'=>'img-responsive wa-img-servicesarrow')) }}
										<a href="{{ URL::to('reverse-ip-api.php') }}">Reverse IP</a>
									</li>
									-->
								</ul>
								<div class="wa-lbl-services wa-serices-margin">Data Feeds</div>
								<ul class="list-unstyled wa-content-text wa-content-spacing">
									<li class="wa-lbl-li-services wa-li-services-footer wa-cursor">{{ HTML::image('images/servicesArrow.png', 'Responsive image', array('class'=>'img-responsive wa-img-servicesarrow')) }}
										<a href="{{ URL::to('whois-database-download.php') }}">Whois Database Download</a>
									</li>
									<li class="wa-lbl-li-services wa-li-services-footer wa-cursor">{{ HTML::image('images/servicesArrow.png', 'Responsive image', array('class'=>'img-responsive wa-img-servicesarrow')) }}
										<a href="{{ URL::to('newly-registered-domains.php') }}">Newly Registered Domains</a>
									</li>
									<li class="wa-li-services-footer wa-cursor">{{ HTML::image('images/servicesArrow.png', 'Responsive image', array('class'=>'img-responsive wa-img-servicesarrow')) }}
										<a href="{{ URL::to('domain-ip-database.php') }}">Domain IP Database</a>
									</li>
								</ul>
							</div>
						</div>
					</div>
					<div class="col-sm-4 col-xs-12">
						<div class="row">
							<div class="col-xs-12"><span class="wa-field-title wa-field-title-footer wa-lbl-text" id="wa-subscribe-footer">SUBSCRIBE</span></div>
						</div>
						<div class="form-group col-xs-12 wa-form-group wa-form-group-footer">
							<label for="wa-dontWorry" class="wa-lbl wa-content-text wa-content-spacing" id="wa-dw-footer">Don't worry. We don't spam.</label>
							<input type="text" class="form-control wa-field-input wa-field-input-subscribe" id="wa-dontWorry">
							<div class="help-block wa-input-error" style="margin-bottom: 0;"></div>
						</div>
						<div class="row">
							<div class="col-xs-12">
								<button type="button" class="btn btn-default wa-btn-form-action wa-btn-newaccnt" id="wa-btn-newaccnt-footer">SUBSCRIBE</button>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="row wa-footerEnd">
			<div class="col-xs-12">
				<div class="row">
					<div class="col-sm-offset-3 col-sm-6 col-xs-12 wa-content-text wa-content-spacing text-center">
						<span>{{ HTML::link('terms-of-service.php', 'Terms Of Use') }}</span>
						<span class="wa-line">|</span>
						<span>{{ HTML::link('privacy.php', 'Privacy Policy') }}</span>
						<span class="wa-line">|</span>
						<span>{{ HTML::link('affiliate-program.php', 'Affiliate') }}</span>
						<span class="wa-line">|</span>
						<span>{{ HTML::link('sitemap.php', 'Site Map') }}</span>
						<span class="wa-line">|</span>
						<span>{{ HTML::link('whois-api-contact.php', 'Contact Us') }}</span>
					</div>
				</div>
				<div class="row">
					<div class="col-sm-offset-3 col-sm-6 col-xs-12 text-center">
						<span class="wa-content-text wa-content-spacing">&copy;2014-2015 <a href="{{ URL::to('/') }}">Whois API LLC</a>. All rights reserved.</span>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- Footer End -->

	<!-- Popup Start -->
	<div class="container wa-container-popup" id="wa-popup">
		<div class="row">
			<div class="col-xs-12">
				<span class="glyphicon glyphicon-remove wa-glyphicon-remove pull-right" id="wa-popup-btn-close"></span>
			</div>
		</div>
		<div class="row">
			<div class="col-xs-12">
				<div class="wa-header-popup" id="wa-header-popup">Header</div>
			</div>
		</div>
		<div class="row">
			<div class="col-xs-12">
				<div class="wa-msg-popup" id="wa-msg-popup">Message</div>
			</div>
		</div>
		<div class="row">
			<div class="col-xs-12">
				<button type="button" class="pull-right btn btn-default wa-btn-orange" id="wa-popup-btn-ok">OK</button>
			</div>
		</div>
	</div>
	<!-- Popup End -->

	<!-- Ajax Loader Start -->
	<div class="wa-loader">
		{{ HTML::image('images/Whois-Loading-Icon-72x72.gif', 'Loading.....', array('width'=>'48','height'=>'48')) }}
	</div>
	<div class="wa-overlay"></div>
	<!-- Ajax Loader End -->
<script type="text/javascript">

  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-91879-2']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();



</script>
</body>
</html>