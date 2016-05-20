<?php
$libPath = base_path(). "/whoisxmlapi_4";
?>
@extends('layouts.master')

@section('title')
Contact Us
@stop

@section('styles')
@parent
{{ HTML::style('css/contactUs.css') }}
@stop

@section('content')
<div class="main-content">
	<div class="row wa-searchbox-radio">
		<div class="col-xs-12 wa-auto-margin">
				<div class="row">
					<div class="col-sm-6 col-xs-12 wa-search-eg-box wa-search-eg-box-contatcUS">
						<form id="whoisform" name="whoisform" action="{{ URL::to('whoislookup.php') }}">
							<div class="form-group has-feedback wa-search-box wa-search-box-contatcUS">
								<input type="text" class="form-control wa-search wa-search-contatcUS" name="domainName" id="wa-search-whoislookup" placeholder="Whois Lookup">
								<span class="glyphicon glyphicon-search form-control-feedback wa-cursor" id="wa-search-icon-whoislookup"></span>
								<div class="wa-exapple wa-example-contatcUS">Example: google.com or 74.125.45.100</div>
								<div class="wa-radio-inputs wa-radio-xml-Josn wa-radio-xml-Josn-contatcUS">
									<div class="wa-radio-input wa-radio-input-xml wa-radio-input-contatcUS">
										<input type="radio" value="xml" checked id="wa-radio-xml" class="wa-cursor wa-radio-orange-bar wa-field-input-selection wa-field-input-selection-contatcUS wa-api-res-type" name="outputFormat">
										<label for="wa-radio-xml" class="wa-cursor wa-field-value-selection wa-field-value-selection-home-XML wa-field-value-selection-contatcUS" id="wa-lbl-XMl">XML</label>
										<div class="wa-home-radio-outerCircle">
											<div class="wa-home-radio-innerCircle"></div>
										</div>
									</div>
									<div class="wa-radio-input wa-radio-input-json wa-radio-input-contatcUS">
										<input type="radio" value="json" id="wa-radio-json" class="wa-cursor wa-radio-orange-bar wa-field-input-selection wa-field-input-selection-contatcUS wa-api-res-type" name="outputFormat">
										<label for="wa-radio-json" class="wa-cursor wa-field-value-selection  wa-field-value-selection-home-JSON wa-field-value-contatcUS" id="wa-lbl-JSON">JSON</label>
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
					<div class="col-sm-6 col-xs-12 wa-btn wa-btn-contactUs">
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
			<h1 class="text-center wa-title wa-title-contactUs">Contact Us</h1>
		</div>
	</div>
	<div id="wa-page-content">
		<div class="row wa-content-bg">
			<div class="col-xs-12  wa-col-xs-no-padding wa-auto-margin wa-box-margin-whoisapi wa-box-margin-contatcUS">
				<div class="wa-box wa-box-xs-padding wa-top-content-margin wa-bottom-content-margin">
					<div class=" wa-content-text wa-content-text-contactUs">Interested in a strategic partnership? Interested in licensing the complete source code behind Whois API? Are you a registrar or business that need to setup and manage public whois servers? Do you need to outsource your organization's whois or domain management services? Please contact us for a customized quote
					</div>
					<div class=" wa-content-text wa-content-text-contactUs wa-section2-contactUs">Our excellent development and customer service teams are here to answer your questions and service your needs 24/7.</div>
					<div>
						<ul class="list-unstyled">
							<li class="wa-content-text wa-ourFeatures-lists wa-warningList-whoisapi"><span class="wa-list-no">01</span><span class="wa-ourFeatures-lbl">For support issues, contact <span class="wa-link wa-cursor wa-textDecoration">{{ HTML::link('mailto:support@whoisxmlapi.com', 'support@whoisxmlapi.com') }}</span> and we will get back to you within a day. You may report a bug or submit a feature request via our <span class="wa-link wa-cursor wa-textDecoration">{{ HTML::link('http://bugtracker.whoisxmlapi.com/login_page.php', 'bug tracker') }}</span> (separate registration is required).</span></li>
							<li class="wa-content-text wa-ourFeatures-lists  wa-warningList-whoisapi"><span class="wa-list-no">02</span><span class="wa-ourFeatures-lbl">Need a customized whois service/product? contact <span class="wa-link wa-cursor wa-textDecoration">{{ HTML::link('mailto:dev@whoisxmlapi.com', 'dev@whoisxmlapi.com') }}</span></li>
							<li class="wa-content-text wa-ourFeatures-lists wa-warningList-whoisapi"><span class="wa-list-no">03</span><span class="wa-ourFeatures-lbl">For general inqueries and all other questions, contact <span class="wa-link wa-cursor wa-textDecoration">{{ HTML::link('mailto:general@whoisxmlapi.com', 'general@whoisxmlapi.com') }}</span></li>
							<li class="wa-content-text wa-ourFeatures-lists wa-warningList-whoisapi"><span class="wa-list-no">04</span><span class="wa-ourFeatures-lbl"> <span class="wa-link wa-cursor wa-textDecoration">{{ HTML::link('https://www.whoisxmlapi.com/blog/', 'View our Blog here') }}</span></span></li>
						</ul>
					</div>
					
					<address class="wa-content-text wa-content-text-contactUs">
						<strong>Mailing Address</strong>
						<div itemscope itemtype="http://schema.org/LocalBusiness"> 
							<span itemprop="name">Whois API LLC</span>
							<div itemprop="address" itemscope itemtype="http://schema.org/PostalAddress">
								<span itemprop="streetAddress">340 S LEMON AVE, #1362,</span><br/>
								<span itemprop="addressLocality">WALNUT,</span> 
								<span itemprop="addressRegion">CA</span> 
								<span itemprop="postalCode">91789,</span> 
							</div>
						  <span itemprop="telephone"></span> 
						</div>
					</address>
				</div>
			</div>
		</div>
	</div>
</div>
@stop