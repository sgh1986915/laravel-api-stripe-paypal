<?php
$libPath = base_path(). "/whoisxmlapi_4";
?>
@extends('layouts.master')

@section('title')
Support
@stop

@section('styles')
@parent
{{ HTML::style('css/support.css') }}
@stop

@section('content')
<div class="main-content">
	<div class="row wa-searchbox-radio">
		<div class="col-xs-12 wa-auto-margin">
				<div class="row">
					<div class="col-sm-6 col-xs-12 wa-search-eg-box wa-search-eg-box-support">
						<form id="whoisform" name="whoisform" action="{{ URL::to('whoislookup.php') }}">
							<div class="form-group has-feedback wa-search-box wa-search-box-support">
								<input type="text" class="form-control wa-search wa-search-support" name="domainName" id="wa-search-whoislookup" placeholder="Whois Lookup">
								<span class="glyphicon glyphicon-search form-control-feedback wa-cursor" id="wa-search-icon-whoislookup"></span>
								<div class="wa-exapple wa-example-support">Example: google.com or 74.125.45.100</div>
								<div class="wa-radio-inputs wa-radio-xml-Josn wa-radio-xml-Josn-support">
									<div class="wa-radio-input wa-radio-input-xml wa-radio-input-support">
										<input type="radio" value="xml" checked id="wa-radio-xml" class="wa-cursor wa-radio-orange-bar wa-field-input-selection wa-field-input-selection-support wa-api-res-type" name="outputFormat">
										<label for="wa-radio-xml" class="wa-cursor wa-field-value-selection wa-field-value-selection-home-XML wa-field-value-selection-support" id="wa-lbl-XMl">XML</label>
										<div class="wa-home-radio-outerCircle">
											<div class="wa-home-radio-innerCircle"></div>
										</div>
									</div>
									<div class="wa-radio-input wa-radio-input-json wa-radio-input-support">
										<input type="radio" value="json" id="wa-radio-json" class="wa-cursor wa-radio-orange-bar wa-field-input-selection wa-field-input-selection-support wa-api-res-type" name="outputFormat">
										<label for="wa-radio-json" class="wa-cursor wa-field-value-selection  wa-field-value-selection-home-JSON wa-field-value-support" id="wa-lbl-JSON">JSON</label>
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
					<div class="col-sm-6 col-xs-12 wa-btn wa-btn-support">
						<div class="row">
							<div class="col-sm-offset-4 col-sm-4 col-xs-12 wa-btn-order">
								<a href={{ URL::to('order_paypal.php') }}><button type="button" class="btn btn-default wa-btn-orderNow wa-btn-orderNow-brandalert center-block" id="wa-btn-home-orderNow">ORDER NOW</button></a>
							</div>
						</div>
					</div>
				</div>
		</div>
	</div>
	<div class="row wa-page-title-content-bg">
		<div class="col-xs-12 wa-about-whoisApi wa-xs wa-auto-margin">
			<h1 class="text-center wa-title wa-title-support">Support</h1>
		</div>
	</div>
	<div id="wa-page-content">
		<div class="row wa-content-bg">
			<div class="col-xs-12 wa-auto-margin wa-col-xs-no-padding wa-box-margin-support">
				<div class="wa-box wa-box-support wa-box-xs-padding wa-top-content-margin wa-bottom-content-margin">
					<div class="wa-content-text wa-content-text-support wa-section2-support">Our excellent development and customer service teams are here to answer your questions and service your needs 24/7.</div>
					<div>
						<ul class="list-unstyled">
							<li class="wa-content-text wa-ourFeatures-lists wa-warningList-whoisapi"><span class="wa-list-no">01</span><span class="wa-ourFeatures-lbl">For support issues, contact <span class="wa-link wa-cursor wa-textDecoration">{{ HTML::link('mailto:support@whoisxmlapi.com', 'support@whoisxmlapi.com') }}</span> and we will get back to you within a day. You may report a bug or submit a feature request via our <span class="wa-link wa-cursor wa-textDecoration">{{ HTML::link('http://bugtracker.whoisxmlapi.com/login_page.php', 'bug tracker') }}</span> (separate registration is required).</span></li>
							<li class="wa-content-text wa-ourFeatures-lists  wa-warningList-whoisapi"><span class="wa-list-no">02</span><span class="wa-ourFeatures-lbl">Need a customized whois service/product? contact <span class="wa-link wa-cursor wa-textDecoration">{{ HTML::link('mailto:dev@whoisxmlapi.com', 'dev@whoisxmlapi.com') }}</span></li>
							<li class="wa-content-text wa-ourFeatures-lists wa-warningList-whoisapi"><span class="wa-list-no">03</span><span class="wa-ourFeatures-lbl">For general inquiries and all other questions, contact <span class="wa-link wa-cursor wa-textDecoration">{{ HTML::link('mailto:general@whoisxmlapi.com', 'general@whoisxmlapi.com') }}</span></li>
						</ul>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
@stop