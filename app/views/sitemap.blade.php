<?php
$libPath = base_path(). "/whoisxmlapi_4";
?>
@extends('layouts.master')

@section('title')
Sitemap
@stop

@section('styles')
@parent
{{ HTML::style('css/sitemap.css') }}
@stop

@section('content')
<div class="main-content">
	<div class="row wa-searchbox-radio">
		<div class="col-xs-12 wa-auto-margin">
			<div class="row">
				<div class="col-sm-6 col-xs-12 wa-search-eg-box wa-search-eg-box-Sitemap">
					<form id="whoisform" name="whoisform" action="{{ URL::to('whoislookup.php') }}">
						<div class="form-group has-feedback wa-search-box">
							<input type="text" class="form-control wa-search wa-search-Sitemap" name="domainName" id="wa-search-whoislookup" placeholder="Whois Lookup">
							<span class="glyphicon glyphicon-search form-control-feedback wa-cursor" id="wa-search-icon-whoislookup"></span>
							<div class="wa-exapple wa-exapple-Sitemap">Example: google.com or 74.125.45.100</div>
							<div class="wa-radio-inputs wa-radio-xml-Josn wa-radio-xml-Josn-Sitemap">
								<div class="wa-radio-input wa-radio-input-xml wa-radio-input-xml-Sitemap">
									<input type="radio" value="xml" checked id="wa-radio-xml" class="wa-cursor wa-radio-orange-bar wa-field-input-selection wa-field-input-selection-Sitemap wa-api-res-type" name="outputFormat">
									<label for="wa-radio-xml" class="wa-cursor wa-field-value-selection wa-field-value-selection-xml wa-field-value-selection-Sitemap" id="wa-lbl-XMl">XML</label>
									<div class="wa-home-radio-outerCircle">
										<div class="wa-home-radio-innerCircle"></div>
									</div>
								</div>
								<div class="wa-radio-input wa-radio-input-json wa-radio-input-json-Sitemap">
									<input type="radio" value="json" id="wa-radio-json" class="wa-cursor wa-radio-orange-bar wa-field-input-selection wa-field-input-selection-Sitemap wa-api-res-type" name="outputFormat">
									<label for="wa-radio-json" class="wa-cursor wa-field-value-selection wa-field-value-selection-json wa-field-value-selection-Sitemap" id="wa-lbl-JSON">JSON</label>
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
				<div class="col-sm-6 col-xs-12 wa-btn">
					<div class="row">
						<div class="col-sm-offset-4 col-sm-4 col-xs-12 wa-btn-order">
							<a href={{ URL::to('order_paypal.php') }}><button type="button" class="btn btn-default wa-btn wa-btn-orderNow wa-btn-orderNow-Sitemap center-block" id="wa-btn-home-orderNow">ORDER NOW</button></a>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="row wa-page-title-content-bg">
		<div class="col-xs-12 wa-about-whoisApi wa-auto-margin">
			<h1 class="text-center wa-title wa-title-Sitemap">Sitemap</h1>
		</div>
	</div>
	<div id="wa-page-content">
		<div class="row wa-content-bg wa-content-bg-Sitemap">
			<div class="col-xs-12 wa-auto-margin wa-col-xs-no-padding">
				<div class="wa-box wa-box-xs-padding wa-top-content-margin wa-bottom-content-margin wa-box-link-Sitemap wa-box-Sitemap">
					<ul>
						<h2 class="wa-list-sitemap wa-section-title">{{ HTML::link('home.php', 'HOME') }}</h2>
						<h2 class="wa-list-sitemap wa-section-title">PRODUCTS & SERVICES</h2>
							<div>
								<div class="row">
									<div class="col-sm-6 col-xs-12">
										<div class="wa-list-submenu">APIs</div>
											<div class="wa-list-product">
												<div class="wa-img-list">
													{{ HTML::image('images/servicesArrow.png', 'Responsive image', array('class'=>'img-responsive wa-img-servicesarrow')) }}
													<div class="wa-list">{{ HTML::link('whois-api-doc.php', 'Whois API') }}</div>
												</div>
												<div class="wa-img-list">
													{{ HTML::image('images/servicesArrow.png', 'Responsive image', array('class'=>'img-responsive wa-img-servicesarrow')) }}
													<div class="wa-list">{{ HTML::link('domain-availability-api.php', 'Domain Availability API') }}</div>
												</div>
												<div class="wa-img-list">
													{{ HTML::image('images/servicesArrow.png', 'Responsive image', array('class'=>'img-responsive wa-img-servicesarrow')) }}
													<div class="wa-list">{{ HTML::link('reverse-whois-api.php', 'Reverse Whois API') }}</div>
												</div>
												<div class="wa-img-list">
													{{ HTML::image('images/servicesArrow.png', 'Responsive image', array('class'=>'img-responsive wa-img-servicesarrow')) }}
													<div class="wa-list">{{ HTML::link('reverse-ip-api.php', 'Reverse IP API') }}</div>
												</div>
												<div class="wa-img-list">
													{{ HTML::image('images/servicesArrow.png', 'Responsive image', array('class'=>'img-responsive wa-img-servicesarrow')) }}
													<div class="wa-list">{{ HTML::link('brand-alert-api.php', 'Brand Alert API') }}</div>
												</div>
												<div class="wa-img-list">
													{{ HTML::image('images/servicesArrow.png', 'Responsive image', array('class'=>'img-responsive wa-img-servicesarrow')) }}
													<div class="wa-list">{{ HTML::link('registrant-alert-api.php', 'Registrant Alert API') }}</div>
												</div>
											</div>
										<div class="wa-list-submenu">CUSTOM SOLUTIONS</div>
											<div class="wa-list-product">
												<div class="wa-img-list">
													{{ HTML::image('images/servicesArrow.png', 'Responsive image', array('class'=>'img-responsive wa-img-servicesarrow')) }}
													<div class="wa-list">{{ HTML::link('whois-api-software.php', 'Whois API Software Package') }}</div>
												</div>
												<div class="wa-img-list">
													{{ HTML::image('images/servicesArrow.png', 'Responsive image', array('class'=>'img-responsive wa-img-servicesarrow')) }}
													<div class="wa-list">{{ HTML::link('registrar-whois-services.php', 'Registrar Whois Service') }}</div>
												</div>
											</div>
									</div>
									<div class="col-sm-6 col-xs-12">
										<div class="wa-list-submenu">DOMAIN RESEARCH</div>
											<div class="wa-list-product">
												<div class="wa-img-list">
													{{ HTML::image('images/servicesArrow.png', 'Responsive image', array('class'=>'img-responsive wa-img-servicesarrow')) }}
													<div class="wa-list">{{ HTML::link('http://whois.whoisxmlapi.com/', 'Whois Lookup') }}</div>
												</div>
												<div class="wa-img-list">
													{{ HTML::image('images/servicesArrow.png', 'Responsive image', array('class'=>'img-responsive wa-img-servicesarrow')) }}
													<div class="wa-list">{{ HTML::link('reverse-whois.php', 'Reverse Whois Search') }}</div>
												</div>
												<div class="wa-img-list">
													{{ HTML::image('images/servicesArrow.png', 'Responsive image', array('class'=>'img-responsive wa-img-servicesarrow')) }}
													<div class="wa-list">{{ HTML::link('bulk-whois-lookup.php', 'Bulk Whois Lookup') }}</div>
												</div>
												<div class="wa-img-list">
													{{ HTML::image('images/servicesArrow.png', 'Responsive image', array('class'=>'img-responsive wa-img-servicesarrow')) }}
													<div class="wa-list">{{ HTML::link('reverse-ip.php', 'Reverse IP Lookup') }}</div>
												</div>
											</div>
										<div class="wa-list-submenu">DATA FEEDS</div>
											<div class="wa-list-product">
												<div class="wa-img-list">
													{{ HTML::image('images/servicesArrow.png', 'Responsive image', array('class'=>'img-responsive wa-img-servicesarrow')) }}
													<div class="wa-list">{{ HTML::link('whois-database-download.php', 'Whois Database Download') }}</div>
												</div>
												<div class="wa-img-list">
													{{ HTML::image('images/servicesArrow.png', 'Responsive image', array('class'=>'img-responsive wa-img-servicesarrow')) }}
													<div class="wa-list">{{ HTML::link('newly-registered-domains.php', 'New Registered Domains') }}</div>
												</div>
												<div class="wa-img-list">
													{{ HTML::image('images/servicesArrow.png', 'Responsive image', array('class'=>'img-responsive wa-img-servicesarrow')) }}
													<div class="wa-list">{{ HTML::link('domain-ip-database.php', 'Domain IP Database') }}</div>
												</div>
											</div>
									</div>
								</div>
							</div>
						<h2 class="wa-list-sitemap wa-section-title">SOLUTIONS</h2>
							<div class="wa-list-product">
								<div class="wa-img-list">
									{{ HTML::image('images/servicesArrow.png', 'Responsive image', array('class'=>'img-responsive wa-img-servicesarrow')) }}
									<div class="wa-list">{{ HTML::link('whitepapers.php', 'White papers') }}</div>
								</div>
							</div>
						<h2 class="wa-list-sitemap wa-section-title">{{ HTML::link('whois-api-contact.php', 'CONTACT US') }}</h2>
						<h2 class="wa-list-sitemap wa-section-title">{{ HTML::link('support.php', 'SUPPORT') }}</h2>
					</ul>
				</div>
			</div>
		</div>
	</div>
</div>
@stop