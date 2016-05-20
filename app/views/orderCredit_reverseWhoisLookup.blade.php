<?php
error_reporting(0);
@ini_set('display_errors', 0);
$libPath = base_path(). "/whoisxmlapi_4";
require_once $libPath. "/reverse-whois/prices.php";
require_once $libPath. "/httputil.php";
require_once  $libPath. "/util.php";
require_once  $libPath. "/users/utils.inc";
require_once  $libPath. "/users/users.inc";

if(!session_id())session_start();

global $group_license_price, $source_license_price;
global $promo_start;
global $promo_end;
global $historic_price_discount;
global $current_price_discount;
global $price_interval;
global $current_price;
global $historic_price;
global $rwQueryPrices;
global $rwMembershipPrices;
global $payAsYouGoFactor;
global $monthlyFactor;
global $rwQueryCount;
global $rwQueryAmount;
global $rwMembershipCount;
global $rwMembershipAmount;
global $lowestPricePerReport;

init(); // Load all global variable values

$pay_choice = get_from_post_get("pay_choice");
if(!$pay_choice) $pay_choice = "pp";
$query_quantity = get_from_post_get("query_quantity");
if(!$query_quantity) $query_quantity = 500;
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
Bulk Reverse Whois Secure Order Form
@stop

@section('styles')
@parent
{{ HTML::style('css/orderCredit_reverseWhoisLookup.css') }}
{{ HTML::style('css/reverseWhoisLookup.css') }}

@stop

@section('scripts')
{{ HTML::script('js/moreoptions_radio.js') }}
{{ HTML::script('js/reverseWhoisLookup.js') }}
@stop

@section('content')
<div class="row wa-searchbox-radio">
	<div class="col-xs-12 wa-auto-margin">
		<form id="whoisform" name="whoisform" action="{{ URL::to('reverselookup.php') }}">
			<div class="row">
				<div class="col-sm-6 col-xs-12 wa-search-eg-box wa-search-eg-box-orderBulkReverse">
					<div class="form-group has-feedback wa-search-box wa-search-box-orderBulkReverse">
						<input type="text" class="form-control wa-search wa-search-orderBulkReverse"  name="term1" id="wa-search-reversewhoislookup" placeholder="Reverse Whois Lookup">
						<span class="glyphicon glyphicon-search form-control-feedback wa-cursor" id="wa-search-icon-reverselookup"></span>
						<div class="wa-exapple wa-example-orderBulkReverse">Example: John Smith, test@gmail.com</div>
						<div class="wa-checkbox-inputs wa-checkbox-moreOptions wa-checkbox-moreOptions-reversewhoisapi">
							<div class="wa-checkbox-input wa-checkbox-input-moreOption wa-checkbox-input-reversewhoisapi">
								<input type="checkbox" value="moreoptions"  id="wa-checkbox-moreOption" class="wa-cursor wa-field-input-selection wa-field-input-selection-reversewhoisapi">
								<label for="wa-checkbox-moreOption" class="wa-cursor wa-field-value-selection wa-field-value-selection-reversewhoisapi" id="wa-checkbox-moreOption">More Options</label>
							</div>
						</div>
						<div id="wa-user-stats" class="wa-user-stats"><?php include_once $libPath . "/user_stats.php"; ?>
						</div>
					</div>
				</div>
				<div class="col-sm-6 col-xs-12 wa-btn wa-btn-orderBulkReverse">
					<div class="row">
						@if (Auth::check())
						<div class="col-sm-offset-4 col-sm-4 col-xs-12 wa-btn-view-my-rw-report">
							<button type="button" class="btn btn-default wa-btn-my-rw-report wa-btn-viewShopping-innerpg center-block no-margin" id="wa-btn-view-my-rw-report">MY REVERSE WHOIS REPORTS</button>
						</div>
						<div class="col-sm-offset-4 col-sm-4 col-xs-12 wa-btn-viewShopping">
							<a href={{ URL::to('reverse-whois-order.php') }}><button type="button" class="btn btn-default wa-btn-viewShoppingCart wa-btn-viewShopping-innerpg center-block no-margin" id="wa-btn-home-orderNow">VIEW SHOPPING CART</button></a>
						</div>
						@else
						<div class="col-sm-offset-4 col-sm-4 col-xs-12 wa-btn-viewShopping">
							<a href={{ URL::to('reverse-whois-order.php') }}><button type="button" class="btn btn-default wa-btn-viewShoppingCart wa-btn-viewShopping-innerpg center-block" id="wa-btn-home-orderNow">VIEW SHOPPING CART</button></a>
						</div>
						@endif
					</div>
				</div>
			</div>
			<div class="wa-moreoptions">
				<div class="row">
					<div class="col-xs-12 wa-historicRecord-orderBulkReverse">
						<div class="wa-margin-checkbox wa-historic-checkbox wa-cursor">
							<label class="wa-field-value-selection"><input type="checkbox" class="wa-checkbox-orderBulkReverse" name="search_type" value="" id="wa-checkbox-historic-records">Include Historic Records</label>
						</div>
					</div>
					<div class="col-xs-12">
						<label class="wa-field-value-selection wa-field-value-selection-checkbox">Include whois records containing ALL of the following terms in addition to the primary search term above:</label>
					</div>
					<div class="col-sm-6 col-xs-12 ">
						<input type="text" class="form-control wa-search-form wa-search-form-orderBulkReverse wa-search-form1" id="wa-input-type-include-1"name="term2">
						<input type="text" class="form-control wa-search-form wa-search-form-orderBulkReverse wa-search-form2" id="wa-input-type-include-2"name="term4">
					</div>
					<div class="col-sm-6 col-xs-12">
						<input type="text" class="form-control wa-serach-form wa-search-form-orderBulkReverse wa-search-form3" id="wa-input-type-include-3"name="term3">
					</div>
				</div>
				<div class="row">
					<div class="col-sm-12 col-xs-12 wa-searchBox-exclude-orderBulkReverse">
						<div class ="wa-field-value-selection">
							<label class="wa-field-value-selection">Exclude whois records containing ANY of following terms:</label>
						</div>
					</div>
					<div class="col-sm-6 col-xs-12 wa-textspacing">
						<input type="text" class="form-control wa-search-form wa-search-form-orderBulkReverse wa-search-form4" id="wa-input-type-exclude-1"name="exclude_term1">
						<input type="text" class="form-control wa-search-form wa-search-form-orderBulkReverse wa-search-form5" id="wa-input-type-exclude-2"name="exclude_term3">
					</div>
					<div class="col-sm-6 col-xs-12">
						<input type="text" class="form-control wa-search-form wa-search-form-orderBulkReverse wa-search-form6" id="wa-input-type-exclude-3"name="exclude_term2">
					</div>
				</div>
			</div>
		</form>
	</div>
</div>
<div class="row wa-page-title-content-bg">
	<div class="col-xs-12 wa-about-whoisApi wa-auto-margin">
		<h1 class="text-center wa-title wa-title-orderBulkReverse">Order Credit</h1>
	</div>
</div>
<div id="wa-page-content">
	<div class="row wa-content-bg wa-content-bg-orderBulkReverse">
		<form action="{{ $data['form_action'] }}" class="ignore_jssm">
			<input id="choice_cc" name="pay_choice" type="hidden" value="{{ $data['pay_choice'] }}">
			<!--<input type="hidden" name="sandbox" value="1"/>-->
			<div class="col-xs-12 wa-col-xs-no-padding wa-box-width-xs wa-auto-margin wa-box-margin-whoisapi">
				<div class="row">
					<div class="col-xs-12">
						<div class="wa-box wa-box-xs-padding wa-top-content-margin wa-box-width-xs wa-box-Secure-orderBulkReverse">
							<h2 class="wa-section-title wa-section-title-orderBulkReverse text-center">Secure Order</h2>
							<div class=" text-center wa-content-text wa-content-text-orderBulkReverse wa-content-text-pricing-orderBulkReverse wa-content-spacing">We offer 2 pricing models for bulk reverse whois credits, <span class="wa-link wa-cursor wa-textDecoration">{{ HTML::link('#ordercredit-payplan', 'pay as you go') }}</span> or <span class="wa-link wa-cursor wa-textDecoration">{{ HTML::link('#ordercredit-memberplan', 'monthly memberships') }}</span>. Each 10,000 domains in a reverse whois lookup result cost 1 reverse whois lookup credit.</div>
							<div class="form-group">
								<label for="wa-un-orderBulkReverse" class="col-sm-5 control-label wa-field-lbl-uname wa-field-bl-orderBulkReverse">Username of the account to make purchase for:</label>
								<div class="col-sm-5">
									<input type="text" class="form-control" name="order_username" id="order_username" value="{{ $data['username'] }}">
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="col-xs-12 wa-col-xs-no-padding wa-auto-margin">
				<div class="row">
					<div class="col-sm-6 col-xs-12 wa-box-width-xs wa-box-margin-whoisapi">
						<!--  table for Pay as you go Purchase Plan -->
						<div class="row">
							<div class="col-xs-12">
								<div class="wa-box wa-box-xs-padding wa-box-payPlan-orderBulkReverse" id="ordercredit-payplan">
									<h2 class="wa-section-title wa-section-title-orderBulkReverse wa-section-title-purchase-orderBulkReverse text-center">Pay as you go purchase plan</h2>
									<div class="wa-content-text wa-content-text-orderBulkReverse  wa-content-text-purchase-orderBulkReverse wa-content-spacing">Simply purchase the number of whois queries you require and they will be added to your account instantly. You will receive a notification email before your account reaches empty. You can buy more queries or replenish your account any time.</div>
									<div>
										<table class="table table-bordered table-striped wa-content-text wa-content-text-orderBulkReverse wa-table-pay">
											<thead>
												<tr>
													<th colspan="2">Number of credits</th>
													<th>Price/Credit</th>
													<th>Price (USD)</th>
												</tr>
											</thead>
											<tbody>
												<?php for($i=0;$i<$rwQueryCount;$i++){
													$avg_price = $rwQueryPrices[$rwQueryAmount[$i]] / $rwQueryAmount[$i] ;
													$checked = ($rwQueryAmount[$i]==$query_quantity);

													?>
													<tr>
														<td><input type="radio" name="query_quantity" id="wa-optionsRadios1-payPlan-orderBulkReverse" value="<?php echo $rwQueryAmount[$i];?>" <?php echo $checked?"checked":"";?>></td>
														<td><?php echo number_format($rwQueryAmount[$i])?></td>
														<td>$<?php echo $avg_price?></td>
														<td>$<?php echo $rwQueryPrices[$rwQueryAmount[$i]]?></td>
													</tr>
													<?php } ?>
													<tr>
														<td></td>
														<td>><?php echo number_format($rwQueryAmount[$rwQueryCount-1])?></td>
														<td>Bulk Discount</td>
														<td class="wa-link wa-cursor wa-textDecoration">{{ HTML::link('mailto:support@whoisxmlapi.com', 'contact us') }}</td>
													</tr>
												</tbody>
											</table>
										</div>
										<div class="row">
											<div class="col-xs-12">
												<div class="pull-right" ><button type="submit" class="btn btn-default submit-button wa-btn-next wa-btn-orange wa-btn-order-credit">Next</button></div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="col-sm-6 col-xs-12 wa-box-width-xs wa-box-margin-whoisapi">
							<!--  table for Membership Plans -->
							<div class="row">
								<div class="col-xs-12">
									<div class="wa-box wa-box-xs-padding wa-box-membership-orderBulkReverse" id="ordercredit-memberplan">
										<h2 class="wa-section-title wa-section-title-orderBulkReverse wa-section-title-membership-orderBulkReverse text-center">Membership plans</h2>
										<div  class=" wa-content-text wa-content-text-orderBulkReverse wa-content-text-membership-orderBulkReverse wa-content-spacing">By purchasing a membership plan, you may use up to a certain maximum number of queries each month, this is recommended if you use Whois API on a regular basis. You may cancel/change your plan anytime</div>
										<table class="table table-bordered table-striped wa-content-text wa-content-text-orderBulkReverse wa-table-membership-plan">
											<thead>
												<tr>
													<th>Maximum number of credits/month</th>
													<th>Price/Credit</th>
													<th>Price/Month (USD)</th>
													<th>Price/Year (USD)</th>
												</tr>
											</thead>
											<tbody>
												<?php for($i=0;$i<$rwMembershipCount;$i++){
													$avg_price =  $rwMembershipAmount[$i] >0 ? $rwMembershipPrices[$rwMembershipAmount[$i]] / $rwMembershipAmount[$i] : 0;
													$checked = (strcmp($rwMembershipAmount[$i], $rwMembershipAmount[$i]) == 0);
													$yearlyPrice=10 * $rwMembershipPrices[$rwMembershipAmount[$i]];
													?>
													<tr>
														<?php if($rwMembershipAmount[$i] == 'unlimited'){?>
														<td colspan="2">* Unlimited</td>
														<?php
													}else {?>

													<td><?php echo number_format($rwMembershipAmount[$i])?></td>
													<td>$<?php echo $avg_price?></td>
													<?php }?>
													<td>$<?php echo $rwMembershipPrices[$rwMembershipAmount[$i]]?>
														<?php if ($yearlyPrice>10000){?>
														<div class="wa-link wa-cursor">{{ HTML::link('mailto:support@whoisxmlapi.com', 'contact us') }}</div>
														<?php } else{?>
														<div><button type="submit" value="Bill Monthly" name="bill_monthly_<?php echo number_format($rwMembershipAmount[$i])?>" class="btn btn-default wa-btn wa-btn-bill wa-btn-bill-montly  wa-btn-bill-orderBulkReverse wa-link wa-cursor">Bill Monthly</button></div>
														<?php }?>
													</td>
													<td>$<?php echo $yearlyPrice?><div><button type="submit" value="Bill Yearly" name="bill_yearly_<?php echo number_format($rwMembershipAmount[$i])?>" class="btn btn-default wa-btn wa-btn-bill wa-btn-bill-yearly  wa-btn-bill-orderBulkReverse wa-link wa-cursor" value="Bill Yearly" name="bill_yearly_<?php echo number_format($rwMembershipAmount[$i])?>">Bill Yearly</button></div></td>
												</tr>
												<?php } ?>
												<tr>
													<td colspan="4">*user of the unlimited plan may not exceed the query rate of 1 query per 3 seconds</td>
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
			<div class="col-xs-12 wa-col-xs-no-padding wa-box-width-xs wa-box-margin-whoisapi wa-auto-margin">
				<div class="wa-box wa-box-xs-padding wa-bottom-content-margin wa-box-width-xs wa-box-payment-orderBulkReverse wa-paymentpolicy-orderBulkReverse">
					<h2 class="wa-section-title wa-section-title-orderBulkReverse wa-section-title-payment-orderBulkReverse text-center">Payment policy</h2>
					{{ HTML::image('images/paypal3.png', 'Responsive image', array('class'=>'img-responsive')) }}
					<div  class="wa-content-text wa-content-text-orderBulkReverse wa-content-spacing wa-content-text-payment-orderBulkReverse">All transactions are done via paypal for safety and security. Unused Query purchases never expire but are not refundable. You may change or cancel your membership at any time by simply <span class="wa-link wa-cursor wa-textDecoration"> {{ HTML::link('mailto:support@whoisxmlapi.com', 'sending us an email') }}</span> with your username. Please <span class="wa-link wa-cursor wa-textDecoration">{{ HTML::link('mailto:support@whoisxmlapi.com', 'Contact Us') }}</span> if you encounter any issue with the checkout proccess.</div>
					<div class="wa-subtitle-paypal-orderBulkReverse wa-subtitle-orderBulkReverse wa-content-text-viewShopping ">Need to write a check?  </div>

					<div  class="wa-content-text wa-content-text-orderBulkReverse wa-content-text-paypal-orderBulkReverse">Please <span class="wa-link wa-cursor wa-textDecoration"> {{ HTML::link('mailto:support@whoisxmlapi.com', 'sending us an email') }}</span> for instruction on how to pay by check, wire-transfer, or other options</div>
				</div>
			</div>
		</div>
	</div>
	@stop