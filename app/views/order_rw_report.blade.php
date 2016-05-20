<?php
error_reporting(0);
@ini_set('display_errors', 0);
$libPath = base_path(). "/whoisxmlapi_4";
require_once $libPath . "/models/report.php";
require_once $libPath . "/db_cart/db_cart_class.php";
if(isset($V2)){
	require_once $libPath . "/reverse-whois-v2/config.php";
}
else if(isset($V1)){
	require_once $libPath . "/reverse-whois-v1/config.php";
}
else require_once $libPath . "/reverse-whois/config.php";
require_once $libPath . "/users/users.inc";
require_once $libPath . "/util.php";
require_once $libPath . "/users/account.php";
global $MAX_DOMAIN_SEARCH_MATCH;
global $MAX_SEARCH_CUTOFF;
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
$unlimited=0;
if(isset($_REQUEST['unlimited']) && $_REQUEST['unlimited']){
	$unlimited=1;
	$MAX_DOMAIN_SEARCH_MATCH = 500000;
	$MAX_SEARCH_CUTOFF = 500000;
}

function check_report_exists($report, $cart_rows){
	if(!$cart_rows)return false;
	foreach($cart_rows as $row){
		if($report->equals($row['report']))return true;
	}
	return false;
}
function count_credits_needed($cart_rows){
	$res=0;
	foreach($cart_rows as $row){
		$report=$row['report'];
		if($report){
			$report->compute_credits();
			$res+=$report->num_credits;
		}
	}
	return $res;
}

$order_error = false;
my_session_start();
$pay_choice = isset($_REQUEST['pay_choice'])?$_REQUEST['pay_choice']:false;
if(!$pay_choice) $pay_choice = "pp";

$order_username = "";
$session_user = (isset($_SESSION['myuser']) ?  $_SESSION['myuser'] : false);
if(isset($_REQUEST['order_username'])){
	$order_username = $_REQUEST['order_username'];
}
else if(isset($_SESSION['myuser'])){
	$order_username = $_SESSION['myuser']->username;
}
$cart = !empty($_SESSION['cart'])? $_SESSION['cart'] : false;
if(!is_object($cart) || empty($_SESSION['order_id'])){
	$cart_param=array('order_for' => $order_username);
	if(is_object($session_user)){
		$cart['customer_id'] = $session_user->username;
		$cart_param['customer_id'] = $session_user->username;
	} else {
		$cart_param['customer_id'] = session_id();
	}
	$cart = new db_cart($cart_param);

	if($cart->error){
		$order_error = $cart->error;
	}
	else $_SESSION['cart'] = $cart;
}
if(!$order_error){
	if (isset($_REQUEST['add_report']) && $_REQUEST['add_report'] == 1) {
		$report = Report::get_report_from_request();
		if(is_object($report) && $report->num_total_d>0){
			if(!check_report_exists($report, $cart->order_array)){
				if(!$report->save_report(array('username'=>$order_username))){
					$order_error = $report->error;
				}

				if(!$order_error){
					if(!$cart->handle_cart_row($report->report_id, $report->name, 'R', 1, $report->price, "yes")){
						$order_error = $cart->error;
					}

				}
			}


		}
	}
	else if (isset($_REQUEST['update']) && $_REQUEST['update'] == "remove") {
		if(!$cart->update_row($_REQUEST['row_id'], 0))$order_error = $cart->error;
	}
	if(!$order_error){
		if($cart->show_ordered_rows_by_type('R') === false) {
			$order_error = $cart->error;

		}
	}
}

$num_items = count($cart->order_array);

$credits_before_purchase = 0;
$total_credits_needed = count_credits_needed($cart->order_array);
$total_credits_required=$total_credits_needed;

$has_credit = false;
if( isLoggedIn()){
	$user = $session_user;
	$userAccount = getUserAccount($user->username);

	if(is_object($userAccount)){
		$credits_before_purchase += max(0, $userAccount->reverse_whois_monthly_balance);
		$credits_before_purchase += max(0, $userAccount->reverse_whois_balance);

		if($userAccount->reverse_whois_monthly_balance > 0){
			if($userAccount->reverse_whois_monthly_balance > $total_credits_needed){
				$userAccount->reverse_whois_monthly_balance-=$total_credits_needed;
				$total_credits_needed = 0;

			}
			else{
				$total_credits_needed -= $userAccount->reverse_whois_monthly_balance;
				$userAccount->reverse_whois_monthly_balance = 0;

			}
			$has_credit=true;
		}
		if($total_credits_needed > 0){
			if($userAccount->reverse_whois_balance > 0){
				if($userAccount->reverse_whois_balance > $total_credits_needed){
					$userAccount->reverse_whois_balance-=$total_credits_needed;
					$total_credits_needed = 0;

				}
				else{
					$total_credits_needed -= $userAccount->reverse_whois_balance;
					$userAccount->reverse_whois_balance = 0;

				}
				$has_credit = true;
			}
		}

	}
}
$total_credits_used = 0;
?>

@extends('layouts.master')

@section('title')
Order Reverse Whois Report
@stop

@section('styles')
@parent
{{ HTML::style('css/order_rw_report.css') }}
{{ HTML::style('css/reverseWhoisLookup.css') }}
@stop

@section('scripts')
{{ HTML::script('js/moreoptions_radio.js') }}
{{ HTML::script('js/reverseWhoisLookup.js') }}
@stop

@section('content')
<div class="main-content">
	<div class="row wa-searchbox-radio">
		<div class="col-xs-12 wa-auto-margin">
			<form id="whoisform" name="whoisform" action="{{ URL::to('reverselookup.php') }}">
				<div class="row">
					<div class="col-sm-6 col-xs-12 wa-search-eg-box wa-search-eg-box-rw-report">
						<div class="form-group has-feedback wa-search-box wa-search-box-rw-report">
							<input type="text" class="form-control wa-search wa-search-rw-report"  name="term1" id="wa-search-reversewhoislookup" placeholder="Reverse Whois Lookup">
							<span class="glyphicon glyphicon-search form-control-feedback wa-cursor" id="wa-search-icon-reverselookup"></span>
							<div class="wa-exapple wa-example-rw-report">Example: John Smith, test@gmail.com</div>
							<div class="wa-checkbox-inputs wa-checkbox-moreOptions wa-checkbox-moreOptions-reverseIpapi">
								<div class="wa-checkbox-input wa-checkbox-input-moreOption wa-checkbox-input-reverseIpapi">
									<input type="checkbox" value="moreoptions" id="wa-checkbox-moreOption" class="wa-cursor wa-field-input-selection wa-field-input-selection-reverseIpapi">
									<label for="wa-checkbox-moreOption" class="wa-cursor wa-field-value-selection wa-field-value-selection-reverseIpapi" id="wa-checkbox-moreOption">More Options</label>
								</div>
							</div>
							<div id="wa-user-stats" class="wa-user-stats"><?php include_once $libPath . "/user_stats.php"; ?>
							</div>
						</div>
					</div>

					<div class="col-sm-6 col-xs-12 wa-btn wa-btn-rw-report">
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
						<div class="col-xs-12 wa-historicRecord-rw-report">
							<div class="wa-margin-checkbox wa-historic-checkbox wa-cursor">
								<label class="wa-field-value-selection"><input type="checkbox" class="wa-checkbox-rw-report" name="search_type" value=""id="wa-checkbox-historic-records">Include Historic Records</label>
							</div>
						</div>
						<div class="col-xs-12">
							<label class="wa-field-value-selection wa-field-value-selection-checkbox">Include whois records containing ALL of the following terms in addition to the primary search term above:</label>
						</div>
						<div class="col-sm-6 col-xs-12 ">
							<input type="text" class="form-control wa-search-form wa-search-form-rw-report wa-search-form1" id="wa-input-type-include-1"name="term2">
							<input type="text" class="form-control wa-search-form wa-search-form-rw-report wa-search-form2" id="wa-input-type-include-2"name="term4">
						</div>
						<div class="col-sm-6 col-xs-12">
							<input type="text" class="form-control wa-search-form wa-search-form-rw-report wa-search-form3" id="wa-input-type-include-3"name="term3">
						</div>
					</div>
					<div class="row">
						<div class="col-sm-12 col-xs-12 wa-searchBox-exclude-rw-report">
							<div class ="wa-field-value-selection">
								<label class="wa-field-value-selection">Exclude whois records containing ANY of following terms:</label>
							</div>
						</div>
						<div class="col-sm-6 col-xs-12">
							<input type="text" class="form-control wa-search-form wa-search-form-rw-report wa-search-form4" id="wa-input-type-exclude-1"name="exclude_term1">
							<input type="text" class="form-control wa-search-form wa-search-form-rw-report wa-search-form5" id="wa-input-type-exclude-2"name="exclude_term3">
						</div>
						<div class="col-sm-6 col-xs-12">
							<input type="text" class="form-control wa-search-form wa-search-form-rw-report wa-search-form6" id="wa-input-type-exclude-3"name="exclude_term2">
						</div>
					</div>
				</div>
			</form>
		</div>
	</div>
	<div class="row wa-page-title-content-bg">
		<div class="col-xs-12 wa-about-whoisApi wa-xs wa-auto-margin">
			<h1 class="text-center wa-title wa-title-rw-report">Order Reverse Whois Report</h1>
		</div>
	</div>
	<div id="wa-page-content">
		<div class="row wa-content-bg wa-content-bg-rw-report">
			<div class="col-xs-12 wa-col-xs-no-padding wa-auto-margin">
				<form action="{{ $data['form_action'] }}" class="ignore_jssm">
					<!--<input type="hidden" name="sandbox" value="1"/>-->
					<input type="hidden" name="order_id" value="{{ $_SESSION['order_id'] }}">
					@if($unlimited)
					<input type="hidden" name="unlimited" value="1">
					@endif
					<div class="row">
						<div class="col-xs-12">
							<div class="wa-box wa-box-xs-padding wa-top-content-margin wa-box-rw-report wa-box-secureorder-rw-report">
								<h2 class="wa-section-title wa-section-title-rw-report wa-section-title-secureorder-rw-report text-center">Secure order form</h2>
								<div class="form-group">
									<label for="wa-un-rw-report" class="col-sm-5 control-label wa-field-lbl-uname wa-field-lbl-rw-report">Username of the account to make purchase for:</label>
									<div class="col-sm-5">
										<input type="text" maxlength="255" class="form-control" name="order_username" id="order_username" value="{{ $data['username'] }}">
									</div>
								</div>
							</div>
						</div>
					</div>
					@if(!$num_items)
					<div class="row">
						<div class="col-xs-12 wa-col-orderreport">
							<div class="wa-box wa-box-xs-padding wa-box-rw-report wa-shoppingcart-rw-report wa-box-orderreport-rw-report">
								<h2 class="wa-section-title wa-section-title-rw-report wa-section-title-orderreport-rw-report text-center">Your shopping cart is currently empty</h2>

								@if($current_price_discount>0)
								<div class="wa-content-text wa-content-text-rw-report wa-content-spacing wa-content-text-credits-rw-report" style="color:red;font-weight:bold;">
									Promotional discount of  {{ 100* $current_price_discount}}% is only available from {{ $promo_start }}  to {{ $promo_end }}!
								</div>
								@endif

								@if($total_credits_needed  > $credits_before_purchase)

								<div class="wa-content-text wa-content-text-rw-report wa-content-spacing wa-content-text-credits-rw-report">You currently have {{ $credits_before_purchase }} credits. This transaction requires {{ $total_credits_required }} credits. <span class="wa-link wa-cursor wa-textDecoration"><a href="{{ URL::to('bulk-reverse-whois-order.php') }}">Click here to order more Reverse whois reports(credits) in Bulk for as low as ${{ $lowestPricePerReport}} per report (credit)!</a></span>
								</div>

								@else

								<div class="wa-content-text wa-content-text-rw-report wa-content-spacing wa-content-text-credits-rw-report">You currently have {{ $credits_before_purchase }} credits. This transaction requires {{ $total_credits_required }} credits. <span class="wa-link wa-cursor wa-textDecoration"><a href="{{ URL::to('bulk-reverse-whois-order.php') }}">Click here to order more Reverse whois reports(credits) in Bulk for as low as ${{ $lowestPricePerReport}} per report (credit)!</a></span>
								</div>

								@endif

							</div>
						</div>
					</div>
					@else
					<div class="row">
						<div class="col-xs-12 wa-col-orderreport">
							<div class="wa-box wa-box-xs-padding wa-box-rw-report wa-box-orderreport-rw-report">
								<h2 class="wa-section-title wa-section-title-rw-report wa-section-title-orderreport-rw-report text-center">Order the following Reverse Whois Report</h2>
								<div>
									<table class="table table-bordered wa-content-text wa-content-text-orderreport-rw-report wa-table-rw-report wa-table-orderreport-rw-report">
										<thead>
											<tr>
												<th>Remove</th>
												<th>Search terms</th>
												<th>Type</th>
												<th>Domains</th>
												<th>Regular price or credits required</th>
											</tr>
										</thead>
										<tbody>
											<?php
											$total_price = 0;
											for($i=0;$i<$num_items; $i++){
												$cl = ($i%2==0?"evcell":"oddcell");
												$item = $cart->order_array[$i];
												$report = $item['report'];
												$report->compute_price();
												$report->compute_credits();
												$search_type = "Current and Historic";
												if($current_price_discount>0) {
													$originalPrice = $report->price / (1-$current_price_discount);
													$total_price+=$report->price;
													$unit_price_display ="<div style=\"color:red\" title=\"" . 100* $current_price_discount . "% off\"><del >" . $originalPrice . "</del><br/>". "$".$report->price . " </div>";
												} else {
													$perReportPrice = $report->price;
													$total_price+=$perReportPrice;
													$unit_price_display = "$" . $perReportPrice;
												}
												$removeUrlParam = array('row_id'=>$item['id'], 'update'=>'remove');
												$removeUrl =  URL::to('reverse-whois-order.php').'?'.http_build_query($removeUrlParam);

												?>
												<tr>
													<td>
														<a href="{{ $removeUrl }}" class="ignore_jssm"><span class="glyphicon glyphicon-trash wa-cursor" aria-hidden="true"></span></a>
													</td>
													<td>{{ $report->render_search_terms() }}</td>
													<td>{{ $search_type }}</td>
													<td>{{ $report->num_total_d }}</td>
													<td>{{ $unit_price_display }} or {{ $report->num_credits }} credits</td>
												</tr>
												<?php
											}
											?>
											<tr>
												<td colspan="4">Total Price:</td>
												<td>
													@if($total_price > 0)
													${{ $total_price }}
													@endif
													@if($total_credits_required > 0)
													or {{ $total_credits_required }} credit(s)
													@endif
												</td>
											</tr>
										</tbody>
									</table>
								</div>

								@if($current_price_discount>0)
								<div class="wa-content-text wa-content-text-rw-report wa-content-spacing wa-content-text-credits-rw-report" style="color:red;font-weight:bold;">
									Promotional discount of  {{ 100* $current_price_discount}}% is only available from {{ $promo_start }}  to {{ $promo_end }}!
								</div>
								@endif

								@if($total_credits_needed  > $credits_before_purchase)

								<div class="wa-content-text wa-content-text-rw-report wa-content-spacing wa-content-text-credits-rw-report">You currently have {{ $credits_before_purchase }} credits. This transaction requires {{ $total_credits_required }} credits. <span class="wa-link wa-cursor wa-textDecoration"><a href="{{ URL::to('bulk-reverse-whois-order.php') }}">Click here to order more Reverse whois reports(credits) in Bulk for as low as ${{ $lowestPricePerReport}} per report (credit)!</a></span>
								</div>

								@else

								<div class="wa-content-text wa-content-text-rw-report wa-content-spacing wa-content-text-credits-rw-report">You currently have {{ $credits_before_purchase }} credits. This transaction requires {{ $total_credits_required }} credits. <span class="wa-link wa-cursor wa-textDecoration"><a href="{{ URL::to('bulk-reverse-whois-order.php') }}">Click here to order more Reverse whois reports(credits) in Bulk for as low as ${{ $lowestPricePerReport}} per report (credit)!</a></span>
								</div>

								@endif

								<div class="row">
									<div class="col-xs-12">
										<div class="pull-right"><button type="submit" class="btn btn-default submit-button wa-btn wa-btn-next wa-btn-orange wa-btn-orange wa-btn-next-rw-report" id="wa-btn-next-whoissp" {{ ($num_items > 0) ? "" : "disabled" }}>Next</button></div>
									</div>
								</div>
							</div>
						</div>
					</div>
					@endif
					<div class="row">
						<div class="col-xs-12 wa-box-width-xs ">
							<div class="wa-box wa-box-xs-padding wa-box-whoissp wa-box-paymentpolicy-rw-report" id="payment_policy">
								<h2 class="wa-section-title wa-section-title-rw-report wa-section-title-paymentpolicy-rw-report text-center">Payment policy</h2>
								{{ HTML::image('images/paypal3.png', 'Responsive image', array('class'=>'img-responsive')) }}
								<div class="wa-subtitle wa-subtitle-rw-report wa-subtitle-paypal-rw-report">Paypal accepts credit card</div>
								<div class="wa-content-text wa-content-text-rw-report wa-content-spacing wa-content-paymentpolicy-rw-report">All transactions are done via paypal for safety and security. Your Reverse Whois Reports will be saved in your account so that you may access them any time. Please <span class="wa-cursor wa-link wa-textDecoration">{{ HTML::link('mailto:support@whoisxmlapi.com', 'Contact Us') }}</span> if you encounter any issue with the checkout proccess.</div>
							</div>
						</div>
					</div>
					<input id="choice_cc" name="pay_choice" type="hidden" value="{{ $data['pay_choice'] }}"/>
				</form>
			</div>
		</div>
	</div>
</div>
@stop