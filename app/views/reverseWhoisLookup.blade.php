<?php
error_reporting(0);
@ini_set('display_errors', 0);
$libPath = base_path(). "/whoisxmlapi_4";
require_once $libPath."/reverse-whois/prices.php";
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
function discount($price, $discount){
	if($discount<=0)return "$" . $price;
	$new_price = $price * (1-$discount);
	$per = $discount * 100;
	$s = "<div style=\"color:red; font-weight:bold;background:transparent;\"><del>$" . "$price</del><br/> $" . "$new_price ($per % off)</div>";
	return $s;
}
?>
<div id="wa-page-content-rw-lookup">
	<div class="row wa-content-bg">
		<div class="col-xs-12 wa-col-xs-no-padding wa-auto-margin wa-col-rw-lookup">
			<div class="wa-box-rw-lookup">
				<nav class="navbar navbar-default wa-tab-navbar wa-tab-navbar-rw-lookup" role="navigation">
					<div class="navbar-header wa-navbar-header-rw-lookup wa-cursor">
						<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
							<span class="sr-only">Toggle navigation</span>
							<span class="icon-bar"></span>
							<span class="icon-bar"></span>
							<span class="icon-bar"></span>
						</button>
						<a class="navbar-brand visible-xs wa-tab-top-menu-xs" href="#" id="wa-xs-tab-active-menu">New Reverse Whois Lookup</a>
					</div>

					<div class="collapse navbar-collapse wa-navbar-collpase-rw-lookup wa-cursor" id="bs-example-navbar-collapse-1">
						<ul class="nav navbar-nav wa-navbar-nav">
							<li class="active wa-tab-li-menu wa-tab-li-menu-rw-lookup wa-border-right-tab" rel="wa-new-report-rw-lookup"><a>New Reverse Whois Lookup</a></li>
							<li class="wa-tab-li-menu wa-tab-li-menu-rw-lookup wa-border-right-tab" rel="wa-pricing-rw-lookup"><a>Pricing</a></li>
							@if (count(Auth::user()))
							<li class=" wa-tab-li-menu wa-tab-li-menu-rw-lookup" rel="wa-my-report-rw-lookup"><a>My Reverse Whois Reports</a></li>
							@endif
						</ul>
					</div>
				</nav>
				<div id="wa-tab-content-rw-lookup" class="wa-tab-background">
					<!-- New Reverse Whois Lookup -->
					<div id="wa-new-report-rw-lookup" class="wa-content-rw-lookup">
						<div class="row  wa-content-bg-innerpg">
							<div class="col-xs-12  wa-box-width-xs wa-box-margin-whoisapi">
								<div id="revWhoisTable">
									<div id="rw_stats">
										<h2 class="wa-section-title wa-section-title-rw-lookup wa-section-title-reverse-rw-lookup text-center" id="rw_search_terms">Search terms:<span id="wa-search-term"></span></h2>
										<div class="wa-content-text wa-content-text-rw-lookup wa-content-spacing text-left">
											<div id="rw_search_time"></div>
											<div class="wa-cursor wa-link" id="search_for_alt_type"><a href="javascript:search_rw_historic();" class="ignore_jssm">Search in both Current and Historic Records</a></div>
										</div>
										<div id="revWhoisError" colspan="3" class="errorMsg" style="display:none"></div>
									</div>
									<div class="row">
										<div class="col-sm-6 col-xs-12">
											<div class="wa-content-text wa-content-text-rw-lookup wa-content-spacing text-left">Number Of domains: <span id="num_current_domains">NA</span></div>
											<div class="wa-content-text wa-content-text-rw-lookup wa-content-spacing text-left">Price in credits:<span id="num_credits">NA</span></div>
										</div>
										<div class="col-sm-6 col-xs-12">
											<div class="wa-content-text wa-content-text-rw-lookup wa-content-spacing text-left">Standard Report Price:	<span id="current_report_price">NA</span></div>
											<div class="wa-content-text wa-content-text-rw-lookup  wa-content-spacing text-left"><span class="wa-link wa-cursor wa-textDecoration">{{ HTML::link('bulk-reverse-whois-order.php', 'Order credits in bulk for as low as $3 / credit!') }}</span></div>
										</div>
									</div>
									<div class="row">
										<!--<div class="col-xs-12">
											<div class="wa-content-text wa-content-text-rw-lookup wa-order-report-rw-lookup wa-content-spacing text-left"><span class="wa-link wa-cursor wa-textDecoration" id="order_cur_reports_b">{{ HTML::link('reverse-whois-order.php'.$queryString, 'Order This Report') }}</span></div>
										</div>-->
										<div class="col-xs-12">
											<a href={{ URL::to('reverse-whois-order.php').$queryString }}><button type="submit" class="btn btn-default wa-btn wa-btn-orange wa-btn-order-rw-new-lookup" id="order_cur_reports_b">Order This Report</button></a>
										</div>
									</div>
								</div>
								<div class="wa-table-rw-lookup">
									<table id="revWhoisGrid"></table>
									<div id="revWhoisPager"></div>
								</div>
							</div>
						</div>
					</div>
					<!-- Pricing -->
					<div id="wa-pricing-rw-lookup" class="wa-content-rw-lookup" style="display:none;">
						<div class="row wa-content-bg-reversewhoislookuppricing">
							<input id="choice_cc" name="pay_choice" type="hidden" value="pp">
							<!--<input type="hidden" name="sandbox" value="1"/>-->
							<div class="col-xs-12 wa-box-width-xs  wa-box-margin-whoisapi">
								<div class="row">
									<div class="col-xs-12">
										<div class="wa-box wa-box-Secure-reversewhoislookuppricing">
											<h2 class="wa-section-title wa-section-title-reversewhoislookuppricing text-center">Reverse Whois Lookup Pricing</h2>
											<div class="text-center wa-content-text wa-content-text-reversewhoislookuppricing wa-content-text-pricing-reversewhoislookuppricing wa-content-spacing">There are 2 ways you can pay for reverse whois reports:</div>
											<div class="wa-content-text wa-content-text-reversewhoislookuppricing wa-content-text-pricing-reversewhoislookuppricing wa-content-spacing">
												<ol>
													<li>Purchase credits in bulk. Each 10,000 domains cost 1 reverse whois lookup credit. Credits can be purchased using either <span class="wa-link wa-cursor wa-textDecoration">{{ HTML::link('#ordercredit-payplan', 'pay-as-you-go plan') }}</span> or <span class="wa-link wa-cursor wa-textDecoration">{{ HTML::link('#ordercredit-memberplan', 'monthly plan.') }}</span></li>
													<li>Pay for a individual report using <span class="wa-link wa-cursor wa-textDecoration">{{ HTML::link('#ordercredit-standardrepot', 'standard report pricing.') }}</span></li>
												</ol>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="col-xs-12">
								<div class="row">
									<div class="col-sm-6 col-xs-12 wa-box-width-xs wa-box-margin-whoisapi">
										<!--  table for Pay as you go Purchase Plan -->
										<div class="row">
											<div class="col-xs-12">
												<div class="wa-box wa-box-xs-padding wa-box-payPlan-reversewhoislookuppricing" id="ordercredit-payplan">
													<h2 class="wa-section-title wa-section-title-reversewhoislookuppricing wa-section-title-purchase-reversewhoislookuppricing text-center">Bulk "Pay as you go" Purchase Plan</h2>
													<div class="wa-content-text wa-content-text-reversewhoislookuppricing wa-content-text-purchase-reversewhoislookuppricing wa-content-spacing">Simply purchase the number of reverse whois credits you require and they will be added to your account instantly. You will receive a notification email before your account reaches empty. You can buy more queries or replenish your account any time.
														<div>
															<span class="wa-link wa-cursor wa-textDecoration">{{ HTML::link('bulk-reverse-whois-order.php', 'Order Now!') }}</span>
														</div>
													</div>
													<div>
														<table class="table table-bordered table-striped wa-table-pricing wa-content-text wa-content-text-reversewhoislookuppricing">
															<thead>
																<tr>
																	<th>Number of credits</th>
																	<th>Price/Credit</th>
																	<th>Price (USD)</th>
																</tr>
															</thead>
															<tbody>
																<?php
																for($i=0;$i<$rwQueryCount;$i++){
																	$avg_price = $rwQueryPrices[$rwQueryAmount[$i]] / $rwQueryAmount[$i] ;
																	?>
																	<tr>
																		<td><?php echo number_format($rwQueryAmount[$i])?></td>
																		<td>$<?php echo $avg_price ?></td>
																		<td>$<?php echo $rwQueryPrices[$rwQueryAmount[$i]]?></td>
																	</tr>
																	<?php
																} ?>
																<tr>
																	<td>><?php echo $rwQueryAmount[$rwQueryCount-1] ?></td>
																	<td>customized</td>
																	<td class="wa-link wa-cursor wa-textDecoration">{{ HTML::link('mailto:support@whoisxmlapi.com', 'contact us') }}</td>
																</tr>
															</tbody>
														</table>
													</div>
												</div>
											</div>
										</div>
									</div>
									<div class="col-sm-6 col-xs-12 wa-box-width-xs wa-box-margin-whoisapi">
										<!--  table for Membership Plans -->
										<div class="row">
											<div class="col-xs-12">
												<div class="wa-box wa-box-xs-padding wa-box-membership-reversewhoislookuppricing" id="ordercredit-memberplan">
													<h2 class="wa-section-title wa-section-title-reversewhoislookuppricing wa-section-title-membership-reversewhoislookuppricing text-center">Bulk Membership Plans</h2>
													<div  class=" wa-content-text wa-content-text-reversewhoislookuppricing wa-content-text-membership-reversewhoislookuppricing wa-content-spacing">By purchasing a membership plan, you may use up to a certain maximum number of reverse whois credits each month, this is recommended if you use Reverse Whois on a regular basis. You may cancel/change your plan anytime. <span class="wa-link wa-cursor wa-textDecoration">{{ HTML::link('bulk-reverse-whois-order.php', 'Order Now!') }} </span></div>
													<div>
														<table class="table table-bordered table-striped  wa-table-pricing wa-content-text wa-content-text-reversewhoislookuppricing">
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
																	$avg_price = ($rwMembershipAmount[$i] > 0 ? $rwMembershipPrices[$rwMembershipAmount[$i]] / $rwMembershipAmount[$i] : 0 );
																	?>
																	<tr>
																		<?php if($rwMembershipAmount[$i] == 'unlimited'){?>
																		<td colspan="2">* Unlimited</td>
																		<?php }else{?>
																		<td><?php echo number_format($rwMembershipAmount[$i])?></td>
																		<td>$<?php echo $avg_price?></td>
																		<?php }?>
																		<td>$<?php echo $rwMembershipPrices[$rwMembershipAmount[$i]]?></td>
																		<td>$<?php echo 10 * $rwMembershipPrices[$rwMembershipAmount[$i]]?></td>
																	</tr>
																	<?php
																} ?>
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
							</div>
							<div class="col-xs-12 wa-box-width-xs wa-box-margin-whoisapi">
								<div class="wa-box wa-box-xs-padding wa-box-standardreport-reversewhoislookuppricing wa-box-reversewhoislookuppricing" id="ordercredit-standardrepot">
									<h2 class="wa-section-title wa-section-title-reversewhoislookuppricing wa-section-title-standardreport-reversewhoislookuppricing text-center">Standard Reverse Whois Report Pricing</h2>
									<div  class="wa-content-text wa-content-text-reversewhoislookuppricing wa-content-text-standardreport-reversewhoislookuppricing wa-content-spacing">The price of any Reverse Whois Report is calculated as the sum of the prices of the current and historical domains in that report. The tables below detail the pricing tiers for current and historical domains.</div>
									<div>
										<table class="table table-bordered table-stripedwa-table-pricing wa-content-text wa-content-text-reversewhoislookuppricing">
											<thead>
												<tr>
													<th>Current and Historic Domains in Report	</th>
													<th>Price (USD)</th>
												</tr>
											</thead>
											<tbody>
												<?php
												$n = count($price_interval);
												for($i=0;$i<$n;$i++){
													$num_domains = "";
													if($price_interval[$i] < 0){
														$num_domains = $price_interval[$i-1]. " + ";
													}
													else $num_domains = ($i-1<0?"0":$price_interval[$i-1]) . " - " .$price_interval[$i];

													$price = ($current_price[$i] < 1 ? ($current_price[$i] . " per domain") : $current_price[$i]);
													?>
													<tr>
														<td>{{ $num_domains }}</td>
														<td>{{ discount($price, $current_price_discount) }}</td>
													</tr>
													<?php
												} ?>
											</tbody>
										</table>
									</div>
									<div class="wa-content-text wa-content-text-reversewhoislookuppricing wa-content-spacing wa-content-text-payment-reversewhoislookuppricing">
										* Current Domains are those domains for which your search terms exist in the current whois record only.
										<div class="wa-content-text wa-content-text-reversewhoislookuppricing wa-content-spacing wa-content-text-payment-reversewhoislookuppricing">
											* Historical Domains are those domains for which your search terms exist in whois records prior to the current record only (and not in the current record).
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>

					<!-- My Reverse Whois Report -->
					<div id="wa-my-report-rw-lookup" class="wa-content-rw-lookup" style="display:none;">
						<div class="row  wa-content-bg-innerpg">
							<div class="col-xs-12  wa-box-width-xs wa-box-margin-whoisapi">
								<div class="wa-table-rw-lookup">
									<table id="myReportsGrid"></table>
									<div id="myReportsPager"></div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
</div>
<?php
$search_type = isset($_REQUEST['search_type'])? $_REQUEST['search_type'] : 1;
function rev_whois_report_table_title(){
	global $search_type;
	$type=(isset($_REQUEST['search_type']) && $_REQUEST['search_type']==2 ? 'Current and Historic' : 'Current'); // modified
	return "$type Report Preview";
}
?>
<script type="text/javascript">
function search_rw_historic() {
	$('#whoisform input[name=search_type]').prop('checked', true);
	$('#whoisform').submit();
}

function search_rw_current() {
	$('#whoisform input[name=search_type]').prop('checked', false);
	$('#whoisform').submit();
}
var $dialog;

function epocToDate(s) {
	return new Date(s * 1000);
}

function stdFormatDate(d) {
	var curr_date = d.getDate();
	var curr_month = d.getMonth();
	var curr_year = d.getFullYear();
	return (curr_month + 1) + "/" + curr_date + "/" + curr_year;
}

function price_format(x) {
	return '$' + round_number(x, 1);
}

function dispNumCurrentDomains(data) {
	$('#num_current_domains_h').text(data.stats['current_total_count']);
	$('#num_current_domains').text(data.stats['current_total_count']);
}

function dispNumHistoricDomains(data) {
	$('#num_historic_domains').text(data.stats['history_total_count']);
}

function round_number(num, dec) {
	return Math.round(num * Math.pow(10, dec)) / Math.pow(10, dec);
}

function dispCurrentReportPrice(data) {
	var discounted_price = discount_price(data.stats['current_report_price'], data.stats['current_price_discount']);
	$('#current_report_price').html(discounted_price);
	var num_credits = data.stats['num_credits'];
	$('#num_credits').html(num_credits);
}

function dispHistoricReportPrice(data) {
	var discounted_price = discount_price(data.stats['history_report_price'], data.stats['historic_price_discount']);
	$('#historic_report_price').html(discounted_price);
}

function discount_price(report_price, discount) {
	if (discount && discount > 0 && discount < 1) {
		orig_price = report_price / (1 - discount);
		return "<div style=\"font-weight:bold\"><del>" + price_format(orig_price) + "</del><br/> " + price_format(report_price) + " (" + (discount * 100) + "%)" + "</div>";
	} else return price_format(report_price);
}

function dispStats(data) {
	$('#rw_stats').show();
	$('#rw_search_terms').html('<b>Search Terms:</b> ' + data.search_terms_disp);
	var search_type = 'Current';
	if (data.search_type == 2) {
		search_type = 'Current and Historic';
		$('#search_for_alt_type a').attr('href', 'javascript:search_rw_current();').html('Search for Current Whois Records');
	} else {
		$('#search_for_alt_type a').attr('href', 'javascript:search_rw_historic();').html('Search for Current and Historic Whois Records');
	}
	$('#rw_search_time').html('searching for ' + search_type + ' Whois Records took ' + data.time + ' seconds.');
}

function errorBox(err) {
	$('<div class="errorMsg">' + err + '</div>').dialog({
		autoOpen: true,
		title: 'Error',
		maxHeight: 200,
		width: 300,
		autoResize: false
	});
}

function dispSearchError(response) {
	response = response || {};
	var err = response.error || response.current_warning;
	if (err) {
		$('#revWhoisError').html(err).show();
	} else {
		$('#revWhoisError').empty().hide();
	}
	if (response.history_error) {
		$('#order_historic_reports_b').unbind('click');
		$('#order_historic_reports_b').click(function(e) {
			e.preventDefault();
			errorBox(response.history_error);
			return false;
		});
	}
	if (response.current_error) {
		$('#order_cur_reports_b').unbind('click');
		$('#order_cur_reports_b').click(function(e) {
			e.preventDefault();
			errorBox(response.current_error);
			return false;
		});
	}
}

function setSearchType(data) {
	if (data && data.search_type) {
		$('#whoisform [name=search_type]').prop('checked', data.search_type == 2);
	}
}

function get_order_link(search_type) {
	return search_type == 2 ? $('#order_historic_reports_b').attr('href') : $('#order_cur_reports_b').attr('href');
}

function linkfyWhoisRecords() {
	$('.whois_record').click(function(event) {
		var href = BASE_URL + 'whoisxmlapi_4/' + $(this).attr('href');
		// $dialog.html('<p><img src="images/icons/ajax-loader-bar.gif" width="400" height="19" /></p>');
		var domain_name = getParameterByName(href, 'd');
		var search_type = getParameterByName(href, 'search_type');
		var date = stdFormatDate(epocToDate(getParameterByName(href, 'w')));
		$dialog.load(href, function() {
			$('#preview_order_report').html('<div style="font-size:12px;width:100%">The following is a preview of the Whois Record. Whois records referenced in this Reverse Whois Report will be available to you once you purchase the full report.  <a href="' + get_order_link(search_type) + '">Order Now</a></div>');
		}).dialog('open');
		return false;
	});
}

function tab_title_html(title) {
	var truncated = truncate(title, 10);
	return '<span title="' + title + '"> Report (' + truncated + ')</span></title>';
}

function truncate(text, max) {
	if (text.length < max) return text;
	var tokens = text.split(/\s/);
	var res = '';
	for (var i = 0; i < tokens.length; i++) {
		var len = res.length;
		if (len + tokens[i].length < max) {
			res += tokens[i];
		} else if (res == '') {
			res += " " + tokens[i].substring(0, len + tokens[i].length - max);
			break;
		}
	}
	if (res.length < text.length) res += '...';
	return res;
}

function get_report_tab_index(tabs, report_id) {
	var reports = tabs.data('reports');
	if (reports) {
		return reports[report_id];
	}
	return -1;
}

function put_report_tab_index(tabs, report_id, index) {
	var reports = tabs.data('reports');
	if (!reports) {
		tabs.data('reports', {});
		reports = tabs.data('reports');
	}
	reports[report_id] = index;
}
$(function() {
	$dialog = $('<div style="width:auto"></div>').dialog({
		autoOpen: false,
		title: 'Whois Record',
		maxHeight: 500,
		width: 600,
		autoResize: true,
		height: 500
	});
	var post_data = <?php echo json_encode(Input::all()); ?> ;
	jQuery("#revWhoisGrid").jqGrid({
		url: BASE_URL + 'reversewhoislookup.php',
		// modified
		beforeRequest: function() {
			$('#whoisform input[type=submit]').attr('disabled', 'disabled');
		},
		loadComplete: function(data) {
			$('#whoisform input[type=submit]').removeAttr('disabled');
			if (data) {
				dispNumCurrentDomains(data);
				dispNumHistoricDomains(data);
				dispCurrentReportPrice(data);
				dispHistoricReportPrice(data);
				dispStats(data);
				linkfyWhoisRecords();
				setSearchType(data);
			}
			dispSearchError(data);
		},
		loadError: function(xhr, status, error) {
			jQuery("#revWhoisSummary").html(error);
			$('#whoisform input[type=submit]').removeAttr('disabled');
			var error = 'Search failed due to server error, please contact support@whoisxmlapi.com.';
			dispSearchError({
				error: error,
				history_error: error,
				current_error: error
			});
		},
		loadtext: '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</div>',
		loadui: 'block',
		hidegrid: false,
		datatype: "json",
		colNames: ['Domain name', 'Whois Records'],
		colModel: [{
			name: 'domain_name',
			index: 'domain_name',
			width: 200
		}, {
			name: 'whois_records',
			index: 'whois_records',
			width: 360
		}],
		shrinkToFit: true,
		rowNum: 10,
		rowList: [10, 20, 30],
		pager: '#revWhoisPager',
		sortname: 'domain_name',
		viewrecords: true,
		sortorder: "asc",
		caption: "<?php echo rev_whois_report_table_title();?>",
		autowidth: true,
		height: '100%',
		postData: post_data
	});
	jQuery("#revWhoisGrid").jqGrid('navGrid', '#revWhoisPager', {
		edit: false,
		add: false,
		del: false,
		search: false,
		refresh: true
	});
	jQuery("#myReportsGrid").jqGrid({
		url: BASE_URL + 'reversewhoismyreport.php',
		loadComplete: function(data) {
			//console.log(data);
			if (data) {
				//$('#rw_main').tabs('option','ajaxOptions',{'dataType': "script"});
				//alert($('#rw_main').tabs('option','ajaxOptions')['dataType']);
				$('.tabfy').click(function(evt) {
					/*try {
									var href = $(this).attr('href');
									var title = $(this).parent().attr('title');
									var report_id = getParameterByName(href, 'report_id');
									var tabs = $('#rw_main');
									var index = -1;
									if ((index = get_report_tab_index(tabs, report_id)) > 0) {
										tabs.tabs('url', href, index);
										tabs.tabs('select', index);
										tabs.tabs('load', index);
									} else {
										index = tabs.tabs('length');
										tabs.tabs('add', href, tab_title_html(title));
										tabs.tabs('select', index);
										put_report_tab_index(tabs, report_id, index);
									}
								} catch (e) {
									alert(e);
								}*/
					var href = $(this).attr('href');
					$dialog.html('<p><img src="images/icons/ajax-loader-bar.gif" width="400" height="19" /></p>');
					var domain_name = getParameterByName(href, 'd');
					var search_type = getParameterByName(href, 'search_type');
					var date = stdFormatDate(epocToDate(getParameterByName(href, 'w')));
					$dialog.load(href, function() {
						/*$('#preview_order_report').html('<div style="font-size:12px;width:100%">The following is a preview of the Whois Record. Whois records referenced in this Reverse Whois Report will be available to you once you purchase the full report.  <a href="'+
										get_order_link(search_type)
										+'">Order Now</a></div>');*/
					}).dialog('open');
					return false;
				});
			}
		},
		loadError: function(xhr, status, error) {
			jQuery("#myReportsSummary").html(error);
		},
		hidegrid: false,
		datatype: "json",
		colNames: ['Created Date', 'Search Term', 'Domains', 'Report Price'],
		colModel: [{
			name: 'created_date',
			index: 'created_date',
			// width: 150,
			formatter: 'date',
			formatoptions: {
				srcformat: 'ISO8601Long',
				newformat: 'm/d/Y H:i:s'
			}
		}, {
			name: 'search_terms',
			index: 'search_terms',
			width: 250
		}, {
			name: 'domains',
			index: 'domains',
			width: 80
		}, {
			name: 'price',
			index: 'price',
			width: 80,
			formatter: 'currency',
			formatoptions: {
				prefix: "$"
			}
		}],
		rowNum: 10,
		rowList: [10, 20, 30],
		pager: '#myReportsPager',
		sortname: 'created_date',
		viewrecords: true,
		sortorder: "desc",
		caption: "Purchased Reports",
		autowidth: true,
		//width:'100%',
		height: '100%'
	});
	jQuery("#myReportsGrid").jqGrid('navGrid', '#myReportsPager', {
		edit: false,
		add: false,
		del: false,
		search: false
	});
	$('.wa-navbar-header-rw-lookup').click(function() {
		$(".wa-navbar-collpase-rw-lookup").collapse('toggle');
	});
});
</script>