<?php
$libPath = base_path(). "/whoisxmlapi_4";
?>
@extends('layouts.master')
@section('title')
@parent
:: User Management
@stop
@section('styles')
@parent
{{ HTML::style('css/management.css') }}
{{ HTML::style('css/viewblade.css') }}
{{ HTML::style('css/viewapi.css') }}
{{ HTML::style('css/createapi.css') }}
{{ HTML::style('css/edit.css') }}
{{ HTML::style('css/account_balance.css') }}
{{ HTML::style('css/invoices.css') }}
@stop
@section('content')
<div class="row wa-searchbox-radio">
	<div class="col-xs-12 wa-auto-margin">
		<div class="row">
			<div class="col-sm-6 col-xs-12 wa-search-eg-box wa-search-eg-box-management	">
				<form id="whoisform" name="whoisform" action="{{ URL::to('whoislookup.php') }}">
					<div class="form-group has-feedback wa-search-box wa-search-box-management">
						<input type="text" class="form-control wa-search wa-search-management" id="wa-search-whoislookup" placeholder="Whois Lookup">
						<span class="glyphicon glyphicon-search form-control-feedback wa-cursor" id="wa-search-icon-whoislookup"></span>
						<div class="wa-exapple wa-example-management">Example:google.com or 74.125.45.100</div>
						<div class="wa-radio-inputs wa-radio-xml-Josn wa-radio-xml-json-management">
							<div class="wa-radio-input wa-radio-input-xml wa-radio-input-whois">
								<input type="radio" value="xml" checked id="wa-radio-xml" class="wa-cursor wa-radio-orange-bar wa-field-input-selection wa-field-input-selection-management wa-api-res-type" name="wa-lbl-radio">
								<label for="wa-radio-xml" class="wa-cursor wa-field-value-selection  wa-field-value-selection-management" id="wa-lbl-XMl">XML</label>
								<div class="wa-home-radio-outerCircle">
									<div class="wa-home-radio-innerCircle"></div>
								</div>
							</div>
							<div class="wa-radio-input wa-radio-input-json wa-radio-input-whois">
								<input type="radio" value="json" id="wa-radio-json" class="wa-cursor wa-radio-orange-bar wa-field-input-selection wa-field-input-selection-management wa-api-res-type" name="wa-lbl-radio">
								<label for="wa-radio-json" class="wa-cursor wa-field-value-selection  wa-field-value-selection-management" id="wa-lbl-JSON">JSON</label>
								<div class="wa-home-radio-outerCircle wa-home-radio-outerCircle-management">
									<div class="wa-home-radio-innerCircle wa-home-radio-innerCircle-management" style="display: none;"></div>
								</div>
							</div>
						</div>
						<div id="wa-user-stats" class="wa-user-stats"><?php include_once $libPath . "/user_stats.php"; ?>
						</div>
					</div>
				</form>
			</div>
			<div class="col-sm-6 col-xs-12 wa-btn wa-btn-management">
				<div class="row">
					<div class="col-sm-offset-4 col-sm-4 col-xs-12 wa-btn-order">
						<a href="{{ URL::to('order_paypal.php') }}"><button type="button" class="btn btn-default wa-btn-orderNow wa-btn-orderNow-management center-block" id="wa-btn-orderNow-management">ORDER NOW</button></a>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="row wa-page-title-content-bg wa-page-title-content-bg-reg">
	<div class="col-xs-12 wa-auto-margin wa-title-reg">
		<div class="text-center wa-title wa-title" id="page-title">My Account</div>
	</div>
</div>
<div id="wa-page-content">
	<div class="row wa-content-bg">
		<div class="col-xs-12 wa-col-xs-no-padding wa-auto-margin wa-col-management">
			<nav class="navbar navbar-default wa-tab-navbar wa-tab-navbar-management" role="navigation">
				<div class="navbar-header wa-navbar-header-management wa-cursor">
				  <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
					<span class="sr-only">Toggle navigation</span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				  </button>
				  <a class="navbar-brand visible-xs wa-tab-top-menu-xs" href="#" id="wa-xs-tab-active-menu">Invoices</a>
				</div>

				<div class="collapse navbar-collapse wa-navbar-collpase-management wa-cursor" id="bs-example-navbar-collapse-1">
				  <ul class="nav navbar-nav wa-navbar-nav">
					<li class="active wa-tab-li-menu wa-border-right-tab wa-tab-invocies" rel="invoice"><a href="#invoices">Invoices</a></li>
					<li class="wa-tab-li-menu wa-border-right-tab wa-tab-account-balance" rel="balance"><a href="#account-balance">Account Balance</a></li>
					<li class="wa-tab-li-menu wa-border-right-tab wa-tab-profile" rel="user/view"><a href="#profile">Profile</a></li>
					<?php
					/* Temp Fix :
					<li class="wa-tab-li-menu wa-tab-api-key-management" rel="api" id="wa-tab-api-key-management"><a href="#api-key-management">API Key Management</a></li> */
					?>
				  </ul>
				</div>
			</nav>
			<div id="wa-tab-content" class="wa-tab-background">
			</div>
		</div>
	</div>
</div>
@stop


@section('scripts')
@parent
{{ HTML::script('js/user_tab.js') }}
{{ HTML::script('js/user_edit.js') }}
{{ HTML::script('js/api_view.js') }}
@stop