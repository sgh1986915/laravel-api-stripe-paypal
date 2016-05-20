<?php
$libPath = base_path(). "/whoisxmlapi_4";
?>
@extends('layouts.master')

@section('title')
White Papers
@stop

@section('styles')
@parent
{{ HTML::style('css/whitepapers.css') }}
@stop

@section('content')
<div class="row wa-searchbox-radio">
	<div class="col-xs-12 wa-auto-margin">
		<div class="row">
			<div class="col-sm-6 col-xs-12 wa-search-eg-box wa-search-eg-box-whitepapers">
				<form id="whoisform" name="whoisform" action="{{ URL::to('whoislookup.php') }}">
					<div class="form-group has-feedback wa-search-box">
						<input type="text" class="form-control wa-search wa-search-whitepapers"  name="domainName" id="wa-search-whoislookup" placeholder="Whois Lookup">
						<span class="glyphicon glyphicon-search form-control-feedback wa-cursor" id="wa-search-icon-whoislookup"></span>
						<div class="wa-exapple wa-example-whitepapers">Example: google.com or 74.125.45.100</div>
						<div class="wa-radio-inputs wa-radio-xml-Josn wa-radio-xml-Josn-whitepapers pull-right">
							<div class="wa-radio-input wa-radio-input-xml wa-radio-input-whitepapers">
								<input type="radio" value="xml" checked id="wa-radio-xml" class="wa-cursor wa-radio-orange-bar wa-field-input-selection wa-field-input-selection-whitepapers wa-api-res-type" name="outputFormat">
								<label for="wa-radio-xml" class="wa-cursor wa-field-value-selection wa-field-value-selection-home-XML wa-field-value-whitepapers" id="wa-lbl-XMl">XML</label>
								<div class="wa-home-radio-outerCircle">
									<div class="wa-home-radio-innerCircle"></div>
								</div>
							</div>
							<div class="wa-radio-input wa-radio-input-json wa-radio-input-whitepapers">
								<input type="radio" value="json" id="wa-radio-json" class="wa-cursor wa-radio-orange-bar wa-field-input-selection wa-field-input-selection-whitepapers wa-api-res-type" name="outputFormat">
								<label for="wa-radio-json" class="wa-cursor wa-field-value-selection  wa-field-value-selection-home-JSON wa-field-value-selection-whitepapers" id="wa-lbl-JSON">JSON</label>
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
			<div class="col-sm-6 col-xs-12 wa-btn wa-btn-whitepapers">
				<div class="row">
					<div class="col-sm-offset-4 col-sm-4 col-xs-12 wa-btn-order">
						<a href={{ URL::to('order_paypal.php') }}><button type="button" class="btn btn-default wa-btn-orderNow center-block" id="wa-btn-home-orderNow">ORDER NOW</button></a>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="row wa-page-title-content-bg">
	<div class="col-xs-12 wa-about-whoisApi wa-auto-margin">
		<h1 class="text-center wa-title wa-title-whitepapers">White Papers </h1>
		<div class="text-center wa-content-text wa-content-text-whoisapi wa-content-text-whitepapers">
			View white papers on Cybersecurity and Whois API Solutions.
		</div>
	</div>
</div>
<div id="wa-page-content">
	<div class="row wa-content-bg">
		<div class="col-xs-12 wa-box-width-xs wa-auto-margin wa-col-xs-no-padding">
			<div class="wa-box wa-top-content-margin wa-bottom-content-margin wa-box-xs-padding wa-margin-whitepapers">
				<h2 class="wa-section-title wa-section-title-whoisapi  text-center">White Papers Published by Whois API LLC</h2>
				<div class="wa-content-text wa-content-text-whoisapi wa-content-text-cyber-whitepapers text-center">
					<span class="wa-link wa-cursor wa-textDecoration"><a href="https://www.whoisxmlapi.com/white-papers/CYBERSECURITY%20INVESTIGATION%20AND%20ANALYSIS%20-%20WhoisAPI%20Solution.pdf" target="_top">CYBERSECURITY INVESTIGATION AND ANALYSIS - WhoisAPI Solution</a></span>
				</div>
			</div>
		</div>
	</div>
</div>
@stop