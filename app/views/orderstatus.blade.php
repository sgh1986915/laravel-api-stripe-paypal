<?php
$libPath = base_path(). "/whoisxmlapi_4";
?>
<?php
error_reporting(0);
@ini_set('display_errors', 0);
$libPath = base_path(). "/whoisxmlapi_4";
require_once $libPath . "/order_util.php";
require_once $libPath . "/httputil.php"; // modified
require_once $libPath. "/price.php";
require_once $libPath. "/util/number_util.php";
require_once $libPath. "/business_def.php";

// global $STRIP_API_CURRENT_PUBLIC_KEY; // modified
$PAYMENT_TEST = 0; // modified

//order summary

$order_type = $_REQUEST['order_type'];
$order_username=$_REQUEST['order_username'];


//whoisapi
$query_quantity=$_REQUEST['query_quantity'];
$membership=getMembership();
//bwl
$num_domains=$_REQUEST['num_domains'];
$bwl_speed=$_REQUEST['bwl_speed'];
//wc
$wc_license_type=$_REQUEST['wc_license_type'];
$num_user_license=$_REQUEST['num_user_license'];
//wdb
$wdb_quantity=$_REQUEST['wdb_quantity'];
$wdb_type=$_REQUEST['wdb_type'];
//cctld_wdb
$cctld_wdb_ids = $_REQUEST['cctld_wdb_ids'];
$cctld_whois_db_type=$_REQUEST['cctld_whois_db_type'];
//custom_wdb
$custom_db_ids=$_REQUEST['custom_db_ids'];
//domain ip db
$dip_db_quantity=$_REQUEST['dip_db_quantity'];

$back_url_params=array('order_username'=>$order_username, 'order_type'=>$order_type);
if($order_type=='whoisapi'){
	$back_url_params['query_quantity']=$query_quantity;
}
else if($order_type=='wc'){
	$back_url_params['wc_license_type']=$wc_license_type;
	$back_url_params['num_user_license']=$num_user_license;
}
else if($order_type=='bwl'){
	$back_url_params['num_domains']=$num_domains;
	$back_url_params['bwl_speed']=$bwl_speed;
}
else if($order_type=='wdb'){
	$back_url_params['wdb_quantity']=$wdb_quantity;
	$back_url_params['wdb_type']=$wdb_type;
}
else if($order_type=='cctld_wdb'){
	$back_url_params['cctld_wdb_ids']  = $_REQUEST['cctld_wdb_ids'];
	$back_url_params['cctld_whois_db_type']=$cctld_whois_db_type;
}
else if($order_type=='custom_wdb'){
	$back_url_params['custom_wdb_ids']=$_REQUEST['custom_wdb_ids'];
}

// $back_url=build_ssl_url("order.php?".http_build_query($back_url_params));
$back_url=build_ssl_url("order.php?".http_build_query($back_url_params)); // modified

if($order_type=='whoisapi'){

	if($membership){
		$queryAmount=$membership['query_amount'];
		$payperiod=$membership['payperiod'];
		$s=ucfirst($payperiod)."ly membership subscription of $queryAmount queries/month for $order_username";
		$price = (strcasecmp($payperiod, 'month')===0? $membershipPrices[$queryAmount] : $membershipPrices[$queryAmount]*10);
		$price = format_price($price) . "/$payperiod";

	}
	else if($query_quantity){
		$s= "$query_quantity whois queries for $order_username";
		$price=$queryPrices[$query_quantity];
	}
}
else if($order_type=='bwl'){
	global $regular_bwl_prices, $expedited_bwl_prices,$regular_bwl_speed,$expedited_bwl_speed;
	require_once $libPath . "/bulk-whois-lookup-price.php";
	$price = compute_bwl_price($num_domains, $bwl_speed);
	$days_to_complete = compute_bwl_time($num_domains, $bwl_speed);
	$s = "Bulk Whois Lookup for $num_domains domains on a $bwl_speed schedule with $days_to_complete days to complete." ;

}
else if($order_type=='wc'){
	global $group_license_price,$source_license_price;
	require_once $libPath . "/wc_price.php";
	$price = compute_wc_license_price($wc_license_type, $num_user_license);
	$s = '';
	if($wc_license_type == 'sourcecode_license')$s = "Whois API Client Application Source Code License";
	else if($wc_license_type =='group_license')$s="Whois API Client Application Group License";
	else $s = "$num_user_license Whois API Client User License";
}
else if($order_type=='wdb'){
	global $dbDiscount,$dbRawPrices,$dbParsedPrices,$dbCount,$dbAmount;
	require_once $libPath . "/whois-database-price.php";
	$price = compute_real_db_price($wdb_quantity, $wdb_type);
	$s = "Whois Database download: $wdb_quantity million ".($wdb_type=='raw' ? "Raw" : "Parsed and Raw ") . " whois records" ;

}
else if($order_type=='cctld_wdb'){
	require_once $libPath . "/models/cctld_whois_database_product.php";
	$cctldWhoisDatabaseProduct = new CCTLDWhoisDatabaseProduct();

	$s = ($cctld_whois_db_type=='domain_names' ? 'Domain Names Only: ' : 'Whois Records: ' ) . "<pre>" .$cctldWhoisDatabaseProduct->concat_product_item_names_by_ids($cctld_wdb_ids, '<br/>') . "</pre>";

	$items = $cctldWhoisDatabaseProduct->get_product_items_by_ids($cctld_wdb_ids);

	$price=$cctldWhoisDatabaseProduct->get_product_items_price($items, ($cctld_whois_db_type=='domain_names'?'domain_names_price' : 'parsed_whois_price'));
}
else if($order_type=='custom_wdb'){
	require_once $libPath . "/models/custom_whois_database_product.php";
	$customWhoisDatabaseProduct = new CustomWhoisDatabaseProduct();
	$s=$customWhoisDatabaseProduct->concat_product_item_names_by_ids($_REQUEST['custom_wdb_ids'],"<br/>");
	$price=$customWhoisDatabaseProduct->get_product_items_price_by_ids($_REQUEST['custom_wdb_ids']);
}
else if($order_type=='dipdb'){
	global $dipDBDiscount,$dipDBPrices,$dipDBCount,$dipDBAmount;
	require_once $libPath . "/domain-ip-database-price.php";
	$s="$dip_db_quantity million domain name to ip addresses mappings";
	$price=compute_real_dip_db_price($dip_db_quantity);
}
if($PAYMENT_TEST){
	$price=1;
}

if(!$membership){
	$price=format_price($price);
}

?>
@extends('layouts.master')

@section('title')
ORDER STATUS
@stop

@section('styles')
@parent
{{ HTML::style('css/orderstatus.css') }}
@stop

@section('content')
<div class="main-content">
	<div class="row wa-searchbox-radio">
		<div class="col-xs-12 wa-auto-margin">
			<div class="row">
				<div class="col-sm-6 col-xs-12 wa-search-eg-box wa-search-eg-box-orderstatus">
					<form id="whoisform" name="whoisform" action="{{ URL::to('whoislookup.php') }}">
						<div class="form-group has-feedback wa-search-box">
							<input type="text" class="form-control wa-search wa-search-orderstatus" name="domainName" id="wa-search-whoislookup" placeholder="Whois Lookup">
							<span class="glyphicon glyphicon-search form-control-feedback wa-cursor" id="wa-search-icon-whoislookup"></span>
							<div class="wa-exapple wa-exapple-orderstatus">Example: google.com or 74.125.45.100</div>
							<div class="wa-radio-inputs wa-radio-xml-Josn wa-radio-xml-Josn-orderstatus">
								<div class="wa-radio-input wa-radio-input-xml wa-radio-input-xml-orderstatus">
									<input type="radio" value="xml" checked id="wa-radio-xml" class="wa-cursor wa-radio-orange-bar wa-field-input-selection wa-field-input-selection-orderstatus wa-api-res-type" name="outputFormat">
									<label for="wa-radio-xml" class="wa-cursor wa-field-value-selection wa-field-value-selection-xml wa-field-value-selection-orderstatus" id="wa-lbl-XMl">XML</label>
									<div class="wa-home-radio-outerCircle">
										<div class="wa-home-radio-innerCircle"></div>
									</div>
								</div>
								<div class="wa-radio-input wa-radio-input-json wa-radio-input-json-orderstatus">
									<input type="radio" value="json" id="wa-radio-json" class="wa-cursor wa-radio-orange-bar wa-field-input-selection wa-field-input-selection-orderstatus wa-api-res-type" name="outputFormat">
									<label for="wa-radio-json" class="wa-cursor wa-field-value-selection wa-field-value-selection-json wa-field-value-selection-orderstatus" id="wa-lbl-JSON">JSON</label>
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
							<a href={{ URL::to('order_paypal.php') }}><button type="button" class="btn btn-default wa-btn wa-btn-orderNow wa-btn-orderNow-orderstatus center-block" id="wa-btn-home-orderNow">ORDER NOW</button></a>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="row wa-page-title-content-bg">
		<div class="col-xs-12 wa-about-whoisApi wa-auto-margin">
			<h1 class="text-center wa-title wa-title-orderstatus">Order Summary</h1>
		</div>
	</div>
	<div id="wa-page-content">
		<div class="row wa-content-bg wa-content-bg-orderstatus">
			<div class="col-xs-12 wa-col-xs-no-padding wa-auto-margin wa-box-margin-whoisapi">
				<div class="row">
					<div class="col-xs-12">
						<div class="wa-box wa-box-xs-padding wa-top-content-margin wa-box-orderstatus wa-box-payPlan-orderstatus" id="ordernow-payplan">
							<div>
								<table class="table table-bordered table-striped wa-content-text wa-content-text-orderstatus wa-table-orderstatus wa-table-payPlan-orderstatus">
									<thead>
										<tr>
											<th>Product</th>
											<th>Price (USD)</th>
										</tr>
									</thead>
									<tbody>
										<tr>
											<td>{{ $s }}</td>
											<td>{{ $price }}</td>
										</tr>
									</tbody>
								</table>
							</div>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-xs-12 wa-box-width-xs wa-box-margin-whoisapi">
						<div class="wa-box wa-box-xs-padding wa-box-orderstatus wa-box-paymentoption-orderstatus">
							<div class="wa-securecarddetails-form center-block">
								<h2 class="wa-section-title wa-section-title-orderstatus wa-section-title-paymentoption-orderstatus text-center">Secure Payment Form</h2>
								<div class="wa-section-title-orderstatus wa-section-title-securesite-orderstatus">{{ HTML::image('images/Icon_LockSm.png', 'Responsive image', array('class'=>'img-responsive wa-img-iconLock')) }}This is a secure site</div>
								<form class="payment-form" role="form" method="post" action="{{ $data['form_action'] }}">
									<input type="hidden" name="pay_choice" value="cc"/>
									<input type="hidden" name="order_username" value="{{ $order_username }}" />
									<input type="hidden" name="order_type" value="{{ $order_type }}" />
									<?php
									if($order_type=='whoisapi'){
										if($membership){
											foreach($_REQUEST as $rkey=>$rval){
												if($rkey!=null){
													if(stripos($rkey, "bill_yearly_") ===0 || stripos($rkey, "bill_monthly_") ===0){
														echo "<input type=\"hidden\" name=\"$rkey\" value=\"$rval\"/>";
													}
												}
											}
										}
										else if($query_quantity){
											echo "<input type=\"hidden\" name=\"query_quantity\" value=\"$query_quantity\"/>";
										}

									}
									else if($order_type=='bwl'){
										echo "<input type=\"hidden\" name=\"num_domains\" value=\"$num_domains\"/>";
										echo "<input type=\"hidden\" name=\"bwl_speed\" value=\"$bwl_speed\"/>";
									}
									else if($order_type=='wc'){
										echo "<input type=\"hidden\" name=\"wc_license_type\" value=\"$wc_license_type\"/>";
										echo "<input type=\"hidden\" name=\"num_user_license\" value=\"$num_user_license\"/>";
									}
									else if($order_type=='wdb'){
										echo "<input type=\"hidden\" name=\"wdb_quantity\" value=\"$wdb_quantity\"/>";
										echo "<input type=\"hidden\" name=\"wdb_type\" value=\"$wdb_type\"/>";
									}
									else if($order_type=='cctld_wdb'){
										$cctld_wdb_ids = $_REQUEST['cctld_wdb_ids'];
										foreach($cctld_wdb_ids as $cctld_wdb_id){
											echo "<input type=\"hidden\" name=\"cctld_wdb_ids[]\" value=\"$cctld_wdb_id\"/>";
										}
										echo "<input type=\"hidden\" name=\"cctld_whois_db_type\" value=\"$cctld_whois_db_type\"/>";
									}
									else if($order_type=='custom_wdb'){
										if($custom_wdb_ids){
											foreach($custom_wdb_ids as $custom_wdb_id){
												echo "<input type=\"hidden\" name=\"custom_wdb_ids[]\" value=\"$custom_wdb_id\"/>";
											}
										}
									}

									?>
									<div class="row wa-paymentimage-orderstatus">
										<div class="col-xs-12 wa-securesite-orderstatus">
											<div class="wa-paymentradio">
												{{ HTML::image('images/paypal2.png', 'Responsive image', array('class'=>'img-responsive wa-img-payPal1')) }}
											</div>
											<div class="errorMsg payment-errors" style="display:none;"></div>
											<div class="wa-box wa-box-cardnumber wa-box-margin-whoisapi wa-paymentradioshow">
												<div class="form-group">
													<label for="wa-lbl-cn"><span class="wa-field-lbl">Card Number</span></label>
													<input type="text" class="form-control card-number" id="wa-lbl-cn">
												</div>
												<div class="form-group">
													<label for="wa-lbl-cvc"><span class="wa-field-lbl">CVC</span></label>
													<input type="text" class="form-control card-cvc" id="wa-lbl-cvc">
												</div>
												<div class="form-group wa-form-group-exp-date">
													<div><label for="wa-lbl-exp-date"><span class="wa-field-lbl">Expiration (MM/YYYY)</span></label></div>
													<div class="col-xs-3 col-sm-2 wa-col-exp-date"><input type="text" class="form-control card-expiry-month" id="wa-lbl-exp-date"></div>
													<div class="wa-slashsize-orderstatus">/</div>
													<div class="col-xs-7 col-sm-4 wa-col-exp-date wa-col2-exp-date"><input type="text" class="form-control card-expiry-year" id="wa-lbl-exp-date"></div>
												</div>
												<div class="form-group">
													<label for="wa-lbl-email"><span class="wa-field-lbl">Email (where payment confirmation will be sent)</span></label>
													<input type="text" class="form-control" name="customer_email" id="customer_email" value="{{ $data['user_email'] }}">
												</div>
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-xs-12">
											<a href="{{ $back_url }}"><button type="button" class="btn btn-default submit-button wa-btn wa-btn-orange wa-btn-previous-orderstatus wa-btn-orderstatus pull-left">Previous</button></a>
											<button type="submit" class="btn btn-default wa-btn wa-btn-orange submit-button wa-btn-next-orderstatus wa-btn-orderstatus pull-right">Next</button>
										</div>
									</div>
								</form>
							</div>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-xs-12 wa-box-width-xs wa-box-margin-whoisapi">
						<div class="wa-box wa-box-xs-padding wa-box-paymentpolicy wa-box-paymentpolicy-orderstatus">
							<h2 class="wa-section-title wa-section-title-orderstatus wa-section-title-paymentpolicy-orderstatus text-center">Payment Security and Policy</h2>
							{{ HTML::image('images/paypal2.png', 'Responsive image', array('class'=>'img-responsive')) }}
							<div  class="wa-content-text wa-content-text-orderstatus wa-content-spacing wa-content-paymentpolicy-orderstatus">We take great care to use physical, electronic and procedural precautions, including the use of up to 256-bit data encryption and secure socket layer (SSL) technology. Our precautionary measures meet or exceed all industry standards.
							We do not collect your credit card number, your payment information are encrypted and passed on directly to either paypal or other industry leading PCI-compliant credit card payment processor that uses the highest standard and encryptions.
							Unused Query purchases never expire but are not refundable. You may change or cancel your membership at any time by simply <span class="wa-link wa-cursor wa-textDecoration">{{ HTML::link('mailto:support@whoisxmlapi.com', 'sending us an email') }} </span> with your username. Please <span class="wa-link wa-cursor wa-textDecoration">{{ HTML::link('mailto:support@whoisxmlapi.com', 'Contact Us') }} </span> if you encounter any issue with the checkout procces</div>
							<div class="wa-subtitle wa-subtitle-orderstatus wa-subtitle-paypal-orderstatus">Need an alternative payment option? {{ HTML::image('images/paypal_1.png', 'Responsive image', array('class'=>'img-responsive wa-img-paypal2-orderstatus')) }}</div>
							<div  class="wa-content-text wa-content-text-orderstatus">You may <span class="wa-link wa-cursor wa-textDecoration">{{ HTML::link('order_paypal.php', 'use paypal') }}</span> to checkout. Please <span class="wa-link wa-cursor wa-textDecoration">{{ HTML::link('mailto:support@whoisxmlapi.com', 'sending us an email') }} </span> for instruction on how to pay by check, wire-transfer, or other options</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
	@stop

	@section('scripts')
	@parent
	<script type="text/javascript" src="https://js.stripe.com/v1/"></script>
	<script type="text/javascript">

// this identifies your website in the createToken call below
Stripe.setPublishableKey('<?php echo $STRIP_API_CURRENT_PUBLIC_KEY?>');
$(document).ready(function() {
	$(".payment-form").bind('submit', function(event) {
		event.preventDefault();
		if (!validate_input()) return false;
		try {
			// disable the submit button to prevent repeated clicks
			$('.submit-button').attr("disabled", "disabled");
			$('.submit-button').attr("src", "images/next_disable.png");
			$('#wa-loader,#wa-overlay').show();
			Stripe.createToken({
				number: $('.card-number').val(),
				cvc: $('.card-cvc').val(),
				exp_month: $('.card-expiry-month').val(),
				exp_year: $('.card-expiry-year').val()
			}, stripeResponseHandler);
			// prevent the form from submitting with the default action
		} catch (e) {
			$('.submit-button').removeAttr("disabled");
			$('.submit-button').attr("src", "images/next.png");
			$('#wa-loader,#wa-overlay').hide();
			alert(e);
		}
		return false;
	});
});

function stripeResponseHandler(status, response) {
	//test 4242424242424242
	$('#wa-loader,#wa-overlay').hide();
	if (response.error) {
		$(".payment-errors").show().text(response.error.message);
		$('.submit-button').removeAttr("disabled");
		$('.submit-button').attr("src", "images/next.png");
	} else {
		var form = $(".payment-form");
		// token contains id, last4, and card type
		var token = response['id'];
		// insert the token into the form so it gets submitted to the server
		form.append("<input type='hidden' name='stripeToken' value='" + token + "'/>");
		// and submit
		form.get(0).submit();
	}
}

function validate_input() {
	if (!isValidEmailAddress($('#customer_email').val())) {
		showPaymentError('Please enter a valid email.');
		return false;
	}
	return true;
}

function isValidEmailAddress(emailAddress) {
	var pattern = new RegExp(/^((([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+(\.([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+)*)|((\x22)((((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(([\x01-\x08\x0b\x0c\x0e-\x1f\x7f]|\x21|[\x23-\x5b]|[\x5d-\x7e]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(\\([\x01-\x09\x0b\x0c\x0d-\x7f]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))))*(((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(\x22)))@((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?$/i);
	return pattern.test(emailAddress);
};

function showPaymentError(err) {
	$(".payment-errors").show().text(err);
}
</script>

@stop