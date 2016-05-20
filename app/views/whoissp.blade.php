<?php
$libPath = base_path(). "/whoisxmlapi_4";
?>
@extends('layouts.master')

@section('title')
Whois API Software Package
@stop

@section('styles')
@parent
{{ HTML::style('css/whoissp.css') }}
@stop

@section('content')
<div class="main-content">
	<div class="row wa-searchbox-radio">
		<div class="col-xs-12 wa-auto-margin">
			<div class="row">
				<div class="col-sm-6 col-xs-12 wa-search-eg-box wa-search-eg-box-whoissp">
					<form id="whoisform" name="whoisform" action="{{ URL::to('whoislookup.php') }}">
						<div class="form-group has-feedback wa-search-box">
							<input type="text" class="form-control wa-search wa-search-whoissp" name="domainName" id="wa-search-whoislookup" placeholder="Whois Lookup">
							<span class="glyphicon glyphicon-search form-control-feedback wa-cursor" id="wa-search-icon-whoislookup"></span>
							<div class="wa-exapple wa-example-whoissp">Example: google.com or 74.125.45.100</div>
							<div class="wa-radio-inputs wa-radio-xml-Josn wa-radio-xml-Josn-whoissp">
							<div class="wa-radio-input wa-radio-input-xml wa-radio-input-whoissp">
							<input type="radio" value="xml" checked id="wa-radio-xml" class="wa-cursor wa-radio-orange-bar wa-field-input-selection wa-field-input-selection-whoissp wa-api-res-type" name="outputFormat">
							<label for="wa-radio-xml" class="wa-cursor wa-field-value-selection wa-field-value-selection-xml wa-field-value-selection-whoissp" id="wa-lbl-XMl">XML</label>
							<div class="wa-home-radio-outerCircle">
								<div class="wa-home-radio-innerCircle"></div>
							</div>
							</div>
							<div class="wa-radio-input wa-radio-input-json wa-radio-input-json-whoissp">
								<input type="radio" value="json" id="wa-radio-json" class="wa-cursor wa-radio-orange-bar wa-field-input-selection wa-field-input-selection-whoissp wa-api-res-type" name="outputFormat">
								<label for="wa-radio-json" class="wa-cursor wa-field-value-selection wa-field-value-selection-json wa-field-value-selection-whoissp" id="wa-lbl-JSON">JSON</label>
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
				<div class="col-sm-6 col-xs-12 wa-btn wa-btn-whoissp">
					<div class="row">
						<div class="col-sm-offset-4 col-sm-4 col-xs-12 wa-btn-order">
							<a href={{ URL::to('order_paypal.php') }}><button type="button" class="btn btn-default wa-btn wa-btn-orderNow wa-btn-orderNow-whoissp center-block" id="wa-btn-home-orderNow">ORDER NOW</button></a>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="row wa-page-title-content-bg">
		<div class="col-xs-12 wa-about-whoisApi wa-auto-margin">
			<h1 class="text-center wa-title wa-title-whoissp">Whois API Software Package</h1>
				<div class="text-center wa-content-text wa-content-text-whoissp wa-content-spacing wa-page-description wa-page-description-whoissp">
					Use the exact same technology we are using for "Hosted Whois Webservice", this is the right choice for you if you believe you have the necessary infrastructure and support power to run our whois webservice in house.
				</div>
		</div>
	</div>
	<div id="wa-page-content">
		<div class="row wa-content-bg wa-content-bg-whoissp">
			<div class="col-xs-12 wa-col-xs-no-padding wa-auto-margin">
				<form action="{{ $data['form_action'] }}" class="ignore_jssm">
					<input type="hidden" name="order_type" value="spk">
					<!--pricing of whois api software package -->
					<div class="row">
						<div class="col-xs-12">
							<div class="wa-box wa-top-content-margin wa-box-xs-padding wa-box-whoissp wa-box-pricingAPIsp-whoissp">
								<h2 class="wa-section-title wa-section-title-whoissp wa-section-title-pricingAPIsp-whoissp text-center">Pricing of Whois API Software Packages</h2>
								<div class="wa-content-text wa-content-text-whoissp wa-content-spacing wa-content-text-pricingAPIsp-whoissp">We offer 3 software editions. Licenses cover <span class="wa-cursor wa-link wa-textDecoration">{{ HTML::link('#payment_policy', 'non-redistributable use only') }}</span>. Please <span class="wa-cursor wa-link wa-textDecoration">{{ HTML::link('mailto:support@whoisxmlapi.com', 'contact us') }}</span> for other options including a quote for complete source code license. Simply check the edition you want and click on next to purchase.</div>
								<div>
									<table class="table table-bordered table-striped wa-content-text wa-table-trheight wa-content-text-whoissp wa-table-whoissp wa-table-pricingAPIsp-whoissp">
										<thead>
											<tr class="wa-table-trheight">
												<th>Features</th>
												<th>
													<input type="radio" name="spk_sel" class="wa-input-radio-table" id="wa-optionsRadios1-whoissp" value="0">
													<div class="wa-lbl-radio">Lite</div>
												</th>
												<th>
													<input type="radio" name="spk_sel" class="wa-input-radio-table" id="wa-optionsRadios2-whoissp" value="1"checked>
													<div class="wa-lbl-radio">Professional</div>
												</th>
												<th>
													<input type="radio" name="spk_sel" class="wa-input-radio-table" id="wa-optionsRadios2-whoissp" value="2">
													<div class="wa-lbl-radio">Enterprise</div>
												</th>
											</tr>
										</thead>
										<tbody>
											<tr class="wa-table-trheight">
												<td>Raw Query</td>
												<td>{{ HTML::image('images/check.png', 'Responsive image', array('class'=>'img-responsive wa-img wa-img-check-td')) }} </td>
												<td>{{ HTML::image('images/check.png', 'Responsive image', array('class'=>'img-responsive wa-img wa-img-check-td')) }} </td>
												<td>{{ HTML::image('images/check.png', 'Responsive image', array('class'=>'img-responsive wa-img wa-img-check-td')) }} </td>
											</tr>
											<tr class="wa-table-trheight">
												<td>Automatic registry/registrar selection</td>
												<td>{{ HTML::image('images/check.png', 'Responsive image', array('class'=>'img-responsive wa-img wa-img-check-td')) }}</td>
												<td>{{ HTML::image('images/check.png', 'Responsive image', array('class'=>'img-responsive wa-img wa-img-check-td')) }}</td>
												<td>{{ HTML::image('images/check.png', 'Responsive image', array('class'=>'img-responsive wa-img wa-img-check-td')) }}</td>
											</tr>
											<tr class="wa-table-trheight">
												<td>Basic Whois record parsing in XML ?</td>
												<td></td>
												<td>{{ HTML::image('images/check.png', 'Responsive image', array('class'=>'img-responsive wa-img wa-img-check-td')) }}</td>
												<td>{{ HTML::image('images/check.png', 'Responsive image', array('class'=>'img-responsive wa-img wa-img-check-td')) }}</td>
											</tr>
											<tr class="wa-table-trheight">
												<td>Advanced Whois record parsing in XML & JSON ?</td>
												<td></td>
												<td></td>
												<td>{{ HTML::image('images/check.png', 'Responsive image', array('class'=>'img-responsive wa-img wa-img-check-td')) }}</td>
											</tr>
											<tr class="wa-table-trheight">
												<td>Domain availability check</td>
												<td></td>
												<td>{{ HTML::image('images/check.png', 'Responsive image', array('class'=>'img-responsive wa-img wa-img-check-td')) }}</td>
												<td>{{ HTML::image('images/check.png', 'Responsive image', array('class'=>'img-responsive wa-img wa-img-check-td')) }}</td>
											</tr>
											<tr class="wa-table-trheight">
												<td>Scalable Proxy servers support to allow unlimited querying</td>
												<td></td>
												<td></td>
												<td>{{ HTML::image('images/check.png', 'Responsive image', array('class'=>'img-responsive wa-img wa-img-check-td')) }}</td>
											</tr>
											<tr class="wa-table-trheight">
												<td>Standalone Java Whois API Library</td>
												<td>{{ HTML::image('images/check.png', 'Responsive image', array('class'=>'img-responsive wa-img wa-img-check-td')) }}</td>
												<td>{{ HTML::image('images/check.png', 'Responsive image', array('class'=>'img-responsive wa-img wa-img-check-td')) }}</td>
												<td>{{ HTML::image('images/check.png', 'Responsive image', array('class'=>'img-responsive wa-img wa-img-check-td')) }}</td>
											</tr>
											<tr class="wa-table-trheight">
												<td>Number of standalone Whois API installable licenses</td>
												<td class="text-center">1</td>
												<td class="text-center">1</td>
												<td class="text-center">1</td>
											</tr>
											<tr class="wa-table-trheight">
												<td>Running as webservice to support clients in any technology</td>
												<td></td>
												<td>{{ HTML::image('images/check.png', 'Responsive image', array('class'=>'img-responsive wa-img wa-img-check-td')) }}</td>
												<td>{{ HTML::image('images/check.png', 'Responsive image', array('class'=>'img-responsive wa-img wa-img-check-td')) }}</td>
											</tr>
											<tr class="wa-table-trheight">
												<td>Number of Whois API webservice installable licenses</td>
												<td></td>
												<td class="text-center"></td>
												<td class="text-center">1</td>
											</tr>
											<tr class="wa-table-trheight">
												<th colspan="4" class="wa-td-heading">Support (included for one year)</th>
											</tr>
											<tr class="wa-table-trheight">
												<td>Minor version upgrades and updates</td>
												<td>{{ HTML::image('images/check.png', 'Responsive image', array('class'=>'img-responsive wa-img wa-img-check-td')) }}</td>
												<td>{{ HTML::image('images/check.png', 'Responsive image', array('class'=>'img-responsive wa-img wa-img-check-td')) }}</td>
												<td>{{ HTML::image('images/check.png', 'Responsive image', array('class'=>'img-responsive wa-img wa-img-check-td')) }}</td>
											</tr>
											<tr class="wa-table-trheight">
												<td>Help with installation and license issues</td>
												<td>{{ HTML::image('images/check.png', 'Responsive image', array('class'=>'img-responsive wa-img wa-img-check-td')) }}</td>
												<td>{{ HTML::image('images/check.png', 'Responsive image', array('class'=>'img-responsive wa-img wa-img-check-td')) }}</td>
												<td>{{ HTML::image('images/check.png', 'Responsive image', array('class'=>'img-responsive wa-img wa-img-check-td')) }}</td>
											</tr>
											<tr class="wa-table-trheight">
												<td>Technical support incidents</td>
												<td class="text-center">5</td>
												<td class="text-center">20</td>
												<td class="text-center">Unlimited</td>
											</tr>
											<tr class="wa-table-trheight">
												<td>Direct access to key developers</td>
												<td></td>
												<td>{{ HTML::image('images/check.png', 'Responsive image', array('class'=>'img-responsive wa-img wa-img-check-td')) }}</td>
												<td>{{ HTML::image('images/check.png', 'Responsive image', array('class'=>'img-responsive wa-img wa-img-check-td')) }}</td>
											</tr>
											<tr class="wa-table-trheight">
												<td>Priority response to feature requests</td>
												<td></td>
												<td></td>
												<td>{{ HTML::image('images/check.png', 'Responsive image', array('class'=>'img-responsive wa-img wa-img-check-td')) }}</td>
											</tr>
											<tr class="wa-table-trheight">
												<td>Price</td>
												<td class="td-nowrap text-center">
													<!-- <s>$899</s>-->
													<div>$899</div>
												</td>
												<td class="td-nowrap text-center">
													<!--<s>$4999</s>-->
													<div>$4999</div>
												</td>
												<td class="td-nowrap text-center">
													<!--<s>$8899</s>-->
													<div>$8899</div>
												</td>
											</tr>
										</tbody>
									</table>
								</div>
								<!--  <div class="wa-content-text wa-content-text-whoissp wa-content-promotionaldiscount-whoissp wa-content-spacing">Promotional discount is only available until end of this month!</div>
								-->
								<div class="row">
									<div class="col-xs-12">
										<div class="pull-right"><button type="submit" class="btn btn-default submit-button wa-btn wa-btn-orange wa-btn-next-whoissp wa-btn-whoissp" id="wa-btn-next-whoissp">Next</button></div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="row ">
						<!--License Terms -->
						<div class="col-sm-6 col-xs-12 wa-box-width-xs ">
							<div class="wa-box wa-box-xs-padding wa-box-whoissp wa-box-licenseterms-whoissp">
								<h2 class="wa-section-title wa-section-title-whoissp wa-section-title-licenseterms-whoissp text-center">License terms</h2>
								<div class="wa-content-text wa-content-text-whoissp wa-content-spacing wa-content-licenseterms-whoissp">Non-redistributable means that the license covers installation only on your own machines (or machines that you exclusively lease) up to the number specified by the license type. With this license, you may not include the Whois interface as part of a product sent to other parties.</div>
							</div>
						</div>
						<!-- Privacy Policy -->
						<div class="col-sm-6 col-xs-12 wa-box-width-xs ">
							<div class="wa-box wa-box-xs-padding wa-bottom-content-margin wa-box-whoissp wa-box-paymentpolicy-whoissp" id="payment_policy">
								<h2 class="wa-section-title wa-section-title-whoissp wa-section-title-paymentpolicy-whoissp text-center">Payment policy</h2>
								{{ HTML::image('images/paypal3.png', 'Responsive image', array('class'=>'img-responsive')) }}
								<div class="wa-subtitle wa-subtitle-whoissp wa-subtitle-paypal-whoissp">Paypal accepts credit card</div>
								<div class="wa-content-text wa-content-text-whoissp wa-content-spacing wa-content-paymentpolicy-whoissp">All transactions are done via paypal for safety and security. Please <span class="wa-cursor wa-link wa-textDecoration">{{ HTML::link('mailto:support@whoisxmlapi.com', 'contact us') }}</span> if you encounter any issue with the checkout proccess.</div>
							</div>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
@stop