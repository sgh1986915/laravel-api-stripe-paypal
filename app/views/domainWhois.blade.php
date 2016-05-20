<?php
$libPath = base_path(). "/whoisxmlapi_4";
?>
@extends('layouts.master')

@section('title')
Domain Availability API - Domain Names | Whois API
@stop

@section('description')
Domain Availability API tells you if a domain is available to be registered or not. It checks domain name availability quickly and accurately for all available tlds.
@stop

@section('styles')
@parent
{{ HTML::style('css/domainwhois.css') }}
@stop

@section('content')
<div class="row wa-searchbox-radio">
	<div class="col-xs-12 wa-auto-margin">
		<div class="row">
			<div class="col-sm-6 col-xs-12 wa-search-eg-box wa-search-eg-box-domainWhois">
				<form id="whoisform" name="whoisform" action="{{ URL::to('whoislookup.php') }}">
					<div class="form-group has-feedback wa-search-box wa-search-box-domainWhois">
						<input type="text" class="form-control wa-search wa-search-domainWhois"  name="domainName" id="wa-search-whoislookup" placeholder="Whois Lookup">
						<span class="glyphicon glyphicon-search form-control-feedback wa-cursor" id="wa-search-icon-whoislookup"></span>
						<div class="wa-exapple wa-example-domainWhois">Example: google.com or 74.125.45.100</div>
						<div class="wa-radio-inputs wa-radio-xml-Josn wa-radio-xml-Josn-domainWhois">
							<div class="wa-radio-input wa-radio-input-xml wa-radio-input-domainWhois">
								<input type="radio" value="xml" checked id="wa-radio-xml" class="wa-cursor wa-radio-orange-bar wa-field-input-selection wa-field-input-selection-domainWhois wa-api-res-type" name="outputFormat">
								<label for="wa-radio-xml" class="wa-cursor wa-field-value-selection wa-field-value-selection-domainWhois" id="wa-lbl-XMl-domainWhois">XML</label>
								<div class="wa-home-radio-outerCircle">
									<div class="wa-home-radio-innerCircle"></div>
								</div>
							</div>
							<div class="wa-radio-input wa-radio-input-json wa-radio-input-domainWhois">
								<input type="radio" value="json" id="wa-radio-json" class="wa-cursor wa-radio-orange-bar wa-field-input-selection wa-field-input-selection-domainWhois  wa-api-res-type" name="outputFormat">
								<label for="wa-radio-json" class="wa-cursor wa-field-value-selection  wa-field-value-selection-home-JSON wa-field-value-domainWhois" id="wa-lbl-JSON">JSON</label>
								<div class="wa-home-radio-outerCircle wa-home-radio-domainWhois">
									<div class="wa-home-radio-innerCircle wa-home-radio-domainWhois" style="display: none;"></div>
								</div>
							</div>
						</div>
						<div id="wa-user-stats" class="wa-user-stats"><?php include_once $libPath . "/user_stats.php"; ?>
						</div>
					</div>
				</form>
			</div>
			<div class="col-sm-6 col-xs-12 wa-btn wa-btn-domainWhois">
				<div class="row">
					<div class="col-sm-offset-4 col-sm-4 col-xs-12 wa-btn-order">
						<a href={{ URL::to('order_paypal.php') }}><button type="button" class="btn btn-default wa-btn wa-btn-orderNow wa-btn-orderNow-domainWhois center-block" id="wa-btn-orderNow-domainWhois">ORDER NOW</button></a>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="row wa-page-title-content-bg wa-page-title-content-bg-domainWhois ">
	<div class="col-xs-12 wa-about-whoisApi wa-auto-margin">
		<h1 class="text-center wa-title wa-title-domainWhois">Domain Availability API</h1>
		<div class="wa-content-text wa-content-spacing wa-content-text-domainWhois wa-page-description wa-page-description-domainWhois text-center">
		Domain Availability API is the most accurate domain availability checker available to the public. Checks whether a domain is available to be registered and returns result in XML/JSON.
		</div>
	</div>
</div>
<div id="wa-page-content">
	<div class="row wa-content-bg wa-content-bg-domainWhois">
		<div class="col-xs-12 wa-col-xs-no-padding wa-box-width-xs wa-box-margin-whoisapi wa-auto-margin">
			<div class="wa-box wa-box-domainWhois wa-box-xs-padding wa-top-content-margin">
				<h3 class="wa-section-title wa-section-title-domainwhois wa-section-title-domaincheck-domainWhois text-center">Domain Availability Check</h3>
				<div class="wa-content-text wa-content-text-domainwhois wa-content-spacing wa-content-text-domaincheck-domainWhois text-center">
					If you require domain name availability information, rely on our service. Our domain availability check service returns well-parsed information about domain availability in popular formats (XML & JSON) per http request. The API works for a majority of the TLDs. The first 100 domain names availability lookups are offered for free and you just need to <span class="wa-link wa-cursor wa-textDecoration">{{ HTML::link('user/create.php', 'register free developer account.') }}</span> View <span class="wa-link wa-cursor wa-textDecoration">{{ HTML::link('hosted_pricing.php', 'pricing chart') }}</span> for advanced offerings or <span class="wa-link wa-cursor wa-textDecoration">{{ HTML::link('order_paypal.php', 'Order Now.') }}</span>
				</div>
			</div>
		</div>
		<div class="col-xs-12 wa-col-xs-no-padding wa-auto-margin">
			<div class="row">
				<div class="col-sm-6 col-xs-12 wa-box-width-xs wa-box-margin-whoisapi">
					<div class="wa-box wa-box-xs-padding wa-box-domainWhois wa-box-webserviceCall-domainWhois">
						<h3 class="text-center wa-section-title wa-section-title-domainwhois wa-section-title-webservice-domainWhois">
						How to make a webservice call to Domain Availability API?
						</h3>
						<div class="wa-content-text wa-content-text-domainWhois wa-url-webservice-domainwhois wa-content-spacing">										https://www.whoisxmlapi.com/whoisserver/WhoisService?cmd=GET_DN_AVAILABILITY&domainName=test.com&username=xxxxx&password=xxxxx
						</div>
						<div>
							{{ HTML::image('images/dna_snap.jpg', 'Responsive image', array('class'=>'img-responsive wa-img-domain')) }}
						</div>
						<div class="wa-content-text">
							<div class="wa-domain">
								<div>input parameters: </div>
								<div>cmd = GET_DN_AVAILABILITY  (required)</div>
								<div>domainName = (required)</div>
								<div>userName</div>
								<div>password</div>
								<div>getMode = DNS_AND_WHOIS | DNS_ONLY (default: DNS_ONLY)</div>
								<div>the default getMode DNS_ONLY is the quickest way, </div>
								<div>DNS_AND_WHOIS mode is slower but the most accurat way.</div>
								<div>outputFormat = XML | JSON (default: XML)</div>
							</div>
						</div>
					</div>
				</div>
				<div class="col-sm-6 col-xs-12 wa-box-width-xs wa-box-margin-whoisapi wa-box-margin-domainwhois">
					<div class="wa-box wa-box-xs-padding wa-box-warning-domainWhois wa-box-warning-domainWhois">
						<h3 class="text-center wa-section-title wa-section-title-domainwhois wa-section-title-warning-domainWhois ">
							Will I receive warning if my account balance is low or zero?
						</h3>
						<div class="wa-content-text wa-content-text-domainwhois wa-content-warning-domainWhois wa-content-spacing ">Yes, the system will send you a warning email when your account balance falls below a pre-determined level. Default is 10 and it is customizable. When account balance is zero, another warning email is sent. To set the warning level, go to
						<div class="wa-url-domainWhois">          https://www.whoisxmlapi.com/accountServices.php?servicetype=accountUpdate&username=xxxxx&password=xxxxx&warn_threshold=30
						</div>
						</div>
						<div>
							<ul class="list-unstyled wa-list-unstyled">
								<li class="wa-content-text wa-ourFeatures-lists  wa-warningList-domainWhois"><span class="wa-list-no">01</span><span class="wa-ourFeatures-lbl wa-lbl-warningList-domainWhois">warn_threshold = the account balance at which a warning email will be sent to you</br>
									value: a positive number</br>
									default value: 10</span>
								</li>
								<li class="wa-content-text wa-ourFeatures-lists wa-warningList-domainWhois"><span class="wa-list-no">02</span><span class="wa-ourFeatures-lbl wa-lbl-warningList-domainWhois">warn_threshold_enabled = indicate whether a warning letter should be sent to you when the account balance reaches warn_threshold</br>
									positive values: 1, true, on</br>
									negative values: 0, false, off</br>
									default value: 1</span>
								</li>
								<li class="wa-content-text wa-ourFeatures-lists  wa-warningList-domainWhois"><span class="wa-list-no">03</span><span class="wa-ourFeatures-lbl wa-lbl-warningList-domainWhois">warn_empty_enabled = indicate whether a warning letter should be sent to you when the account balance reaches 0</br>
									positive values: 1, true, on</br>
									negative values: 0, false, off</br>
									default value: 1</span>
								</li>
							</ul>
						</div>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-sm-6 col-xs-12 wa-box-margin-whoisapi wa-box-width-xs ">
					<div class="wa-box wa-box-xs-padding wa-box-domainWhois wa-box-query-domainWhois">
						<h3 class="text-center wa-section-title wa-section-title-query-domainWhois">How to query for my account balance?</h3>
						<div class="wa-content-text wa-content-text-domainWhois wa-content-query-domainWhois wa-content-spacing text-center">
							https://www.whoisxmlapi.com/accountServices.php?servicetype=accountbalance&username=xxxxx&password=xxxxx
						</div>
					</div>
				</div>
				<div class="col-sm-6 col-xs-12 wa-box-margin-whoisapi wa-box-margin-domainwhois wa-box-width-xs">
					<div class="wa-box wa-box-xs-padding wa-box-domainWhois wa-box-makequery-domainWhois">
						<h3 class="text-center wa-section-title wa-section-title-domainWhois wa-section-title-language-domainwhois">
							How can I make query in java or php?
						</h3>
						<div class="wa-content-text wa-content-text-domainWhois wa-content-language-domainWhois wa-content-spacing text-center">
							Find here the <span class="wa-link wa-cursor wa-textDecoration">{{ HTML::link('https://www.whoisxmlapi.com/code/java/DomainAvailabilityAPIQuery.java', 'sample Java code') }} </span> of making a query to Domain Availability API web service in java using <span class="wa-link wa-cursor wa-textDecoration">{{ HTML::link('http://hc.apache.org/', 'Apache Http Component.') }}</span> Download here the <span class="wa-link wa-cursor wa-textDecoration">{{ HTML::link('https://www.whoisxmlapi.com/code/java/WhoisAPITest.zip', 'complete netbean project') }} </span> with the necessary libraries.Here is the <span class="wa-link wa-cursor wa-textDecoration">{{ HTML::link('https://www.whoisxmlapi.com/code/php/domain_availability_api_test.txt', 'sample PHP code') }}</span> of of making a query.
						</div>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-sm-6 col-xs-12 wa-box-margin-whoisapi  wa-box-margin-domainWhois wa-box-width-xs">
					<div class="wa-box wa-box-xs-padding wa-box-domainWhois wa-box-http-domainWhois">
						<div class="text-center wa-section-title wa-section-title-domainWhois wa-section-title-http-domainWhois">
							Can https be used?
						</div>
						<div class="wa-content-text wa-content-text-domainWhois wa-content-http-domainWhois wa-content-spacing text-center">
							Yes, you can use https in place of http, the connection will be more secure but slower.
						</div>
					</div>
				</div>
				<div class="col-sm-6 col-xs-12 wa-box-margin-whoisapi wa-box-margin-whoisapi wa-box-width-xs">
					<div class="wa-box wa-box-xs-padding wa-box-domainWhois wa-box-support-domainWhois">
						<div class="text-center wa-section-title wa-section-title-domainWhois wa-section-title-support-dominWhois">What TLDs (gTLDs and ccTLDs) do you support?</div>
							<div class="wa-content-text wa-content-text-domainWhois wa-content-support-domainWhois wa-content-spacing text-center">
							Please check the <span class="wa-link wa-cursor wa-textDecoration">{{ HTML::link('https://www.whoisxmlapi.com/support/supported_tlds.php', 'list of TLDs') }}</span> that we support.
							</div>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-sm-6 col-xs-12 wa-box-margin-whoisapi wa-box-margin-domainWhois wa-box-width-xs ">
					<div class="wa-box wa-box-xs-padding wa-box-domainWhois wa-box-domainName-domainWhois">
						<div class="text-center wa-section-title wa-section-title-domainWhois wa-section-title-xml-domainWhois">
							Is there an xml schema for the domain name availability api result?
						</div>
						<div class="wa-content-text wa-content-text-domainWhois wa-content-xml-domainWhois wa-content-spacing text-center">
							Yes, download here the <span class="wa-link wa-cursor wa-textDecoration">{{ HTML::link('https://www.whoisxmlapi.com/documentation/DomainInfoSchema.xsd', 'xml schema') }} </span> and a <span class="wa-link wa-cursor wa-textDecoration">{{ HTML::link('https://www.whoisxmlapi.com/', 'sample xml result.')}}</span>
						</div>
					</div>
				</div>
				<div class="col-sm-6 col-xs-12 wa-box-margin-whoisapi wa-box-margin-domainWhois wa-box-width-xs ">
					<div class="wa-box wa-box-xs-padding wa-box-domainWhois wa-box-service-domainWhois">
						<div class="text-center wa-section-title wa-section-title-domainWhois wa-section-title-tos-domainWhois">
							Is there a term of service for using whois API/Domain Availability Check?
						</div>
						<div class="wa-content-text wa-content-text-domainWhois wa-content-tos-domainWhois wa-content-spacing text-center">
						Please view the <span class="wa-link wa-cursor wa-textDecoration">{{ HTML::link('terms-of-service.php', 'Terms of Service') }}</span> here.
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
@stop






