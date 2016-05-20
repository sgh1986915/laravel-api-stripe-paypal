<?php
$libPath = base_path(). "/whoisxmlapi_4";
?>
@extends('layouts.master')

@section('title')
@parent
:: Login
@stop

@section('styles')
@parent
{{ HTML::style('css/Login.css') }}
@stop

{{-- Content --}}
@section('content')
<div class="row wa-searchbox-radio">
	<div class="col-xs-12 wa-auto-margin">
		<div class="row">
			<div class="col-sm-6 col-xs-12 wa-search-eg-box wa-search-eg-box-Login">
				<form id="whoisform" name="whoisform" action="{{ URL::to('whoislookup.php') }}">
					<div class="form-group has-feedback wa-search-box wa-search-box-Login">
						<input type="text" class="form-control wa-search wa-search-Login" id="wa-search-whoislookup" placeholder="Whois Lookup">
						<span class="glyphicon glyphicon-search form-control-feedback wa-cursor" id="wa-search-icon-whoislookup"></span>
						<div class="wa-exapple wa-example-Login pull-left">Example:google.com or 74.125.45.100</div>
						<div class="wa-radio-inputs wa-radio-xml-Josn wa-radio-xml-json-Login">
							<div class="wa-radio-input wa-radio-input-xml wa-radio-input-whois pull-left">
								<input type="radio" value="xml" checked id="wa-radio-xml" class="wa-cursor wa-radio-orange-bar wa-field-input-selection wa-field-input-selection-Login wa-api-res-type" name="wa-lbl-radio">
								<label for="wa-radio-xml" class="wa-cursor wa-field-value-selection  wa-field-value-selection-Login" id="wa-lbl-XMl">XML</label>
								<div class="wa-home-radio-outerCircle">
									<div class="wa-home-radio-innerCircle"></div>
								</div>
							</div>
							<div class="wa-radio-input wa-radio-input-json wa-radio-input-whois pull-left">
								<input type="radio" value="json" id="wa-radio-json" class="wa-cursor wa-radio-orange-bar wa-field-input-selection wa-field-input-selection-Login wa-api-res-type" name="wa-lbl-radio">
								<label for="wa-radio-json" class="wa-cursor wa-field-value-selection  wa-field-value-selection-Login" id="wa-lbl-JSON">JSON</label>
								<div class="wa-home-radio-outerCircle wa-home-radio-outerCircle-Login">
									<div class="wa-home-radio-innerCircle wa-home-radio-innerCircle-Login" style="display: none;"></div>
								</div>
							</div>
						</div>
						<div id="wa-user-stats" class="wa-user-stats"><?php include_once $libPath . "/user_stats.php"; ?>
						</div>
					</div>
				</form>
			</div>
			<div class="col-sm-6 col-xs-12 wa-btn wa-btn-Login">
				<div class="row">
					<div class="col-sm-offset-4 col-sm-4 col-xs-12 wa-btn-order">
						<a href={{ URL::to('order_paypal.php') }}><button type="button" class="btn btn-default wa-btn-orderNow wa-btn-orderNow-Login center-block" id="wa-btn-orderNow-Login">ORDER NOW</button></a>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="row wa-page-title-content-bg wa-page-title-content-bg-Login">
	<div class="col-xs-12 wa-auto-margin wa-about-whoisApi wa-title-Login">
		<div class="text-center wa-title wa-title-Login" id="page-title">Login into your account</div>
	</div>
</div>
<div id="wa-page-content">
	<div class="row wa-content-bg">
		<div class="col-xs-12 wa-auto-margin wa-col-login">
			<div class="wa-box">
				<div class="wa-login-form center-block" id="Loginprofile">
				{{ Form::open(array('url' => 'login.php', 'class' => 'form-horizontal','id' => 'wa-login-form')) }}
					<!-- Name -->
					<div class="form-group wa-form-group-uname col-xs-12 {{{ $errors->has('username') ? 'has-error' : '' }}}">
						<div class="control-group {{{ $errors->has('username') ? 'error' : '' }}}">
							{{ Form::label('username', 'Username', array('class' => 'control-label wa-field-title')) }}
							{{ Form::text('username', null ,array('class' => 'form-control wa-form-control wa-form-control-uname wa-formcontent-Login')) }}
							@if ($errors->first('username'))
								<div class="help-block wa-input-error">{{ $errors->first('username') }}</div>
							@endif
						</div>
					</div>
					<div class="clearfix"></div>
					<!-- Password -->
					<div class="form-group col-xs-12 {{{ $errors->has('password') ? 'has-error' : '' }}}">
						<div class="control-group {{{ $errors->has('password') ? 'error' : '' }}}">
							{{ Form::label('password', 'Password', array('class' => 'control-label wa-field-title')) }}
							{{ Form::password('password' ,array('id' => 'wa-login-password', 'class' => 'form-control wa-form-control wa-form-control-pwd wa-formcontent-Login')) }}
							@if ($errors->first('password'))
								<div class="help-block wa-input-error">{{ $errors->first('password') }}</div>
							@endif
						</div>
					</div>
					<!-- Login button -->
					<div class="clearfix"></div>				
					<div class="form-group col-xs-12">
						<div class="control-group pull-left">
							<div class="controls">
								<button type="submit" class="btn btn-default wa-btn-orange wa-btn-login" id="wa-btn-login">LOGIN</button>
							</div>
						</div>
						<div class="pull-right">
							{{ HTML::link('password/remind.php', 'Forgot Username/Password?',array('class' => 'wa-lbl-form-header wa-lbl-forgot-pwd-header')) }}
							{{ HTML::link('user/create.php', 'Register',array('class' => 'wa-lbl-form-header wa-lbl-forgot-pwd-header')) }}
						</div>
					</div>				
				</div>
				{{ Form::close() }}
			</div>
		</div>
	</div>
</div>
@stop
<?php
    //print_r($errors);
?>