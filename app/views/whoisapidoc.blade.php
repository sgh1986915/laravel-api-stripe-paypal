<?php
$libPath = base_path(). "/whoisxmlapi_4";
?>
@extends('layouts.master')

@section('title')
Whois API
@stop

@section('styles')
@parent
{{ HTML::style('css/whoisapidoc.css') }}
@stop

@section('content')
	<div class="main-content">
		<div class="row wa-searchbox-radio">
			<div class="col-xs-12 wa-auto-margin">
				<div class="row">
					<div class="col-sm-6 col-xs-12 wa-search-eg-box wa-search-eg-box-whoisApi">
						<form id="whoisform" name="whoisform" action="{{ URL::to('whoislookup.php') }}">
							<div class="form-group has-feedback wa-search-box wa-search-box-whoisApi">
								<input type="text" class="form-control wa-search wa-search-whoisApi" name="domainName" id="wa-search-whoislookup" placeholder="Whois Lookup">
								<span class="glyphicon glyphicon-search form-control-feedback wa-cursor" id="wa-search-icon-whoislookup"></span>
								<div class="wa-exapple wa-example-whoisApi">Example: google.com or 74.125.45.100</div>
								<div class="wa-radio-inputs wa-radio-xml-Josn wa-radio-xml-json-whoisApi">
									<div class="wa-radio-input wa-radio-input-xml wa-radio-input-whois">
										<input type="radio" value="xml" checked id="wa-radio-xml" class="wa-cursor wa-radio-orange-bar wa-field-input-selection wa-field-input-selection-whoisApi wa-api-res-type" name="outputFormat">
										<label for="wa-radio-xml" class="wa-cursor wa-field-value-selection  wa-field-value-selection-whoisApi" id="wa-lbl-XMl">XML</label>
										<div class="wa-home-radio-outerCircle">
											<div class="wa-home-radio-innerCircle"></div>
										</div>
									</div>
									<div class="wa-radio-input wa-radio-input-json wa-radio-input-whois">
										<input type="radio" value="json" id="wa-radio-json" class="wa-cursor wa-radio-orange-bar wa-field-input-selection wa-field-input-selection-whoisApi wa-api-res-type" name="outputFormat">
										<label for="wa-radio-json" class="wa-cursor wa-field-value-selection  wa-field-value-selection-WhoisApi" id="wa-lbl-JSON">JSON</label>
										<div class="wa-home-radio-outerCircle wa-home-radio-outerCircle-whoisApi">
											<div class="wa-home-radio-innerCircle wa-home-radio-innerCircle-whoisApi" style="display: none;"></div>
										</div>
									</div>
								</div>
								<div id="wa-user-stats" class="wa-user-stats"><?php include_once $libPath . "/user_stats.php"; ?>
								</div>
							</div>
						</form>
					</div>
					<div class="col-sm-6 col-xs-12 wa-btn wa-btn-whoisApi">
						<div class="row">
							<div class="col-sm-offset-4 col-sm-4 col-xs-12 wa-btn-order">
								<a href={{ URL::to('order_paypal.php') }}><button type="button" class="btn btn-default wa-btn-orderNow wa-btn-orderNow-WhoisApi center-block" id="wa-btn-orderNow-whoisApi">ORDER NOW</button></a>
							</div>
						</div>
					</div>

				</div>
			</div>
		</div>
		<div class="row wa-page-title-content-bg wa-page-title-content-bg-whoisApi">
			<div class="col-xs-12 wa-about-whoisApi wa-xs wa-auto-margin">
				<h1 class="text-center wa-title wa-title-whoisapi">Whois API</h1>
			</div>
		</div>
		<div id="wa-page-content">
			<div class="row wa-content-bg wa-content-bg-whoisApi">
				<div class="col-xs-12 wa-box-width-xs wa-box-width-whoisApi wa-auto-margin wa-col-xs-no-padding">
					<div class="wa-box wa-top-content-margin wa-box-xs-padding">
						<h2 class="wa-section-title wa-section-title-whoisapi wa-section-title-whoisWebservice-whoisapi text-center">How to use hosted whois webservice</h2>
						<div class="text-center wa-content-text wa-content-text-whoisapi wa-content-whoisWebservice-whoisapi">
							Our Hosted Whois Web Service can be used to query domain name or ip address to return its whois record in XML or JSON. Both parsed fields and raw texts are included in the resulting whois record.
						</div>
					</div>
				</div>
				<div class="col-xs-12  wa-auto-margin wa-col-xs-no-padding">
					<div class="row">
						<div class="col-sm-6 col-xs-12 wa-box-width-xs wa-box-margin-makeWebservice-whoisApi">
							<div class="wa-box wa-box-xs-padding wa-box-call-whoisApi">
								<h2 class="wa-section-title wa-section-title-whoisapi  a-section-title-webserviceCall-whoisapi text-center">How to make webservice call to whois API?</h2>
								<div class="wa-url-whoisapi">http://www.whoisxmlapi.com/whoisserver/WhoisService?domainName=google.com&username=xxxxx&password=xxxxx </div>
								{{ HTML::image('images/codeCall.png', 'Responsive image', array('class'=>'img-responsive wa-img-code-whoisapi')) }}
								<div>
									<div class="wa-content-text wa-content-spacing wa-content-text-whoisapi wa-content-warning-whoisapi"> <b>additional input parameters:</b>
										<div>outputFormat = XML|JSON  (defaults to XML)</div>
										<div>  da = 0|1|2 (defaults to 0) 1 results in a quick check on</div>
										<div>domain availability, 2 is a slower but more accurate check on</div>
										<div>domain availability.</div>
										<div>ip = 0|1 (defaults to 0) 1 results in returning ips for the domain name.</div>
										<div>checkProxyData = 0|1 (defaults to 0) 1 results in checking to see if the whois record contains proxy/whois guard data. The corresponding response field is WhoisRecord->privateWhoisProxy</div>
										<div>callback = a javascript callback function used when</div>
										<div>outputFormat is JSON.  This is an impelmentation known as JSONP.</div>
										<div>It invokes the callback function on the returned JSON response.</div>
										<div>thinWhois = 0|1 (defaults to 0) 1 results in returning whois</div>
										<div>data from registry only without fetching whois data from</div>
										<div>registrar.  In schema registry data is returned under</div>
										<div>WhoisRecord-&gt;registryData.</div>
										<div>_parse = 0|1 (defaults to 0) 1 provides parsing for input whois raw texts.</div>
										<div>registryRawText = String represents the registry whois raw text to be parsed.</div>
										<div>registrarRawText = String reprents the registrar whois raw text to be parsed.</div>

									</div>
								</div>
							</div>
						</div>
						<div class="col-sm-6 col-xs-12  wa-box-width-xs">
							<div class="row">
								<div class="col-xs-12">
									<div class="wa-box wa-box-xs-padding wa-box-balance-whoisApi">
										<h2 class="wa-section-title wa-section-title-whoisapi wa-section-title-warning-whoisapi text-center">Will you warn me if my account balance is low or zero?</h2>
										<div class="text-center wa-content-text wa-content-text-whoisapi wa-content-warning-whoisapi">
										Yes, you will receive a warning email when your account balance falls below a level(default to be 10 and customizable by you). You will receive another warning email when your account balance reaches 0. To set the warning level, go to</div>
										<div class="wa-content-text wa-content-text-whoisapi wa-contentLink-warning-whoisapi">http://www.whoisxmlapi.com/accountServices.php?servicetype=accountUpdate&username=xxxxx&password=xxxxx&warn_threshold=30</div>
										<div class="wa-content-text wa-content-text-whoisapi">supported input parameters are:</div>

										<ul class="list-unstyled">
											<li class="wa-content-text wa-ourFeatures-lists  wa-warningList-whoisapi"><span class="wa-list-no wa-list-no-whoisApi">01</span><span class="wa-ourFeatures-lbl wa-list-whoisApi">warn_threshold = the account balance at which a warning email will be sent to you value: a positive number default value: 10</span></li>
											<li class="wa-content-text wa-ourFeatures-lists  wa-warningList-whoisapi"><span class="wa-list-no wa-list-no-whoisApi">02</span><span class="wa-ourFeatures-lbl wa-list-whoisApi">warn_threshold_enabled = indicate whether a warning letter should be sent to you when the account balance reaches warn_threshold
											positive values: 1, true, on
											negative values: 0, false, off
											default value: 1</span></li>
											<li class="wa-content-text wa-ourFeatures-lists  wa-warningList-whoisapi"><span class="wa-list-no wa-list-no-whoisApi">03</span><span class="wa-ourFeatures-lbl wa-list-whoisApi">warn_empty_enabled = indicate whether a warning letter should be sent to you when the account balance reaches 0
											positive values: 1, true, on
											negative values: 0, false, off
											default value: 1</span></li>
											<li class="wa-content-text wa-ourFeatures-lists  wa-warningList-whoisapi"><span class="wa-list-no wa-list-no-whoisApi">04</span><span class="wa-ourFeatures-lbl wa-list-whoisApi">output_format=JSON | XML(default)</span></li>
										</ul>
									</div>
								</div>
								<div class="col-xs-12 wa-box-width-xs">
									<div class=" wa-box wa-box-xs-padding wa-box-query-whoisApi">
										<h2 class="wa-section-title wa-section-title-whoisapi  text-center ">How to query for my account balance?</h2>
										<div class="text-center wa-content-text wa-content-text-whoisapi wa-query-url-whoisapi">http://www.whoisxmlapi.com/accountServices.php?servicetype=accountbalance&username=xxxxx&password=xxxxx </div>
									</div>
								</div>
								<div class="col-xs-12 wa-box-width-xs">
									<div class="wa-box wa-box-xs-padding wa-box-webservice-whoisApi">
										<h2 class="wa-section-title wa-section-title-whoisapi wa-section-title-webservice-whoisapi text-center">How to make query to Whois API Webservice in Java, PHP, .Net(C#), Ruby, Python, or Javascript?</h2>

										<div class=" wa-content-text wa-content-text-whoisapi wa-code-list-whoisapi wa-content-javaCode-whoisapi"><span class="wa-eg">Java code: </span><span class="wa-link wa-cursor wa-textDecoration">{{ HTML::link('https://www.whoisxmlapi.com/code/java/SimpleQuery.java', 'Example1') }}</span> <span class="wa-link wa-cursor wa-textDecoration">{{ HTML::link('https://www.whoisxmlapi.com/code/java/DomainAvailabilityAPIQuery.java', 'Example2') }}</span> <span class="wa-link wa-cursor wa-textDecoration">{{ HTML::link('https://www.whoisxmlapi.com/code/java/WhoisAPITest.zip', 'Netbean project files') }}</span> with <span class="wa-link wa-cursor wa-textDecoration"> {{ HTML::link('http://hc.apache.org/user-docs.html', 'Aapache http component') }}</span></div>
										<div class=" wa-content-text wa-content-text-whoisapi wa-code-list-whoisapi wa-content-phpCode-whoisapi"><span class="wa-eg">PHP code: </span><span class="wa-link wa-cursor wa-textDecoration">{{ HTML::link('https://www.whoisxmlapi.com/code/php/whois_api_test1.txt', 'Example1') }}</span> <span class="wa-link wa-cursor wa-textDecoration">{{ HTML::link('https://www.whoisxmlapi.com/code/php/whois_api_test2.txt', 'Example2') }}</span> <span class="wa-link wa-cursor wa-textDecoration">{{ HTML::link('https://www.whoisxmlapi.com/code/php/domain_availability_api_test.txt', 'Example3') }}</span></div>
										<div class="wa-content-text wa-content-text-whoisapi wa-code-list-whoisapi wa-content-netCode-whoisapi"><span class="wa-eg">.NET(c#)Code: </span><span class="wa-link wa-cursor wa-textDecoration">{{ HTML::link('https://www.whoisxmlapi.com/code/dot_net/WhoisCSharp/whois.cs', 'Example1') }}</span> <span class="wa-link wa-cursor wa-textDecoration">{{ HTML::link('https://www.whoisxmlapi.com/code/dot_net/WhoisCSharp/visualStudio.zip', 'Visual Studio solution') }}  </span></div>
										<div class="wa-content-text wa-content-text-whoisapi wa-code-list-whoisapi wa-content-rubyCode-whoisapi"><span class="wa-eg">Ruby code: </span><span class="wa-link wa-cursor wa-textDecoration">{{ HTML::link('https://www.whoisxmlapi.com/code/ruby/whois.txt', 'Example1') }}</span></div>
										<div class="wa-content-text wa-content-text-whoisapi wa-code-list-whoisapi wa-content-pythonCode-whoisapi"><span class="wa-eg">Python code: </span><span class="wa-link wa-cursor wa-textDecoration">{{ HTML::link('https://www.whoisxmlapi.com/code/python/whois.txt', 'Example1') }}</span></div>
										<div class="wa-content-text wa-content-text-whoisapi  wa-code-list-whoisapi wa-content-jsCode-whoisapi"><span class="wa-eg">Javascript Code: </span><span class="wa-link wa-cursor wa-textDecoration">{{ HTML::link('https://www.whoisxmlapi.com/code/javascript/whois.txt', 'Example1') }}</span> <span class="wa-link wa-cursor wa-textDecoration">{{ HTML::link('https://www.whoisxmlapi.com/code/javascript/whois_jquery_jsonp.html.txt', 'Example2') }}</span></div>
										<div class="wa-content-text wa-content-text-whoisapi  wa-code-list-whoisapi wa-content-perlCode-whoisapi"><span class="wa-eg">Perl Code: </span><span class="wa-link wa-cursor wa-textDecoration">{{ HTML::link('https://www.whoisxmlapi.com/code/perl/whois.txt', 'Example1') }}</span></div>
									</div>
								</div>
								<div class=" col-xs-12 wa-box-width-xs">
									<div class="wa-box wa-box-xs-padding wa-box-https-whoisApi">
										<h2 class="wa-section-title wa-section-title-whoisapi  wa-section-title-https-whoisapi text-center">Can I use https?</h2>
										<div class="text-center wa-content-text wa-content-text-whoisapi wa-content-text-https-whoisapi">Yes, simply use https instead of http, the connection would be more secure but slower. </div>
									</div>
								</div>
								<div class="col-xs-12 wa-box-width-xs">
									<div class="wa-box wa-box-xs-padding wa-tlds-box-whoisapi wa-box-tlds-whoisApi">
										<h2 class="wa-section-title wa-section-title-whoisapi  wa-section-title-tlds-whoisapi text-center">What TLDs (gTLDs and ccTLDs) do you support?</h2>
										<div class="text-center wa-content-text wa-content-text-whoisapi wa-content-text-tlds-whoisapi">Yes, please check the <span class="wa-link wa-cursor wa-textDecoration">{{ HTML::link('https://www.whoisxmlapi.com/support/supported_tlds.php', 'list of TLDs') }}</span> that we support.</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="row">
					</div>
					<div class="row">
						<div class="col-sm-6 col-xs-12 wa-box-width-xs wa-xmlSchema-whoisapi">
							<div class="wa-box wa-box-xs-padding wa-box-scheme-whoisApi">
								<h2 class="wa-section-title wa-section-title-whoisapi wa-section-title-schema-whoisapi text-center">Is there a xml schema/documentation for the whois query result?</h2>
								<div class="text-center wa-content-text wa-content-text-whoisapi wa-content-text-schema-whoisapi">Yes, please download the <span class="wa-link wa-cursor wa-textDecoration"> {{HTML::link('https://www.whoisxmlapi.com/documentation/WhoisRecordSchema.xsd','xml schema,') }}</span> <span class="wa-link wa-cursor wa-textDecoration"> {{ HTML::link('https://www.whoisxmlapi.com/documentation/whoisapi_documentation/index.html','documentation on the query result') }}</span>, and a <span class="wa-link wa-cursor wa-textDecoration">{{ HTML::link('https://www.whoisxmlapi.com/documentation/WhoisRecord.xml','sample xml result')}}</span>.</div>
							</div>
						</div>
						<div class="col-sm-6 col-xs-12 wa-box-width-xs">
							<div class=" wa-box wa-box-xs-padding wa-tos-box-whoisapi">
								<h2 class="wa-section-title wa-section-title-whoisapi wa-section-title-tos-whoisapi text-center">Is there a term of service or SLA for using Whois API Webservice?</h2>
								<div class="text-center wa-content-text wa-content-text-whoisapi wa-content-text-tos-whoisapi">Please view the <span class="wa-link wa-cursor wa-textDecoration">{{ HTML::link('terms-of-service.php', 'Terms of Service') }}</span> here.</div>
							</div>
						</div>
					</div>
				</div>
			
			</div>
		</div>
	</div>
@stop