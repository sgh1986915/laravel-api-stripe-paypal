<?php
$libPath = base_path(). "/whoisxmlapi_4";
?>
@extends('layouts.master')

@section('title')
Domain IP Database
@stop

@section('styles')
@parent
{{ HTML::style('css/domainIpdb.css') }}
@stop

@section('content')
<div class="main-content">
	<div class="row wa-searchbox-radio">
		<div class="col-xs-12 wa-auto-margin">
			<div class="row">
				<div class="col-sm-6 col-xs-12 wa-search-eg-box wa-search-eg-box-domainip">
					<form id="whoisform" class="ignore_jssm" name="whoisform" action="{{ URL::to('reverseiplookup.php') }}">
						<div class="form-group has-feedback wa-search-box ">
							<input type="text" class="form-control wa-search  wa-search-domainip" name="input" id="wa-search-iplookup" placeholder="Reverse IP Lookup">
							<span class="glyphicon glyphicon-search form-control-feedback wa-cursor" id="wa-search-icon-iplookup"></span>
							<div class="wa-exapple wa-example-domainip pull-left">Example: 208.64.121.161 or 208.64.121.% or %.64.121.161 or test.com</div>
							<div id="wa-user-stats" class="wa-user-stats"><?php include_once $libPath . "/user_stats.php"; ?>
							</div>
						</div>
					</form>
				</div>
				<div class="col-sm-6 col-xs-12 wa-example-domainip ">
					<div class="row">
						<div class="col-sm-offset-4 col-sm-4 col-xs-12 wa-btn-viewShopping">							
							<a href={{ URL::to('reverse-whois-order.php') }}><button type="button" class="btn btn-default  wa-btn-viewShoppingCart wa-btn-viewShopping-domainip center-block" id="wa-btn-viewShopping-reversewhoisapi">VIEW SHOPPING CART</button></a>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="row wa-page-title-content-bg">
		<div class="col-xs-12 wa-about-whoisApi wa-auto-margin">
			<h1 class="text-center wa-title wa-title-domainip">Domain IP Database</h1>
			<div class="text-center wa-content-text wa-content-spacing wa-content-text-domainip wa-page-description wa-page-description-domainip">
			Provides IP addresses for hundreds of millions of domain names via bulk download.
			</div>
		</div>
	</div>
	<div id="wa-page-content">
		<div class="row wa-content-bg wa-content-bg-domainip">
			<div class="col-xs-12 wa-box-width-xs wa-box-margin-whoisapi wa-col-xs-no-padding wa-auto-margin">
				<div class="wa-box wa-box-xs-padding wa-box-domainip wa-box-domainipdd-domainip wa-top-content-margin">
					<h2 class="wa-section-title wa-section-title-domainip wa-section-title-domainipdd-domainip text-center">Domain IP Database Download</h2>
					<div class="wa-content-text wa-content-text-domainip wa-content-spacing wa-content-domainipdd-domainip text-center">
						A Domain IP database contains every domain name to its hosting IP addresses mapping. We provide archived Domain IP database for download as database dumps (MYSQL or MYSSQL dump) or CSV files. Currently we provide downloads for the major GTLDs: .com, .net, .org, .us, .biz, .mobi, .info, .pro, .coop, and .asia. Download <span class="wa-link wa-cursor wa-textDecoration">{{ HTML::link('https://www.whoisxmlapi.com/download.php?file=documentation/sample_raw_db.csv', 'a sample of the domain records.') }} </span>
					</div>
				</div>
			</div>
		</div>
		<div class="row wa-content-bg wa-content-bg-domainip">
			<div class="col-xs-12 wa-auto-margin wa-col-xs-no-padding">
				<div class="row">
					<div class="col-sm-6 col-xs-12 wa-box-width-xs">
						<div class="wa-box wa-box-xs-padding wa-box-domainip wa-box-possibleappsIP-domainip">
							<h2 class="wa-section-title wa-section-title-domainip wa-section-title-possibleappsIP-domainip text-center">Possible applications and usages of Domain IP Database:</h2>
							<ul class="list-unstyled">
								<li class="wa-content-text wa-ourFeatures-lists"><span class="wa-list-no">01</span><span class="wa-ourFeatures-lbl">Cybersecurity analysis, Fraud detection</span></li>
								<li class="wa-content-text wa-ourFeatures-lists"><span class="wa-list-no">02</span><span class="wa-ourFeatures-lbl">Statistical research analysis</span></li>
								<li class="wa-content-text wa-ourFeatures-lists "><span class="wa-list-no">03</span><span class="wa-ourFeatures-lbl">Extract fine-grained information and gain insight from a comprehensive pool of whois records</span></li>
								<li class="wa-content-text wa-ourFeatures-lists  wa-warningList-whoisapi"><span class="wa-list-no">04</span><span class="wa-ourFeatures-lbl">Much more... The possiblities are limitless</span></li>
							</ul>
						</div>
					</div>
					<div class="col-sm-6 col-xs-12 wa-box-width-xs">
						<div class="wa-box wa-box-xs-padding wa-box-domainip wa-box-pricing-domainip">
							<h2 class="wa-section-title wa-section-title-domainip wa-section-title-pricing-domainip text-center">Pricing for Domain IP Database download</h2>
							<div class="wa-content-text wa-content-text-domainip wa-content-spacing wa-content-pricing-domainip">
								We offer partial or complete Domain IP database download. We also offer a yearly plan with 4 quarterly downloads of complete domain IP databases. <span class="wa-link wa-cursor wa-textDecoration">{{ HTML::link('order_paypal.php', 'Order Now!') }}</span>
								<div>Promotional discount is only available until end of this month!</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
@stop
