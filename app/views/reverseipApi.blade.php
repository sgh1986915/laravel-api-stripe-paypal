<?php
$libPath = base_path(). "/whoisxmlapi_4";
?>
@extends('layouts.master')

@section('title')
Reverse IP API
@stop

@section('styles')
@parent
{{ HTML::style('css/reverseipApi.css') }}
{{ HTML::style('css/reverseWhoisLookup.css') }}
@stop

@section('scripts')
{{ HTML::script('js/moreoptions_radio.js') }}
{{ HTML::script('js/reverseWhoisLookup.js') }}
@stop

@section('content')
<div class="row wa-searchbox-radio">
	<div class="col-xs-12 wa-auto-margin">
		<form id="whoisform" name="whoisform" action="{{ URL::to('reverselookup.php') }}">
			<div class="row">
				<div class="col-sm-6 col-xs-12 wa-search-eg-box wa-search-eg-box-reverseIpapi">
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
				<div class="col-sm-6 col-xs-12 wa-btn wa-btn-rw-lookup">
					<div class="row">
						<div class="col-sm-offset-4 col-sm-4 col-xs-12 wa-btn-viewShopping">
							<a href={{ URL::to('reverse-whois-order.php') }}><button type="button" class="btn btn-default wa-btn-viewShoppingCart wa-btn-viewShopping-rw-lookup center-block" id="wa-btn-home-orderNow">VIEW SHOPPING CART</button></a>
						</div>
					</div>
				</div>
			</div>
			<div class="wa-moreoptions">
				<div class="row">
					<div class="col-xs-12 wa-historicRecord-reverseIpapi">
						<div class="wa-margin-checkbox wa-historic-checkbox wa-cursor ">
							<label class="wa-field-value-selection wa-field-value-selection-checkbox"><input type="checkbox" class="wa-checkbox-reverseIpapi" name="search_type" value="" id="wa-checkbox-historic-records">Include Historic Records</label>
						</div>
					</div>
					<div class="col-xs-12">
						<label class="wa-field-value-selection wa-field-value-selection-checkbox">Include whois records containing ALL of the following terms in addition to the primary search term above:</label>
					</div>
					<div class="col-sm-6 col-xs-12">
						<input type="text" class="form-control wa-search-form wa-search-form-reverseIpapi wa-search-form1" id="wa-input-type-include-1"name="term2">
						<input type="text" class="form-control wa-search-form wa-search-form-reverseIpapi wa-search-form2" id="wa-input-type-include-2"name="term4">
					</div>
					<div class="col-sm-6 col-xs-12">
						<input type="text" class="form-control wa-search-form wa-search-form-reverseIpapi wa-search-form3" id="wa-input-type-include-3"name="term3">
					</div>
				</div>
				<div class="row">
					<div class="col-sm-12 col-xs-12 wa-searchBox-exclude-reverseIpapi">
						<div class ="wa-field-value-selection">
						<label class="wa-field-value-selection">Exclude whois records containing ANY of following terms:</label>
						</div>
					</div>
					<div class="col-sm-6 col-xs-12">
						<input type="text" class="form-control wa-search-form wa-search-form-reverseIpapi wa-search-form4" id="wa-input-type-exclude-1" name="exclude_term1">
						<input type="text" class="form-control wa-search-form wa-search-form-reverseIpapi wa-search-form5" id="wa-input-type-exclude-2" name="exclude_term3">
					</div>
					<div class="col-sm-6 col-xs-12">
						<input type="text" class="form-control wa-search-form wa-search-form-reverseIpapi wa-search-form6" id="wa-input-type-exclude-3" name="exclude_term2">
					</div>
				</div>
			</div>
		</form>
	</div>
</div>

<div class="row wa-page-title-content-bg">
	<div class="col-xs-12 wa-about-whoisApi wa-auto-margin">
		<h1 class="text-center wa-title wa-title-reverseIpApi">Reverse IP API </h1>
		<div class="text-center wa-content-text wa-content-text-reverseIpapi wa-page-description wa-page-description-reverseIpapi wa-content-spacing">
			 Reverse IP API provides a RESTful webservice for Reverse IP lookup with XML and JSON response.
		</div>
	</div>
</div>
<div id="wa-page-content">
	<div class="row wa-content-bg wa-content-bg-reverseIpapi">
		<div class="col-xs-12 wa-col-xs-no-padding wa-box-width-xs wa-box-margin-whoisapi wa-auto-margin">
			<div class="wa-box  wa-box-xs-padding wa-top-content-margin wa-box-reverseIp">
				<h2 class="wa-section-title wa-section-title-reverseIp wa-section-title-apiGuide-reverseIpapi text-center">Reverse IP API Guide</h2>
			</div>
		</div>
		<div class="col-sm-6 col-xs-12 wa-col-xs-no-padding wa-auto-margin">
			<div class="wa-box  wa-box-xs-padding wa-bottom-content-margin wa-box-webserviceCall-reverseIpapi wa-box-reverseipapi">
				<h2 class="text-center wa-section-title wa-section-title-webService-reverseIp">
					How to make a webservice call to Reverse IP API?
				</h2>
				<div class="wa-content-text wa-contentLink-reverseIpapi wa-content-spacing">
					http://www.whoisxmlapi.com/api/reverseip/$input?outputFormat=XML&username=xxxxx&password=xxxxx
				</div>

				<div class="wa-content-text wa-content-spacing ">
					<table class="table table-bordered table-striped wa-table-webserviceCall-reverseIpapi wa-table-reverseIpapi">
						<thead>
							<tr>
								<th>Parameters</th>
								<th>Values</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td>input</td>
								<td>either an ip address or a domain name. If input is an ip address, it will return all the domains hosted on this ip address. If input is a domain name, it will find all connected domains by first finding all hosting ip addresses for the domain name and then retrieve all domains hosted all these ip addresses.</td>
							</tr>
							<tr>
								<td>offset</td>
								<td>starting index for the resulting domains. optional. Defaults to 0. For example, if offset is 10, then it will only return domains starting at the 10th element. (0-based index)</td>
							</tr>
							<tr>
								<td>limit</td>
								<td>maximum number of domains to retrieve, starting from the offset. optional. Defaults to the total length of the resulting domains. For example, if limit is 9 and offset is 0, then it will only return the first 9 elements in the resulting domains.</td>
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
				<div class="wa-content-text wa-content-text-reverseIpapi wa-content-text-purchase-reverseIpapi wa-content-spacing">
					You must <span class="wa-link wa-cursor wa-textDecoration">{{ HTML::link('bulk-reverse-ip-order.php', 'purchase Reverse IP credits') }}</span> to use Reverse IP API.</a>
				</div>
			</div>
		</div>
	</div>
</div>
@stop