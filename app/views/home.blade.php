<?php
$libPath = base_path(). "/whoisxmlapi_4";
?>
@extends('layouts.master')

@section('title')
Whois XML API
@stop

@section('styles')
@parent
{{ HTML::style('css/home.css') }}
@stop

@section('content')
<div class="row wa-searchbox-radio">
	<div class="col-xs-12 wa-auto-margin">
		<div class="row">
			<div class="col-sm-6 col-xs-12 wa-whois-xml-api-title-home">
				<div class="wa-unified-consistent">Unified & Consistent</div>
				<div class="whoisapi">Whois API & Whois</div>
				<div class="parserSystem">Parser System</div>
			</div>
			<div class="col-sm-6 wa-searchBox-home">
				<div class="row">
					<div class="col-xs-12">
						<form id="whoisform" name="whoisform" action="{{ URL::to('whoislookup.php') }}">
							<div class="form-group has-feedback wa-search-box">
								<input type="text" class="form-control wa-search wa-search-home" name="domainName" id="wa-search-whoislookup" placeholder="Whois Lookup">
								<span class="glyphicon glyphicon-search form-control-feedback wa-cursor" id="wa-search-icon-whoislookup"></span>
								<div class="wa-exapple">Example:google.com or 74.125.45.100</div>
								<div class="wa-radio-inputs wa-radio-xml-Josn wa-radio-xml-json-home">
									<div class="wa-radio-input  wa-radio-input-xml wa-radio-input-home">
										<input type="radio" value="xml" checked id="wa-radio-xml" class="wa-cursor wa-radio-orange-bar wa-field-input-selection wa-field-input-selection-radio wa-api-res-type" name="outputFormat">
										<label for="wa-radio-xml" class="wa-cursor wa-field-value-selection  wa-field-value-selection-home-XML" id="wa-lbl-XMl">XML</label>
										<div class="wa-home-radio-outerCircle">
											<div class="wa-home-radio-innerCircle"></div>
										</div>
									</div>
									<div class="wa-radio-input  wa-radio-input-json wa-radio-input-home">
										<input type="radio" value="json" id="wa-radio-json" class="wa-cursor wa-radio-orange-bar wa-field-input-selection wa-field-input-selection-radio wa-api-res-type" name="outputFormat">
										<label for="wa-radio-json" class="wa-cursor wa-field-value-selection  wa-field-value-selection-home-JSON" id="wa-lbl-JSON">JSON</label>
										<div class="wa-home-radio-outerCircle">
											<div class="wa-home-radio-innerCircle" style="display: none;"></div>
										</div>
									</div>
								</div>
								<div id="wa-user-stats" class="wa-user-stats"><?php @include_once $libPath . "/user_stats.php"; ?></div>
							</div>
						</form>
					</div>
					<div class="col-sm-offset-4 col-sm-4 col-xs-12">
						<a href={{ URL::to('order_paypal.php') }}><button type="button" class="btn btn-default wa-btn-orderNow wa-btn-orderNow-home center-block" id="wa-btn-home-orderNow">ORDER NOW</button></a>
					</div>
				</div>
			</div>
		</div>
	</div>

</div>
<div id="wa-page-content">
	<div class="row">
		<div class="col-xs-12 wa-auto-margin wa-about-whoisApi">
			<h1 class="text-center wa-title wa-title-home">ABOUT WHOIS API-HOSTED WHOIS WEBSERVICE</h1>
			<div class="text-center wa-content-text wa-content-whoisWebservice-home wa-margin">
				Whois API Hosted Webservice returns well-parsed whois fields to your application in popular formats (XML&JSON) per http request. Leave all the hard work to us, you need not worry about the query limit & restrictions imposed by the whois registrars. <span class="wa-link wa-cursor wa-textDecoration">{{ HTML::link('/user/create.php', ' Register your free developer account') }}</span>. now to get your first 500 whois lookups for free. See <span class="wa-link wa-cursor wa-textDecoration">{{ HTML::link('hosted_pricing.php', 'pricing chart') }}</span> for advanced offerings or <span class="wa-link wa-cursor wa-textDecoration">{{ HTML::link('order_paypal.php#ordernow-payplan', 'order now') }}</span>.
			</div>
		</div>
	</div>
	<!-- Our Products & Services-->
	<div class="row  wa-ourServices">
		<div class="col-xs-12 wa-auto-margin">
			<div class="row">
				<div class="col-xs-12 wa-auto-margin">
					<div class="row">
						<h2 class="col-xs-12 text-center wa-ourServices-heading wa-title-home wa-title">OUR PRODUCTS & SERVICES</h2>
					</div>
					<div id="carousel-example-generic" class="carousel slide wa-carousel-slide-home" data-ride="carousel">
						<!-- Wrapper for slides -->
						<ol class="carousel-indicators">
							<li data-target="#carousel-example-generic" data-slide-to="0" class="active"></li>
							<li data-target="#carousel-example-generic" data-slide-to="1"></li>
							<li data-target="#carousel-example-generic" data-slide-to="2"></li>
						 </ol>
						<div class="carousel-inner" role="listbox">
							<div class="item active">
								<div class="row wa-row-ourServices">
									<div class="col-sm-6 col-md-4 col-xs-12 wa-col-whoisApi wa-ourServices-col-padding">
										<a href={{ URL::to('home.php') }}>
											<div class="wa-ourServices-box wa-ourServices-firstRow text-center">
												{{ HTML::image('images/whois-webservice.png', 'Responsive image', array('class'=>'img-responsive wa-img-ourService wa-img-whois-webservice')) }}
												<h3 class="wa-lbl-heading wa-ourServices-lbl">Hosted Whois API Webservice</h3>
												<div class="wa-content-text wa-ourServices-content" id="wa-content-webservice">Provides consistent, well-structured data in whois XML & JSON. Keeps most updated, accurate whois data accessible to your application 24/7.</div>
											</div>
										</a>
									</div>
									<div class="col-sm-6 col-md-4 col-xs-12 wa-col-whoisdd wa-ourServices-col-padding">
										<a href={{ URL::to('whois-database-download.php') }}>
											<div class="wa-ourServices-box wa-ourServices-firstRow text-center">
												{{ HTML::image('images/whois-database.png', 'Responsive image', array('class'=>'img-responsive wa-img-ourService wa-img-whois-database')) }}<h3 class="wa-lbl-heading wa-ourServices-lbl">Whois Database Download</h3>
												<div class="wa-content-text wa-ourServices-content" id="wa-content-whois-database">Provides historic Whois Database download in both parsed and raw format as csv file.</div>
											</div>
										</a>
									</div>
									<div class="col-sm-6 col-md-4 col-xs-12  wa-ourServices-col-padding">
										<a href={{ URL::to('domain-availability-api.php') }}>
											<div class="wa-ourServices-box wa-ourServices-firstRow text-center">
												{{ HTML::image('images/domain-api.png', 'Responsive image', array('class'=>'img-responsive wa-img-ourService wa-img-domain-api')) }}
												<h3 class="wa-lbl-heading wa-ourServices-lbl">Domain Availability API</h3>
												<div class="wa-content-text wa-ourServices-content" id="wa-content-domain-api">...is the most accurate domain availability checker available to the public. Checks whether domain is available to be registered and returns result in XML/JSON.</div>
											</div>
										</a>
									</div>
									<div class="col-sm-6 col-md-4 col-xs-12 wa-col-bulkWhois wa-ourServices-col-padding">
										<a href={{ URL::to('bulk-whois-lookup.php') }}>
											<div class="wa-ourServices-box wa-ourServices-firstRow text-center">
												{{ HTML::image('images/whois-lookup.png', 'Responsive image', array('class'=>'img-responsive wa-img-ourService wa-img-whois-lookup')) }}
												<h3 class="wa-lbl-heading wa-ourServices-lbl">Bulk Whois Lookup</h3>
												<div class="wa-content-text wa-ourServices-content" id="wa-content-Whois-lookup">Use our fast-track service or software to check domain names and collect parsed whois data in bulk.</div>
											</div>
										</a>
									</div>
									<div class="col-sm-6 col-md-4 col-xs-12  wa-ourServices-col-padding">
										<a href={{ URL::to('reverse-whois.php') }}>
											<div class="wa-ourServices-box wa-ourServices-firstRow text-center">
												{{ HTML::image('images/reverse-search.png', 'Responsive image', array('class'=>'img-responsive wa-img-ourService wa-img-whois-api')) }}
												<h3 class="wa-lbl-heading wa-ourServices-lbl">Reverse whois Search</h3>
												<div class="wa-content-text wa-ourServices-content" id="wa-content-whois-api">Reverse Whois lets you find all the domain names registered in the name of any specific owner.</div>
											</div>
										</a>
									</div>
									<div class="col-sm-6 col-md-4 col-xs-12  wa-ourServices-col-padding">
										<a href={{ URL::to('newly-registered-domains.php') }}>
											<div class="wa-ourServices-box wa-ourServices-firstRow text-center">
												{{ HTML::image('images/new-domain.png', 'Responsive image', array('class'=>'img-responsive wa-img-ourService wa-imgwhois-service')) }}
												<h3 class="wa-lbl-heading wa-ourServices-lbl">Newly Registered Domains</h3>
												<div class="wa-content-text wa-ourServices-content" id="wa-content-whois-service">Setup and manage whois servers for registrars and businesses. Outsource part or all of whois services and the other domain management services to us.</div>
											</div>
										</a>
									</div>
								</div>
							</div>
							<div class="item">
								<div class="row wa-row-ourServices">
									<div class="col-sm-6 col-md-4 col-xs-12  wa-ourServices-col-padding">
										<a href={{ URL::to('whois-api-software.php') }}>
											<div class="wa-ourServices-box wa-ourServices-firstRow text-center">
												{{ HTML::image('images/whois-api.png', 'Responsive image', array('class'=>'img-responsive wa-img-ourService wa-img-whois-api')) }}
												<h3 class="wa-lbl-heading wa-ourServices-lbl">Whois API Software Package</h3>
												<div class="wa-content-text wa-ourServices-content" id="wa-content-whois-api">Use the exact same technology we are using for "Hosted Whois Webservice", this is the right choice for you if you believe you have the neccessary infrastructure...</div>
											</div>
										</a>
									</div>
									<div class="col-sm-6 col-md-4 col-xs-12  wa-ourServices-col-padding">
										<a href={{ URL::to('registrar-whois-services.php') }}>
											<div class="wa-ourServices-box wa-ourServices-firstRow text-center">
												{{ HTML::image('images/whois-service.png', 'Responsive image', array('class'=>'img-responsive wa-img-ourService wa-imgwhois-service')) }}
												<h3 class="wa-lbl-heading wa-ourServices-lbl">Registrar Whois Service</h3>
												<div class="wa-content-text wa-ourServices-content" id="wa-content-whois-service">Setup and manage whois servers for registrars and businesses. Outsource part or all of whois services and the other domain management services to us.</div>
											</div>
										</a>
									</div>
									<div class="col-sm-6 col-md-4 col-xs-12  wa-ourServices-col-padding">
										<a href={{ URL::to('whois-api-doc.php') }}>
											<div class="wa-ourServices-box wa-ourServices-firstRow text-center">
												{{ HTML::image('images/Whois Api.png', 'Responsive image', array('class'=>'img-responsive wa-img-ourService wa-imgwhois-service')) }}
												<h3 class="wa-lbl-heading wa-ourServices-lbl">Whois API</h3>
												<div class="wa-content-text wa-ourServices-content" id="wa-content-whois-service">Our Hosted Whois Web Service can be used to query domain name or ip address to return its whois record in XML or JSON.</div>
											</div>
										</a>
									</div>
									<div class="col-sm-6 col-md-4 col-xs-12  wa-ourServices-col-padding">
										<a href={{ URL::to('reverse-whois-api.php') }}>
											<div class="wa-ourServices-box wa-ourServices-firstRow text-center">
												{{ HTML::image('images/Reverse Whois Api.png', 'Responsive image', array('class'=>'img-responsive wa-img-ourService wa-imgwhois-service')) }}
												<h3 class="wa-lbl-heading wa-ourServices-lbl">Reverse Whois API</h3>
												<div class="wa-content-text wa-ourServices-content" id="wa-content-whois-service">Reverse Whois API provides a RESTful webservice for reverse search.</div>
											</div>
										</a>
									</div>
									<div class="col-sm-6 col-md-4 col-xs-12  wa-ourServices-col-padding">
										<a href={{ URL::to('reverse-ip-api.php') }}>
											<div class="wa-ourServices-box wa-ourServices-firstRow text-center">
												{{ HTML::image('images/Reverse IP API.png', 'Responsive image', array('class'=>'img-responsive wa-img-ourService wa-imgwhois-service')) }}
												<h3 class="wa-lbl-heading wa-ourServices-lbl">Reverse IP API</h3>
												<div class="wa-content-text wa-ourServices-content" id="wa-content-whois-service">Reverse IP API provides a RESTful webservice for Reverse IP lookup with XML and JSON response.</div>
											</div>
										</a>
									</div>
									<div class="col-sm-6 col-md-4 col-xs-12  wa-ourServices-col-padding">
										<a href={{ URL::to('brand-alert-api.php') }}>
											<div class="wa-ourServices-box wa-ourServices-firstRow text-center">
												{{ HTML::image('images/Brand Alert.png', 'Responsive image', array('class'=>'img-responsive wa-img-ourService wa-imgwhois-service')) }}
												<h3 class="wa-lbl-heading wa-ourServices-lbl">Brand Alert API</h3>
												<div class="wa-content-text wa-ourServices-content" id="wa-content-whois-service">The Brand Alert API searches new .com, .net, .org, .biz, .mobi, .us, .pro, .coop and .asia domain names for specific terms.</div>
											</div>
										</a>
									</div>

								</div>
							</div>
							<div class="item">
								<div class="row wa-row-ourServices">
									<div class="col-sm-6 col-md-4 col-xs-12  wa-ourServices-col-padding">
										<a href={{ URL::to('registrant-alert-api.php') }}>
											<div class="wa-ourServices-box wa-ourServices-firstRow text-center">
												{{ HTML::image('images/Registrant Alert.png', 'Responsive image', array('class'=>'img-responsive wa-img-ourService wa-imgwhois-service')) }}
												<h3 class="wa-lbl-heading wa-ourServices-lbl">Registrant Alert API</h3>
												<div class="wa-content-text wa-ourServices-content" id="wa-content-whois-service">The The Registrant Alert API searches through newly registered and changed domain names to find whois records matching the alert terms.</div>
											</div>
										</a>
									</div>
									<div class="col-sm-6 col-md-4 col-xs-12  wa-ourServices-col-padding">
										<a href={{ URL::to('http://whois.whoisxmlapi.com/') }}>
											<div class="wa-ourServices-box wa-ourServices-firstRow text-center">
												{{ HTML::image('images/Whois Lookup.png', 'Responsive image', array('class'=>'img-responsive wa-img-ourService wa-img-whois-api')) }}
												<h3 class="wa-lbl-heading wa-ourServices-lbl">Whois Lookup</h3>
												<div class="wa-content-text wa-ourServices-content" id="wa-content-whois-api">Whois Lookup provides you all the information associated with a desired domain.</div>
											</div>
										</a>
									</div>
									<div class="col-sm-6 col-md-4 col-xs-12  wa-ourServices-col-padding">
										<a href={{ URL::to('reverse-ip.php') }}>
											<div class="wa-ourServices-box wa-ourServices-firstRow text-center">
												{{ HTML::image('images/Reverse Lookup.png', 'Responsive image', array('class'=>'img-responsive wa-img-ourService wa-imgwhois-service')) }}
												<h3 class="wa-lbl-heading wa-ourServices-lbl">Reverse IP</h3>
												<div class="wa-content-text wa-ourServices-content" id="wa-content-whois-service">Reverse IP lets you find all the connected domain names hosted on the same IP address.</div>
											</div>
										</a>
									</div>
									<div class="col-sm-6 col-md-4 col-xs-12  wa-ourServices-col-padding">
										<a href={{ URL::to('domain-ip-database.php') }}>
											<div class="wa-ourServices-box wa-ourServices-firstRow text-center">
												{{ HTML::image('images/Domain IP Database.png', 'Responsive image', array('class'=>'img-responsive wa-img-ourService wa-imgwhois-service')) }}
												<h3 class="wa-lbl-heading wa-ourServices-lbl">Domain IP Database</h3>
												<div class="wa-content-text wa-ourServices-content" id="wa-content-whois-service">Provides IP addresses for hundreds of millions of domain names via bulk download.</div>
											</div>
										</a>
									</div>
								</div>
							</div>
						</div>

						<!-- Controls -->
						<a class="left carousel-control hidden-md hidden-xs" href="#carousel-example-generic" role="button" data-slide="prev">
							{{ HTML::image('images/left-slider.png', '', array('class'=>'img-responsive wa-left-slider pull-right', 'id' => 'wa-img-left-slider')) }}
						</a>
						<a class="right carousel-control hidden-md hidden-xs" href="#carousel-example-generic" role="button" data-slide="next">
							{{ HTML::image('images/right-slider.png', '', array('class'=>'img-responsive wa-right-slider pull-left', 'id' => 'wa-img-right-slider')) }}
						</a>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="row wa-ourFeatures-bg">
		<div class="col-xs-12 wa-col-xs-no-padding wa-auto-margin wa-featurescontent-whois">
			<div class="row">
				<div class="col-sm-6 wa-box-width-xs">
					<div class="wa-ourFeatures-box wa-whyus-box-home">
						<div class="row">
							<div class="col-xs-12 wa-ourFeatures-heading wa-lbl-heading">Why Us</div>
							<div class="col-xs-12">
								<ul class="list-unstyled">
									<li class="wa-content-text wa-ourFeatures-lists"><span class="wa-list-no">01</span><span class="wa-ourFeatures-lbl">Checking domain name availability</span></li>
									<li class="wa-content-text wa-ourFeatures-lists"><span class="wa-list-no">02</span><span class="wa-ourFeatures-lbl">Tracking domain registrations</span></li>
									<li class="wa-content-text wa-ourFeatures-lists"><span class="wa-list-no">03</span><span class="wa-ourFeatures-lbl">Detection (Credit card) fraud</span></li>
									<li class="wa-content-text wa-ourFeatures-lists"><span class="wa-list-no">04</span><span class="wa-ourFeatures-lbl">Investigating spam, fraud and other such activities</li>
									<li class="wa-content-text wa-ourFeatures-lists"><span class="wa-list-no">05</span><span class="wa-ourFeatures-lbl">Researching internet data</span></li>
									<li class="wa-content-text wa-ourFeatures-lists"><span class="wa-list-no">06</span><span class="wa-ourFeatures-lbl">Locating user geographically</span></li>
									<li class="wa-content-text wa-ourFeatures-lists"><span class="wa-list-no">07</span><span class="wa-ourFeatures-lbl">Thers is no end to possibilties</span></li>
								</ul>
							</div>
						</div>
					</div>
				</div>
				<!-- features -->
				<div class="col-sm-6 wa-box-width-xs">
					<div class="wa-ourFeatures-box  wa-features-box-home">
						<div class="row">
							<div class="col-xs-12 wa-ourFeatures-heading wa-lbl-heading ">Features</div>
							<div class="col-xs-12">
								<ul class="list-unstyled">
									<li class="wa-content-text wa-ourFeatures-lists"><span class="wa-list-no">01</span><span class="wa-ourFeatures-lbl">Our smart mechanism neutralizes the query limits imposed by whois registrars</span></li>
									<li class="wa-content-text wa-ourFeatures-lists"><span class="wa-list-no">02</span><span class="wa-ourFeatures-lbl">Robust online whois parser system parses an array of freeform whois data into well-structured fields (XML and JSON), the formats that can be read by your application.</span></li>
									<li class="wa-content-text wa-ourFeatures-lists"><span class="wa-list-no">03</span><span class="wa-ourFeatures-lbl">Parser API has been developed to work over basic HTTP. This helps avoid firewall-related problems of accessing whois server on port 43</li>
									<li class="wa-content-text wa-ourFeatures-lists"><span class="wa-list-no">04</span><span class="wa-ourFeatures-lbl">Return an indication about an availability of a domain</span></li>
									<li class="wa-content-text wa-ourFeatures-lists"><span class="wa-list-no">05</span><span class="wa-ourFeatures-lbl"> standardized created date, updated date, and expiration date in the whois record</span></li>
								</ol>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
@stop