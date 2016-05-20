<?php
error_reporting(0);
@ini_set('display_errors', 0);
$libPath = base_path(). "/whoisxmlapi_4";
require_once $libPath. "/reverse-ip/prices.php";
require_once $libPath. "/httputil.php";
require_once  $libPath. "/util.php";
require_once  $libPath. "/users/utils.inc";
require_once  $libPath. "/users/users.inc";

if(!session_id())session_start();

global $group_license_price, $source_license_price;
global $group_license_price, $source_license_price;
global $promo_start;
global $promo_end;
global $current_price_discount;
global $price_interval;
global $current_price;
global $riQueryPrices;
global $riMembershipPrices;
global $monthlyFactor;
global $payAsYouGoFactor;
global $riQueryCount;
global $riQueryAmount;
global $riMembershipCount;
global $riMembershipAmount;
global $riLowestPricePerReport;
global $riRegularReportPrice;

$pay_choice = get_from_post_get("pay_choice");
if(!$pay_choice) $pay_choice = "pp";
$query_quantity = get_from_post_get("query_quantity");
if(!$query_quantity) $query_quantity = 10000;
my_session_start();
$order_username = "";
if(isset($_REQUEST['order_username'])){
	$order_username = $_REQUEST['order_username'];
}
else if(isset($_SESSION['myuser'])){
	$order_username = $_SESSION['myuser']->username;
}
?>
<?php if(isset($_REQUEST['goto']) && $_REQUEST['goto']){?>
<script type ="text/javascript">
	$(document).ready(function(){
		location.href=location.href+"#<?php echo $_REQUEST['goto']?>";
	});
</script>

<?php
}?>
@extends('layouts.master')

@section('title')
Reverse IP Purchase
@stop

@section('styles')
@parent
{{ HTML::style('css/reverseIppurchase.css') }}
@stop

@section('content')
<div class="row wa-searchbox-radio">
	<div class="col-xs-12 wa-auto-margin">
		<div class="row">
			<div class="col-sm-6 col-xs-12 wa-search-eg-box wa-search-eg-box-reverseIppurchase">
				<form id="whoisform" class="ignore_jssm" name="whoisform" action="{{ URL::to('reverseiplookup.php') }}">
					<div class="form-group has-feedback wa-search-box">
						<input type="text" class="form-control wa-search wa-search-reverseIppurchase" name="input" id="wa-search-iplookup" placeholder="Reverse IP Lookup">
						<span class="glyphicon glyphicon-search form-control-feedback wa-cursor" id="wa-search-icon-iplookup"></span>
						<div class="wa-exapple wa-example-reverseIppurchase pull-left">Example: 208.64.121.161 or 208.64.121.% or %.64.121.161 or test.com</div>
						<div id="wa-user-stats" class="wa-user-stats"><?php include_once $libPath . "/user_stats.php"; ?>
						</div>
					</div>
				</form>
			</div>
			<div class="col-sm-6 col-xs-12 wa-example-reverseIppurchase">
				<div class="row">
					<div class="col-sm-offset-4 col-sm-4 col-xs-12 wa-btn-viewShopping">
						<a href={{ URL::to('reverse-whois-order.php') }}><button type="button" class="btn btn-default wa-btn-viewShoppingCart wa-btn-viewShopping-reverseIppurchase center-block" id="wa-btn-viewShopping-reverseIppurchase">VIEW SHOPPING CART</button></a>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="row wa-page-title-content-bg">
	<div class="col-xs-12 wa-about-whoisApi wa-auto-margin">
		<h1 class="text-center wa-title wa-title-reverseIppurchase wa-title-purchaseIPcredits-reverseIppurchase">Purchase Reverse IP credits </h1>
	</div>
</div>
<div id="wa-page-content">
	<div class="row wa-content-bg">
		<div class="col-xs-12 wa-col-xs-no-padding wa-auto-margin wa-col-xs-no-padding">
			<form action="{{ $data['form_action'] }}" class="ignore_jssm">
				<input type="hidden" name="order_type" value="whoisapi">
				<input id="pay_choice" name="pay_choice" type="hidden" value="{{ $data['pay_choice'] }}">
				<!-- <input type="hidden" name="sandbox" value="1"/> -->
				@if($data['pay_choice'] == 'cc')
				<input type="hidden" name="submit" value="1">
				@endif
				<div class="row">
					<div class="col-xs-12">
						<div class="wa-box wa-box-xs-padding wa-top-content-margin wa-box-Secure-reverseIppurchase">
							<h2 class="wa-section-title wa-section-title-reverseIppurchase wa-section-title-secureorder-reverseIppurchase text-center">Secure order</h2>
							<div class=" text-center wa-content-text wa-content-text-reverseIppurchase wa-content-text-secureorder-reverseIppurchase wa-content-spacing">We offer 2 pricing models for bulk reverse whois credits, <span class="wa-link wa-cursor wa-textDecoration">{{ HTML::link('#wa-paypurchase-plan', 'pay as you go') }}</span> or <span class="wa-link wa-cursor wa-textDecoration">{{ HTML::link('#wa-membershipplan', 'monthly memberships') }}</span> .Each 10,000 domains in a reverse whois lookup result cost 1 reverse whois lookup credit.</div>

							<div class="form-group">
								<label for="wa-un-reverseIppurchase" class="col-sm-5 control-label wa-field-lbl-uname wa-field-bl-reverseIppurchase">Username of the account to make purchase for:</label>
								<div class="col-sm-5">
									<input type="text" class="form-control" name="order_username" id="order_username" value="{{ $data['username'] }}">
								</div>
							</div>

						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-sm-6 col-xs-12 wa-box-width-xs">
						<div class="row">
							<div class="col-xs-12">
								<div class="wa-box wa-box-xs-padding wa-box-payPlan-reverseIppurchase wa-box-reverseIppurchase">
									<h2 class="wa-section-title wa-section-title-reverseIppurchase wa-section-title-payPlan-reverseIppurchase text-center" id="wa-paypurchase-plan">
										Pay as you go purchase plan
									</h2>
									<div class="wa-content-text wa-content-text-reverseIppurchase wa-content-spacing wa-content-payPlan-reverseIppurchase">Simply purchase the number of reverse IP credits you require and they will be added to your account instantly. You can buy more credits or replenish your account any time.</div>

									<div>
										<table class="table table-bordered table-striped wa-content-text wa-content-text-reverseIppurchase wa-table-reverseIppurchase wa-table-payPlan-reverseIppurchase">
											<thead>
												<tr>
													<th colspan="2">Number of credits</th>
													<th>Price/Credit</th>
													<th>Price (USD)</th>
												</tr>
											</thead>
											<tbody>
												<?php for($i=0;$i<$riQueryCount;$i++){
													$avg_price = $riQueryPrices[$riQueryAmount[$i]] / $riQueryAmount[$i] ;
													$checked = ($riQueryAmount[$i]==$query_quantity);
													?>
													<tr>
														<td><input type="radio" value="{{ $riQueryAmount[$i] }}" name="query_quantity" <?php echo $checked?"checked":"";?> ></td>
														<td>{{ number_format($riQueryAmount[$i]) }}</td>
														<td>{{ $avg_price }}</td>
														<td>${{ $riQueryPrices[$riQueryAmount[$i]] }}</td>
													</tr>
													<?php
												}?>
												<tr>
													<td></td>
													<td>>{{ number_format($riQueryAmount[$riQueryCount-1]) }}</td>
													<td>Bulk Discount</td>
													<td><div class="wa-link wa-curso wa-textDecoration">{{ HTML::link('mailto:support@whoisxmlapi.com', 'Contact Us') }}</div></td>
												</tr>
											</tbody>
										</table>
									</div>
									<div class="row">
										<div class="col-xs-12">
											<div class="pull-right" ><button type="submit" class="btn btn-default submit-button wa-btn-orange wa-btn-reverseIppurchase">Next</button></div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="col-sm-6 col-xs-12 wa-box-width-xs wa-box-margin-reverseIppurchase">
						<div class="row">
							<div class="col-xs-12">
								<div class="wa-box wa-box-xs-padding wa-box-menbershipPlans-reverseIppurchase wa-box-reverseIppurchase">
									<h2 class="wa-section-title wa-section-title-reverseIppurchase wa-section-title-menbershipPlans-reverseIppurchase text-center" id="wa-membershipplan">Membership plans</h2>
									<div class="wa-content-text wa-content-text-reverseIppurchase wa-content-spacing wa-content-menbershipPlans-reverseIppurchase">By purchasing a membership plan, you may use a fixed number of Credit each month, this is recommended if you use Whois API on a regular basis. You may cancel/change your plan anytime.</div>

									<div>
										<table class="table table-bordered table-striped wa-content-text wa-content-text-reverseIppurchase wa-table-reverseIppurchase wa-table-menbershipPlans-reverseIppurchase">
											<thead>
												<tr>
													<th>Number of credits/month</th>
													<th>Price/Credit</th>
													<th>Price/Month</th>
													<th>Price/Year</th>
												</tr>
											</thead>
											<tbody>
												<?php for($i=0;$i<$riMembershipCount;$i++){
													$avg_price =  $riMembershipAmount[$i] >0 ? $riMembershipPrices[$riMembershipAmount[$i]] / $riMembershipAmount[$i] : 0;
													$checked = (strcmp($riMembershipAmount[$i], $riMembershipAmount[$i]) == 0);
													$yearlyPrice=10 * $riMembershipPrices[$riMembershipAmount[$i]];
													?>
													<tr>
														<?php if($riMembershipAmount[$i] == 'unlimited'){ ?>
														<td colspan="2">* Unlimited</td>
														<?php
													} else { ?>
													<td>{{ number_format($riMembershipAmount[$i]) }}</td>
													<td>${{ $avg_price }}</td>
													<?php }?>
													<td>${{ $riMembershipPrices[$riMembershipAmount[$i]] }}<button type="submit" class="btn btn-default wa-btn wa-btn-bill wa-btn-bill-montly wa-btn-bill-orderBulkReverse wa-link wa-cursor" value="Bill Monthly" name="bill_monthly_{{ $riMembershipAmount[$i] }}">Bill Monthly</button></td>
													<td>${{ $yearlyPrice }}
														<?php if ($yearlyPrice>10000){?>
														<div class="wa-link wa-curso wa-textDecoration">{{ HTML::link('mailto:support@whoisxmlapi.com', 'Contact Us') }}</div>
														<?php } else{?>
														<button type="submit" class="btn btn-default wa-btn wa-btn-bill wa-btn-bill-yearly wa-btn-bill-orderBulkReverse wa-link wa-cursor" value="Bill Yearly" name="bill_yearly_{{ $riMembershipAmount[$i] }}">Bill Yearly</button>
														<?php }?>
													</td>
												</tr>
												<?php
											}?>
											<tr>
												<td colspan ="4">
													*user of the unlimited plan may not exceed the query rate of 1 query per 3 seconds
												</td>
											</tr>
										</tbody>
									</table>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</form>
		<div class="row">
			<div class="col-xs-12 wa-box-width-xs">
				<div class="wa-box wa-box-xs-padding wa-bottom-content-margin wa-box-paymentpolicy-reverseIppurchase wa-box-reverseIppurchase">
					<h2 class="wa-section-title wa-section-title-reverseIppurchase text-center">Payment policy</h2>
					{{ HTML::image('images/paypal3.png', 'Responsive image', array('class'=>'img-responsive')) }}
					<div class="wa-subtitle-paypal-reverseIppurchase wa-content-text-reverseIppurchase">Paypal accepts credit card</div>
					<div  class="wa-content-text wa-content-text-reverseIppurchase wa-content-paypalcredit-reverseIppurchase">All transactions are done via paypal for safety and security. Unused credits never expire but are not refundable. You may change or cancel your membership at any time by simply <span class="wa-link wa-cursor wa-textDecoration">{{ HTML::link('mailto:support@whoisxmlapi.com', 'sending us an email') }}</span> with your username. Please <span class="wa-link wa-cursor wa-textDecoration">{{ HTML::link('mailto:support@whoisxmlapi.com', 'Contact Us') }}</span> if you encounter any issue with the checkout proccess. </div>
					<div class="wa-subtitle-paypal-reverseIppurchase wa-content-text-reverseIppurchase ">Need to write a check?</div>
					<div  class="wa-content-text wa-content-text-reverseIppurchase wa-content-needcheck-reverseIppurchase">Please <span class="wa-link wa-cursor wa-textDecoration"> {{ HTML::link('mailto:support@whoisxmlapi.com', 'send us an email') }}</span> for instruction on how to pay by check, wire-transfer, or other options</div>
				</div>
			</div>
		</div>
	</div>
</div>
</div>

@stop