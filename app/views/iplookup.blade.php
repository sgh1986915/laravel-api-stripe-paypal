<?php
$libPath = base_path(). "/whoisxmlapi_4";
?>
@extends('layouts.master')

@section('title')
Reverse IP Lookup
@stop

@section('styles')
@parent
{{ HTML::style('css/iplookup.css') }}
@stop

@section('content')
<div class="main-content">
	<div class="row wa-searchbox-radio">
		<div class="col-xs-12 wa-auto-margin">
			<div class="row">
				<div class="col-sm-6 col-xs-12 wa-search-eg-box wa-search-eg-box-iplookup">
					<form id="whoisform" class="ignore_jssm" name="whoisform" action="{{ URL::to('reverseiplookup.php') }}">
						<div class="form-group has-feedback wa-search-box">
							<input type="text" class="form-control wa-search wa-search-iplookup" name="input" id="wa-search-iplookup " placeholder="Reverse IP Lookup">
							<span class="glyphicon glyphicon-search form-control-feedback wa-cursor" id="wa-search-icon-iplookup"></span>
							<div class="wa-exapple wa-example-iplookup pull-left">Example: 208.64.121.161 or 208.64.121.% or %.64.121.161 or test.com</div>
							<div id="wa-user-stats" class="wa-user-stats"><?php include_once $libPath . "/user_stats.php"; ?>
							</div>
						</div>
					</form>
				</div>
				<div class="col-sm-6 col-xs-12 wa-example-iplookup">
					<div class="row">
						<div class="col-sm-offset-4 col-sm-4 col-xs-12 wa-btn-viewShopping">
								<a href={{ URL::to('reverse-whois-order.php') }}><button type="button" class="btn btn-default wa-btn-viewShoppingCart wa-btn-viewShopping-iplookup center-block" id="wa-btn-viewShopping-iplookup">VIEW SHOPPING CART</button></a>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="row wa-page-title-content-bg">
		<div class="col-xs-12  wa-about-whoisApi wa-auto-margin">
			<h1 class="text-center wa-title wa-title-iplookup">Reverse IP Lookup</h1>
		</div>
	</div>
	<div id="wa-page-content">
		<div class="row wa-content-bg wa-content-bg-iplookup">
			<div class="col-xs-12 wa-col-xs-no-padding wa-box-width-xs wa-box-margin-whoisapi wa-auto-margin">
				<div class="wa-box wa-box-xs-padding">

				</div>
			</div>
		</div>
	</div>
</div>