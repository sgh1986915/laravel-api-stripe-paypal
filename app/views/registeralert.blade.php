<?php
$libPath = base_path(). "/whoisxmlapi_4";
?>
@extends('layouts.master')
@section('title')
Register Alert API
@stop

@section('styles')
@parent
{{ HTML::style('css/registeralert.css') }}
@stop

@section('content')
<div class="row wa-searchbox-radio">
	<div class="col-xs-12 wa-auto-margin">
		<div class="row">
			<div class="col-sm-6 col-xs-12 wa-search-eg-box wa-search-eg-box-registraAlert">
				<form id="whoisform" name="whoisform" action="{{ URL::to('whoislookup.php') }}">
					<div class="form-group has-feedback wa-search-box wa-search-box-registerAlert">
						<input type="text" class="form-control wa-search wa-search-registerAlert"  name="domainName" id="wa-search-whoislookup" placeholder="Whois Lookup">
						<span class="glyphicon glyphicon-search form-control-feedback wa-cursor" id="wa-search-icon-whoislookup"></span>
						<div class="wa-exapple wa-example-registerAlert">Example: google.com or 74.125.45.100</div>
						<div class="wa-radio-inputs wa-radio-xml-Josn wa-radio-xml-Josn-registerAlert">
							<div class="wa-radio-input wa-radio-input-xml wa-radio-input-xml-registerAlert">
								<input type="radio" value="xml" checked id="wa-radio-xml" class="wa-cursor wa-radio-orange-bar wa-field-input-selection wa-field-input-selection-registerAlert wa-api-res-type" name="outputFormat">
								<label for="wa-radio-xml" class="wa-cursor wa-field-value-selection wa-field-value-selection-xml wa-field-value-selection-registerAlert" id="wa-lbl-XMl">XML</label>
								<div class="wa-home-radio-outerCircle">
									<div class="wa-home-radio-innerCircle"></div>
								</div>
							</div>
							<div class="wa-radio-input wa-radio-input-json wa-radio-input-json-registerAlert">
								<input type="radio" value="json" id="wa-radio-json" class="wa-cursor wa-radio-orange-bar wa-field-input-selection wa-field-input-selection-registerAlert wa-api-res-type" name="outputFormat">
								<label for="wa-radio-json" class="wa-cursor wa-field-value-selection wa-field-value-selection-json wa-field-value-selection-registerAlert" id="wa-lbl-JSON">JSON</label>
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
			<div class="col-sm-6 col-xs-12 wa-btn wa-btn-registraAlert">
				<div class="row">
					<div class="col-sm-offset-4 col-sm-4 col-xs-12 wa-btn-order">
						<a href={{ URL::to('order_paypal.php') }}><button type="button" class="btn btn-default wa-btn wa-btn-orderNow wa-btn-orderNow-registerAlert center-block" id="wa-btn-orderNow-registerAlert">ORDER NOW</button></a>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="row wa-page-title-content-bg">
	<div class="col-xs-12 wa-about-whoisApi wa-auto-margin">
		<h1 class="text-center wa-title wa-title-registerAlert wa-title-registrantaletAPI-registerAlert">Registrant Alert API</h1>
	</div>
</div>
<div id="wa-page-content">
	<div class="row wa-content-bg wa-content-bg-registerAlert">
		<div class="col-xs-12  wa-col-xs-no-padding wa-box-width-xs wa-box-margin-whoisapi wa-auto-margin">
			<div class="wa-box wa-box-xs-padding wa-top-content-margin wa-box-registerAlert wa-box-apiGuide-registerAlert">
				<h2 class="wa-section-title wa-section-title-registerAlert wa-section-title-apiGuide-registerAlert text-center text-center">Registrant Alert API Guide</h2>
			</div>
		</div>
		<div class="col-sm-6 col-xs-12 wa-col-xs-no-padding wa-auto-margin">
			<div class="wa-box wa-box-xs-padding wa-box-webserviceCall-registeralert wa-box-registeralert">

				<h2 class="text-center wa-section-title wa-section-title-registraAlert wa-section-title-webservice-registraAlert">
					How to make a webservice call to Registrant Alert API?
				</h2>
				<div class="wa-contentLink-registeralert wa-top-registerAlert wa-content-spacing">
					http://www.whoisxmlapi.com/registrant-alert-api/search.php?term1=cinema&username=xxxxx&password=xxxxx
				</div>
				<div class="wa-content-text wa-content-text-registerAlert wa-conten-text-register-registerAlert wa-content-spacing">
					The Registrant Alert API searches through newly registered and changed domain names to find whois records matching the alert terms. By default, the result contains all the domain names registered or dropped today(the day your query is submmited on). Your program can monitor each set of terms by submitting a single query each day (after 4AM PST). Optionally you can set since_date or days_back to backtrack the alerts up to 12 days back. The product is ideal for brand protection agents to monitor registrations that contain a trademarked word or product phrase.
				</div>
				<div>
					<table class="table table-bordered table-striped wa-content-text wa-content-spacing wa-content-text-registraAlert wa-table-registraAlert wa-table-webservice-registraAlert">
						<thead>
							<tr>
								<th>Parameters</th>
								<th>Values</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td>termx</td>
								<td>term to search for, x ranges from 1 to 4, term1 is the first term to search for, term2 is the second term to search for, the relationship between the terms is AND. At least one term is required.</td>
							</tr>
							<tr>
								<td>exclude_termx</td>
								<td>term to exclude in the search, x ranges from 1 to 4, exclude_term1 is the first term to exclude, exclude_term2 is the second term to exclude. optional.</td>
							</tr>
							<tr>
								<td>since_date</td>
								<td>search domains registered/dropped from this date to today (inclusive). The date format is YYYY-MM-DD    (eg. 2012-04-01). It can be up to 12 days prior to today. optional.</td>
							</tr>
							<tr>
								<td>days_back</td>
								<td>search domains registered/dropped up to 12 days prior to today. an integer between 0 to 12. optional.</td>
							</tr>
							<tr>
								<td>username</td>
								<td>required, your account username</td>
							</tr>
							<tr>
								<td>password</td>
								<td>required, your account password</td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>
		</div>
		<div class="col-xs-12 wa-col-xs-no-padding wa-auto-margin">
			<div class="wa-box wa-box-xs-padding wa-bottom-content-margin wa-box-pricing-registeralert wa-box-registeralert">
				<h2 class="text-center wa-section-title wa-section-title-registerAlert wa-section-title-pricing-registerAlert">
						Pricing for Registrant Alert API?
				</h2>
				<div class="wa-content-text wa-content-text-registerAlert wa-content-spacing wa-content-text-pricing-registraAlert">
					A monthly subscription fee of $5 is required to use Registrant Alert API. The cost per query is $0.02. <span class="wa-link wa-cursor wa-textDecoration">{{ HTML::link('order-api.php', 'Order Now') }}</span>
				</div>
			</div>
		</div>
	</div>
</div>
@stop