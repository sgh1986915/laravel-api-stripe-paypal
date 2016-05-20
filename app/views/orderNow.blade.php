<?php
$libPath = base_path(). "/whoisxmlapi_4";
?>
<?php
error_reporting(0);
@ini_set('display_errors', 0);

global $group_license_price, $source_license_price;
global $queryCount,$queryPrices,$queryAmount ;
global $membershipCount, $membershipAmount, $membershipPrices;
global $dbDiscount, $dbRawPrices, $dbParsedPrices, $dbCount,$dbAmount;

// Bulk Whois Lookup Service
global $bwl_speed;
$include_choice=true;
$selected_choice=true;

// historic cctld Whois Database
$cctld_db_show_input=1;
$cctld_wdb_ids = $_REQUEST['cctld_wdb_ids'];
$cctld_whois_db_type = $_REQUEST['cctld_whois_db_type'];
if(!$cctld_whois_db_type) $cctld_whois_db_type = "whois_records";

//custom whois database
$custom_db_show_input=1;

$libPath = base_path(). "/whoisxmlapi_4";
require_once $libPath . "/price.php";
require_once $libPath . "/httputil.php";
require_once $libPath . "/util.php";
require_once $libPath . "/util/number_util.php";
require_once $libPath . "/users/utils.inc";
require_once $libPath . "/users/users.inc";
require_once $libPath . "/affiliate_track.php";
require_once $libPath . "/bulk-whois-lookup-price.php";
require_once $libPath . "/wc_price.php";
require_once $libPath . "/whois-database-price.php";
require_once $libPath . "/models/cctld_whois_database_product.php";
require_once $libPath . "/models/cctld_whois_database_product.php";
require_once $libPath . "/models/custom_whois_database_product.php";

if(!session_id())session_start();

$pay_choice = "pp";
if(isset($cc))$pay_choice="cc";

$query_quantity = get_from_post_get("query_quantity");
if(!$query_quantity) $query_quantity = 25000;

$wdb_quantity = $_REQUEST['wdb_quantity'];
if(!$wdb_quantity) $wdb_quantity = 1;

$wdb_type = isset($_REQUEST['wdb_type']) ? $_REQUEST['wdb_type'] : false;
if(!$wdb_type) $wdb_type = 'both';

$cctld_whois_db_type =  isset($_REQUEST['cctld_whois_db_type']) ? $_REQUEST['cctld_whois_db_type'] : false;
if(!$cctld_whois_db_type) $cctld_whois_db_type ="whois_records";


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

// Bulk Whois Lookup Service
$bwl_speed=$_REQUEST['bwl_speed'];
if(!$bwl_speed)$bwl_speed="regular";
$num_domains=$_REQUEST['num_domains'];
if(!$num_domains)$num_domains=500000;

// historic cctld Whois Database
$CCTLDDBProduct = new CCTLDWhoisDatabaseProduct();

//custom whois database
$custom_wdb_ids = $_REQUEST['custom_wdb_ids'];
$customDBProduct = new CustomWhoisDatabaseProduct();
?>
@extends('layouts.master')

@section('title')
ORDER NOW
@stop

@section('styles')
@parent
{{ HTML::style('css/orderNow.css') }}
@stop

@section('content')
<div class="main-content">
	<div class="row wa-searchbox-radio">
		<div class="col-xs-12 wa-auto-margin">
			<div class="row">
				<div class="col-sm-6 col-xs-12 wa-search-eg-box wa-search-eg-box-orderNow">
					<form id="whoisform" name="whoisform" action="{{ URL::to('whoislookup.php') }}">
						<div class="form-group has-feedback wa-search-box">
							<input type="text" class="form-control wa-search wa-search-orderNow"  name="domainName" id="wa-search-whoislookup" placeholder="Whois Lookup">
							<span class="glyphicon glyphicon-search form-control-feedback wa-cursor" id="wa-search-icon-whoislookup"></span>
							<div class="wa-exapple wa-exapple-orderNow">Example: google.com or 74.125.45.100</div>
							<div class="wa-radio-inputs wa-radio-xml-Josn wa-radio-xml-Josn-orderNow">
								<div class="wa-radio-input wa-radio-input-xml wa-radio-input-xml-orderNow">
									<input type="radio" value="xml" checked id="wa-radio-xml" class="wa-cursor wa-radio-orange-bar wa-field-input-selection wa-field-input-selection-orderNow wa-api-res-type" name="outputFormat">
									<label for="wa-radio-xml" class="wa-cursor wa-field-value-selection wa-field-value-selection-xml wa-field-value-selection-orderNow" id="wa-lbl-XMl">XML</label>
									<div class="wa-home-radio-outerCircle">
										<div class="wa-home-radio-innerCircle"></div>
									</div>
								</div>
								<div class="wa-radio-input wa-radio-input-json wa-radio-input-json-orderNow">
									<input type="radio" value="json" id="wa-radio-json" class="wa-cursor wa-radio-orange-bar wa-field-input-selection wa-field-input-selection-orderNow wa-api-res-type" name="outputFormat">
									<label for="wa-radio-json" class="wa-cursor wa-field-value-selection wa-field-value-selection-json wa-field-value-selection-orderNow" id="wa-lbl-JSON">JSON</label>
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
							<a href={{ URL::to('order_paypal.php') }}><button type="button" class="btn btn-default wa-btn wa-btn-orderNow wa-btn-orderNow-orderNow center-block" id="wa-btn-home-orderNow">ORDER NOW</button></a>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="row wa-page-title-content-bg">
		<div class="col-xs-12 wa-about-whoisApi wa-auto-margin">
			<h1 class="text-center wa-title wa-title-orderNow">Order Now</h1>
		</div>
	</div>
	<div id="wa-page-content">
		<div class="row wa-content-bg wa-content-bg-orderNow">
			<div class="col-xs-12 wa-col-xs-no-padding wa-auto-margin wa-box-margin-whoisapi">
				<form action="{{ $data['form_action'] }}" class="ignore_jssm form-horizontal" role="form">
					<input type="hidden" name="order_type" value="whoisapi">
					<input id="pay_choice" name="pay_choice" type="hidden" value="{{ $data['pay_choice'] }}">
					<!-- <input type="hidden" name="sandbox" value="1"/> -->
					@if($data['pay_choice'] == 'cc')
					<input type="hidden" name="submit" value="1">
					@endif
					<div class="row">
						<div class="col-xs-12">
							<div class="wa-box wa-box-xs-padding wa-top-content-margin wa-box-orderNow wa-box-paypalorder-orderNow">
								<h2 class="wa-section-title wa-section-title-orderNow wa-section-title-paypalorder-orderNow text-center">{{ ($data['pay_choice'] == 'cc') ? "Credit card" : "Paypal"}} order form</h2>
								<div class="wa-content-text wa-content-text-orderNow wa-content-spacing wa-content-text-paypalorder-orderNow text-center">We offer 2 pricing models, <span class="wa-link wa-cursor wa-textDecoration">{{ HTML::link('#ordernow-payplan', 'pay as you go') }}</span> or <span class="wa-link wa-cursor wa-textDecoration">{{ HTML::link('#ordernow-memberplan', 'monthly memberships') }}</span>.</div>
								<div class="form-group">
									<label for="wa-un-orderNow" class="col-sm-5 control-label wa-field-lbl-uname wa-field-lbl-orderNow">Username of the account to make purchase for:</label>
									<div class="col-sm-5">
										<input type="text" class="form-control" name="order_username" id="wa-un-orderNow" value="{{ $data['username'] }}">
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-sm-6 col-xs-12 wa-box-width-xs wa-box-margin-whoisapi">
							<!--  table for Pay as you go Purchase Plan -->
							<div class="row">
								<div class="col-xs-12">
									<div class="wa-box wa-box-xs-padding wa-box-orderNow wa-box-payPlan-orderNow" id="ordernow-payplan">
										<h2 class="wa-section-title wa-section-title-orderNow wa-section-title-payPlan-orderNow text-center">Pay as you go purchase plan</h2>
										<div class="wa-content-text wa-content-text-orderNow wa-content-spacing wa-content-text-payPlan-orderNow">Simply purchase the number of whois queries you require and they will be added to your account instantly. You will receive a notification email before your account reaches empty. You can buy more queries or replenish your account any time.</div>
										<div>
											<table class="table table-bordered table-striped wa-content-text wa-content-text-orderNow wa-table-orderNow wa-table-payPlan-orderNow">
												<thead>
													<tr>
														<th colspan="2">Number of queries</th>
														<th>Price (USD)</th>
													</tr>
												</thead>
												<tbody>
													<?php for($i=0;$i<$queryCount;$i++){
														$avg_price = $queryPrices[$queryAmount[$i]] / $queryAmount[$i] * 1000;
														$cl = ($i%2==0?"evcell":"oddcell");
														$checked = ($queryAmount[$i]==$query_quantity);
														?>
														<tr>
															<td><input type="radio" name="query_quantity" id="wa-optionsRadios1-payPlan-orderNow" value="{{ $queryAmount[$i] }}" {{ $checked?"checked":"" }} ></td>
															<td>{{ number_format($queryAmount[$i]) }}</td>
															<td>{{ $queryPrices[$queryAmount[$i]] }}</td>
														</tr>
														<?php
													}
													?>
													<tr>
														<td><input type="radio" name="wa-radio-payPlan-orderNow" id="wa-optionsRadios12-payPlan-orderNow" value="wa-option12-payPlan-orderNow" ></td>
														<td>> {{ number_format($queryAmount[$queryCount-1]) }}</td>
														<td class="wa-link wa-cursor wa-textDecoration">{{ HTML::link('mailto:support@whoisxmlapi.com', 'Contact Us') }}</td>
													</tr>
												</tbody>
											</table>
										</div>
										<div class="row">
											<div class="col-xs-12">
												<div class="pull-right"><button type="submit" class="btn btn-default submit-button wa-btn wa-btn-next wa-btn-orange wa-btn-next-orderNow wa-btn-whoissp">Next</button></div>
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
									<div class="wa-box wa-box-xs-padding wa-box-orderNow wa-membershipplan-orderNow" id="ordernow-memberplan">
										<h2 class="wa-section-title wa-section-title-orderNow wa-section-title-membershipplan-orderNow text-center">Membership plans</h2>
										<div  class="wa-content-text wa-content-text-orderNow wa-content-spacing wa-content-membershipplan-orderNow">By purchasing a membership plan, you may use up to a certain maximum number of queries each month, this is recommended if you use Whois API on a regular basis. You may cancel/change your plan anytime</div>
										<div>
											<table class="table table-bordered table-striped wa-content-text wa-content-text-orderNow wa-table-orderNow wa-table-membershipplan-orderNow">
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
															<td>${{ $membershipPrices[$membershipAmount[$i]] }} <div><button type="submit" value="Bill Monthly" name="{{ 'bill_monthly_' . $membershipAmount[$i] }}" class="btn btn-default wa-btn wa-btn-bill wa-btn-bill-montly  wa-btn-bill-orderNow wa-link wa-cursor">Bill Monthly</button></div></td>
															<td>${{ (10 * $membershipPrices[$membershipAmount[$i]]) }}<div> <button type="submit" value="Bill Yearly" name="{{ 'bill_yearly_' . $membershipAmount[$i] }}" class="btn btn-default wa-btn wa-btn-bill wa-btn-bill-yearly  wa-btn-bill-orderNow wa-link wa-cursor">Bill Yearly</button></div>
															</td>
														</tr>
														<?php
													}
													?>
													<tr>
														<td>>{{ number_format($membershipAmount[$membershipCount-1]) }}</td>
														<td colspan="2"><div class="wa-link wa-curso wa-textDecoration">{{ HTML::link('mailto:support@whoisxmlapi.com', 'Bulk Discount, Contact Us') }}</div></td>
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
					<div class="col-sm-6 col-xs-12 wa-box-width-xs wa-box-margin-whoisapi">
						<!--  table for Bulk Whois Lookup Service -->
						<div class="row">
							<div class="col-xs-12">
								<div class="wa-box wa-box-xs-padding wa-box-lService-orderNow wa-box-orderNow">
									<h2 class="wa-section-title wa-section-title-orderNow wa-section-title-lService-orderNow text-center" id="bulk-fasttrack">Bulk whois lookup service</h2>
									<div class="wa-content-text wa-content-text-orderNow wa-content-spacing wa-content-lService-orderNow">Our fast-track bulk whois lookup service provides the quickest whois lookup on a large number of domain names(hundreds of thousands or millions) directly via our backend processes for a fee. Results will be downloaded as csv or mysqldump files in bulk.</div>
									<form id="bwl_form" name="bwl_form" action="{{ $data['form_action'] }}" class="ignore_jssm">
										<input type="hidden" name="order_type" value="bwl">
										<input name="pay_choice" type="hidden" value="{{ $data['pay_choice'] }}">
										@if($data['pay_choice'] == 'cc')
										<input type="hidden" name="submit" value="1">
										@endif
										<div>
											<table class="table table-bordered table-striped wa-content-text wa-content-text-orderNow wa-table-orderNow wa-table-lService-orderNow">
												<thead>
													<tr>
														<th></th>
														<th>Number of domain names</th>
														<th colspan="3">
															<div>Price & Time to completion*</div>
															<div class="radio-inline wa-radio-inline">
																<label class="radio-inline">
																	<input type="radio" name="bwl_speed" class="wa-radio-orderNow" id="wa-optionsRadios1-th-lService-orderNow" value="regular" {{ $bwl_speed=='regular'?'checked':'' }}> Regular
																</label>
															</div>
															<div class="radio-inline wa-radio-inline">
																<label class="radio-inline">
																	<input type="radio" name="bwl_speed" class="wa-radio-orderNow" id="wa-optionsRadios2-th-lService-orderNow" value="expedited" {{ $bwl_speed=='expedited'?'checked':'' }} > Expedited
																</label>
															</div>
														</th>
													</tr>
												</thead>
												<tbody>
													<?php
													foreach($regular_bwl_prices as $amount=>$price){
														$price2=$expedited_bwl_prices[$amount];
			  											//$avg_price = $queryPrices[$queryAmount[$i]] / $queryAmount[$i] * 1000;
														$cl = ($i%2==0?"evcell":"oddcell");

														if(isset($include_choice))$checked = ($amount==$num_domains);
														?>
														<tr>
															<td>
																<?php if($include_choice) : ?>
																	<input type="radio" name="num_domains" id="wa-optionsRadios1-lService-orderNow" value="{{ $amount }}" {{  $checked?"checked":"" }}>
																<?php endif; ?>
															</td>
															<td>{{ number_format($amount) }}</td>
															<td>${{ $price }}<div>{{ $regular_bwl_speed[$amount] }} days</div></td>
															<td>${{ $price2 }}<div>{{ $expedited_bwl_speed[$amount] }} days</div></td>
														</tr>
														<?php
													}
													?>
													<tr>
														<td></td>
														<td>> ${{ number_format($amount) }}</td>
														<td colspan="2" class="wa-link wa-cursor wa-textDecoration">{{ HTML::link('mailto:support@whoisxmlapi.com', 'Contact Us') }}</td>
													</tr>
												</tbody>
											</table>
										</div>
										<div class="row">
											<div class="col-xs-12">
												<div class="pull-right"><button type="submit" class="btn btn-default submit-button wa-btn wa-btn-next wa-btn-orange wa-btn-next-orderNow wa-btn-whoissp">Next</button></div>
											</div>
										</div>
									</form>
								</div>
							</div>
						</div>
					</div>
					<div class="col-sm-6 col-xs-12 wa-box-width-xs wa-box-margin-whoisapi">
						<!-- pricing table for Bulk Whois Lookup Application -->
						<div class="row">
							<div class="col-xs-12">
								<div class="wa-box wa-box-xs-padding wa-box-orderNow wa-bulklooupapp-orderNow">
									<h2 class="wa-section-title wa-section-title-orderNow wa-section-title-bulklooupapp-orderNow text-center" id="wa-bulklookupapp">Bulk whois lookup application</h2>
									<div class="wa-content-text wa-content-text-orderNow wa-content-spacing wa-content-bulklooupapp-orderNow">Bulk Whois Lookup Application is a desktop graphical user interface application that communicates with Whois API Webservice. It allows users to bulk load, mass query, import and export whois data. All licenses are non-redistributable. You need to purchase Whois API queries in order to use it beyond the 100 free lookups. All minor updates(binary or source code) within one major version are for free. The source code for Bulk Whois Client is under the terms and conditions of a separate <span class="wa-link wa-cursor wa-textDecoration">{{ HTML::link('https://www.whoisxmlapi.com/wc_sourcecode_license.php', 'Source Code License Agreement') }}</span>.</div>
									<form id="wpc_form" name="wpc_form" action="{{ $data['form_action'] }}" class="ignore_jssm">
										<input type="hidden" name="order_type" value="wc">
										<input name="pay_choice" type="hidden" value="{{ $data['pay_choice'] }}">
										@if($data['pay_choice'] == 'cc')
										<input type="hidden" name="submit" value="1">
										@endif
										<div>
											<table class="table table-bordered table-striped wa-content-text wa-content-text-orderNow wa-table-orderNow wa-table-bulklooupapp-orderNow">
												<thead>
													<tr>
														<th colspan="2">Number of Licenses</th>
														<th>License Type</th>
														<th class="wa-th-desc">Description</th>
														<th>Unit Price (USD)</th>
														<th>Total Price (USD)</th>
													</tr>
												</thead>
												<tbody>
													<tr>
														<td><input type="radio" name="wc_license_type" id="wa-optionsRadios1-lApp-orderNow" value="per_user_license" {{ $wc_license_type=='per_user_license'?'checked':'' }}></td>
														<td><input type="text" class="form-control" value="{{ $num_user_license }}" name="num_user_license" id="num_user_license"></td>
														<td>Per User License</td>
														<td>1-5 users $59/license <div>6-10 users $49/license</div></td>
														<td>$<span id="user_license_unit_price">{{ number_format($user_license_unit_price) }}</span></td>
														<td>$<span id="user_license_total_price">{{ number_format($user_license_total_price) }}</span></td>
													</tr>
													<tr>
														<td><input type="radio" name="wc_license_type" id="wa-optionsRadios2-lApp-orderNow" value="group_license" {{ $wc_license_type=='group_license'?'checked':'' }}></td>
														<td>1</td>
														<td>Group  License</td>
														<td>Unlimited user licenses for all members of your organization</td>
														<td>${{ number_format($group_license_price) }}</td>
														<td>${{ number_format($group_license_price) }}</td>
													</tr>
													<tr>
														<td><input type="radio" name="wc_license_type" id="wa-optionsRadios3-lApp-orderNow" value="sourcecode_license"></td>
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
												<div class="pull-right" ><button type="submit" class="btn btn-default submit-button wa-btn wa-btn-next wa-btn-orange wa-btn-next-orderNow wa-btn-whoissp">Next</button></div>
											</div>
										</div>
									</form>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-sm-6 col-xs-12 wa-box-width-xs wa-box-margin-whoisapi">
						<!--  table for Historic Whois Database Download -->
						<div class="row">
							<div class="col-xs-12">
								<div class="wa-box wa-box-xs-padding wa-box-orderNow wa-box-historicTable-orderNow">
									<h2 class="wa-section-title wa-section-title-orderNow wa-section-title-historicTable-orderNow text-center" id="wa-dd-ordernow">Historic whois database download</h2>
									<div class="wa-content-text wa-content-text-orderNow wa-content-spacing wa-content-historicTable-orderNow">We provide archived historic whois database in both parsed and raw format for download as databases dumps(Mysql or Mssql dump) or CSV files. Data will be delievered between 1 to 3 business days(depending on the volume).</div>
									<form id="wdb_form" name="wdb_form" action="{{ $data['form_action'] }}" class="ignore_jssm">
										<input type="hidden" name="order_type" value="wdb">
										<input name="pay_choice" type="hidden" value="{{ $data['pay_choice'] }}">
										@if($data['pay_choice'] == 'cc')
										<input type="hidden" name="submit" value="1">
										@endif
										<div>
											<table class="table table-bordered table-striped wa-content-text wa-content-text-orderNow wa-table-orderNow wa-table-historicTable-orderNow">
												<thead>
													<tr>
														<th colspan="2">Number of whois records</th>
														<th>

															<input type="radio" name="wdb_type" class="wa-radio-orderNow" id="wa-optionsRadios1-th-historicTable-orderNow" value="raw" {{ $wdb_type=='raw' ? "checked": "" }}>
															<div>Price(Raw text only)</div>
														</th>
														<th>

															<input type="radio" name="wdb_type" class="wa-radio-orderNow" id="wa-optionsRadios2-th-historicTable-orderNow" value="both" {{ $wdb_type!='raw' ? "checked": "" }}>
															<div>Price(Raw text and parsed fields)</div>
														</th>
													</tr>
												</thead>
												<tbody>
													<?php
													for($i=0;$i<$dbCount;$i++){
			  											//$avg_price = $dbPrices[$dbAmount[$i]] / $dbAmount[$i] * 1000;
														$cl = ($i%2==0?"evcell":"oddcell");
														$checked = ($dbAmount[$i]==$wdb_quantity);
														?>
														<tr>
															<td><input type="radio" name="wdb_quantity" id="wa-optionsRadios1-historicTable-orderNow" value="{{ $dbAmount[$i] }}" {{ $checked?"checked":"" }}></td>
															<td>{{ number_format($dbAmount[$i]) }} million randomly chosen</td>
															<td>{{ discount($dbRawPrices[$dbAmount[$i]], $dbDiscount) }}</td>
															<td>{{ discount($dbParsedPrices[$dbAmount[$i]], $dbDiscount) }}</td>
														</tr>
														<?php
													}
													?>
													<tr>
														<td></td>
														<td>> 5 million</td>
														<td><span class="wa-link wa-cursor wa-textDecoration">{{ HTML::link('mailto:support@whoisxmlapi.com', 'Contact Us') }} </span></td>
														<td><span class="wa-link wa-cursor wa-textDecoration">{{ HTML::link('mailto:support@whoisxmlapi.com', 'Contact Us') }}</span></td>
													</tr>
													<tr>
														<td></td>
														<td>The complete database(155 million records</td>
														<td><span class="wa-link wa-cursor wa-textDecoration">{{ HTML::link('mailto:support@whoisxmlapi.com', 'Contact Us') }} </span></td>
														<td><span class="wa-link wa-cursor wa-textDecoration">{{ HTML::link('mailto:support@whoisxmlapi.com', 'Contact Us') }} </span></td>
													</tr>
													<tr>
														<td></td>
														<td>Yearly Subscription(4 quarterly downloads/year) of complete databases</td>
														<td><span class="wa-link wa-cursor wa-textDecoration">{{ HTML::link('mailto:support@whoisxmlapi.com', 'Contact Us') }} </span></td>
														<td><span class="wa-link wa-cursor wa-textDecoration">{{ HTML::link('mailto:support@whoisxmlapi.com', 'Contact Us') }} </span></td>
													</tr>
													<tr>
														<td></td>
														<td>Yearly Subscription(Daily updates!) of complete databases</td>
														<td><span class="wa-link wa-cursor wa-textDecoration">{{ HTML::link('mailto:support@whoisxmlapi.com', 'Contact Us') }} </span></td>
														<td><span class="wa-link wa-cursor wa-textDecoration">{{ HTML::link('mailto:support@whoisxmlapi.com', 'Contact Us') }}</span></td>
													</tr>

												</tbody>
											</table>
										</div>
										<div class="row">
											<div class="col-xs-12">
												<div class="pull-right" ><button type="submit" class="btn btn-default submit-button wa-btn wa-btn-next wa-btn-orange wa-btn-next-orderNow wa-btn-whoissp">Next</button></div>
											</div>
										</div>
									</form>
								</div>
							</div>
						</div>
					</div>
					<div class="col-sm-6 col-xs-12 wa-box-width-xs wa-box-margin-whoisapi">
						<!-- pricing table for Alexa & Quantcast Whois Database Download -->
						<div class="row">
							<div class="col-xs-12">
								<div class="wa-box wa-box-xs-padding wa-box-orderNow wa-pricingAlexa-orderNow">
									<h2 class="wa-section-title wa-section-title-orderNow wa-section-title-pricingAlexa-orderNow text-center" id="wa-alexquant-dd">Pricing for alexa & quantcast whois database & other custom database download</h2>
									<div  class="wa-content-text wa-content-text-orderNow wa-content-spacing wa-content-pricingAlexa-orderNow">Historic whois databases for top 1 million Alexa & Quantcast domains. Plus other custom database downloads. Receive <b>20% discount</b> when you purchase more than one database.</div>
									<form id="custom_wdb_form" name="custom_wdb_form" action="{{ $data['form_action'] }}" class="ignore_jssm">
										<input type="hidden" name="order_type" value="custom_wdb">
										<input name="pay_choice" type="hidden" value="{{ $data['pay_choice'] }}">
										@if($data['pay_choice'] == 'cc')
										<input type="hidden" name="submit" value="1">
										@endif
										<div>
											<table class="table table-bordered table-striped wa-content-text wa-content-text-orderNow wa-table-orderNow wa-table-pricingAlexa-orderNow">
												<thead>
													<tr>
														<th colspan="2">Description</th>
														<th>Price</th>
													</tr>
												</thead>
												<tbody>
													<?php
													$i=0;
													$custom_db_products = $customDBProduct->get_products();
													$ordered_custom_db_product_items=$customDBProduct->get_product_items_by_ids($custom_wdb_ids);
													$total_price = "";
													if($ordred_custom_db_product_items && count($ordred_custom_db_product_items)>0) $total_price = CustomWhoisDatabase::get_product_items_price($ordered_custom_db_product_items);
													foreach($custom_db_products as $pkey=>$product_group){

														?>
														<tr>
															<td colspan="3">{{ $pkey }}</td>
														</tr>
														<?php
														foreach($product_group as $p){
															$id=$p['id'];
															$name=$p['name'];
															$description=$p['description'];
															$detail=$p['detail'];
															$price=format_price($p['price']);
															$checked = ($custom_wdb_ids ? in_array($id, $custom_wdb_ids) : false);
															if(empty($custom_wdb_ids) && $id == 'alexa1mil_current'){
																$checked = true;
															}
															?>
															<tr>
																<?php if($custom_db_show_input){ ?>
																<td><input type="checkbox" value="{{ $id }}" name="custom_wdb_ids[]" price="{{ $p['price'] }}" {{ ($checked ?"checked":"") }}></td>
																<?php }?>
																<td>{{ $description }}</td>
																<td>{{ $price }}</td>
															</tr>
															<?php
														}
														?>
														<?php
													}
													?>
													<?php if($custom_db_show_input): ?>
														<tr>
															<td colspan="2">Total Price:</td>
															<td id="custom_db_total_price">$0</td>
														</tr>
													<?php endif; ?>
												</tbody>
											</table>
										</div>
										<div class="row">
											<div class="col-xs-12">
												<div class="pull-right" ><button type="submit" class="btn btn-default submit-button wa-btn wa-btn-next wa-btn-orange wa-btn-next-orderNow wa-btn-whoissp">Next</button></div>
											</div>
										</div>
									</form>
								</div>
							</div>
						</div>
					</div>
				</div>
				<!-- pricing table for historic cctld Whois Database Download -->
				<div class="row">
					<div class="col-xs-12 wa-box-width-xs wa-box-margin-whoisapi">
						<div class="wa-box wa-box-xs-padding wa-box-hcctld-orderNow wa-box-orderNow">
							<h2 class="wa-section-title wa-section-title-orderNow wa-section-title-hcctld-orderNow text-center" id="wa-historic-dd">Pricing for historic cctld whois database download</h2>
							<div  class="wa-content-text wa-content-text-orderNow wa-content-spacing wa-content-hcctld-orderNow">The following cctlds are offered as a one-time download only. You may choose to buy domain names list only or domains with whois records.
								Bulk discounts up to 50% off are given when you purchase 2 or more databases.
								For cctld where the whois database is not immediately available, you may wait anywhere between 3 business days to a month for it to be delievered. All number of domains are estimation only.</div>
								<div  class="wa-content-text wa-content-text-orderNow wa-content-spacing wa-content-hcctld-orderNow"><span class="wa-link wa-cursor wa-textDecoration"> {{ HTML::link('mailto:general@whoisxmlapi.com', 'Contact us') }}</span> if you want a custom cctld whois database that's not listed here or want a combo quote to get all the cctlds listed. There are a total of 28 cctld databases listed below.
									<div class="wa-content-text wa-content-text-orderNow wa-content-spacing">Check the products you want to see updated total price.</div>
								</div>
								<form id="cctld_wdb_form" name="cctld_wdb_form" action="{{ $data['form_action'] }}" class="ignore_jssm">
									<input type="hidden" name="order_type" value="cctld_wdb">
									<input name="pay_choice" type="hidden" value="{{ $data['pay_choice'] }}">
									@if($data['pay_choice'] == 'cc')
									<input type="hidden" name="submit" value="1">
									@endif
									<div>
										<table class="table table-bordered table-striped wa-content-text wa-content-text-orderNow wa-table-orderNow wa-table-hcctld-orderNow">
											<thead>
												<tr>
													<th><input type="checkbox" value="wa-checkbox-hcctld-orderNow" class="select_all"></th>
													<th>Description</th>
													<th>
														<?php if($cctld_db_show_input): ?>
															<div><input type="radio" name="cctld_whois_db_type" value="domain_names" id="wa-optionsRadios1-th-hcctld-orderNow" {{ $cctld_whois_db_type=='domain_names'?'checked':''}} ></div>
														<?php endif ?>
														<div>Price (Domains)</div>
													</th>
													<th>
														<?php if($cctld_db_show_input): ?>
															<div><input type="radio" name="cctld_whois_db_type" value="whois_records" id="wa-optionsRadios2-th-hcctld-orderNow" {{ $cctld_whois_db_type=='whois_records'?'checked':''}} ></div>
														<?php endif ?>
														<div>Price (Whois)</div>
													</th>
												</tr>
											</thead>
											<tbody>
												<?php
												$i=0;
												$cctld_db_products = $CCTLDDBProduct->get_products();
												$ordered_cctld_db_product_items=$CCTLDDBProduct->get_product_items_by_ids($cctld_wdb_ids);
												$total_price = "";
												if($ordred_cctld_db_product_items && count($ordred_cctld_db_product_items)>0) $total_price = $CCTLDDBProduct->get_product_items_price($ordered_cctld_db_product_items, 'domain_names_price');
												foreach($cctld_db_products as $pkey=>$product_group){
													$i++;
													$available=false;
													foreach($product_group as $p){
														if(!$p['whois_unavailable'])$available=true;
													}
													?>
													<tr>
														<td colspan="4"><?php echo ($available?"<b>":"") . $pkey?></td>
													</tr>
													<?php
													foreach($product_group as $p){
														$id=$p['id'];
														$name=$p['name'];
														$description= number_format($p['num_domains']);
														if($p['total_domains']){
															$percent_coverage= round(100*($p['num_domains'] / $p['total_domains']));
															$description .= " / ". number_format($p['total_domains']) . "($percent_coverage% coverage)";
														}
														$description .= " domains<br/> ". $p['description'];


														if($p['whois_unavailable']){
															$description .= 'whois data to be delivered within 3 business days to 1 month upon order';
														}

														$detail=$p['detail'];
														$domains_price=format_price($p['domain_names_price']);
														$parsed_whois_price=format_price($p['parsed_whois_price']);
														$checked = ($cctld_wdb_ids ? in_array($id, $cctld_wdb_ids) : false);
														?>
														<tr>
															<?php
															if($cctld_db_show_input){
																?>
																<td><input type="checkbox" value="<?php echo $id?>" <?php echo ($checked ?"checked":""); ?> class="wa-checkbox-cctld-orderNow" name="cctld_wdb_ids[]" domain_names_price="<?php echo $p['domain_names_price']; ?>" whois_records_price="<?php echo $p['parsed_whois_price']; ?>" id="wa-checkbox{{$i}}-hcctld-orderNow"></td>
																<?php
															}
															?>
															<td>{{ $description }}</td>
															<td>{{ $domains_price }}</td>
															<td>{{ $parsed_whois_price }}</td>
														</tr>
														<?php
													}
												}
												?>
												<?php if($cctld_db_show_input): ?>
													<tr>
														<td><input type="checkbox" class="wa-checkbox-cctld-orderNow select_all" id="wa-checkbox35-hcctld-orderNow"></td>
														<td>Total Price:</td>
														<td id="cctld_db_domain_names_total_price">$0</td>
														<td id="cctld_db_whois_records_total_price">$0</td>
													</tr>
												<?php endif; ?>
											</tbody>
										</table>
									</div>
									<div class="row">
										<div class="col-xs-12">
											<div class="pull-right" ><button type="submit" class="btn btn-default submit-button wa-btn wa-btn-next wa-btn-orange wa-btn-next-orderNow wa-btn-whoissp">Next</button></div>
										</div>
									</div>
								</form>
							</div>
						</div>
					</div>
					@if ($data['pay_choice'] == 'pp')
					<div class="row">
						<div class="col-xs-12 wa-box-width-xs">
							<div class="wa-box wa-box-xs-padding wa-box-paymentpolicy wa-box-paymentpolicy-orderNow">
								<h2 class="wa-section-title wa-section-title-orderNow wa-section-title-paymentpolicy-orderNow text-center">Payment policy</h2>
								{{ HTML::image('images/paypal4.png', 'Responsive image', array('class'=>'img-responsive')) }}
								<div  class="wa-content-text wa-content-text-orderNow wa-content-spacing wa-content-paymentpolicy-orderNow">All transactions are done via paypal for safety and security. Unused Query purchases never expire but are not refundable. You may change or cancel your membership at any time by simply <span class="wa-link wa-cursor wa-textDecoration"> {{ HTML::link('mailto:support@whoisxmlapi.com', ' sending us an email') }} </span> with your username. Please <span class="wa-link wa-cursor wa-textDecoration"> {{ HTML::link('mailto:support@whoisxmlapi.com', 'Contact Us') }} </span>if you encounter any issue with the checkout proccess.</div>
								<div class="wa-subtitle wa-subtitle-orderNow wa-subtitle-paypal-orderNow">Need an alternative payment option? {{ HTML::image('images/paypal2.png', 'Responsive image', array('class'=>'img-responsive wa-img-paypal2-orderNow')) }}</div>
								<div  class="wa-content-text wa-content-text-orderNow">You may also
									<span class="wa-link wa-cursor wa-textDecoration"><a href={{ URL::to('order.php') }}> use a credit card </a></span>
									to checkout. Please <span class="wa-link wa-cursor wa-textDecoration">{{ HTML::link('mailto:support@whoisxmlapi.com', 'send us an email') }} </span>for instruction on how to pay by check, wire-transfer, or other options</div>
								</div>
							</div>
						</div>
						@else
						<div class="row">
							<div class="col-xs-12 wa-box-width-xs">
								<div class="wa-box wa-box-xs-padding wa-bottom-content-margin wa-box-orderNow wa-box-paymentsecuritypolicy-orderNow">
									<h2 class="wa-section-title wa-section-title-orderNow wa-section-title-paymentsecuritypolicy-orderNow text-center">Payment Security and Policy</h2>
									{{ HTML::image('images/paypal2.png', 'Responsive image', array('class'=>'img-responsive')) }}
									<div  class="wa-content-text wa-content-text-orderNow wa-content-spacing wa-content-paymentsecuritypolicy-orderNow">We take great care to use physical, electronic and procedural precautions, including the use of up to 256-bit data encryption and secure socket layer (SSL) technology. Our precautionary measures meet or exceed all industry standards.
										<div>We do not collect your credit card number, your payment information are encrypted and passed on directly to either paypal or other industry leading PCI-compliant credit card payment processor that uses the highest standard and encryptions.
										</div>
										<div>
											Unused Query purchases never expire but are not refundable. You may change or cancel your membership at any time by simply <span class="wa-link wa-cursor wa-textDecoration"> {{ HTML::link('mailto:support@whoisxmlapi.com', ' sending us an email') }} </span> with your username. Please <span class="wa-link wa-cursor wa-textDecoration"> {{ HTML::link('mailto:support@whoisxmlapi.com', 'Contact Us') }} </span> if you encounter any issue with the checkout proccess.
										</div>
									</div>
									<div class="wa-subtitle wa-subtitle-orderNow wa-subtitle-paypal-orderNow">Need an alternative payment option? {{ HTML::image('images/paypal_1.png', 'Responsive image', array('class'=>'img-responsive wa-img-paypal2-orderNow')) }}</div>
									<div  class="wa-content-text wa-content-text-orderNow">You may <span class="wa-link wa-cursor wa-textDecoration"><a href={{ URL::to('order_paypal.php') }}> use paypal </a></span> to checkout. Please <span class="wa-link wa-cursor wa-textDecoration">{{ HTML::link('mailto:support@whoisxmlapi.com', ' send us an email') }} </span> for instruction on how to pay by check, wire-transfer, or other options
									</div>
								</div>
							</div>
						</div>
						@endif
					</div>
				</div>
			</div>
		</div>
		@stop

		@section('scripts')
		@parent
		<script type="text/javascript">
			$(function(){

				/* Load previous state of selected order */
				var order_username = getParameterByName('order_username') || '';
				var order_type = getParameterByName('order_type') || '';
				var query_quantity = getParameterByName('query_quantity') || '';

				if(order_type != '') {
					var currForm = $('input[name="order_type"][value="'+order_type+'"]').parents('form');
					$('#wa-un-orderNow').val(order_username);
					currForm.find('input[name="query_quantity"][value="'+query_quantity+'"]').attr('checked','checked');
				}
				/* ------- */

				/* Bulk Whois Lookup Application */
				$('#num_user_license').blur(function(evt){
					fix_user_license_price();
				});

				$('#wpc_form').submit(function(evt){
					fix_user_license_price();
				});
				/* ------- */

				/* Pricing for alexa & quantcast whois database & other custom database download */
				var inputs=$('input:checkbox[name="custom_wdb_ids[]"]');
				inputs.click(function(){
					update_custom_whois_db_price();
				});
				/* ------- */

				/* historic cctld Whois Database Download */
				var inputs = $('input:checkbox[name="cctld_wdb_ids[]"]');
				inputs.click(function(){
					update_cctld_whois_db_price();
				});
				var type_radio=$('input:radio[name="cctld_whois_db_type"]');
				type_radio.click(function(){
					update_cctld_whois_db_price();
				});

				$('.select_all').click(function(){
					var $this = $(this);
					if($this.is(':checked')) {
						$('.select_all').prop('checked',true);
						$('input:checkbox[name="cctld_wdb_ids[]"]').prop('checked',true);
					} else {
						$('input:checkbox[name="cctld_wdb_ids[]"]').prop('checked',false);
						$('.select_all').prop('checked',false);
					}
					update_cctld_whois_db_price();
				});

					/*if($('.select_all').is(':checked')) {
						$('.select_all').first().click()
					}*/
					/* ------- */
				});

/* historic cctld Whois Database Download */
function update_cctld_whois_db_price(){
	var inputs=$('input:checked[name="cctld_wdb_ids[]"]');
	var type=$('input:checked[name="cctld_whois_db_type"]').val();
	var types=['domain_names','whois_records'];
	for(var i=0;i<types.length;i++){
		var total_price = PriceUtil.compute_cctld_wdb_items_total_price(inputs, types[i]);
		var field=$('#cctld_db_'+types[i]+'_total_price');
		field.html('$'+total_price);

	}
}
/* ------- */

/* historic cctld Whois Database Download */
Number.prototype.formatMoney = function(c, d, t){
	var n = this, c = isNaN(c = Math.abs(c)) ? 2 : c, d = d == undefined ? "," : d, t = t == undefined ? "." : t, s = n < 0 ? "-" : "", i = parseInt(n = Math.abs(+n || 0).toFixed(c)) + "", j = (j = i.length) > 3 ? j % 3 : 0;
	return s + (j ? i.substr(0, j) + t : "") + i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + t) + (c ? d + Math.abs(n - i).toFixed(c).slice(2) : "");
};

function toggle_text(el, texts){
	for(var i=0;i<texts.length;i++){
		if(texts[i]==el.text()){
			el.text(texts[(i+1)%texts.length]);
		}
	}

}

function fix_user_license_price(){
	var n = get_num($('#num_user_license').val());
	$('#num_user_license').val(n);
	var price = comp_user_license_price(n);
	$('#user_license_total_price').text(price);
	var unit_price = comp_user_unit_price(n);
	$('#user_license_unit_price').text(unit_price);
}
function get_num(n){
	n = parseInt(n);
	if(!n)return 1;
	return n;
}
function comp_user_license_price(n){
	var unit_price = comp_user_unit_price(n);
	return n*unit_price;
}
function comp_user_unit_price(n){
	if(n<6)return 59;
	return 49;
}
/* ------- */

/* Pricing for alexa & quantcast whois database & other custom database download */
function update_custom_whois_db_price(){
	var inputs=$('input:checked[name="custom_wdb_ids[]"]');
	var total_price = PriceUtil.compute_custom_wdb_items_total_price(inputs);
	$('#custom_db_total_price').html('$'+total_price);
}
/* ------- */
</script>
@stop