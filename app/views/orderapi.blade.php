<?php
error_reporting(0);
@ini_set('display_errors', 0);
$libPath = base_path(). "/whoisxmlapi_4";
require_once $libPath."/api-products.php";
require_once $libPath."/httputil.php";
require_once $libPath."/util.php";

require_once $libPath."/users/utils.inc";
require_once $libPath."/users/users.inc";
?>
@extends('layouts.master')

@section('title')
Order API
@stop

@section('styles')
@parent
{{ HTML::style('css/Orderapi.css') }}
@stop

@section('content')
<div class="main-content">
	<div class="row wa-searchbox-radio">
		<div class="col-xs-12 wa-auto-margin">
			<div class="row">
				<div class="col-sm-6 col-xs-12 wa-search-eg-box wa-search-eg-box-Orderapi">
					<form id="whoisform" name="whoisform" action="{{ URL::to('whoislookup.php') }}">
						<div class="form-group has-feedback wa-search-box">
							<input type="text" class="form-control wa-search wa-search-Orderapi" name="domainName" id="wa-search-whoislookup" placeholder="Whois Lookup">
							<span class="glyphicon glyphicon-search form-control-feedback wa-cursor" id="wa-search-icon-whoislookup"></span>
							<div class="wa-exapple wa-example-Orderapi">Example: google.com or 74.125.45.100</div>
							<div class="wa-radio-inputs wa-radio-xml-Josn wa-radio-xml-Josn-Orderapi">
								<div class="wa-radio-input wa-radio-input-xml wa-radio-input-xml-Orderapi">
									<input type="radio" value="xml" checked id="wa-radio-xml" class="wa-cursor wa-radio-orange-bar wa-field-input-selection wa-field-input-selection-Orderapi wa-api-res-type" name="outputFormat">
									<label for="wa-radio-xml" class="wa-cursor wa-field-value-selection wa-field-value-selection-xml wa-field-value-selection-Orderapi" id="wa-lbl-XMl">XML</label>
									<div class="wa-home-radio-outerCircle">
										<div class="wa-home-radio-innerCircle"></div>
									</div>
								</div>
								<div class="wa-radio-input wa-radio-input-json wa-radio-input-json-Orderapi">
									<input type="radio" value="json" id="wa-radio-json" class="wa-cursor wa-radio-orange-bar wa-field-input-selection wa-field-input-selection-Orderapi wa-api-res-type" name="outputFormat">
									<label for="wa-radio-json" class="wa-cursor wa-field-value-selection wa-field-value-selection-json wa-field-value-selection-Orderapi" id="wa-lbl-JSON">JSON</label>
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
				<div class="col-sm-6 col-xs-12 wa-btn wa-btn-Orderapi">
					<div class="row">
						<div class="col-sm-offset-4 col-sm-4 col-xs-12 wa-btn-order">
							<a href={{ URL::to('order_paypal.php') }}><button type="button" class="btn btn-default wa-btn wa-btn-orderNow wa-btn-orderNow-Orderapi center-block" id="wa-btn-orderNow-Orderapi">ORDER NOW</button></a>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="row wa-page-title-content-bg">
		<div class="col-xs-12 wa-about-whoisApi wa-xs wa-auto-margin">
			<h1 class="text-center wa-title wa-title-Orderapi">Order API</h1>
		</div>
	</div>
	<div class="row wa-content-bg wa-content-bg-Orderapi">
		<div class="col-xs-12 wa-col-xs-no-padding wa-auto-margin">
			<!--pricing of whois api software package -->
			<div class="row">
				<div class="col-xs-12 wa-box-margin-whoisapi wa-margin-sp">
					<form id="order-api-form" action="{{ $data['form_action'] }}" class="ignore_jssm" onsubmit="return prepare_form();">
						<div class="wa-box wa-box-xs-padding wa-box-Orderapi wa-box-APIproduct-Orderapi wa-top-content-margin">
							<h2 class="wa-section-title wa-section-title-Orderapi wa-section-title-APIproduct-Orderapi text-center">Select the API Products you need</h2>
							<div id="order_api_error" class="errorMsg payment-errors" style="display:none;"></div>
							<div>
								<table class="table table-bordered  wa-content-text wa-table-trheight wa-content-text-Orderapi wa-table-Orderapi wa-table-APIproduct-Orderapi">
									<thead>
										<tr class="wa-table-trheight">
											<th colspan="2">Product name</th>
											<th class="twidth">
												Description
											</th>
											<th class="twidth wa-th-price">
												Price (USD)
											</th>
										</tr>
									</thead>
									<tbody>
										<?php
										$i=0;
										$DEFAULT_QUERIES_VALUE=1000;
										foreach(APIProducts::$api_products as $product_id=>$product_info){
											$checked = $_REQUEST[$product_id] || isset($_REQUEST[$product_id."_queries"]);
											$cl = ($i++%2==0?"evcell":"oddcell");
											$monthly_price=$product_info['monthly_price'];
											$unit_price=$product_info['unit_price'];
											$name=$product_info['name'];
											$des=$product_info['description'];
											$subscribed = APIProducts::isUserSubscribed($user, $product_id);
											$queries_input_name=$product_id."_queries";

											$num_queries = isset($_REQUEST[$queries_input_name]) ? $_REQUEST[$queries_input_name] : $DEFAULT_QUERIES_VALUE;

											if(!(is_numeric_int($num_queries) && $num_queries>=0)){
												$num_queries = $DEFAULT_QUERIES_VALUE;
											}

											?>
											<tr class="wa-table-trheight">
												<td>
													<input type="checkbox" name="<?php echo $product_id?>" value="1" <?php echo $checked  ?"checked":"";?>>
													<input type="hidden" name="<?php echo $queries_input_name?>" value="<?php echo $num_queries?>" >
												</td>
												<td><?php echo $name;?> <?php if($subscribed){?><br/><span class="errorMsg">(Already Subscribed)</span><?php }?></td>
												<td><?php echo $des;?></td>
												<td>$<?php echo $monthly_price?>/month + <div>$<?php echo $unit_price;?>/query</div></td>
											</tr>
											<?php }?>
										</tr>
									</tbody>
								</table>
							</div>
							<div class="row">
								<div class="col-xs-12">
									<div class="pull-right"><button type="submit" class="btn btn-default wa-btn submit-button wa-btn-orange wa-btn-next-Orderapi wa-btn-Orderapi" id="wa-btn-next-Orderapi">Next</button></div>
								</div>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>
@stop

@section('scripts')
@parent
<script type="text/javascript">
	function prepare_form(){
		$('#order_api_error').html('').hide();
		if(!validate_form())return false;
		var form = $('#order-api-form');
		var chkboxes=form.find('input[type=checkbox]');
		for(var i=0;i<chkboxes.length;i++){
			var ck=$(chkboxes[i]);
			if(!ck.prop('checked')){
				ck.siblings('input[type=hidden]').remove();

			}
		}
		return true;
	}

	function validate_form(){
		var chkboxes= $('#order-api-form').find('input[type=checkbox]');
		for(var i=0;i<chkboxes.length;i++){
			if($(chkboxes[i]).prop('checked')){
				return true;
			}
		}
		show_error('You must select at least one API Product.');
		return false;
	}

	function show_error(msg){
		$('#order_api_error').html(msg).show();
	}
</script>
@stop