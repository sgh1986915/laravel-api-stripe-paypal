<?php
error_reporting(0);
@ini_set('display_errors', 0);
$libPath = base_path(). "/whoisxmlapi_4";
require_once $libPath."/price.php";
?>
@extends('layouts.master')

@section('title')
Affiliate_Program
@stop

@section('styles')
@parent
{{ HTML::style('css/affiliate_Program.css') }}
@stop

@section('content')
<div class="main-content">
	<div class="row wa-searchbox-radio">
		<div class="col-xs-12 wa-auto-margin">
			<div class="row">
				<div class="col-sm-6 col-xs-12 wa-search-eg-box wa-search-eg-box-affiliateprogram">
					<form id="whoisform" name="whoisform" action="{{ URL::to('whoislookup.php') }}">
						<div class="form-group has-feedback wa-search-box">
							<input type="text" class="form-control wa-search wa-search-affiliateprogram" name="domainName" id="wa-search-whoislookup" placeholder="Whois Lookup">
							<span class="glyphicon glyphicon-search form-control-feedback wa-cursor" id="wa-search-icon-whoislookup"></span>
							<div class="wa-exapple wa-example-affiliateprogram">Example: google.com or 74.125.45.100</div>
							<div class="wa-radio-inputs wa-radio-xml-Josn wa-radio-xml-Josn-affiliateprogram">
								<div class="wa-radio-input wa-radio-input-xml wa-radio-input-xml-affiliateprogram">
									<input type="radio" value="xml" name="outputFormat" checked id="wa-radio-xml" class="wa-cursor wa-radio-orange-bar wa-field-input-selection wa-field-input-selection-affiliateprogram wa-api-res-type">
									<label for="wa-radio-xml" class="wa-cursor wa-field-value-selection wa-field-value-selection-xml wa-field-value-selection-affiliateprogram" id="wa-lbl-XMl">XML</label>
									<div class="wa-home-radio-outerCircle">
										<div class="wa-home-radio-innerCircle"></div>
									</div>
								</div>
								<div class="wa-radio-input wa-radio-input-json wa-radio-input-json-affiliateprogram">
									<input type="radio" value="json" id="wa-radio-json" name="outputFormat" class="wa-cursor wa-radio-orange-bar wa-field-input-selection wa-field-input-selection-affiliateprogram wa-api-res-type">
									<label for="wa-radio-json" class="wa-cursor wa-field-value-selection wa-field-value-selection-json wa-field-value-selection-affiliateprogram" id="wa-lbl-JSON">JSON</label>
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
				<div class="col-sm-6 col-xs-12 wa-btn wa-btn-affiliateprogram">
					<div class="row">
						<div class="col-sm-offset-4 col-sm-4 col-xs-12 wa-btn-order">
							<a href={{ URL::to('order_paypal.php') }}><button type="button" class="btn btn-default wa-btn wa-btn-orderNow wa-btn-orderNow-affiliateprogram center-block" id="wa-btn-orderNow-affiliateprogram">ORDER NOW</button></a>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="row wa-page-title-content-bg">
		<div class="col-xs-12 wa-about-whoisApi wa-xs wa-auto-margin">
			<h1 class="text-center wa-title wa-title-affiliateprogram">Affiliate Program</h1>
		</div>
	</div>
	<div id="wa-page-content">
		<div class="row wa-content-bg wa-content-bg-affiliateprogram">
			<div class="col-xs-12 wa-col-xs-no-padding wa-box-width-xs wa-box-margin-whoisapi wa-auto-margin">
				<div class="wa-box wa-box-xs-padding wa-box-affiliateprogram wa-box-affiliatePro-affiliateprogram wa-top-content-margin">
					<h2 class="wa-section-title wa-section-title-affiliatePro wa-section-title-affiliatePro-affiliateprogram text-center">Affiliate Program</h2>
					<div class="wa-content-text wa-content-text-affiliateprogram wa-content-spacing wa-content-text-affiliatePro-affiliateprogram text-center">
						Join the whoisxmlapi.com affiliate program administered through <span class="wa-link wa-cursor wa-textDecoration">{{ HTML::link('http://www.clickbank.com/', 'clickbank.') }}</span> We offer affiliate program on the following products with 10% commission for our affiliates. due to limitation imposed by clickbank we are currently only able to offer the following product tiers, future expansion on higher product tiers is possible. <span class="wa-link wa-cursor wa-textDecoration"> {{ HTML::link('mailto:general@whoisxmlapi.com', 'Contact Us') }} </span>for detail on how to join.  </div>
					</div>
				</div>
			</div>
			<div class="row wa-content-bg wa-content-bg-affiliateprogram">
				<div class="col-xs-12 wa-col-xs-no-padding wa-auto-margin">
					<div class="wa-box wa-box-xs-padding wa-box-whoisapi-queries wa-bottom-content-margin">
						<h2 class="wa-section-title wa-section-title-affiliateprogram wa-section-title-whoisAPI-affiliateprogram text-center">Whois API queries</h2>
						<div class="row">
							<div class="col-sm-6 col-xs-12 wa-box-width-xs">
								<div class="wa-box wa-box-xs-padding wa-box-affiliateprogram wa-box-payasugo-affiliateprogram">
									<h2 class="wa-section-title wa-section-title-affiliateprogram wa-section-title-payasugo-affiliateprogram text-center">Pay as you go plan</h2>
									<div>
										<table class="table table-bordered table-striped  wa-content-text wa-content-text-affiliateprogram wa-table-affiliateprogram wa-table-whoisAPI-affiliateprogram">
											<thead>
												<tr>
													<th>Number of queries	</th>
													<th>Price (USD</th>
												</tr>
											</thead>
											<tbody>
												<?php for($i=0;$i<3;$i++){ ?>
												<tr>
													<td><?php echo number_format($queryAmount[$i])?></td>
													<td>$<?php echo $queryPrices[$queryAmount[$i]]?></td>
												</tr>
												<?php } ?>
											</tbody>
										</table>
									</div>
								</div>
							</div>
							<div class="col-sm-6 col-xs-12 wa-box-width-xs">
								<div class="wa-box wa-box-xs-padding wa-box-affiliateprogram wa-box-monthlyplan-affiliateprogram">
									<h2 class="wa-section-title wa-section-title-affiliateprogram wa-section-title-monthlyplan-affiliateprogram text-center">Monthly plans</h2>
									<div>
										<table class="table table-bordered table-striped  wa-content-text wa-content-text-affiliateprogram wa-table-affiliateprogram wa-table-monthlyuplan-affiliateprogram">
											<thead>
												<tr>
													<th>Maximum number of queries/month</th>
													<th>Price/Month (USD)</th>
												</tr>
											</thead>
											<tbody>
											<?php for($i=0;$i<2;$i++){ ?>
												<tr>
													<td><?php echo number_format($membershipAmount[$i])?></td>
													<td>$<?php echo $membershipPrices[$membershipAmount[$i]]?></td>
												</tr>
												<?php } ?>
											</tbody>
										</table>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	@stop