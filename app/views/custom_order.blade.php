<?php
error_reporting(0);
@ini_set('display_errors', 0);
$libPath = base_path(). "/whoisxmlapi_4";

require_once $libPath."/custom_price.php";
require_once $libPath."/httputil.php";
require_once $libPath."/util.php";

require_once $libPath."/users/utils.inc";
require_once $libPath."/users/users.inc";

require_once $libPath."/wc_price.php";
require_once $libPath."/whois-database-price.php";

if(!session_id())session_start();

$pay_choice = get_from_post_get("pay_choice");
if(!$pay_choice) $pay_choice = "pp";
$query_quantity = get_from_post_get("query_quantity");
if(!$query_quantity) $query_quantity = 25000;

$wdb_quantity = $_REQUEST['wdb_quantity'];
if(!$wdb_quantity) $wdb_quantity = 5;

$wdb_type = isset($_REQUEST['wdb_type']) ? $_REQUEST['wdb_type'] : false;
if(!$wdb_type) $wdb_type = 'both';


my_session_start();
$order_username = "";
if(isset($_REQUEST['order_username'])){
	$order_username = $_REQUEST['order_username'];
}
else if(isset($_SESSION['myuser'])){
	$order_username = $_SESSION['myuser']->username;

}

$wc_order_username = "";
if(isset($_REQUEST['wc_order_username'])){
	$wc_order_username = $_REQUEST['wc_order_username'];
}
else if(isset($_SESSION['myuser'])){
	$wc_order_username = $_SESSION['myuser']->username;

}
$wc_license_type = (isset($_REQUEST['wc_license_type'])? $_REQUEST['wc_license_type'] : false);
if(!$wc_license_type){
	$wc_license_type = 'group_license';
}
$num_user_license = (isset($_REQUEST['num_user_license'])? $_REQUEST['num_user_license'] : false);
if(!$num_user_license){
	$num_user_license = 1;
}

$user_license_unit_price = compute_user_license_unit_price($num_user_license);
$user_license_total_price = compute_user_license_price($num_user_license);

$wdb_prefix = $_REQUEST['wdb_prefix'];

/* special_order */
$item_name = $_REQUEST['item_name'];
$payperiod=$_REQUEST['payperiod'];
$price=$_REQUEST['price'];
$payperiod_multiple=$_REQUEST['payperiod_multiple'];
$trial_price=$_REQUEST['trial_price'];
$trial_duration=$_REQUEST['trial_duration'];
$trial_duration_unit=$_REQUEST['trial_duration_unit'];
$end_date = $_REQUEST['end_date'];

$tmparry = explode("-", $end_date);
$exp_year = $tmparry[0];
$exp_month = $tmparry[1];

?>

<?php if(isset($_REQUEST['goto']) && $_REQUEST['goto']){?>
<script type ="text/javascript">
	$(document).ready(function(){
		location.href=location.href+"#<?php echo $_REQUEST['goto']?>";
	});

</script>

<?php
}?>
<?php if(isset($wc_order_error)){?>
<script type ="text/javascript">
	$(document).ready(function(){
		location.href=location.href+"#whois_api_client";
	});

</script>

<?php
}?>

@extends('layouts.master')

@section('title')
Custom Order
@stop

@section('styles')
@parent
{{ HTML::style('css/custom_order.css') }}
@stop

@section('content')
<div class="main-content">
	<div class="row wa-searchbox-radio">
		<div class="col-xs-12 wa-auto-margin">
			<div class="row">
				<div class="col-sm-6 col-xs-12 wa-search-eg-box wa-search-eg-box-customOrder">
					<form id="whoisform" name="whoisform" action="{{ URL::to('whoislookup.php') }}">
						<div class="form-group has-feedback wa-search-box">
							<input type="text" class="form-control wa-search wa-search-customOrder"  name="domainName" id="wa-search-whoislookup" placeholder="Whois Lookup">
							<span class="glyphicon glyphicon-search form-control-feedback wa-cursor" id="wa-search-icon-whoislookup"></span>
							<div class="wa-exapple wa-exapple-customOrder">Example: google.com or 74.125.45.100</div>
							<div class="wa-radio-inputs wa-radio-xml-Josn wa-radio-xml-Josn-customOrder">
								<div class="wa-radio-input wa-radio-input-xml wa-radio-input-xml-customOrder">
									<input type="radio" value="xml" checked id="wa-radio-xml" class="wa-cursor wa-radio-orange-bar wa-field-input-selection wa-field-input-selection-customOrder wa-api-res-type" name="outputFormat">
									<label for="wa-radio-xml" class="wa-cursor wa-field-value-selection wa-field-value-selection-xml wa-field-value-selection-customOrder" id="wa-lbl-XMl">XML</label>
									<div class="wa-home-radio-outerCircle">
										<div class="wa-home-radio-innerCircle"></div>
									</div>
								</div>
								<div class="wa-radio-input wa-radio-input-json wa-radio-input-json-customOrder">
									<input type="radio" value="json" id="wa-radio-json" class="wa-cursor wa-radio-orange-bar wa-field-input-selection wa-field-input-selection-customOrder wa-api-res-type" name="outputFormat">
									<label for="wa-radio-json" class="wa-cursor wa-field-value-selection wa-field-value-selection-json wa-field-value-selection-customOrder" id="wa-lbl-JSON">JSON</label>
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
							<a href={{ URL::to('order_paypal.php') }}><button type="button" class="btn btn-default wa-btn wa-btn-orderNow wa-btn-orderNow-customOrder center-block" id="wa-btn-home-orderNow">ORDER NOW</button></a>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="row wa-page-title-content-bg">
		<div class="col-xs-12 wa-about-whoisApi wa-auto-margin wa-col-xs-no-padding">
			<h1 class="text-center wa-title wa-title-customOrder wa-title-secureorder-customOrder">Custom Order</h1>
		</div>
	</div>
	<div id="wa-page-content">
		<div class="row wa-content-bg wa-content-bg-customOrder">
			<div class="col-xs-12 wa-col-xs-no-padding wa-auto-margin wa-box-margin-whoisapi">

				@if (Input::has('special_order'))
				<div class="row">
					<div class="col-xs-12 wa-auto-margin">
						<div class="wa-box wa-box-xs-padding wa-box-customizedOrder">
							<h2 class="wa-section-title wa-section-title-customizedOrder text-center">Customized Order</h2>
							<form action="{{ $data['custom_form_action'] }}" class="ignore_jssm">
								<input type="hidden" name="special_order" value="1"/>
								<div class="row">
									<div class="col-xs-12">
										<table class="wa-table wa-content-text wa-content-text-customizedOrder wa-table-customizedOrder wa-table-details-customizedOrder">
											<tbody>
												<tr>
													<td>Item Name:</td>
													<td>
														<input type="text" class="form-control wa-item-name" name="item_name" value="{{ $item_name }}">
													</td>
												</tr>
												@if ($payperiod)
												<tr>
													<td>Pay Period:</td>
													<td>
														<input type="text" class="form-control wa-cust-form" name="payperiod" value="{{ $payperiod }}"> {{ ($payperiod_multiple?"x".$payperiod_multiple:"") }}
													</td>
												</tr>
												@endif
												<tr>
													<td>Price:</td>
													<td>
														<input type="text" class="form-control wa-cust-form" name="price" value="{{ $price }}">
													</td>
												</tr>
												@if ($trial_price)
												<tr>
													<td>Initial payment:</td>
													<td>
														<input type="text" class="form-control wa-cust-form" name="trial_price" value="{{ $trial_price }}">
													</td>
													<input type="hidden" name="trial_duration" value="{{ $trial_duration }}">
													<input type="hidden" name="trial_duration_unit" value="{{ $trial_duration_unit }}">
												</tr>
												@endif
												@if ($end_date)
												<tr>
													<td>End Date:</td>
													<td class="form-inline">
														<select class="form-control wa-cust-form" id="expdate_month" name="expdate_month">
															<option value="01">01</option>
															<option value="02">02</option>
															<option value="03">03</option>
															<option value="04">04</option>
															<option value="05">05</option>
															<option value="06">06</option>
															<option value="07">07</option>
															<option value="08">08</option>
															<option value="09">09</option>
															<option value="10">10</option>
															<option value="11">11</option>
															<option value="12">12</option>
														</select>
														<script type="text/javascript">
												            $("#expdate_month").val('<?=$exp_month?>');
												        </script>
														<span> / </span>
												        <select class="form-control wa-cust-form" id="expdate_year" name="expdate_year">
												        </select>
												        <script type="text/javascript">
												            var select = $("#expdate_year"),
												            year = new Date().getFullYear();
												 
												            for (var i = 0; i < 12; i++) {
												                select.append($("<option value='"+(i + year)+"' "+((i + year) == '<?=$exp_year?>' ? "selected" : "")+">"+(i + year)+"</option>"))
												            }
												        </script>
													</td>
												</tr>
												@endif
											</tbody>
										</table>
									</div>
								</div>
								<div class="row">
									<div class="col-xs-12">
										<div class="pull-right"><button type="submit" class="btn btn-default submit-button wa-btn wa-btn-next wa-btn-orange wa-btn-next-customizedOrder wa-btn-customizedOrder">Next</button></div>
									</div>
								</div>
							</form>
						</div>
					</div>
				</div>
				@else
				<form action="{{ $data['custom_form_action'] }}" class="ignore_jssm">
					<input id="choice_cc" name="pay_choice" type="hidden" value="pp">
					@if (Input::has('payperiod_multiple'))
					<input type="hidden" name="payperiod_multiple" value="{{ Input::get('payperiod_multiple') }}" />
					@endif
					@if (Input::has('payto'))
					<input type="hidden" name="payto" value="{{ Input::get('payto') }}" />
					@endif
					@if (Input::has('payto'))
					<input type="hidden" name="payto" value="{{ Input::get('payto') }}" />
					@endif
					<div class="row">
						<div class="col-xs-12">
							<div class="wa-box wa-box-xs-padding wa-top-content-margin wa-box-customOrder wa-box-secureorder-customOrder">
								<h2 class="wa-section-title wa-section-title-customOrder wa-section-title-paypalorder-customOrder text-center">Secure order form</h2>
								<div class="wa-content-text wa-content-text-customOrder wa-content-spacing wa-content-text-paypalorder-customOrder text-center">We offer 2 pricing models, <span class="wa-link wa-cursor wa-textDecoration">{{ HTML::link('#pay_as_you_go', 'pay as you go') }}</span> or <span class="wa-link wa-cursor wa-textDecoration">{{ HTML::link('#wa-customOrder-mplan', 'monthly memberships') }}</span>.</div>
								<div class="form-group wa-secure-form">
									<label for="wa-un-customOrder" class="col-sm-5 control-label wa-field-lbl-uname wa-field-lbl-customOrder">Username of the account to make purchase for:</label>
									<div class="col-sm-5">
										<input type="text" class="form-control" name="order_username" id="wa-un-customOrder" value="{{ $data['username'] }}">
									</div>
								</div>
							</div>
						</div>
					</div>
					@if ($queryCount > 0)
					<div class="row">
						<div class="col-xs-12 wa-box-width-xs wa-box-margin-customOrder">
							<div class="wa-box wa-box-xs-padding wa-box-customOrder wa-box-mplan-customOrder" id="wa-customOrder-mplan">
								<h2 class="wa-section-title wa-section-title-customOrder wa-section-title-mplan-customOrder text-center">Pay as you go Purchase Plan</h2>
								<div class="wa-content-text wa-content-text-customOrder wa-content-spacing wa-content-text-mplan-customOrder">Simply purchase the number of whois queries you require and they will be added to your account instantly. You will receive a notification email before your account reaches empty.  You can buy more queries or replenish your account any time.</div>
								<div>
									<table class="table table-bordered table-striped wa-content-text wa-content-text-customOrder wa-table-customOrder wa-table-pplan-customOrder">
										<thead>
											<tr>
												<th colspan="2">Number of queries</th>
												<th>Price (USD)</th>
											</tr>
										</thead>
										<tbody>
											<?php for($i=0;$i<$queryCount;$i++){
												$avg_price = $membershipPrices[$membershipAmount[$i]] / $membershipAmount[$i] * 1000;
												$cl = ($i%2==0?"evcell":"oddcell");
												$checked = (strcmp($membershipAmount[$i], $membershipAmount[$i]) == 0);

												?>
												<tr>
													<td>
														<input type="radio" id="wa-optionsRadios12-bwhois-orderNow"  value="per_user_license" name="query_quantity" {{ $checked?"checked":"" }}>
													</td>
													<td>{{  number_format($queryAmount[$i]) }}</td>
													<td>${{ $queryPrices[$queryAmount[$i]] }}
														<div> <button type="submit" value="Bill Yearly" name="{{ 'bill_yearly_' . $membershipAmount[$i] }}" class="btn btn-default wa-btn wa-btn-bill wa-btn-bill-yearly wa-btn-bill-customOrder wa-link wa-cursor">Bill Yearly</button></div>
													</td>
												</tr>
												<?php
											}
											?>
										</tbody>
									</table>
								</div>
							</div>
						</div>
					</div>
					@endif
					@if ($membershipCount > 0)
					<div class="row">
						<div class="col-xs-12 wa-box-width-xs wa-box-margin-customOrder">
							<div class="wa-box wa-box-xs-padding wa-box-customOrder wa-box-mplan-customOrder" id="wa-customOrder-mplan">
								<h2 class="wa-section-title wa-section-title-customOrder wa-section-title-mplan-customOrder text-center">Membership Plans</h2>
								<div class="wa-content-text wa-content-text-customOrder wa-content-spacing wa-content-text-mplan-customOrder">By purchasing a membership plan, you may use up to a certain maximum number of queries each month, this is recommended if you use Whois API on a regular basis. You may cancel/change your plan anytime.</div>
								<div>
									<table class="table table-bordered table-striped wa-content-text wa-content-text-customOrder wa-table-customOrder wa-table-mplan-customOrder">
										<thead>
											<tr>
												<th>Maximum number of queries/month</th>
												<th>Price/Month (USD)</th>
												<th>Price/Year (USD)</th>
											</tr>
										</thead>
										<tbody>
											<?php for($i=0;$i<$membershipCount;$i++){
												$avg_price = $membershipPrices[$membershipAmount[$i]] / $membershipAmount[$i] * 1000;
												$cl = ($i%2==0?"evcell":"oddcell");
												$checked = (strcmp($membershipAmount[$i], $membershipAmount[$i]) == 0);

												?>
												<tr>
													<td>{{ number_format($membershipAmount[$i]) }}</td>
													<td>${{ $membershipPrices[$membershipAmount[$i]] }}
														<div><button type="submit" value="Bill Monthly" name="{{ 'bill_monthly_' . $membershipAmount[$i] }}" class="btn btn-default wa-btn wa-btn-bill wa-btn-bill-montly wa-btn-bill-customOrder wa-link wa-cursor">Bill Monthly</button></div>
													</td>
													<td>${{ 10 * $membershipPrices[$membershipAmount[$i]] }}
														<div> <button type="submit" value="Bill Yearly" name="{{ 'bill_yearly_' . $membershipAmount[$i] }}" class="btn btn-default wa-btn wa-btn-bill wa-btn-bill-yearly wa-btn-bill-customOrder wa-link wa-cursor">Bill Yearly</button></div>
													</td>
												</tr>
												<?php
											}
											?>
										</tbody>
									</table>
								</div>
							</div>
						</div>
					</div>
					@endif
				</form>

				<div class="row">
					<div class="col-sm-6 col-xs-12 wa-box-width-xs wa-box-margin-whoisapi">
						<!--  table for Pay as you go Purchase Plan -->
						<div class="row">
							<div class="col-xs-12">
								<div class="wa-box wa-box-xs-padding wa-box-orderNow wa-box-bwhois-orderNow" id="ordernow-bwhois">
									<h2 class="wa-section-title wa-section-title-orderNow wa-section-title-bwhois-orderNow text-center">Bulk Whois Client Application</h2>
									<div class="wa-content-text wa-content-text-orderNow wa-content-spacing wa-content-text-bwhois-orderNow">Bulk Whois Client Application is a desktop graphical user interface application that communicates with Whois API Webservice. It allows users to bulk load, mass query, import and export whois data. All licenses are non-redistributable. All minor updates(binary or source code) within one major version are for free. The source code for Bulk Whois Client is under the terms and conditions of a separate <span class="wa-link wa-cursor wa-textDecoration">{{ HTML::link('https://www.whoisxmlapi.com/whoisxmlapi_4/wc_sourcecode_license.php', 'Source Code License Agreement.') }} </span></div>
									<form id='wpc_form' name="wpc_form" action="{{ $data['order_form_action'] }}" class="ignore_jssm">
										<input type="hidden"  name="order_type" value="wc"/>
										<input  name="pay_choice" type="hidden" value="pp"/>
										<div class="wa-uname-bwhois">
											<label for="wa-wc_order_username" class="col-xs-12 control-label wa-field-lbl-uname wa-field-lbl-customOrder">Username of the account to make purchase for:</label>
											<div class="col-xs-12">
												<input type="text" class="form-control" name="wc_order_username" id="wa-wc_order_username" value="{{ $data['username'] }}">
											</div>
										</div>
										<div>
											<table class="table table-bordered table-striped wa-content-text wa-content-text-orderNow wa-table-orderNow wa-table-bwhois-orderNow">
												<thead>
													<tr>
														<th colspan="2">Number of Licenses</th>
														<th>License Type</th>
														<th>Description</th>
														<th>Unit Price (USD)</th>
														<th>Total Price (USD)</th>
													</tr>
												</thead>
												<tbody>
													<tr>
														<td>
															<input type="radio" id="wa-optionsRadios12-bwhois-orderNow"  value="per_user_license" name="wc_license_type" {{ $wc_license_type=='per_user_license' ? 'checked' : '' }}>
														</td>
														<td>
															<input type="text" class="form-control" value="{{ $num_user_license }}" name="num_user_license" id="num_user_license">
														</td>
														<td>Per User License</td>
														<td><div>1-5 users $99/license</div>
															<div>6-10 users $89/license</div>
														</td>
														<td>${{ number_format($user_license_unit_price) }}</td>
														<td id="user_license_total_price">${{ number_format($user_license_total_price)}}</td>
													</tr>
													<tr>
														<td>
															<input type="radio" id="wa-optionsRadios12-bwhois-orderNow" value="group_license" name="wc_license_type" {{ $wc_license_type=='group_license'?'checked':'' }}>
														</td>
														<td>1</td>
														<td>Group License</td>
														<td>Unlimited user licenses for all members of your organization</td>
														<td>${{ number_format($group_license_price) }}</td>
														<td>${{ number_format($group_license_price) }}</td>
													</tr>
													<tr>
														<td>
															<input type="radio" id="wa-optionsRadios12-bwhois-orderNow" value="sourcecode_license" name="wc_license_type" {{ $wc_license_type=='sourcecode_license'?'checked':'' }}>
														</td>
														<td>1</td>
														<td>Source Code</td>
														<td>Source code allows customization to meet your own need.</td>
														<td>${{ number_format($source_license_price) }}</td>
														<td>${{ number_format($source_license_price) }}</td>
													</tr>
												</tbody>
											</table>
										</div>
										<div class="row">
											<div class="col-xs-12">
												<div class="pull-right"><button type="submit" class="btn btn-default submit-button wa-btn wa-btn-next wa-btn-orange wa-btn-next-orderNow wa-btn-customOrder">Next</button></div>
											</div>
										</div>
									</form>
								</div>
							</div>
						</div>
					</div>
					<div class="col-sm-6 col-xs-12 wa-box-width-xs wa-box-margin-whoisapi">
						<!--  table for Pay as you go Purchase Plan -->
						<div class="row">
							<div class="col-xs-12">
								<div class="wa-box wa-box-xs-padding wa-box-orderNow wa-box-historic-customOrder" id="ordernow-payplan">
									<h2 class="wa-section-title wa-section-title-orderNow wa-section-title-historic-customOrder text-center">Historic Whois Database Download</h2>
									<div class="wa-content-text wa-content-text-orderNow wa-content-spacing wa-content-text-payPlan-orderNow">We provide archived historic whois database in both parsed and raw format for download as CSV files. Data will be delievered between 2 to 10 business days(depending on the volume). The database is sorted in ascending alphabetical order. You may choose to download the whois records with domain names starting with any alphabet letter or digit. You may also choose the tlds. For example, download 10 million whois records with .com and .net domain names starting from letter 'a'.</div>
									<form id='wdb_form' name="wdb_form" action="{{ $data['order_form_action'] }}" class="ignore_jssm">
										<input type="hidden"  name="order_type" value="wdb"/>
										<input  name="pay_choice" type="hidden" value="pp"/>
										<div class="wa-digit-container">
											<span class="wa-subtitle-customOrder">Download starts with the letter or digit:</span>
											<input type="text" class="form-control wa-digit-form-control" name="wdb_prefix" id="wa-wdb_prefix" value="{{ $wdb_prefix }}">
											<span class="wa-subtitle-customOrder">(optional)</span>
										</div>
										<div>
											<table class="table table-bordered table-striped wa-content-text wa-content-text-orderNow wa-table-orderNow wa-table-bwhois-orderNow">
												<thead>
													<tr>
														<th colspan="2">Number of whois records</th>
														<th>
															<input type="radio" class="wa-input-radio-table" id="wa-optionsRadios1-customOrder" name="wdb_type" value="raw" {{ $wdb_type=='raw' ? "checked": "" }}>
															<div class="wa-lbl-radio">Price(Raw text only)</div>
														</th>
														<th>
															<input type="radio" class="wa-input-radio-table" id="wa-optionsRadios1-customOrder" name="wdb_type" {{ $wdb_type!='raw' ? "checked": "" }}>
															<div class="wa-lbl-radio">Price(Raw text and parsed fields)</div>
														</th>
													</tr>
												</thead>
												<tbody>
													<?php

													for($i=0;$i<$dbCount;$i++) {
			  											//$avg_price = $dbPrices[$dbAmount[$i]] / $dbAmount[$i] * 1000;
														$cl = ($i%2==0?"evcell":"oddcell");
														$checked = ($dbAmount[$i]==$wdb_quantity);
														?>
														<tr>
															<td>
																<input type="radio" id="wa-optionsRadios12-bwhois-orderNow" value="{{ $dbAmount[$i] }}" name="wdb_quantity" {{ $checked?"checked":"" }}>

															</td>
															<td>{{ number_format($dbAmount[$i]) }} million</td>
															<td>
																{{ discount($dbRawPrices[$dbAmount[$i]], $dbDiscount) }}
																<!-- <s>$800</s>
																<div>$240 (70 % off)</div> -->
															</td>
															<td>
																{{ discount($dbParsedPrices[$dbAmount[$i]], $dbDiscount) }}
																<!-- <s>$960</s>
																<div>$288 (70 % off)</div> -->
															</td>
														</tr>
														<?php
													}
													?>
													<tr>

														<td colspan="2">> 5 million</td>
														<td class="wa-link wa-cursor wa-textDecoration"><a href="mailto:support@whoisxmlapi.com">Contact Us</a></td>
														<td class="wa-link wa-cursor wa-textDecoration"><a href="mailto:support@whoisxmlapi.com">Contact Us</a></td>
													</tr>
													<tr>
														<td colspan="2">The complete database(125 million records)</td>
														<td class="wa-link wa-cursor wa-textDecoration"><a href="mailto:support@whoisxmlapi.com">Contact Us</a></td>
														<td class="wa-link wa-cursor wa-textDecoration"><a href="mailto:support@whoisxmlapi.com">Contact Us</a></td>
													</tr>
												</tbody>
											</table>
										</div>
										<div class="row">
											<div class="col-xs-12">
												<div class="pull-right"><button type="submit" class="btn btn-default submit-button wa-btn wa-btn-next wa-btn-orange wa-btn-next-orderNow wa-btn-customOrder">Next</button></div>
											</div>
										</div>
									</form>
								</div>
							</div>
						</div>
					</div>
				</div>
				@endif
				<div class="row ">
					<!-- Privacy Policy -->
					<div class="col-xs-12 wa-box-width-xs ">
						<div class="wa-box wa-box-xs-padding wa-bottom-content-margin wa-box-customOrder wa-box-paymentpolicy-customOrder" id="payment_policy">
							<h2 class="wa-section-title wa-section-title-customOrder wa-section-title-paymentpolicy-customOrder text-center">Payment policy</h2>
							{{ HTML::image('images/paypal3.png', 'Responsive image', array('class'=>'img-responsive')) }}
							<div class="wa-subtitle wa-subtitle-customOrder wa-subtitle-paypal-customOrder">Paypal accepts credit card</div>
							<div class="wa-content-text wa-content-text-customOrder wa-content-spacing wa-content-paymentpolicy-customOrder">All transactions are done via paypal for safety and security.Unused Query purchases never expire but are not refundable. You may change or cancel your membership at any time by simply  <span class="wa-link wa-cursor wa-textDecoration"> {{ HTML::link('mailto:support@whoisxmlapi.com', 'send us an email') }}</span> with your username.  Please <span class="wa-cursor wa-link wa-textDecoration">{{ HTML::link('mailto:support@whoisxmlapi.com', 'contact us') }}</span> if you encounter any issue with the checkout proccess.</div>
							<div class="wa-subtitle-customOrder">Need an alternative payment option?</div>
							<div  class="wa-content-text wa-content-text-reverseIppurchase wa-content-needcheck-reverseIppurchase">Please <span class="wa-link wa-cursor wa-textDecoration"> {{ HTML::link('mailto:support@whoisxmlapi.com', 'send us an email') }}</span> for instruction on how to pay by check, wire-transfer, or other options</div>
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
		$('#num_user_license').blur(function(evt){
			fix_user_license_price();
		});

		$('#wpc_form').on('submit',function(evt){
			fix_user_license_price();
		});
	});

	function fix_user_license_price(){
		var n = get_num($('#num_user_license').val());
		$('#num_user_license').val(n);
		var price = '$'+comp_user_license_price(n);
		$('#user_license_total_price').text(price);
	}

	function get_num(n){
		n = parseInt(n);
		if(!n)return 1;
		return n;
	}

	function comp_user_license_price(n){

		if(n<6)return n*99;
		return n*89;
	}
</script>
@stop