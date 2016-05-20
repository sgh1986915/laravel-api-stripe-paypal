<?php
error_reporting(0);
@ini_set('display_errors', 0);
$libPath = base_path(). "/whoisxmlapi_4";
require_once $libPath . "/users/users.conf";
require_once $libPath . "/users/users.inc";
require_once $libPath . "/util.php";
require_once $libPath . "/users/account.php";
require_once $libPath . "/business_def.php";
require_once $libPath . "/price.php";

$features = array("Registered domains for current day"=>array(true, true),
"Registered domains for current day and historic days" => array(false, true),
"Daily downloads" => array(true, true),
//"Daily email list" => array(false, false, true),
"Historic data "=>array("1 day", "3 months+")
);

$spk_sel = (isset($_REQUEST['data_edition']) ? $_REQUEST['data_edition'] : 0);

$spks = $domainNameDataZoneFileEditionsAll;

$yearlyPriceDisplays=array();
for($yi=0;$yi<count($domainNameDataZoneFilePricesAll);$yi++){
	$yearlyPriceDisplays[]=	"$".computeDomainNameZoneFileDataYearlyPrice($yi) ."/year";
}
$yearlyPriceDisplay=$yearlyPriceDisplays[$spk_sel];

$pay_choice="cc";
if($_REQUEST['pay_choice']){
	$pay_choice=$_REQUEST['pay_choice'];
}
$customer_email="";
if($_REQUEST['customer_email']){
	$customer_email=$_REQUEST['customer_email'];
}
else{
	my_session_start();

	$user=$_SESSION['myuser'];
	if(is_object($user)){
		$customer_email=$user->email;
	}
}

function wrap($s){
	if($s === true){
		$srcURL = asset('images/check.png');
		return "<img src=\"".$srcURL."\" class=\"img-responsive wa-img wa-img-check-td\">";
	}
	else if ($s === false) return "";
	return $s;
}
?>

@extends('layouts.master')

@section('title')
All Registered Domains
@stop

@section('styles')
@parent
{{ HTML::style('css/newDomain.css') }}
@stop

@section('content')
<div class="main-content">
	<div class="row wa-searchbox-radio">
		<div class="col-xs-12 wa-auto-margin">
			<div class="row">
				<div class="col-sm-6 col-xs-12 wa-search-eg-box wa-search-eg-box-newDomain">
					<form id="whoisform" name="whoisform" action="{{ URL::to('whoislookup.php') }}">
						<div class="form-group has-feedback wa-search-box wa-search-box-newDomain">
							<input type="text" class="form-control wa-search wa-search-newDomain" name="domainName" id="wa-search-whoislookup" placeholder="Whois Lookup">
							<span class="glyphicon glyphicon-search form-control-feedback wa-cursor" id="wa-search-icon-whoislookup"></span>
							<div class="wa-exapple wa-example-newDomain">Example: google.com or 74.125.45.100</div>
							<div class="wa-radio-inputs wa-radio-xml-Josn wa-radio-xml-json-newDomain">
								<div class="wa-radio-input wa-radio-input-xml wa-radio-input-whois">
									<input type="radio" value="xml" checked id="wa-radio-xml" class="wa-cursor wa-radio-orange-bar wa-field-input-selection wa-field-input-selection-newDomain wa-api-res-type" name="outputFormat">
									<label for="wa-radio-xml" class="wa-cursor wa-field-value-selection  wa-field-value-selection-newDomain" id="wa-lbl-XMl">XML</label>
									<div class="wa-home-radio-outerCircle">
										<div class="wa-home-radio-innerCircle"></div>
									</div>
								</div>
								<div class="wa-radio-input wa-radio-input-json wa-radio-input-whois">
									<input type="radio" value="json" id="wa-radio-json" class="wa-cursor wa-radio-orange-bar wa-field-input-selection wa-field-input-selection-newDomain wa-api-res-type" name="outputFormat">
									<label for="wa-radio-json" class="wa-cursor wa-field-value-selection  wa-field-value-selection-newDomain" id="wa-lbl-JSON">JSON</label>
									<div class="wa-home-radio-outerCircle wa-home-radio-outerCircle-newDomain">
										<div class="wa-home-radio-innerCircle wa-home-radio-innerCircle-newDomain" style="display: none;"></div>
									</div>
								</div>
							</div>
							<div id="wa-user-stats" class="wa-user-stats"><?php include_once $libPath . "/user_stats.php"; ?>
							</div>
						</div>
					</form>
				</div>
				<div class="col-sm-6 col-xs-12 wa-btn wa-btn-newDomain">
					<div class="row">
						<div class="col-sm-offset-4 col-sm-4 col-xs-12 wa-btn-order">
							<a href={{ URL::to('order_paypal.php') }}><button type="button" class="btn btn-default wa-btn-orderNow wa-btn-orderNow-newDomain center-block" id="wa-btn-orderNow-newDomain">ORDER NOW</button></a>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="row wa-page-title-content-bg">
		<div class="col-xs-12 wa-about-whoisApi wa-auto-margin">
			<h1 class="text-center wa-title wa-title-newDomain wa-title-newregister-newDomain">All Registered Domains</h1>
		</div>
	</div>
	<div id="wa-page-content">
		<div class="row wa-content-bg wa-content-bg-newDomain">
			<div class="col-xs-12 wa-col-xs-no-padding wa-auto-margin">
				<form action="{{ URL::to('whoisxmlapi_4/order_process.php') }}"  class="ignore_jssm payment-form" method="post">
					<!--pricing of whois api software package -->
					<div class="row">
						<div class="col-xs-12 wa-box-margin-whoisapi">
							<div class="wa-box wa-box-xs-padding wa-box-newDomain wa-box-listnewregister-newDomain wa-top-content-margin">
								<h2 class="wa-section-title wa-section-title-newDomain wa-section-title-listnewregister-newDomain text-center">List of all Registered domains & Zone files</h2>
								<div class="wa-content-text wa-content-spacing wa-content-text-newDomain wa-content-listnewregister-newDomain">   Supported TLDs are ".com, .net, .org, .us, .biz, .mobi, .info, .pro, .coop, .asia, .name, .tel, .aero" plus <span class="wa-link wa cursor wa-textDecoration">{{ HTML::link('https://www.whoisxmlapi.com/support/supported_ngtlds.php', 'hundreds of new gTLDs!') }} </span>.    Simply check the edition you want and click on next to purchase.</div>
								<input type="hidden" name="order_type" value="domain_data_zone_file">
								<div>
									<div class="wa-table-title wa-section-title-newDomain wa-section-title-list newregister-allDomain">BELOW PRICING IS FOR MONTHLY SUBSCRIPTION PLANS ONLY</div>
									<table class="table table-bordered table-striped wa-content-text wa-content-text-newDomain wa-table-newDomain wa-table-listnewregister-newDomain">
										<thead>
											<tr>
												<th>Features</th>
												<?php for($i=0;$i<count($spks);$i++) {
													$spk =$spks[$i];
													$sel = ($spk_sel == $i ? "checked" : "");
													?>
													<th>
														<input type="radio" name="data_edition" class="wa-input-radio-table" id="wa-optionsRadios1-th-newDomain" value="{{ $i }}" {{ $sel }}>
														<div class="wa-lbl-radio">{{ $spk }}</div>
													</th>
													<?php
												} ?>
											</tr>
										</thead>
										<tbody>
											<?php
											$i = 0;
											foreach($features as $key=>$val){

												$cl = ($i%2==0?"evcell":"oddcell");
												$i++;
												$help = "";
												if($helps[$key]){
													$help="<span style=\"padding-left:5px\"><image title=\"" . $helps[$key] . "\" src=\"images/help_16x16.gif\"></span>";
												}
												?>
												<tr class="wa-table-trheight">
													<td><?php echo $key  . $help ?></td>
													<?php foreach($val as $feature_flag){?>
													<td><?php echo wrap($feature_flag)?></td>
													<?php
												} ?>

											</tr>
											<?php
										} ?>
										<tr class="wa-table-trheight">
											<td>Price/Month</td>
											<?php
											for($i=0;$i<count($domainNameDataZoneFilePricesAll);$i++) {
												$spkPriceDisp = '$'.$domainNameDataZoneFilePricesAll[$i];
												if($domainNameDataZoneFileDiscountsAll[$i] > 0) {

													$discountPrice = sprintf("%d", $domainNameDataZoneFilePricesAll[$i] * (1.0-$domainNameDataZoneFileDiscountsAll[$i]));
													$discountPerc = sprintf("%d",$domainNameDataZoneFileDiscountsAll[$i] * 100);

													$spkPriceDisp = "<div style=\"color:red\" title=\"$discountPerc% off\"><del >" . $spkPriceDisp. "</del><br/>". "$".$discountPrice . " </div>";
												}
												?>
												<td class="text-center">{{ $spkPriceDisp }}</td>
												<?php
											} ?>
										</tr>
									</tbody>
								</table>
							</div>
							<!-- <div class="wa-content-text wa-content-text-newDomain wa-content-removal-newDomain">* "Removal of Proxies" means that all whois records with a whois guard/whois proxy (used by registrant to hide their identities) are removed.</div> -->
						</div>
					</div>
				</div>
				<div class="row wa-licenseterms-newdomain wa-payment-container">
					<div class="col-sm-12 col-xs-12 wa-box-width-xs wa-box-margin-whoisapi">
						<div class="wa-box wa-box-xs-padding wa-box-newDomain wa-box-paymentoption-newDomain">
							<h2 class="wa-section-title wa-section-title-newDomain wa-section-title-paymentoption-newDomain text-center">Payment options</h2>
							<div class="wa-section-title-newDomain wa-section-title-securesite-newDomain">{{ HTML::image('images/Icon_LockSm.png', 'Responsive image', array('class'=>'img-responsive wa-img-iconLock')) }}This is a secure site</div>
							<div class="row wa-paymentimage-newDomain">
								<div class="col-xs-12">
									<div class="wa-paymentradio">
										<input type="radio" name="pay_choice" id="pp_pay_choice" value="pp" <?php if($pay_choice=='pp')echo 'checked'?> />
										<label for="pp_pay_choice">{{ HTML::image('images/paypal_1.png', 'Responsive image', array('class'=>'img-responsive wa-img-payPal1')) }}</label>
									</div>
								</div>
								<div class="col-xs-12">
									<div class="wa-paymentradio">
										<input type="radio" name="pay_choice" id="cc_pay_choice" value="cc" <?php if($pay_choice=='cc')echo 'checked'?>>
										<label for="cc_pay_choice">{{ HTML::image('images/paypal2.png', 'Responsive image', array('class'=>'img-responsive wa-img-payPal1')) }}</label>
									</div>
									<div class="errorMsg payment-errors" style="display:none;"></div>
									<div class="wa-box wa-box-cardnumber wa-box-margin-whoisapi wa-paymentradioshow" id="cc_form" <?php if($pay_choice!='cc') echo "style=\"display:none;\"" ?>>
										<div class="form-group">
											<label for="wa-lbl-cn"><span class="wa-field-lbl">Card Number</span></label>
											<input type="text" class="form-control card-number" id="wa-lbl-cn">
										</div>
										<div class="form-group">
											<label for="wa-lbl-cvc"><span class="wa-field-lbl">CVC</span></label>
											<input type="text" class="form-control card-cvc" id="wa-lbl-cvc">
										</div>
										<div class="form-group">
											<div><label for="wa-lbl-exp-date"><span class="wa-field-lbl">Expiration (MM/YYYY)</span></label></div>
											<div class="col-xs-4 wa-col-exp-date"><input type="text" class="form-control card-expiry-month" id="wa-lbl-exp-date"></div>
											<div class="col-xs-1 wa-slash text-center">/</div>
											<div class="col-xs-7 wa-col-exp-date wa-col2-exp-date"><input type="text" class="form-control card-expiry-year" id="wa-lbl-exp-date"></div>
										</div>
										<div class="form-group">
											<label for="customer_email"><span class="wa-field-lbl">Email (where payment confirmation will be sent)</span></label>
											<input type="text" class="form-control" name="customer_email" id="customer_email" value="{{ $user_email }}">
										</div>
									</div>
								</div>
							</div>
							<!-- <div class="wa-box wa-box-margin-whoisapi wa-box-payandsave-newDomain wa-payyearly-checkbox checkbox wa-content-text wa-content-spacing wa-content-text-newDomain wa-content-text-payyearly-newDomain  ">
								<label>
									<input type="checkbox" name="pay_yearly" value="1">Pay yearly and save <?php echo $domainNameDataZoneFileYearlyDiscountAll*100; ?>% (<span id="yearly_price"><?php echo $yearlyPriceDisplay?></span>)
								</label>
							</div> -->

							<div class="col-xs-12 wa-next-btn">
								<div class="pull-right"><button type="submit" class="btn btn-default submit-button wa-btn wa-btn-orange wa-btn-next-newDomain wa-btn-newDomain wa-btn-next-newDomain" id="wa-btn-next-newDomain">Next</button></div>
							</div>
						</div>
					</div>

					<div class="col-sm-12 col-xs-12 wa-box-margin-whoisapi wa-box-width-xs wa-licenseterms-newDomain">
						<div class="wa-box wa-box-xs-padding wa-box-newDomain wa-box-licenseterms-newDomain ">
							<h2 class="wa-section-title wa-section-title-newDomain wa-section-title-licenseterms-newDomain text-center">License terms</h2>
							<div class=" wa-content-text wa-content-text-newDomain wa-content-spacing wa-content-licenseterms-newDomain">All domain name data/list provided are Non-redistributable. It means that you may not redistribute or resell these data to other parties.</div>
						</div>
					</div>
				</div>
				<div class="row wa-licenseterms-newdomain">
					<!-- Privacy Policy -->
					<div class="col-xs-12 wa-box-margin-whoisapi wa-box-width-xs">
						<div class="wa-box wa-box-xs-padding wa-box-newDomain wa-box-paymentsecurity-newDomain  wa-bottom-content-margin">
							<h2 class="wa-section-title wa-section-title-newDomain wa-section-title-paymentsecurtiy-newDomain text-center">Payment security and policy</h2>
							<div class="wa-content-text wa-content-text-newDomain wa-content-spacing wa-content-paymentsecurtiy-newDomain">We take great care to use physical, electronic and procedural precautions, including the use of up 	to 	256-bit data encryption and secure socket layer (SSL) technology. Our precautionary measures meet or exceed all industry standards.
								We do not collect your credit card number, your payment information are encrypted and passed on directly to either paypal or other industry leading PCI-compliant credit card payment processor that uses the highest standard and encryptions. Please <span class="wa-cursor wa-link wa-textDecoration">{{ HTML::link('mailto:support@whoisxmlapi.com', 'contact us') }}</span> if you encounter any issue with the checkout proccess.
							</div>
						</div>
					</div>
				</div>
			</form>
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
var yearly_prices=<?php echo json_encode($yearlyPriceDisplays)?>;
$(document).ready(function() {
	$("input[name='data_edition']").change(function(evt) {
		var data_edition = $(this).val();
		$("#yearly_price").html(yearly_prices[data_edition]);
	});
	$('#cc_pay_choice').click(function(evt) {
		$('#cc_form').show();
	});
	$('#pp_pay_choice').click(function(evt) {
		$('#cc_form').hide();
	});
	$(".payment-form").bind('submit', function(event) {
		var pay_choice = $(this).find('input[name=pay_choice]:checked').val();
		if (pay_choice == 'pp') return true;
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
function validate_input(){
	if(!isValidEmailAddress($('#customer_email').val())){
		showPaymentError('Please enter a valid email.');
		return false;
	}
	return true;

}
function isValidEmailAddress(emailAddress) {
	var pattern = new RegExp(/^((([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+(\.([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+)*)|((\x22)((((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(([\x01-\x08\x0b\x0c\x0e-\x1f\x7f]|\x21|[\x23-\x5b]|[\x5d-\x7e]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(\\([\x01-\x09\x0b\x0c\x0d-\x7f]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))))*(((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(\x22)))@((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?$/i);
	return pattern.test(emailAddress);
};

function showPaymentError(err){
	$(".payment-errors").show().text(err);
}
</script>

@stop