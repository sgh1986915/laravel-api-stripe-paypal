<?php
error_reporting(0);
@ini_set('display_errors', 0);
$libPath = base_path(). "/whoisxmlapi_4";
require_once $libPath."/price.php";
require_once $libPath ."/httputil.php";
?>
@extends('layouts.master')

@section('title')
Pricing Chart
@stop

@section('styles')
@parent
{{ HTML::style('css/pricingchart.css') }}
@stop

@section('content')
<div class="main-content">
	<div class="row wa-searchbox-radio">
		<div class="col-xs-12 wa-auto-margin">
			<div class="row">
				<div class="col-sm-6 col-xs-12 wa-search-eg-box wa-search-eg-box-pricingchart">
					<form id="whoisform" name="whoisform" action="{{ URL::to('whoislookup.php') }}">
						<div class="form-group has-feedback wa-search-box">
							<input type="text" class="form-control wa-search wa-search-pricingchart" name="domainName" id="wa-search-whoislookup" placeholder="Whois Lookup">
							<span class="glyphicon glyphicon-search form-control-feedback wa-cursor" id="wa-search-icon-whoislookup"></span>
							<div class="wa-exapple wa-example-pricingchart">Example: google.com or 74.125.45.100</div>
							<div class="wa-radio-inputs wa-radio-xml-Josn wa-radio-xml-Josn-pricingchart">
								<div class="wa-radio-input wa-radio-input-xml wa-radio-input-xml-pricingchart">
									<input type="radio" value="xml" checked id="wa-radio-xml" class="wa-cursor wa-radio-orange-bar wa-field-input-selection wa-field-input-selection-pricingchart wa-api-res-type" name="outputFormat">
									<label for="wa-radio-xml" class="wa-cursor wa-field-value-selection wa-field-value-selection-xml wa-field-value-selection-pricingchart" id="wa-lbl-XMl">XML</label>
									<div class="wa-home-radio-outerCircle">
										<div class="wa-home-radio-innerCircle"></div>
									</div>
								</div>
								<div class="wa-radio-input wa-radio-input-json wa-radio-input-json-pricingchart">
									<input type="radio" value="json" id="wa-radio-json" class="wa-cursor wa-radio-orange-bar wa-field-input-selection wa-field-input-selection-pricingchart wa-api-res-type" name="outputFormat">
									<label for="wa-radio-json" class="wa-cursor wa-field-value-selection wa-field-value-selection-json wa-field-value-selection-pricingchart" id="wa-lbl-JSON">JSON</label>
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
				<div class="col-sm-6 col-xs-12 wa-btn wa-btn-pricingchart">
					<div class="row">
						<div class="col-sm-offset-4 col-sm-4 col-xs-12 wa-btn-order">
							<a href={{ URL::to('order_paypal.php') }}><button type="button" class="btn btn-default wa-btn wa-btn-orderNow wa-btn-orderNow-pricingchart center-block" id="wa-btn-orderNow-pricingchart">ORDER NOW</button></a>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="row wa-page-title-content-bg">
		<div class="col-xs-12 wa-about-whoisApi wa-xs wa-auto-margin">
			<h1 class="text-center wa-title wa-title-pricingchart">Pricing Of Hosted Whois Webservice</h1>
			<div class="text-center wa-content-text wa-content-spacing wa-content-text-pricingchart wa-content-text wa-content-text-pricingchart">
				We offer 2 pricing models, pay as you go or monthly memberships. <span class="wa-link wa-cursor wa-textDecoration">{{ HTML::link('order_paypal.php', 'Order Now!') }}</span>
			</div>
		</div>
	</div>
	<div id="wa-page-content">
		<div class="row wa-content-bg wa-content-bg-pricingchart">
			<div class="col-xs-12 wa-col-xs-no-padding wa-auto-margin">
				<div class="row">
					<div class="col-sm-6 col-xs-12 wa-box-width-xs wa-box-margin-pricingchart">
						<div class="wa-box wa-box-xs-padding wa-top-content-margin wa-box-purchaseplan-pricingchart wa-box-pricingchart">
							<h2 class="wa-section-title wa-section-title-pricingchart text-center">Pay as you go purchase plan</h2>
							<div class="wa-content-text wa-content-spacing wa-content-text-pricingchart wa-content-text-purchaseplan-pricingchart">Simply purchase the number of whois queries you require and they will be added to your account instantly. You will receive a notification email before your account reaches empty. You can buy more queries or replenish your account any time. <span class="wa-link wa-cursor wa-textDecoration"> {{ HTML::link('order_paypal.php#ordernow-payplan', 'Order Now!') }}</span></div>

							<div>
								<table class="table table-bordered table-striped wa-content-text wa-content-text-pricingchart wa-table-pricingchart wa-table-purchaseplan-pricingchart">
									<thead>
										<tr>
											<th>Number of queries</th>
											<th>Price per thousand	</th>
											<th>Price (USD)</th>
										</tr>
									</thead>
									<tbody>
										<?php for($i=0;$i<$queryCount;$i++){
											$avg_price = $queryPrices[$queryAmount[$i]] / $queryAmount[$i] * 1000;
											?>
											<tr>
												<td><?php echo number_format($queryAmount[$i])?></td>
												<td>$<?php echo $avg_price ?></td>
												<td>$<?php echo $queryPrices[$queryAmount[$i]]?></td>
											</tr>
											<?php } ?>
											<tr>
												<td>> <?php echo number_format($queryAmount[$queryCount-1]);?></td>
												<td>customized</td>
												<td><span class="wa-link wa-cursor wa-textDecoration">{{ HTML::link('mailto:support@whoisxmlapi.com', 'Contact Us') }}</span></td>
											</tr>
										</tbody>
									</table>
								</div>
							</div>
						</div>
						<div class="col-sm-6 col-xs-12 wa-box-width-xs">
							<div class="wa-box wa-box-xs-padding wa-top-content-margin wa-box-membersplan-pricingchart wa-box-pricingchart">
								<h2 class="wa-section-title wa-section-title-membersplan text-center">Membership plans</h2>
								<div class="wa-content-text wa-content-spacing wa-content-text-pricingchart wa-content-text-membersplan-pricingchart">By purchasing a membership plan, you may use up to a certain maximum number of queries each month, this is recommended if you use Whois API on a regular basis. You may cancel/change your plan anytime. <span class="wa-link wa-cursor wa-textDecoration">{{ HTML::link('order_paypal.php#ordernow-payplan', 'Order Now!') }}</span></div>
								<div>
									<table class="table table-bordered table-striped wa-content-text wa-content-text-pricingchart wa-table-pricingchart wa-table-membersplan-pricingchart">
										<thead>
											<tr>
												<th>Maximum number of queries/month</th>
												<th>Price/Month (USD)</th>
												<th>Price/Year (USD)</th>
											</tr>
											<tbody>
												<?php for($i=0;$i<$membershipCount;$i++){
													$avg_price = $membershipPrices[$membershipAmount[$i]] / $membershipAmount[$i] * 1000;
													$cl = ($i%2==0?"evcell":"oddcell");
													$checked = (strcmp($membershipAmount[$i], $membershipAmount[$i]) == 0);

													?>
													<tr>
														<td><?php echo number_format($membershipAmount[$i])?></td>
														<td>$<?php echo $membershipPrices[$membershipAmount[$i]]?></td>
														<td>$<?php echo 	10 * $membershipPrices[$membershipAmount[$i]]?></td>
													</tr>
													<?php } ?>
													<tr>
														<td>> <?php echo number_format($membershipAmount[$membershipCount-1]);?></td>
														<td colspan ="2">
															<span class="wa-link wa-cursor wa-textDecoration">{{ HTML::link('mailto:support@whoisxmlapi.com', 'Bulk Discount, Contact Us') }}</span>
														</td>
													</tr>
												</tbody>
											</thead>
										</table>
									</div>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-xs-12">
								<div class="wa-box wa-box-xs-padding wa-box-paymentpolicy-pricingchart wa-box-pricingchart">
									<h2 class="wa-section-title wa-section-title-pricingchart wa-section-title-paymentpolicy-pricingchart text-center">Payment policy</h2>
									<div class="">
										{{ HTML::image('images/paypal3.png', 'Responsive image', array('class'=>'img-responsive wa-img-paypal3-pricingchart')) }}
									</div>
									<div class="wa-subtitle wa-subtitle-pricingchart wa-subtitle-paypal-pricingchart">Paypal accepts credit card</div>
									<div  class="wa-content-text wa-content-text-pricingchart wa-content-text-pricingchart">All transactions are done via paypal for safety and security. Unused credits never expire but are not refundable. You may change or cancel your membership at any time by simply <span class="wa-link wa-cursor wa-textDecoration">{{ HTML::link('mailto:support@whoisxmlapi.com', 'sending us an email') }}</span> with your username. Please <span class="wa-link wa-cursor wa-textDecoration">{{ HTML::link('mailto:support@whoisxmlapi.com', 'Contact Us') }}</span> if you encounter any issue with the checkout proccess. </div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		@stop