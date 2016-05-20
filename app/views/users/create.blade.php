<?php
$libPath = base_path(). "/whoisxmlapi_4";
?>
@extends('layouts.master')
@section('title')
@parent
:: Create User
@stop
@section('styles')
@parent
{{ HTML::style('css/create.css') }}
@stop
@section('content')
<div class="row wa-searchbox-radio">
	<div class="col-xs-12 wa-auto-margin">
		<div class="row">
			<div class="col-sm-6 col-xs-12 wa-search-eg-box wa-search-eg-box-whoisApi">
				<form id="whoisform" name="whoisform" action="{{ URL::to('whoislookup.php') }}">
					<div class="form-group has-feedback wa-search-box wa-search-box-whoisApi">
						<input type="text" class="form-control wa-search wa-search-whoisApi" id="wa-search-whoislookup" placeholder="Whois Lookup">
						<span class="glyphicon glyphicon-search form-control-feedback" id="wa-search-icon-whoislookup"></span>
						<div class="wa-exapple wa-example-whoisApi">Example:google.com or 74.125.45.100</div>
						<div class="wa-radio-inputs wa-radio-xml-Josn wa-radio-xml-json-whoisApi">
							<div class="wa-radio-input wa-radio-input-xml wa-radio-input-whois">
								<input type="radio" value="xml" checked id="wa-radio-xml" class="wa-cursor wa-radio-orange-bar wa-field-input-selection wa-field-input-selection-whoisApi wa-api-res-type" name="wa-lbl-radio">
								<label for="wa-radio-xml" class="wa-cursor wa-field-value-selection  wa-field-value-selection-whoisApi" id="wa-lbl-XMl">XML</label>
								<div class="wa-home-radio-outerCircle">
									<div class="wa-home-radio-innerCircle"></div>
								</div>
							</div>
							<div class="wa-radio-input wa-radio-input-json wa-radio-input-whois">
								<input type="radio" value="json" id="wa-radio-json" class="wa-cursor wa-radio-orange-bar wa-field-input-selection wa-field-input-selection-whoisApi wa-api-res-type" name="wa-lbl-radio">
								<label for="wa-radio-json" class="wa-cursor wa-field-value-selection  wa-field-value-selection-WhoisApi" id="wa-lbl-JSON">JSON</label>
								<div class="wa-home-radio-outerCircle wa-home-radio-outerCircle-whoisApi">
									<div class="wa-home-radio-innerCircle wa-home-radio-innerCircle-whoisApi" style="display: none;"></div>
								</div>
							</div>
						</div>
						<div id="wa-user-stats" class="wa-user-stats"><?php include_once $libPath . "/user_stats.php"; ?>
						</div>
					</div>
				</form>
			</div>
			<div class="col-sm-6 col-xs-12 wa-btn wa-btn-whoisApi">
				<div class="row">
					<div class="col-sm-offset-4 col-sm-4 col-xs-12 wa-btn-order">
						<a href={{ URL::to('order_paypal.php') }}><button type="button" class="btn btn-default wa-btn-orderNow wa-btn-orderNow-WhoisApi center-block" id="wa-btn-orderNow-whoisApi">ORDER NOW</button></a>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="row wa-page-title-content-bg wa-page-title-content-bg-reg">
	<div class="col-xs-12 wa-auto-margin wa-title-reg">
		<h1 class="text-center wa-title wa-title" id="page-title">Registration Form</h1>
	</div>
</div>
<div id="wa-page-content">
	<div class="row wa-content-bg">
		<div class="col-xs-12 wa-auto-margin wa-col-management">

			{{ Form::open(array('url' => 'user/create.php')) }}

			<div class="wa-box wa-registration-form" id="createprofile">

				<div class="form-group wa-form-group-uname col-sm-6 col-xs-12 {{{ $errors->has('username') ? 'has-error' : '' }}}">
					{{ Form::label('username', '*Username ', array('class' => 'control-label wa-field-title ')) }}
					{{ Form::text('username', null ,array('class' => 'form-control wa-form-control wa-formcontent-registration')) }}
					@if ($errors->first('username'))
					<div class="help-block wa-input-error">{{ $errors->first('username') }}</div>
					@endif

				</div>

				<div class="form-group col-sm-6 col-xs-12 {{{ $errors->has('email') ? 'has-error' : '' }}}">
					{{ Form::label('email', '*Email ID ', array('class' => 'control-label wa-field-title ')) }}
					{{ Form::email('email', null ,array('class' => 'form-control wa-form-control wa-formcontent-registration')) }}
					@if ($errors->first('email'))
					<div class="help-block wa-input-error">{{ $errors->first('email') }}</div>
					@endif
				</div>

				<div class="clearfix"></div>

				<div class="form-group col-sm-6 col-xs-12 {{{ $errors->has('password') ? 'has-error' : '' }}}">
					{{ Form::label('password', '*Password ', array('class' => 'control-label wa-field-title ')) }}
					{{ Form::password('password' ,array('id' => 'wa-registration-password', 'class' => 'form-control wa-form-control wa-formcontent-registration')) }}
					@if ($errors->first('password'))
					<div class="help-block wa-input-error">{{ $errors->first('password') }}</div>
					@endif
				</div>


				<div class="form-group col-sm-6 col-xs-12 {{{ $errors->has('password_confirmation') ? 'has-error' : '' }}}">
					{{ Form::label('password_confirmation', '*Re-Enter Password', array('class' => 'control-label wa-field-title ')) }}
					{{ Form::password('password_confirmation' ,array('id' => 'wa-registration-re-password', 'class' => 'form-control wa-form-control wa-formcontent-registration')) }}
					@if ($errors->first('password_confirmation'))
					<div class="help-block wa-input-error">{{ $errors->first('password_confirmation') }}</div>
					@endif
				</div>

				<div class="clearfix"></div>

				<div class="form-group col-sm-6 col-xs-12 {{{ $errors->has('firstname') ? 'has-error' : '' }}}">
					{{ Form::label('firstname', 'First Name ', array('class' => 'control-label wa-field-title ')) }}
					{{ Form::text('firstname', null,  array('class' => 'form-control wa-form-control wa-formcontent-registration')) }}
					@if ($errors->first('firstname'))
					<div class="help-block wa-input-error">{{ $errors->first('firstname') }}</div>
					@endif
				</div>

				<div class="form-group col-sm-6 col-xs-12 {{{ $errors->has('lastname') ? 'has-error' : '' }}}">
					{{ Form::label('lastname', 'Last Name ', array('class' => 'control-label wa-field-title ')) }}
					{{ Form::text('lastname', null, array('class' => 'form-control wa-form-control wa-formcontent-registration')) }}
					@if ($errors->first('lastname'))
					<div class="help-block wa-input-error">{{ $errors->first('lastname') }}</div>
					@endif
				</div>

				<div class="clearfix"></div>

				<div class="form-group col-sm-6 col-xs-12 {{{ $errors->has('organization') ? 'has-error' : '' }}}">
					{{ Form::label('organization', 'Organization: ', array('class' => 'control-label wa-field-title')) }}
					{{ Form::text('organization', null ,array('class' => 'form-control wa-form-control wa-formcontent-registration')) }}
					@if ($errors->first('organization'))
					<div class="help-block wa-input-error">{{ $errors->first('organization') }}</div>
					@endif
					<div class="form-group">
					<div class="col-xs-12 wa-col-create-btn">
						<button type="submit" class="btn btn-default wa-btn-orange wa-btn-create-accnt">CREATE ACCOUNT</button>
					</div>
				</div>
				</div>

				<div class="form-group  col-sm-6 col-xs-12 {{{ $errors->has('captcha') ? 'has-error' : '' }}}">
					{{ Form::label('captcha', '*Type the code shown: ', array('class' => 'control-label wa-field-title')) }}
					{{ Form::text('captcha', null ,array('class' => 'form-control wa-form-control wa-formcontent-registration')) }}
					@if ($errors->first('captcha'))
					<div class="help-block wa-input-error">{{ $errors->first('captcha') }}</div>
					@endif
					{{ HTML::image(Captcha::url(), 'Responsive image', array('class'=>'img-responsive wa-img-captcha')) }}
				</div>

				<div class="clearfix"></div>
			</div>

			{{ Form::close() }}

		</div>
	</div>
</div>
@stop