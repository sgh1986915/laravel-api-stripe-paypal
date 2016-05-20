<?php
$libPath = base_path(). "/whoisxmlapi_4";
?>
@extends('layouts.master')

@section('title')
Reverse IP
@stop

@section('styles')
@parent
{{ HTML::style('css/reverseip.css') }}
@stop

@section('content')
<div class="row wa-searchbox-radio">
	<div class="col-xs-12 wa-auto-margin">
		<div class="row">
			<div class="col-sm-6 col-xs-12 wa-search-eg-box wa-search-eg-box-reverseip">
				<form id="whoisform" class="ignore_jssm" name="whoisform" action="{{ URL::to('reverseiplookup.php') }}">
					<div class="form-group has-feedback wa-search-box">
						<input type="text" class="form-control wa-search wa-search-reverseip" name="input" id="wa-search-iplookup " placeholder="Reverse IP Lookup">
						<span class="glyphicon glyphicon-search form-control-feedback wa-cursor" id="wa-search-icon-iplookup"></span>
						<div class="wa-exapple wa-example-reverseip pull-left">Example: 208.64.121.161 or 208.64.121.% or %.64.121.161 or test.com</div>
						<div id="wa-user-stats" class="wa-user-stats"><?php include_once $libPath . "/user_stats.php"; ?>
								</div>
					</div>
				</form>
			</div>
			<div class="col-sm-6 col-xs-12 wa-example-reverseip">
				<div class="row">
					<div class="col-sm-offset-4 col-sm-4 col-xs-12 wa-btn-viewShopping">
						<a href={{ URL::to('reverse-whois-order.php') }}><button type="button" class="btn btn-default wa-btn-viewShoppingCart wa-btn-viewShopping-reverseip center-block" id="wa-btn-viewShopping-reverseip">VIEW SHOPPING CART</button></a>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="row wa-page-title-content-bg">
	<div class="col-xs-12 wa-about-whoisApi wa-auto-margin">
		<h1 class="text-center wa-title wa-title-reverseip">Reverse IP Lookup</h1>
		<div class="text-center wa-content-text wa-content-spacing wa-content-text-reverseip">Reverse IP lets you find all the connected domain names hosted on the same IP address.
		</div>
	</div>
</div>
<div id="wa-page-content">
	<div class="row wa-content-bg">
		<div class="col-xs-12 wa-col-xs-no-padding wa-auto-margin">
			<div class="row">
				<div class="col-sm-4 col-xs-12 wa-box-width-xs wa-box-margin-whoisapi">
					<div class="wa-box wa-box-xs-padding wa-top-content-margin wa-box-reverseip wa-box-IP-reverseip">
						<h2 class="wa-section-title wa-section-title-reverseip text-center">Reverse IP</h2>
						<div class="wa-content-text wa-content-text-reverseip wa-content-text-reverseip wa-content-spacing text-center">
							Type in either an ip address or a domain name to lookup. <span class="wa-link wa-cursor wa-textDecoration"> {{ HTML::link('bulk-reverse-ip-order.php', 'Order Reverse IP lookups in bulk now.') }} </span>
						</div>
					</div>
				</div>
				<div class="col-sm-8 col-xs-12 wa-box-width-xs wa-box-margin-whoisapi">
					<div class="wa-box wa-box-xs-padding wa-top-content-margin wa-box-reverseip wa-box-keyfeatures-reverseip">
						<h2 class=" wa-section-title wa-section-title-reverseip wa-section-title-key-features-reverse-ip text-center">
							Key Features:
						</h2>
						<div class="wa-listcontent-reverseip">
							<ul class="list-unstyled">
								<li class="wa-content-text wa-ourFeatures-lists "><span class="wa-list-no">01</span><span class="wa-ourFeatures-lbl">Find all the domains hosted on a given IP address.</span></li>
								<li class="wa-content-text wa-ourFeatures-lists "><span class="wa-list-no">02</span><span class="wa-ourFeatures-lbl">Find all domains hosted on the same ip address as a given domain name.</span></li>
								<li class="wa-content-text wa-ourFeatures-lists "><span class="wa-list-no">03</span><span class="wa-ourFeatures-lbl">Results available in CSV reports. <span class="wa-link wa-cursor wa-textunderline">{{ HTML::link('https://www.whoisxmlapi.com/download.php?file=documentation/domain_ips_sample.csv', 'Download a sample here') }}</span></li>
								<li class="wa-content-text wa-ourFeatures-lists "><span class="wa-list-no">04</span><span class="wa-ourFeatures-lbl">Results available via RESTful API (JSON and XML)</span></li>
							</ul>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

@stop