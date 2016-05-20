<?php
error_reporting(0);
@ini_set('display_errors', 0);
$libPath = base_path(). "/whoisxmlapi_4";
require_once $libPath . "/users/users.conf";
require_once $libPath . "/httputil.php";


?>
@extends('layouts.master')

@section('title')
Cyber-security Data Solution
@stop

@section('styles')
@parent
{{ HTML::style('css/whoisdd.css') }}
@stop

@section('content')
<div class="main-content">
	<div class="row wa-searchbox-radio">
		<div class="col-xs-12 wa-auto-margin">
			<div class="row">
				<div class="col-sm-6 col-xs-12 wa-search-eg-box wa-search-eg-box-whoisdd">
					<form id="whoisform" name="whoisform" action="{{ URL::to('whoislookup.php') }}">
						<div class="form-group has-feedback wa-search-box">
							<input type="text" class="form-control wa-search wa-search-whoisdd" name="domainName" id="wa-search-whoislookup" placeholder="Whois Lookup">
							<span class="glyphicon glyphicon-search form-control-feedback wa-cursor" id="wa-search-icon-whoislookup"></span>
							<div class="wa-exapple wa-example-whoisdd">Example: google.com or 74.125.45.100</div>
							<div class="wa-radio-inputs wa-radio-xml-Josn wa-radio-xml-Josn-whoisdd">
								<div class="wa-radio-input wa-radio-input-xml wa-radio-input-xml-whoisdd">
									<input type="radio" value="xml" checked id="wa-radio-xml" class="wa-cursor wa-radio-orange-bar wa-field-input-selection wa-field-input-selection-whoisdd wa-api-res-type" name="outputFormat">
									<label for="wa-radio-xml" class="wa-cursor wa-field-value-selection wa-field-value-selection-xml wa-field-value-selection-whoisdd" id="wa-lbl-XMl">XML</label>
									<div class="wa-home-radio-outerCircle">
										<div class="wa-home-radio-innerCircle"></div>
									</div>
								</div>
								<div class="wa-radio-input wa-radio-input-json wa-radio-input-json-whoisdd">
									<input type="radio" value="json" id="wa-radio-json" class="wa-cursor wa-radio-orange-bar wa-field-input-selection wa-field-input-selection-whoisdd wa-api-res-type" name="outputFormat">
									<label for="wa-radio-json" class="wa-cursor wa-field-value-selection wa-field-value-selection-json wa-field-value-selection-whoisdd" id="wa-lbl-JSON">JSON</label>
									<div class="wa-home-radio-outerCircle">
										<div class="wa-home-radio-innerCircle" style="display: none;"></div>
									</div>
								</div>
							</div>
							<div id="wa-user-stats" class="wa-user-stats"><?php include_once $libPath . "/user_stats.php"; ?>
							</div>
						</div>
					</form>
				</div>
				<div class="col-sm-6 col-xs-12 wa-btn wa-btn-whoisdd">
					<div class="row">
						<div class="col-sm-offset-4 col-sm-4 col-xs-12 wa-btn-order">
							<a href={{ URL::to('order_paypal.php') }}><button type="button" class="btn btn-default wa-btn wa-btn-orderNow wa-btn-orderNow-whoisdd center-block" id="wa-btn-orderNow-whoisdd">ORDER NOW</button></a>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<!-- Cyber-security Data Solution -->
	<div class="row wa-page-title-content-bg">
		<div class="col-xs-12 wa-about-whoisApi wa-xs wa-auto-margin">
			<h1 class="text-center wa-title wa-title-whoisdd">Cyber-security Data Solution</h1>
			<div class="text-center wa-content-spacing wa-content-text wa-content-text-whoisdd wa-page-description wa-page-description-whoisdd">
				Our cyber-security data solution is a comprehensive data solution for cybersecurity companies/divisions, it's exclusively designed for maximum amount of real-time and historic data coverage. 
			</div>
		</div>
	</div>
	<div id="wa-page-content">
		<div class="row wa-content-bg wa-content-bg-whoisdd">
			<!--  
			<div class="col-xs-12 wa-box-width-xs wa-auto-margin wa-col-xs-no-padding">
				<div class="wa-box wa-top-content-margin wa-box-xs-padding wa-box-whoisdd wa-box-whoisdatabased-whoisdd">
					<h2 class="wa-section-title wa-section-title-whoisdd wa-section-title-whoisdatabased-whoisdd text-center"></h2>
					<div class="wa-content-text wa-content-text-whoisdd wa-content-spacing wa-content-text-whoisdatabased-whoisdd text-center">
					
					</div>
				</div>
			</div>
			-->
			<div class="col-xs-12 wa-auto-margin wa-col-xs-no-padding">
				<div class="row">
					<div class="col-xs-12 ">
						<div class="wa-box wa-box-xs-padding wa-box-whoisdd wa-box-possibleapps-whoisdd">
							<h2 class="wa-section-title wa-section-title-whoisdd wa-section-title-possibleapps-whoisdd text-center">Customizable Solution Components:</h2>
							<ul class="list-unstyled">
				
							
								<li class="wa-content-text wa-ourFeatures-lists wa-app-list-whoisddd "><span class="wa-list-no wa-list-no-whoisdd">01</span><span class="wa-ourFeatures-lbl wa-lbl-app-list-whoisdd">
									Whois Database Download Yearly Subscription Plan: 4 quarterly downloads plus daily updates of the complete whois database.  The complete whois database contains the gtlds including .com, .net, .org, .us, .biz, .mobi, .info, .pro, .coop, .asia, .name, .tel, .aero  plus hundreds of more new gtlds.  There are over 160 million domain names in the latest version of the database.
Duration: 1 year.
								</span></li>
								<li class="wa-content-text wa-ourFeatures-lists wa-section-title-possibleapps-whoisdd"><span class="wa-list-no">02</span><span class="wa-ourFeatures-lbl">
									 2 million whois api webservice queries/month.  Customizable.  Duration: 1 year.   
								</span></li>
								<li class="wa-content-text wa-ourFeatures-lists wa-section-title-possibleapps-whoisdd"><span class="wa-list-no">03</span><span class="wa-ourFeatures-lbl">
								a one time dump of all historic whois records with over 1.5 billion whois records.  This includes mostly gtlds and a small set of cctlds with limited coverages. 
								
								</span></li>
								<li class="wa-link wa-cursor wa-textDecoration">
								{{ HTML::link('mailto:support@whoisxmlapi.com', 'Contact us for pricing and details' ,array('onclick' => "_gaq.push(['_trackEvent', 'mailto', 'clicked']);")) }}
								</li>
								
							</ul>
						</div>
					</div>
				</div>

			</div>
			<div class="col-xs-12 wa-auto-margin wa-col-xs-no-padding">
				<div class="row">
					<div class="col-xs-12 ">
						<div class="wa-box wa-box-xs-padding wa-box-whoisdd wa-box-possibleapps-whoisdd">
							<h2 class="wa-section-title wa-section-title-whoisdd wa-section-title-possibleapps-whoisdd text-center">Possible applications and usages of Cyber-security Data Solution:</h2>
							<ul class="list-unstyled">
								<li class="wa-content-text wa-ourFeatures-lists wa-app-list-whoisddd "><span class="wa-list-no wa-list-no-whoisdd">01</span><span class="wa-ourFeatures-lbl wa-lbl-app-list-whoisdd">Cybersecurity analysis, Fraud detection, Intrusion detection</span></li>
								<li class="wa-content-text wa-ourFeatures-lists wa-section-title-possibleapps-whoisdd"><span class="wa-list-no">03</span><span class="wa-ourFeatures-lbl">Brand Protection</span></li>
								<li class="wa-content-text wa-ourFeatures-lists wa-section-title-possibleapps-whoisdd"><span class="wa-list-no">02</span><span class="wa-ourFeatures-lbl">Extract fine-grained information and gain insight from a comprehensive pool of current and historic whois records</span></li>
								<li class="wa-content-text wa-ourFeatures-lists wa-section-title-possibleapps-whoisdd"><span class="wa-list-no">03</span><span class="wa-ourFeatures-lbl">Cyber intelligence</span></li>
								<li class="wa-content-text wa-ourFeatures-lists wa-section-title-possibleapps-whoisdd"><span class="wa-list-no">04</span><span class="wa-ourFeatures-lbl">Much more... The possibilities are limitless</span></li>
							</ul>
						</div>
					</div>
				</div>

			</div>
		</div>
	</div>
	@stop
