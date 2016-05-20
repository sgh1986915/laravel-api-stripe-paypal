<?php
$libPath = base_path(). "/whoisxmlapi_4";
?>
@extends('layouts.master')

@section('title')
ReverseWhoislookupPricing
@stop

@section('styles')
@parent
{{ HTML::style('css/reversewhoislookuppricing.css') }}
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
				<div class="col-sm-6 col-xs-12 wa-search-eg-box wa-search-eg-box-reversewhoislookuppricing">
					<div class="form-group has-feedback wa-search-box wa-search-box-reversewhoislookuppricing">
						<input type="text" class="form-control wa-search wa-search-reversewhoislookuppricing" name="term1" id="wa-search-reversewhoislookup" placeholder="Reverse Whois Lookup">
						<span class="glyphicon glyphicon-search form-control-feedback wa-cursor" id="wa-search-icon-reverselookup"></span>
						<div class="wa-exapple wa-example-reversewhoislookuppricing">Example: John Smith, test@gmail.com</div>
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
				<div class="col-sm-6 col-xs-12 wa-btn wa-btn-reversewhoislookuppricing">
					<div class="row">
						@if (Auth::check())
						<div class="col-sm-offset-4 col-sm-4 col-xs-12 wa-btn-view-my-rw-report">
							<a href={{ URL::to('reverse-whois-order.php') }}><button type="button" class="btn btn-default wa-btn-my-rw-report wa-btn-viewShopping-innerpg center-block no-margin" id="wa-btn-home-orderNow">MY REVERSE WHOIS REPORTS</button></a>
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
					<div class="col-xs-12 wa-historicRecord-reversewhoislookuppricing">
						<div class="wa-margin-checkbox wa-historic-checkbox wa-cursor">
							<label class="wa-field-value-selection"><input type="checkbox" class="wa-checkbox-reversewhoislookuppricing" name="search_type" value="" id="wa-checkbox-historic-records">Include Historic Records</label>
						</div>
					</div>
					<div class="col-xs-12">
						<label class="wa-field-value-selection wa-field-value-selection-checkbox">Include whois records containing ALL of the following terms in addition to the primary search term above:</label>
					</div>
					<div class="col-sm-6 col-xs-12 ">
						<input type="text" class="form-control wa-search-form wa-search-form-reversewhoislookuppricing wa-search-form1" id="wa-input-type-include-1"name="term2">
						<input type="text" class="form-control wa-search-form wa-search-form-reversewhoislookuppricing wa-search-form2" id="wa-input-type-include-2"name="term4">
					</div>
					<div class="col-sm-6 col-xs-12">
						<input type="text" class="form-control wa-serach-form wa-search-form-reversewhoislookuppricing wa-search-form3" id="wa-input-type-include-3"name="term3">
					</div>
				</div>
				<div class="row">
					<div class="col-sm-12 col-xs-12 wa-searchBox-exclude-reversewhoislookuppricing">
						<div class ="wa-field-value-selection">
						<label class="wa-field-value-selection">Exclude whois records containing ANY of following terms:</label>
						</div>
					</div>
					<div class="col-sm-6 col-xs-12 wa-textspacing">
						<input type="text" class="form-control wa-search-form wa-search-form-reversewhoislookuppricing wa-search-form4" id="wa-input-type-exclude-1"name="exclude_term1">
						<input type="text" class="form-control wa-search-form wa-search-form-reversewhoislookuppricing wa-search-form5" id="wa-input-type-exclude-2"name="exclude_term3">
					</div>
					<div class="col-sm-6 col-xs-12">
						<input type="text" class="form-control wa-search-form wa-search-form-reversewhoislookuppricing wa-search-form6" id="wa-input-type-exclude-3"name="exclude_term2">
					</div>
				</div>
			</div>
		</form>
	</div>
</div>
<div class="row wa-page-title-content-bg">
	<div class="col-xs-12 wa-about-whoisApi wa-auto-margin">
		<h1 class="text-center wa-title wa-title-reversewhoislookuppricing">Reverse Whois Lookup Pricing</h1>
	</div>
</div>
<div id="wa-page-content">
	<div class="row wa-content-bg wa-content-bg-reversewhoislookuppricing">
		<form action="{{ $data['form_action'] }}" class="ignore_jssm">
			<input id="choice_cc" name="pay_choice" type="hidden" value="{{ $data['pay_choice'] }}">
			<!--<input type="hidden" name="sandbox" value="1"/>-->
			<div class="col-xs-12 wa-col-xs-no-padding wa-box-width-xs wa-auto-margin wa-col-xs-no-padding">
				<div class="row">
					<div class="col-xs-12">
						<div class="wa-box wa-box-xs-padding wa-top-content-margin wa-box-Secure-reversewhoislookuppricing">
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
			<div class="col-xs-12 wa-col-xs-no-padding wa-auto-margin wa-col-xs-no-padding">
				<div class="row">
					<div class="col-sm-6 col-xs-12 wa-box-width-xs">
						<!--  table for Pay as you go Purchase Plan -->
						<div class="row">
							<div class="col-xs-12">
								<div class="wa-box wa-box-xs-padding wa-box-payPlan-reversewhoislookuppricing" id="ordercredit-payplan">
									<h2 class="wa-section-title wa-section-title-reversewhoislookuppricing wa-section-title-purchase-reversewhoislookuppricing text-center">Bulk "Pay as you go" Purchase Plan</h2>
									<div class="wa-content-text wa-content-text-reversewhoislookuppricing wa-content-text-purchase-reversewhoislookuppricing wa-content-spacing">Simply purchase the number of reverse whois credits you require and they will be added to your account instantly. You will receive a notification email before your account reaches empty. You can buy more queries or replenish your account any time.
									<div><span class="wa-link wa-cursor wa-textDecoration">{{ HTML::link('order_paypal.php#ordernow-payplan', 'Order Now!') }}</span></div></div>
									<div>
										<table class="table table-bordered  table-striped wa-content-text wa-content-text-reversewhoislookuppricing wa-table-reversewhoislookuppricing">
											<thead>
												<tr>
													<th>Number of credits</th>
													<th>Price/Credit</th>
													<th>Price (USD)</th>
												</tr>
											</thead>
											<tbody>
												<tr>
													<td>5</td>
													<td>$10.8</td>
													<td>$54</td>
												</tr>
												<tr>
													<td>10</td>
													<td>$9</td>
													<td>$90</td>
												</tr>
												<tr>
													<td>25</td>
													<td>$7.2</td>
													<td>$180</td>
												</tr>
												<tr>
													<td>50</td>
													<td>$6</td>
													<td>$300</td>
												</tr>
												<tr>
													<td>100</td>
													<td>$4.8</td>
													<td>$480</td>
												</tr>
												<tr>
													<td>250</td>
													<td>$3.6</td>
													<td>$900</td>
												</tr>
												<tr>
													<td>500</td>
													<td>$3</td>
													<td>$1500</td>
												</tr>
												<tr>
													<td>>500</td>
													<td>customized</td>
													<td class="wa-link wa-cursor wa-textDecoration">{{ HTML::link('mailto:support@whoisxmlapi.com', 'contact us') }}</td>
												</tr>
											</tbody>
										</table>
									</div>
									<div class="row">
										<div class="col-xs-12">
											<div class="pull-right"><button type="submit" class="btn btn-default submit-button wa-btn-next wa-btn-orange wa-btn-whois-lookuppricing">Next</button></div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="col-sm-6 col-xs-12 wa-box-width-xs">
						<!--  table for Membership Plans -->
						<div class="row">
							<div class="col-xs-12">
								<div class="wa-box wa-box-xs-padding wa-box-membership-reversewhoislookuppricing" id="ordercredit-memberplan">
									<h2 class="wa-section-title wa-section-title-reversewhoislookuppricing wa-section-title-membership-reversewhoislookuppricing text-center">Bulk Membership Plans</h2>
									<div  class=" wa-content-text wa-content-text-reversewhoislookuppricing wa-content-text-membership-reversewhoislookuppricing wa-content-spacing">By purchasing a membership plan, you may use up to a certain maximum number of reverse whois credits each month, this is recommended if you use Reverse Whois on a regular basis. You may cancel/change your plan anytime. <span class="wa-link wa-cursor wa-textDecoration">{{ HTML::link('order_paypal.php#ordernow-memberplan', 'Order Now!') }} </span></div>
									<div>
										<table class="table table-bordered table-striped wa-content-text wa-content-text-reversewhoislookuppricing wa-table-reversewhoislookuppricing">
											<thead>
												<tr>
													<th>Maximum number of credits/month</th>
													<th>Price/Credit</th>
													<th>Price/Month (USD)</th>
													<th>Price/Year (USD)</th>
												</tr>
											</thead>
											<tbody>
												<tr>
													<td>5</td>
													<td>$7.2</td>
													<td>$36</td>
													<td>$360</td>
												</tr>
												<tr>
													<td>10</td>
													<td>$6</td>
													<td>$60</td>
													<td>$600</td>
												</tr>
												<tr>
													<td>25</td>
													<td>$4.8</td>
													<td>$120</td>
													<td>$1200</td>
												</tr>
												<tr>
													<td>50</td>
													<td>$4</td>
													<td>$200</td>
													<td>$2000</td>
												</tr>
												<tr>
													<td>100</td>
													<td>$3.2</td>
													<td>$320</td>
													<td>$320</td>
												</tr>
												<tr>
													<td>250</td>
													<td>$2.4</td>
													<td>$600</td>
													<td>$6000</td>
												</tr>
												<tr>
													<td>500</td>
													<td>$2</td>
													<td>$1000</td>
													<td>$10000</td>
												</tr>
												<tr>
													<td colspan="2">* Unlimited</td>
													<td>$1500</td>
													<td>$15000 <div class="wa-link wa-cursor">{{ HTML::link('mailto:support@whoisxmlapi.com', 'contact us') }}</div></td>
												</tr>
												<tr>
													<td colspan="4">*user of the unlimited plan may not exceed the query rate of 1 query per 3 seconds</td>
												</tr>
											</tbody>
										</table>
									</div>
							</form>
									<div class="row">
										<div class="col-xs-12">
											<div class="pull-right" ><button type="submit" class="btn btn-default submit-button wa-btn-next wa-btn-whois-lookuppricing wa-btn-orange">Next</button></div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="col-xs-12 wa-col-xs-no-padding wa-box-width-xs wa-auto-margin wa-col-xs-no-padding">
				<div class="wa-box wa-box-xs-padding wa-box-standardreport-reversewhoislookuppricing wa-box-reversewhoislookuppricing wa-bottom-content-margin" id="ordercredit-standardrepot">
					<h2 class="wa-section-title wa-section-title-reversewhoislookuppricing wa-section-title-standardreport-reversewhoislookuppricing text-center">Standard Reverse Whois Report Pricing</h2>
					<div  class="wa-content-text wa-content-text-reversewhoislookuppricing wa-content-text-standardreport-reversewhoislookuppricing wa-content-spacing">The price of any Reverse Whois Report is calculated as the sum of the prices of the current and historical domains in that report. The tables below detail the pricing tiers for current and historical domains.</div>
					<div>
						<table class="table table-bordered table-striped wa-table-whois-report  wa-content-text wa-content-text-reversewhoislookuppricing">
							<thead>
								<tr>
									<th>Current and Historic Domains in Report	</th>
									<th>Price (USD)</th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td>0 - 10</td>
									<td>$19</td>
								</tr>
								<tr>
									<td>10 - 100</td>
									<td>$29</td>
								</tr>
								<tr>
									<td>100 - 250</td>
									<td>$59</td>
								</tr>
								<tr>
									<td>250 - 500</td>
									<td>$99</td>
								</tr>
								<tr>
									<td>500 - 1000</td>
									<td>$129</td>
								</tr>
								<tr>
									<td>1000 - 2500</td>
									<td>$159</td>
								</tr>
								<tr>
									<td>2500 - 5000</td>
									<td>$249</td>
								</tr>
								<tr>
									<td>5000 - 10000</td>
									<td>$329</td>
								</tr>
								<tr>
									<td>10000 +</td>
									<td>$0.02 per domain</td>
								</tr>
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
@stop