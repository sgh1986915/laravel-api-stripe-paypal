<?php
$libPath = base_path(). "/whoisxmlapi_4";
?>
@extends('layouts.master')
@section('title')
Reverse Whois API
@stop

@section('styles')
@parent
{{ HTML::style('css/reversewhoisapi.css') }}
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
				<div class="col-sm-6 col-xs-12 wa-search-eg-box wa-search-eg-box-reversewhoisapi">
					<div class="form-group has-feedback wa-search-box wa-search-box-reversewhoisapi">
						<input type="text" class="form-control wa-search wa-search-reversewhoisapi"  name="term1" id="wa-search-reversewhoislookup" placeholder="Reverse Whois Lookup">
						<span class="glyphicon glyphicon-search form-control-feedback wa-cursor" id="wa-search-icon-reverselookup"></span>
						<div class="wa-exapple wa-example-reversewhoisapi">Example: John Smith, test@gmail.com</div>
						<div class="wa-checkbox-inputs wa-checkbox-moreOptions wa-checkbox-moreOptions-reversewhoisapi">
							<div class="wa-checkbox-input wa-checkbox-input-moreOption wa-checkbox-input-reversewhoisapi">
								<input type="checkbox" value="moreoptions"  id="wa-checkbox-moreOption" class="wa-cursor wa-field-input-selection wa-field-input-selection-reversewhoisapi wa-api-res-type">
								<label for="wa-checkbox-moreOption" class="wa-cursor wa-field-value-selection wa-field-value-selection-reversewhoisapi " id="wa-checkbox-moreOption">More Options</label>
							</div>
						</div>
						<div id="wa-user-stats" class="wa-user-stats"><?php include_once $libPath . "/user_stats.php"; ?>
						</div>
					</div>
				</div>
				<div class="col-sm-6 col-xs-12 wa-btn wa-btn-reversewhoisapi">
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
					<div class="col-xs-12 wa-historicRecord-reversewhoisapi">
						<div class="wa-margin-checkbox wa-historic-checkbox wa-cursor ">
							<label class="wa-field-value-selection wa-field-value-selection-checkbox"><input type="checkbox" class="wa-checkbox-reversewhoisapi" name="search_type"value="" id="wa-checkbox-historic-records">Include Historic Records</label>
						</div>
					</div>
					<div class="col-xs-12">
						<label class="wa-field-value-selection wa-field-value-selection-checkbox">Include whois records containing ALL of the following terms in addition to the primary search term above:</label>
					</div>
					<div class="col-sm-6 col-xs-12">
						<input type="text" class="form-control wa-search-form wa-search-form-reversewhoisapi wa-search-form1" id="wa-input-type-include-1" name="term2">
						<input type="text" class="form-control wa-search-form wa-search-form-reversewhoisapi wa-search-form2" id="wa-input-type-include-2" name="term4">
					</div>
					<div class="col-sm-6 col-xs-12">
						<input type="text" class="form-control wa-search-form wa-search-form-reversewhoisapi wa-search-form3" id="wa-input-type-include-3" name="term3">
					</div>
				</div>
				<div class="row">
					<div class="col-sm-12 col-xs-12 wa-searchBox-exclude-reversewhoisapi">
						<div class ="wa-field-value-selection">
						<label class="wa-field-value-selection">Exclude whois records containing ANY of following terms:</label>
						</div>
					</div>
					<div class="col-sm-6 col-xs-12">
						<input type="text" class="form-control wa-search-form wa-search-form-reversewhoisapi  wa-search-form4" id="wa-input-type-exclude-1"name="exclude_term1">
						<input type="text" class="form-control wa-search-form wa-search-form-reversewhoisapi  wa-search-form5" id="wa-input-type-exclude-2"name="exclude_term3">
					</div>
					<div class="col-sm-6 col-xs-12">
						<input type="text" class="form-control wa-search-form wa-search-form-reversewhoisapi wa-search-form6" id="wa-input-type-exclude-3"name="exclude_term2">
					</div>
				</div>
			</div>
		</form>
	</div>
</div>
<div class="row wa-page-title-content-bg">
	<div class="col-xs-12 wa-about-whoisApi wa-auto-margin">
		<h1 class="text-center wa-title wa-title-reversewhoisapiapi">Reverse Whois API</h1>
		<div class="wa-content-text wa-content-spacing wa-content-text-reversewhoisapi wa-page-description wa-page-description-reversewhoisapi text-center">
			Reverse Whois API provides a RESTful webservice for reverse search.
		</div>
	</div>
</div>
	<div id="wa-page-content">
		<div class="row wa-content-bg wa-content-bg-reversewhoisapi">
			<div class="col-xs-12 wa-box-width-xs wa-auto-margin wa-col-xs-no-padding">
				<div class="wa-box wa-box-reverse-api-guide wa-top-content-margin wa-box-xs-padding">
					<h2 class="wa-section-title wa-section-title-reversewhoisapi wa-section-title-apiGuide-reversewhoisapi text-center">Reverse Whois API Guide</h2>
				</div>
			</div>
			<div class="col-xs-12 wa-auto-margin wa-col-xs-no-padding">
				<div class="wa-box wa-box-xs-padding wa-bottom-content-margin wa-bottom-content-margin wa-box-webserviceCall-reversewhoisapi wa-box-reversewhoisapi">
					<h2 class="text-center wa-section-title wa-section-title-reversewhoisapi wa-section-title-webService-reversewhoisapi">
						How to make a webservice call to Reverse Whois API?
					</h2>
					<div class="wa-content-text wa-contentLink-reversewhoisapi wa-content-spacing">
						http://www.whoisxmlapi.com/reverse-whois-api/search.php?term1=topcoder&search_type=current&mode=purchase&username=xxxxx&password=xxxxx
					</div>
					<div class="row">
						<div class="col-sm-6 col-xs-12 wa-box-width-xs">
							<div class="wa-content-text wa-content-spacing">
								<table class="table table-bordered table-striped wa-table-webService-reversewhoisapi wa-table-reversewhoisapi">
									<thead>
										<tr>
											<th>Parameters</th>
											<th>Values</th>
										</tr>
									</thead>
									<tbody>
										<tr>
											<td>termx</td>
											<td>term to search for, x ranges from 1 to 4, term1 is the first term to search for, term2 is the second term to search for, the relationship between the terms is AND. At least one term is required.</td>
										</tr>
										<tr>
											<td>exclude_termx</td>
											<td>term to exclude in the search, x ranges from 1 to 4, exclude_term1 is the first term to exclude, exclude_term2 is the second term to exclude. optional.</td>
										</tr>
										<tr>
											<td>search_type</td>
											<td>current or historic, defaults to current. optional.</td>
										</tr>
										<tr>
											<td>mode</td>
											<td>purchase, preview, or sample_purchase. defaults to preview. optional.
												preview: only lists the size and retail price of the query. It does not cost any reverse whois credit.</br>
												purchase: includes the complete list of domain names that match the query. It costs you one reverse whois credit</br>
												sample_purchase: a sample result is returned regardless of the input search terms. It does not cost any reverse whois credit.
											</td>
										</tr>
										<tr>
											<td>since_date</td>
											<td>if specified, only domains with whois record discovered/created after (including domains that are registered after) this date will be returned</td>
										</tr>
										<tr>
											<td>username</td>
											<td>required, your account username</td>
										</tr>
										<tr>
											<td>password</td>
											<td>required, your account password</td>
										</tr>
									</tbody>
								</table>
							</div>
						</div>
						<div class="col-sm-6 col-xs-12 wa-box-width-xs">
							<div class="wa-box wa-box-xs-padding wa-box-reversewhoisapi wa-box-output-reversewhoisapiapi">
								<div class="col-xs-12 wa-output-reversewhoisapiapi">
									Output Schema
									<div class="wa-content-text wa-content-text-reversewhoisapiapi wa-content-text-array-reversewhoisapiapi wa-content-spacing">
	<pre class="wa-reversewhoisapiapi wa-pre-content-reversewhoisapi">{
	/* an array of resulting domains */
	domains: Array,
	/* total number of domains returned for this query */
	records: Integer,
	stats: {
		/* total number of possible domains for this query */
		total_count: Integer,
		/* regular report price */
		report_price: Float
	},
	search_terms: {
		/* input term to search for */
		include: Array,
		/* input terms to exclude */
		exclude: Array,
		/* maximum number of search terms*/
		max_search_terms: Integer
	},
	/* search type: current or historic */
	search_type: String,
	/* duration of the search in seconds */
	time: Float
	}

	</pre>
								</div>
								</div>

							</div>
						</div>
					</div>
					<div class="wa-content-text wa-content-text-reversewhoisapi wa-content-text-purchase-reversewhoisapi wa-content-spacing">You must <span class="wa-link wa-cursor wa-textDecoration">{{ HTML::link('bulk-reverse-whois-order.php', 'purchase reverse whois credits') }}</span> to use Reverse Whois API.</div>
				</div>
			</div>
		</div>
	</div>
@stop