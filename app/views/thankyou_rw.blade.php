<?php
$libPath = base_path(). "/whoisxmlapi_4";
$order_type = (isset($_REQUEST['order_type']) ? $_REQUEST['order_type'] : false);
unset($_SESSION['order_id']);
unset($_SESSION['cart']);
?>
@extends('layouts.master')

@section('title')
Thank you
@stop

@section('styles')
@parent
{{ HTML::style('css/reverseWhoisLookup.css') }}
{{ HTML::style('css/thankyou.css') }}
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
			<h1 class="text-center wa-title wa-title-thankyou">Thank You</h1>
		</div>
	</div>
	<div id="wa-page-content">
		<div class="row wa-content-bg wa-content-bg-thankyou">
			<div class="col-xs-12 wa-box-width-xs wa-col-xs-no-padding wa-auto-margin">
				<div class="wa-box wa-box-thankyou wa-box-xs-padding wa-top-content-margin wa-bottom-content-margin wa-box-thankyoutitle-thankyou">
					<h2 class="wa-section-title wa-section-title-thankyou wa-section-title-thankyoutitle-thankyou text-center">Thank You</h2>
					<div class="wa-content-text wa-content-text-thankyou wa-content-spacing wa-content-text-thankyoutitle-thankyou text-center">
						<?php
						if($order_type=='report') {
							echo 'Your order has been processed. Please login using the account username that you ordered for, click on "Reverse Whois" from "PRODUCTS & SERVICES" menu on the top, and then click on "My Reverse Whois Reports" in the orange bar to see your reports.';
						} else if($order_type=='credit') {
							echo 'Your order has been processed. Please login using the account username that you ordered for and start using reverse whois searches to utilize your credits.';

						} else {
							echo 'Your order will be processed and filled shortly.';
						}
						?>
						Please email <span class="wa-link wa-cursor wa-textDecoration">{{ HTML::link('mailto:support@whoisxmlapi.com', 'support@whoisxmlapi.com') }}</span> if you have any questions.
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
@stop