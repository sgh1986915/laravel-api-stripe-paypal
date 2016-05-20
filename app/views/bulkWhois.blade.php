<?php
error_reporting(0);
@ini_set('display_errors', 0);
$libPath = base_path(). "/whoisxmlapi_4";
require_once $libPath."/users/users.conf";
require_once $libPath."/bulk-whois-lookup-price.php";
$bwl_speed=$_REQUEST['bwl_speed'];
if(!$bwl_speed)$bwl_speed="regular";
$num_domains=$_REQUEST['num_domains'];
if(!$num_domains)$num_domains=500000;
?>
@extends('layouts.master')
@section('title')
Bulk Whois Lookup
@stop

@section('styles')
@parent
{{ HTML::style('css/bulkwhois.css') }}
{{ HTML::style('css/lightbox.css') }}
@stop

@section('scripts')
{{ HTML::script('js/lightbox.min.js') }}
@stop

@section('content')
<div class="row wa-searchbox-radio">
	<div class="col-xs-12 wa-auto-margin">
		<div class="row">
			<div class="col-sm-6 col-xs-12 wa-search-eg-box wa-search-eg-box-bulkwhois">
				<form id="whoisform" name="whoisform" action="{{ URL::to('whoislookup.php') }}">
					<div class="form-group has-feedback wa-search-box wa-search-box-bulkwhois">
						<input type="text" class="form-control wa-search wa-search-bulkwhois" name="domainName" id="wa-search-whoislookup" placeholder="Whois Lookup">
						<span class="glyphicon glyphicon-search form-control-feedback wa-cursor" id="wa-search-icon-whoislookup"></span>
						<div class="wa-exapple wa-example-bulkwhois">Example: google.com or 74.125.45.100</div>
						<div class="wa-radio-inputs wa-radio-xml-Josn wa-radio-xml-Josn-bulkwhois">
							<div class="wa-radio-input wa-radio-input-xml wa-radio-input-bulkwhois">
								<input type="radio" value="xml" checked id="wa-radio-xml" class="wa-cursor wa-radio-orange-bar wa-field-input-selection wa-field-input-selection-bulkwhois wa-api-res-type" name="outputFormat">
								<label for="wa-radio-xml" class="wa-cursor wa-field-value-selection wa-field-value-selection-home-XML wa-field-value-selection-bulkwhois " id="wa-lbl-XMl">XML</label>
								<div class="wa-home-radio-outerCircle">
									<div class="wa-home-radio-innerCircle"></div>
								</div>
							</div>
							<div class="wa-radio-input">
								<input type="radio" value="json" id="wa-radio-json" class="wa-cursor wa-radio-orange-bar wa-field-input-selection wa-field-input-selection-bulkwhois wa-api-res-type" name="outputFormat">
								<label for="wa-radio-json" class="wa-cursor wa-field-value-selection wa-field-value-selection-home-JSON wa-field-value-selection-bulkwhois" id="wa-lbl-JSON">JSON</label>
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
			<div class="col-sm-6 col-xs-12 wa-btn wa-btn-bulkwhois">
				<div class ="row">
					<div class="col-sm-offset-4 col-sm-4 col-xs-12 wa-btn-order">
						<a href="{{ URL::to('order_paypal.php') }}"><button type="button" class="btn btn-default wa-btn-orderNow wa-btn-orderNow-bulkwhois center-block" id="wa-btn-home-orderNow">ORDER NOW</button></a>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="row wa-page-title-content-bg">
	<div class="col-xs-12 wa-about-whoisApi wa-auto-margin">
		<h1 class="text-center wa-title wa-title-bulkwhois">Bulk Whois Lookup</h1>
		<div class="text-center wa-content-text wa-content-spacing wa-content-text-bulkwhois wa-content-text-service-bulkwhois wa-content-spacing">
			Use our fast-track service or software to check domain names and collect parsed whois data in bulk.
		</div>
	</div>
</div>
<div id="wa-page-content">
	<div class="row wa-content-bg wa-content-bg-bulkwhois">
		<div class="col-xs-12 wa-col-xs-no-padding wa-box-width-xs wa-box-margin-whoisapi wa-auto-margin">
			<div class="wa-box wa-box-xs-padding wa-box-lookupdomain-bulkwhois wa-top-content-margin">
				<h2 class="wa-section-title wa-section-title-bulkwhois wa-section-title-lookup-bulkwhois text-center">Bulk Whois Lookup / Bulk Domain Checker</h2>
				<div class="text-center wa-content-text wa-content-text-bulkwhois wa-content-text-lookup-bulkwhois wa-content-spacing">
					You can rely on our <span class="wa-link wa-cursor wa-textDecoration">{{ HTML::link('#bulk_fasttrack', 'bulk whois lookup services') }}</span> for fast-track service. Our robust API conducts super-quick lookup on an array of domain names through our backend processes. The service can be availed for a manual processing fee. The <span class="wa-link wa-cursor wa-textDecoration">{{ HTML::link('#bulk_application', 'application') }}</span> performs the lookup efficiently and exports relevant data with a convenient graphical user interface, thus providing the user complete control of the process. The service as well as the software require a <span class="wa-link wa-cursor wa-textDecoration">{{ HTML::link('/', 'whois API webservice') }}</span> account for the process.
				</div>
			</div>
		</div>
		<div class="col-xs-12 wa-auto-margin wa-col-xs-no-padding">
			<div class="row">
				<div class="col-xs-12 wa-box-width-xs">
					<div class="wa-box wa-box-xs-padding wa-box-bulkwhois wa-box-lookupapp-bulkwhois">
						<h2 class="wa-section-title wa-section-title-bulkwhois wa-section-title-application-bulkwhois text-center" id="bulk_application">
							Bulk Whois Lookup Application
						</h2>
						<div class="wa-content-text wa-content-text-bulkwhois wa-content-spacing wa-content-text-api-bulkwhois">
							Bulk Whois Lookup API is a robust desktop graphical user interface application that communicates with Whois API Webservice. Transmitting data seamlessly, the API allows users to bulk load and mass query. It facilitates import and export of whois data. <span class="wa-link wa-cursor wa-textDecoration">{{ HTML::link('order_paypal.php#wa-bulklookupapp', 'Order Now.') }}</span>
						</div>
						<a href="images/whois_api_client.gif" data-lightbox="image-1" data-title="Whois API Client">{{ HTML::image('images/whois_api_client.gif', 'Responsive image', array('class'=>'img-responsive center-block wa-img-bulkwhois')) }}</a>
						<a href="images/whois_api_client_screen2.gif" data-lightbox="image-1">{{ HTML::image('images/whois_api_client.gif', 'Responsive image', array('class'=>'img-responsive center-block wa-img-bulkwhois-2')) }}</a>
						<div class="embed-responsive embed-responsive-16by9 wa-video-bilkwhois wa-video-application-bulkwhois center-block">
							<iframe width="543" height="360" src="//www.youtube.com/embed/aWxvgNhvZgw" frameborder="0"></iframe>
						</div>
					</div>
				</div>
				<div class="col-xs-12 wa-box-width-xs ">
					<div class="wa-box wa-box-xs-padding wa-box-servie-bulkwhois wa-box-bulkwhois">
						<h2 class="wa-section-title wa-section-title-bulkwhois wa-section-title-service-bulkwhois text-center " id="bulk_fasttrack">
							Bulk Whois Lookup fast-track Service
						</h2>
						<div class="wa-content-text wa-content-text-bulkwhois wa-content-spacing wa-content-text-service-bulkwhois">
							Our backend process facilitates extraction of fresh whois data directly for a domain list of any size. The outcome will be Mysql or Mssql-compatible database dump files available for download. <span class="wa-link wa-cursor wa-textDecoration">{{ HTML::link('https://www.whoisxmlapi.com/download.php?file=documentation/sample_bulk_whois.csv', 'Sample csv file') }}</span> or database schema in <span class="wa-link wa-cursor wa-textDecoration"> {{ HTML::link('https://www.whoisxmlapi.com/db_schemas/whoiscrawler_schema_mysql.sql', 'Mysql') }}</span> and <span class="wa-link wa-cursor wa-textDecoration">{{ HTML::link('https://www.whoisxmlapi.com/db_schemas/whoiscrawler_schema_mssql.sql', 'Mssql') }}</span> can be downloaded. Data in other formats is available as well; however, it will require extra delivery time for that. Processing fee and the time required are based on the table given. <span class="wa-link wa-cursor wa-textDecoration">{{ HTML::link('order_paypal.php#bulk-fasttrack', 'Order Now.') }}</span>
						</div>
						<table class="table table-bordered table-striped wa-table-fast-track wa-content-text wa-content-text-bulkWhois" id="number_dom">
							<thead>
								<tr>
									<th class="wa-noOfDomains-bulk">Number Of Domain Names</th>
									<th colspan="2">
										<div>Price & Time to completion*</div>
									</th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td colspan="1"></td>
									<td>
										<div class="wa-lbl-regular">Regular</div>
									</td>
									<td>
										<div class="wa-lbl-expdited">Expedited</div>
									</td>
								</tr>
								<?php
								$i=0;
								foreach($regular_bwl_prices as $amount=>$price){
									$price2=$expedited_bwl_prices[$amount];
									?>
									<tr>
										<td><?php echo number_format($amount); ?></td>
										<td class="td-nowrap">$<?php echo $price ?></br><?php echo $regular_bwl_speed[$amount]?> days</td>
										<td class="td-nowrap">$<?php echo $price2 ?></br><?php echo $expedited_bwl_speed[$amount]?> days</td>
									</tr>
									<?php } ?>

									<tr class="wa-rowcolor-bulkwhois">
										<td></td>
										<td class="wa-tdfloat-bulkwhois">> <?php echo number_format($amount);?></td>
										<td colspan="2">
											<div class="wa-link wa-cursor wa-textDecoration pull-left">{{ HTML::link('mailto:support@whoisxmlapi.com', 'contact us') }}</div>
										</td>
									</tr>
									<tr class="wa-rowcolor-bulkwhois">
										<td colspan="4">
											<div class="text-center">Note: time to completion are measured in business days.</div>
										</td>
									</tr>
								</tbody>
							</table>
							<div class="embed-responsive embed-responsive-16by9 wa-video-bilkwhois center-block wa-video-service-bulkwhois">
								<iframe width="543" height="360" src="//www.youtube.com/embed/xh6y2lKRLlc" frameborder="0"></iframe>
							</div>

						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-xs-12">
						<div class="wa-box wa-box-xs-padding wa-box-keyfeatures-bulkWhois wa-box-bulkWhois wa-bottom-content-margin">
							<h2 class="wa-section-title wa-section-title-bulkwhois text-center">
								Key Features:
							</h2>
							<div class="wa-listcontent-bulkwhois">
								<ul class="list-unstyled">
									<li class="wa-content-text wa-ourFeatures-lists  wa-featuresList-bulkwhois"><span class="wa-list-no">01</span><span class="wa-ourFeatures-lbl">Mass query on an array of domain names in automated manner</span></li>
									<li class="wa-content-text wa-ourFeatures-lists  wa-featuresList-bulkwhois"><span class="wa-list-no">02</span><span class="wa-ourFeatures-lbl">Display of parsed and raw whois data in a grid</span></li>
									<li class="wa-content-text wa-ourFeatures-lists  wa-featuresList-bulkwhois"><span class="wa-list-no">03</span><span class="wa-ourFeatures-lbl">Export both parsed and raw whois data into Comma Separated Values (.csv) or Excel (.xls and xlsx) files</span></li>
									<li class="wa-content-text wa-ourFeatures-lists  wa-featuresList-bulkwhois"><span class="wa-list-no">04</span><span class="wa-ourFeatures-lbl">Full control over the whois data query process</span></li>
									<li class="wa-content-text wa-ourFeatures-lists  wa-featuresList-bulkwhois"><span class="wa-list-no">05</span><span class="wa-ourFeatures-lbl">No installation required; system uses Java Webstart to deliver and update over the web seamlessly</span></li>
								</ul>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	@stop
