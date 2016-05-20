<?php
$libPath = base_path(). "/whoisxmlapi_4";
?>
<?php
error_reporting(0);
@ini_set('display_errors', 0);
$libPath = base_path(). "/whoisxmlapi_4";
require_once $libPath . "/api-products.php";
require_once $libPath . "/httputil.php";
require_once $libPath . "/util.php";
require_once $libPath . "/users/utils.inc";
require_once $libPath . "/util/number_util.php";
require_once $libPath . "/util/string_util.php";
require_once $libPath . "/business_def.php";

$products=array();
$total_price=0;

foreach(APIProducts::$api_products as $product_id=>$p){
	if($_REQUEST[$product_id]){
		$products[]=array('product_id'=>$product_id, 'subscription'=>1);
			//$total_price+=get_query_price($product_id, $_REQUEST[$query]);
	}
	$product_query=$product_id."_queries";
	if($_REQUEST[$product_query]){
		$num_queries=$_REQUEST[$product_query];
		$products[]=array('product_id'=>$product_id,'queries'=>$num_queries);
	}
}
add_products_to_cart($products);
function add_products_to_cart($products){
	my_session_start();
	$_SESSION['products']=$products;

}

APIProducts::check_remove_cart_product();
APIProducts::check_update_cart_product();
$products= $_SESSION['products'];
?>

@extends('layouts.master')

@section('title')
Order-api-Checkout
@stop

@section('styles')
@parent
{{ HTML::style('css/orderapiCheckout.css') }}
@stop

@section('scripts')
@parent
{{ HTML::script('js/visa_options.js') }}
@stop

@section('content')
<div class="main-content">
	<div class="row wa-searchbox-radio">
		<div class="col-xs-12 wa-auto-margin">
			<div class="row">
				<div class="col-sm-6 col-xs-12 wa-search-eg-box wa-search-eg-box-orderapiCheckout">
					<form id="whoisform" name="whoisform" action="{{ URL::to('whoislookup.php') }}">
						<div class="form-group has-feedback wa-search-box">
							<input type="text" class="form-control wa-search wa-search-orderapiCheckout" name="domainName" id="wa-search-whoislookup" placeholder="Whois Lookup">
							<span class="glyphicon glyphicon-search form-control-feedback wa-cursor" id="wa-search-icon-whoislookup"></span>
							<div class="wa-exapple wa-example-orderapiCheckout">Example: google.com or 74.125.45.100</div>
							<div class="wa-radio-inputs wa-radio-xml-Josn wa-radio-xml-Josn-orderapiCheckout">
								<div class="wa-radio-input wa-radio-input-xml wa-radio-input-xml-orderapiCheckout">
									<input type="radio" value="xml" checked id="wa-radio-xml" class="wa-cursor wa-radio-orange-bar wa-field-input-selection wa-field-input-selection-orderapiCheckout wa-api-res-type" name="outputFormat">
									<label for="wa-radio-xml" class="wa-cursor wa-field-value-selection wa-field-value-selection-xml wa-field-value-selection-orderapiCheckout" id="wa-lbl-XMl">XML</label>
									<div class="wa-home-radio-outerCircle">
										<div class="wa-home-radio-innerCircle"></div>
									</div>
								</div>
								<div class="wa-radio-input wa-radio-input-json wa-radio-input-json-orderapiCheckout">
									<input type="radio" value="json" id="wa-radio-json" class="wa-cursor wa-radio-orange-bar wa-field-input-selection wa-field-input-selection-orderapiCheckout wa-api-res-type" name="outputFormat">
									<label for="wa-radio-json" class="wa-cursor wa-field-value-selection wa-field-value-selection-json wa-field-value-selection-orderapiCheckout" id="wa-lbl-JSON">JSON</label>
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
				<div class="col-sm-6 col-xs-12 wa-btn wa-btn-orderapiCheckout">
					<div class="row">
						<div class="col-sm-offset-4 col-sm-4 col-xs-12 wa-btn-order">
							<a href={{ URL::to('order_paypal.php') }}><button type="button" class="btn btn-default wa-btn wa-btn-orderNow wa-btn-orderNow-orderapiCheckout center-block" id="wa-btn-orderNow-orderapiCheckout">ORDER NOW</button></a>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="row wa-page-title-content-bg">
		<div class="col-xs-12 wa-about-whoisApi wa-xs wa-auto-margin">
			<h1 class="text-center wa-title wa-title-orderapiCheckout">Shopping Cart</h1>
		</div>
	</div>
	<div class="row wa-content-bg wa-content-bg-orderapiCheckout">
		<div class="col-xs-12 wa-col-xs-no-padding wa-auto-margin">
			<!--pricing of whois api software package -->
			<div class="row">
				<div class="col-xs-12 wa-box-margin-whoisapi wa-margin-sp">
					<form id="order-api-summary-form" action="{{ $data['form_action'] }}" class="ignore_jssm payment-form" method="post">
						<input type="hidden" name="update" value="0">
						<input type="hidden" name="remove">
						<div class="wa-box wa-box-xs-padding wa-top-content-margin wa-box-orderapiCheckout wa-box-shopcart-orderapiCheckout">
							<h2 class="wa-section-title wa-section-title-orderapiCheckout wa-section-title-shopcart-orderapiCheckout text-center">Shopping Cart</h2>
							<div>
								<table class="table table-bordered table-striped wa-content-text wa-table-trheight wa-content-text-orderapiCheckout wa-table-orderapiCheckout wa-table-shopcart-orderapiCheckout">
									<thead>
										<tr class="wa-table-trheight">
											<th>Product name</th>
											<th class="twidth">
												Monthly Subscription
											</th>
											<th class="twidth">
												Access Price
											</th>
											<th class="twidth">
												Total
											</th>
											<th colspan="2" class="twidth">

											</th>
										</tr>
									</thead>
									<tbody>
										<?php
										$i=0;
										$link_params=array();
										$total_price = APIProducts::computeTotalPrice($products);
										foreach($products as $product){
											$product_id=$product['product_id'];

											$cl = ($i++%2==0?"evcell":"oddcell");
											$product_info=APIProducts::$api_products[$product_id];

											$monthly_price=$product_info['monthly_price'];
											$unit_price=$product_info['unit_price'];
											$name=$product_info['name'];

											if($product['subscription']){
												$params = array_merge( $_REQUEST, array('remove'=>$product_id."_subscription"));

												$remove_url = build_url( $_SERVER['PHP_SELF'], $params);

												$link_params[$product_id]=1;
												?>
												<tr class="wa-table-trheight">
													<td class="<?php echo $cl?>"><?php echo $name;?>
														<input type="hidden" name="<?php echo $product_id?>" value="1"/>
													</td>
													<td class="<?php echo $cl?>">$<?php echo $monthly_price?>/month </td>
													<td class="<?php echo $cl?>"> $<?php echo $unit_price;?>/query</td>
													<td class="<?php echo $cl?>">$<?php echo $monthly_price?></td>
													<td colspan=2 class="<?php echo $cl?>"><span class="wa-link wa-cursor wa-textDecoration"><a href="" class="ignore_jssm remove">remove </a></span></td>
												</tr>
												<?php }
												else if (isset($product['queries'])) {
													$queries_input_name=$product_id."_queries";
													$queries_input_value=$product['queries'];

													$queries_price=APIProducts::getQueryPrice($product_id,$queries_input_value);

													$link_params[$queries_input_name]=$queries_input_value;

													?>
													<tr class="wa-table-trheight">
														<td colspan="2" class="<?php echo $cl?>">Add <input name="<?php echo $queries_input_name?>" type="text" size="4" value="<?php echo $queries_input_value?>" class="element text xsmall form-control wa-textbox-width"/> <?php echo $name;?> </td>
														<td class="<?php echo $cl?>">  $<?php echo $unit_price;?>/query</td>
														<td class="<?php echo $cl?>"><?php echo format_price($queries_price)?></td>
														<td class="<?php echo $cl?>"><span class="wa-link wa-cursor wa-textDecoration"><a href="" class="ignore_jssm remove">remove </a></span></td>
														<td class="<?php echo $cl?>"><span class="wa-link wa-cursor wa-textDecoration"><a href="" class="ignore_jssm update_price">update </a></span></td>
													</tr>
													<?php
												}
											}
											?>
										<tr>
											<td colspan="6" class="text-right">Total Price: <?php echo format_price($total_price)?></td>
										</tr>
									</tbody>
								</table>
							</div>
							<div class="row">
								<div class="col-xs-12">
									<h2 class="wa-section-title wa-section-title-orderapiCheckout wa-section-title-shopcart-orderapiCheckout text-center">Payment Options</h2>
									<div class="wa-box wa-box-orderapiCheckout wa-box-paymentoption-orderapiCheckout">
										<div class="row wa-paymentimage-orderapiCheckout">
											<div class="col-xs-12">
												<div class="wa-paymentradio">
													<input type="radio" name="pay_choice" id="pp_pay_choice" value="pp" class="wa-radio-paymentOptions" checked>
													<label for="pp_pay_choice">{{ HTML::image('images/paypal_1.png', 'Responsive image', array('class'=>'img-responsive wa-img-payPal1')) }}</label>
												</div>
											</div>
											<div class="col-xs-12">
												<div class="wa-paymentradio">
													<input type="radio" name="pay_choice" id="cc_pay_choice" value="cc" class="wa-radio-paymentOptions">
													<label for="cc_pay_choice">{{ HTML::image('images/paypal2.png', 'Responsive image', array('class'=>'img-responsive wa-img-payPal1')) }}</label>
												</div>
												<div class="errorMsg payment-errors" style="display:none;"></div>
												<div class="wa-box wa-box-cardnumber wa-box-margin-whoisapi wa-paymentradioshow" style="display:none" id="cc_form">
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
														<div class="col-xs-4 wa-col-exp-date" style="padding-left: 0;"><input type="text" class="form-control card-expiry-month" id="wa-lbl-exp-date"></div>
														<div class="col-xs-1 wa-slash text-center">/</div>
														<div class="col-xs-7 wa-col-exp-date wa-col2-exp-date" style="padding-right: 0;"><input type="text" class="form-control card-expiry-year" id="wa-lbl-exp-date"></div>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-xs-12">
									<a href="{{ $data['cancel_action'] }}" class="pull-left"><button type="button" class="btn btn-default wa-btn wa-btn-orange wa-btn-next-orderapiCheckout wa-btn-orderapiCheckout" id="wa-btn-next-orderapiCheckout">Cancel</button></a>
									<div class="pull-right"><button type="submit" class="btn btn-default submit-button wa-btn wa-btn-orange wa-btn-next-orderapiCheckout wa-btn-orderapiCheckout submit-button" id="wa-btn-next-orderapiCheckout">Next</button></div>
									<a href="{{ $data['previous_action'] }}" class="pull-right"><button type="button" class="btn btn-default wa-btn wa-btn-orange wa-btn-next-orderapiCheckout wa-btn-orderapiCheckout" id="wa-btn-next-orderapiCheckout">Previous</button></a>
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

<script type="text/javascript">
	$(document).ready(function(){
		$('a.update_price').click(function(ev){
			ev.preventDefault();
			var form = $(this).closest('form').get(0);
			$(form).attr('action','{{ Request::fullUrl() }}');
			$(form).find('input[name=update]').val(1);
			form.submit();
		});
		$('a.remove').click(function(ev){
			ev.preventDefault();
			var form = $(this).closest('form').get(0);
			$(form).attr('action','{{ Request::fullUrl() }}');
			$(form).find('input[name=update]').val(1);
			var input=$(this).closest('tr').find('input').get(0);
			var to_remove=$(input).attr('name');
			$(form).find('input[name=remove]').val(to_remove);
			form.submit();
		});
	});
</script>

<script type="text/javascript" src="https://js.stripe.com/v1/"></script>

<script type="text/javascript">

// this identifies your website in the createToken call below
Stripe.setPublishableKey('<?php echo $STRIP_API_CURRENT_PUBLIC_KEY?>');
$(document).ready(function() {
	$(".payment-form").bind('submit', function(event) {
		if (!$('#cc_pay_choice').prop('checked')) return true;
		event.preventDefault();
		try {
			// disable the submit button to prevent repeated clicks
			$('.submit-button').attr("disabled", "disabled");
			var link = $('.submit-button').parent('a');
			if (link) {
				href = link.attr('href');
				link.attr("old_href", href);
				link.removeAttr('href');
			}
			$('.submit-button').attr("src", "/images/next_disable.png");
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
			$('.submit-button').attr("src", "/images/next.png");
			if (link) {
				link.attr('href', link.attr('old_href'));
				link.removeAttr('old_href');
			}
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
		$('.submit-button').attr("src", "/images/next.png");
		var link = $('.submit-button').parent('a');
		if (link) {
			link.attr('href', link.attr('old_href'));
			link.removeAttr('old_href');
		}
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

function showPaymentError(err) {
	$(".payment-errors").show().text(err);
}
</script>
@stop
