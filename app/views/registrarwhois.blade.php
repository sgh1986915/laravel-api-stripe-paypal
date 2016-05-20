<?php
$libPath = base_path(). "/whoisxmlapi_4";
?>
@extends('layouts.master')

@section('title')
Registrar Whois Service
@stop

@section('styles')
@parent
{{ HTML::style('css/registrarwhois.css') }}
@stop

@section('content')
<div class="main-content">
	<div class="row wa-searchbox-radio">
		<div class="col-xs-12 wa-auto-margin">
			<div class="row">
				<div class="col-sm-6 col-xs-12 wa-search-eg-box wa-search-eg-box-registraWhois">
					<form id="whoisform" name="whoisform" action="{{ URL::to('whoislookup.php') }}">
						<div class="form-group has-feedback wa-search-box">
							<input type="text" class="form-control wa-search wa-search-registraWhois" name="domainName" id="wa-search-whoislookup" placeholder="Whois Lookup">
							<span class="glyphicon glyphicon-search form-control-feedback wa-cursor" id="wa-search-icon-whoislookup"></span>
							<div class="wa-exapple wa-example-registraWhois">Example: google.com or 74.125.45.100</div>
							<div class="wa-radio-inputs wa-radio-xml-Josn wa-radio-xml-Josn-registraWhois">
								<div class="wa-radio-input wa-radio-input-xml wa-radio-input-registraWhois">
									<input type="radio" value="xml" checked id="wa-radio-xml" class="wa-cursor wa-radio-orange-bar wa-field-input-selection wa-field-input-selection-registraWhois wa-api-res-type" name="outputFormat">
									<label for="wa-radio-xml" class="wa-cursor wa-field-value-selection wa-field-value-selection-xml wa-field-value-selection-registraWhois" id="wa-lbl-XMl">XML</label>
									<div class="wa-home-radio-outerCircle">
										<div class="wa-home-radio-innerCircle"></div>
									</div>
								</div>
								<div class="wa-radio-input wa-radio-input-json wa-radio-input-json-registraWhois">
									<input type="radio" value="json" id="wa-radio-json" class="wa-cursor wa-radio-orange-bar wa-field-input-selection wa-field-input-selection-registraWhois wa-api-res-type" name="outputFormat">
									<label for="wa-radio-json" class="wa-cursor wa-field-value-selection wa-field-value-selection-json wa-field-value-selection-registraWhois" id="wa-lbl-JSON">JSON</label>
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
				<div class="col-sm-6 col-xs-12 wa-btn wa-btn-registraWhois">
					<div class="row">
						<div class="col-sm-offset-4 col-sm-4 col-xs-12 wa-btn-order">
							<a href={{ URL::to('order_paypal.php') }}><button type="button" class="btn btn-default wa-btn wa-btn-orderNow wa-btn-orderNow-registraWhois center-block" id="wa-btn-home-registraWhois">ORDER NOW</button></a>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="row wa-page-title-content-bg">
	<div class="col-xs-12 wa-about-whoisApi wa-auto-margin">
		<h1 class="text-center wa-title wa-title-registrarwhois wa-title-registrarwhoisservice-registraWhois">Registrar Whois Service </h1>
		<div class="text-center wa-content-text wa-content-spacing wa-content-text-registraWhois wa-content-text-registrarwhoisservice-registraWhois wa-page-description wa-page-description-registraWhois">
			Setup and manage whois servers for registrars and businesses. Outsource part or all of whois services and other domain management services to us. Provide consulting on all your whois and domain management needs.
		</div>
	</div>
</div>
<div id="wa-page-content">
	<div class="row wa-content-bg">
		<div class="col-xs-12 wa-col-xs-no-padding wa-col-registraWhois wa-auto-margin">
			<div class="row">
				<div class="col-xs-12 wa-whoishosting wa-whoishosting-registraWhois wa-box-width-xs">
					<div class="wa-box wa-box-xs-padding wa-top-content-margin wa-box-registraWhois wa-box-whoisHosting-registraWhois">
						<h2 class="wa-section-title wa-section-title-registraWhois wa-section-title-whoishosting-registraWhois text-center">Whois hosting</h2>
						<div class="wa-content-text wa-content-text-registraWhois wa-content-spacing wa-content-text-whoishosting-registraWhois text-center">
							Do you find your in-house whois service ticklish, prompting your clients to turn their back on you? Do you struggle to obtain whois data consistently? Each registrar has its own set of whois data, which only increases the complexity of deriving the data. Our whois parsing system is a utility that collects extensive information about any given domain by using API that sends a series of DNS and WHOIS queries. The report is generated in raw as well as parsed format. It would include information about the data center hosting the web server, mail server, and domain name server (DNS) of the specified domain; domain owner, domain registrar that registered the domain, the created/changed/expire date of the domain, list of all DNS records, and more relevant information.
						</div>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-xs-12 wa-registrarService wa-registrarService-registraWhois wa-box-width-xs">
					<div class="wa-box wa-box-xs-padding wa-bottom-content-margin wa-box-registrarService-registraWhois wa-box-registraWhois">
						<h2 class="wa-section-title wa-section-title-registraWhois wa-section-title-registrarService-registraWhois text-center">Our registrar services include:</h2>
						<ul class="list-unstyled">
							<li class="wa-content-text wa-content-spacing wa-ourFeatures-lists wa-warningList-registraWhois wa-section-title-registrarService-registraWhois"><span class="wa-list-no">01</span><span class="wa-ourFeatures-lbl">Setting up and managing public whois servers for your organization</span></li>
							<li class="wa-content-text wa-content-spacing wa-ourFeatures-lists wa-warningList-registraWhois wa-section-title-registrarService-registraWhois"><span class="wa-list-no">02</span><span class="wa-ourFeatures-lbl">Consultation on all whois related projects</span></li>
							<li class="wa-content-text wa-content-spacing wa-ourFeatures-lists wa-warningList-registraWhois wa-section-title-registrarService-registraWhois"><span class="wa-list-no">03</span><span class="wa-ourFeatures-lbl">Consultation on other domain management services</span></li>
						</ul>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
@stop