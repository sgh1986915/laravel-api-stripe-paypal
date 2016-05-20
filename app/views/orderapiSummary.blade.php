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
require_once $libPath . "/users/users.inc";
require_once $libPath . "/util/number_util.php";

my_session_start();
$user=$_SESSION['myuser'];
$i=0;
$DEFAULT_QUERIES_VALUE=1000;

?>

@extends('layouts.master')

@section('title')
Order-api-Summary
@stop

@section('styles')
@parent
{{ HTML::style('css/orderapiSummary.css') }}
@stop

@section('content')
<div class="main-content">
	<div class="row wa-searchbox-radio">
		<div class="col-xs-12 wa-auto-margin">
			<div class="row">
				<div class="col-sm-6 col-xs-12 wa-search-eg-box wa-search-eg-box-orderapiSummary">
					<form id="whoisform" name="whoisform" action="{{ URL::to('whoislookup.php') }}">
						<div class="form-group has-feedback wa-search-box">
							<input type="text" class="form-control wa-search wa-search-orderapiSummary" name="domainName" id="wa-search-whoislookup" placeholder="Whois Lookup">
							<span class="glyphicon glyphicon-search form-control-feedback wa-cursor" id="wa-search-icon-whoislookup"></span>
							<div class="wa-exapple wa-example-orderapiSummary">Example: google.com or 74.125.45.100</div>
							<div class="wa-radio-inputs wa-radio-xml-Josn wa-radio-xml-Josn-orderapiSummary">
								<div class="wa-radio-input wa-radio-input-xml wa-radio-input-xml-orderapiSummary">
									<input type="radio" value="xml" checked id="wa-radio-xml" class="wa-cursor wa-radio-orange-bar wa-field-input-selection wa-field-input-selection-orderapiSummary wa-api-res-type" name="outputFormat">
									<label for="wa-radio-xml" class="wa-cursor wa-field-value-selection wa-field-value-selection-xml wa-field-value-selection-orderapiSummary" id="wa-lbl-XMl">XML</label>
									<div class="wa-home-radio-outerCircle">
										<div class="wa-home-radio-innerCircle"></div>
									</div>
								</div>
								<div class="wa-radio-input wa-radio-input-json wa-radio-input-json-orderapiSummary">
									<input type="radio" value="json" id="wa-radio-json" class="wa-cursor wa-radio-orange-bar wa-field-input-selection wa-field-input-selection-orderapiSummary wa-api-res-type" name="outputFormat">
									<label for="wa-radio-json" class="wa-cursor wa-field-value-selection wa-field-value-selection-json wa-field-value-selection-orderapiSummary" id="wa-lbl-JSON">JSON</label>
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
				<div class="col-sm-6 col-xs-12 wa-btn wa-btn-orderapiSummary">
					<div class="row">
						<div class="col-sm-offset-4 col-sm-4 col-xs-12 wa-btn-order">
							<a href={{ URL::to('order_paypal.php') }}><button type="button" class="btn btn-default wa-btn wa-btn-orderNow wa-btn-orderNow-orderapiSummary center-block" id="wa-btn-orderNow-orderapiSummary">ORDER NOW</button></a>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="row wa-page-title-content-bg">
		<div class="col-xs-12 wa-about-whoisApi wa-xs wa-auto-margin">
			<h1 class="text-center wa-title wa-title-orderapiSummary">Order API Summary</h1>
		</div>
	</div>
	<div class="row wa-content-bg wa-content-bg-orderapiSummary">
		<div class="col-xs-12 wa-col-xs-no-padding wa-auto-margin">
			<!--pricing of whois api software package -->
			<div class="row">
				<div class="col-xs-12 wa-box-margin-whoisapi wa-margin-sp">
					<form id="order-api-summary-form" action="{{ $data['form_action'] }}" class="ignore_jssm">
						<div class="wa-box wa-top-content-margin wa-box-xs-padding wa-box-orderapiSummary wa-box-confirmProduct-orderapiSummary">
							<h2 class="wa-section-title wa-section-title-orderapiSummary wa-section-title-confirmProduct-orderapiSummary text-center">Confirm Product Selections</h2>
							<div class="wa-content-text wa-content-text-orderapiSummary wa-content-spacing wa-content-text-confirmProduct-orderapiSummary text-center">You have selected the API products below. Please confirm your selections and add them to your shopping cart.</div>
							<div>
								<table class="table table-bordered table-striped wa-content-text wa-table-trheight wa-content-text-orderapiSummary wa-table-orderapiSummary wa-table-confirmProduct-orderapiSummary">
									<thead>
										<tr class="wa-table-trheight">
											<th  >Product name</th>
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
										foreach(APIProducts::$api_products as $product_id=>$product_info) {
											$checked = isset($_REQUEST[$product_id]) ? $_REQUEST[$product_id] : false;
											$cl = ($i++%2==0?"evcell":"oddcell");
											$monthly_price=$product_info['monthly_price'];
											$unit_price=$product_info['unit_price'];
											$name=$product_info['name'];

											$queries_input_name=$product_id."_queries";
											$queries_input_value = isset($_REQUEST[$queries_input_name]) ? $_REQUEST[$queries_input_name] : false;
											if(!(is_numeric_int($queries_input_value) && $queries_input_value>=0)){
												$queries_input_value = $DEFAULT_QUERIES_VALUE;
											}
											$subscribed= !$checked;

											if(!$subscribed)$subscribed = APIProducts::isUserSubscribed($user, $product_id);

											if(!$subscribed){
												?>
												<tr class="wa-table-trheight">
													<td class="<?php echo $cl?>"><?php echo $name;?>
														<input type="hidden" name="<?php echo $product_id?>" value="1"?>
													</td>
													<td class="<?php echo $cl?>">$<?php echo $monthly_price?>/month </td>
													<td class="<?php echo $cl?>"> $<?php echo $unit_price;?>/query</td>
													<td class="<?php echo $cl?>">$<?php echo $monthly_price?></td>

													<td colspan=2 class="<?php echo $cl?>"><span class="wa-link wa-cursor wa-textDecoration"><a href="" class="ignore_jssm remove">remove </a></span></td>
												</tr>

												<?php
												if(isset($_REQUEST[$queries_input_name])) {
													$queries_price=APIProducts::getQueryPrice($product_id,$queries_input_value);
													?>

													<tr class="wa-table-trheight">
														<td colspan="2" class="<?php echo $cl?>">Add <input name="<?php echo $queries_input_name?>" type="text" size="4" value="<?php echo $queries_input_value?>" class="element text xsmall form-control wa-textbox-width"/> <?php echo $name;?> queries at $<?php echo $unit_price;?>/query </td>
														<td class="<?php echo $cl?>"><?php echo format_price($queries_price)?></td>
														<td class="<?php echo $cl?>"></td>
														<td class="<?php echo $cl?>"><span class="wa-link wa-cursor wa-textDecoration"><a href="" class="ignore_jssm remove">remove</a></span></td>
														<td class="<?php echo $cl?>"><span class="wa-link wa-cursor wa-textDecoration"><a href="" class="ignore_jssm update_price">update</a></span></td>
													</tr>
													<?php
												}
											}
										}
										?>
									</tbody>
								</table>
							</div>
							<div class="row">
								<div class="col-xs-12">
									<div class="pull-left"><a href="{{ URL::to('order-api.php') }}"><button type="button" class="btn btn-default wa-btn wa-btn-orange wa-btn-next-orderapiSummary wa-btn-orderapiSummary" id="wa-btn-next-orderapiSummary">Previous</button></a></div>
									<div class="pull-right"><button type="submit" class="btn btn-default submit-button wa-btn wa-btn-orange wa-btn-next-orderapiSummary wa-btn-orderapiSummary" id="wa-btn-next-orderapiSummary">Next</button></div>
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
	function next(){
		$("#order-api-summary-form").submit();
		return false;
	};

	$(document).ready(function(){
		$('a.update_price').click(function(ev){
			ev.preventDefault();
			var form = $(this).closest('form').get(0);
			$(form).attr('action','{{ Request::fullUrl() }}');
			form.submit();
		});
		$('a.remove').click(function(ev){
			ev.preventDefault();
			var form = $(this).closest('form').get(0);
			$(form).attr('action','{{ Request::fullUrl() }}');
			var input=$(this).closest('tr');
			$(input).remove();
			form.submit();
		});
	});

</script>

@stop