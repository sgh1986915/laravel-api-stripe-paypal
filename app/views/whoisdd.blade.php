<?php
error_reporting(0);
@ini_set('display_errors', 0);
$libPath = base_path(). "/whoisxmlapi_4";
require_once $libPath . "/users/users.conf";
require_once $libPath . "/httputil.php";
require_once $libPath . "/whois-database-price.php";
require_once $libPath . "/models/cctld_whois_database_product.php";

function discount_new($price, $discount){
	$new_price = $price * (1-$discount);
	$per = $discount * 100;
	$s = "<s>$".$price."</s><div>$".$new_price." (".$per." % off)</div>";
	return $s;
}

require_once $libPath .'/util/number_util.php';
require_once $libPath .'/models/cctld_whois_database_product.php';
$cctld_wdb_ids = $_REQUEST['cctld_wdb_ids'];
$cctld_whois_db_type = $_REQUEST['cctld_whois_db_type'];
if(!$cctld_whois_db_type) $cctld_whois_db_type = "whois_records";

$CCTLDDBProduct = new CCTLDWhoisDatabaseProduct();

require_once $libPath .'/models/custom_whois_database_product.php';
$custom_wdb_ids = $_REQUEST['custom_wdb_ids'];
$customDBProduct = new CustomWhoisDatabaseProduct();
?>
@extends('layouts.master')

@section('title')
Whois Database Download
@stop

@section('styles')
@parent
{{ HTML::style('css/whoisdd.css') }}
@stop

@section('content')
<div class="main-content">
	<div class="row wa-searchbox-radio">
		<div class="col-xs-12 wa-auto-margin">
			<div class="row">
				<div class="col-sm-6 col-xs-12 wa-search-eg-box wa-search-eg-box-whoisdd">
					<form id="whoisform" name="whoisform" action="{{ URL::to('whoislookup.php') }}">
						<div class="form-group has-feedback wa-search-box">
							<input type="text" class="form-control wa-search wa-search-whoisdd" name="domainName" id="wa-search-whoislookup" placeholder="Whois Lookup">
							<span class="glyphicon glyphicon-search form-control-feedback wa-cursor" id="wa-search-icon-whoislookup"></span>
							<div class="wa-exapple wa-example-whoisdd">Example: google.com or 74.125.45.100</div>
							<div class="wa-radio-inputs wa-radio-xml-Josn wa-radio-xml-Josn-whoisdd">
								<div class="wa-radio-input wa-radio-input-xml wa-radio-input-xml-whoisdd">
									<input type="radio" value="xml" checked id="wa-radio-xml" class="wa-cursor wa-radio-orange-bar wa-field-input-selection wa-field-input-selection-whoisdd wa-api-res-type" name="outputFormat">
									<label for="wa-radio-xml" class="wa-cursor wa-field-value-selection wa-field-value-selection-xml wa-field-value-selection-whoisdd" id="wa-lbl-XMl">XML</label>
									<div class="wa-home-radio-outerCircle">
										<div class="wa-home-radio-innerCircle"></div>
									</div>
								</div>
								<div class="wa-radio-input wa-radio-input-json wa-radio-input-json-whoisdd">
									<input type="radio" value="json" id="wa-radio-json" class="wa-cursor wa-radio-orange-bar wa-field-input-selection wa-field-input-selection-whoisdd wa-api-res-type" name="outputFormat">
									<label for="wa-radio-json" class="wa-cursor wa-field-value-selection wa-field-value-selection-json wa-field-value-selection-whoisdd" id="wa-lbl-JSON">JSON</label>
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
				<div class="col-sm-6 col-xs-12 wa-btn wa-btn-whoisdd">
					<div class="row">
						<div class="col-sm-offset-4 col-sm-4 col-xs-12 wa-btn-order">
							<a href={{ URL::to('order_paypal.php') }}><button type="button" class="btn btn-default wa-btn wa-btn-orderNow wa-btn-orderNow-whoisdd center-block" id="wa-btn-orderNow-whoisdd">ORDER NOW</button></a>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<!-- Whois database download -->
	<div class="row wa-page-title-content-bg">
		<div class="col-xs-12 wa-about-whoisApi wa-xs wa-auto-margin">
			<h1 class="text-center wa-title wa-title-whoisdd">Whois Database Download</h1>
			<div class="text-center wa-content-spacing wa-content-text wa-content-text-whoisdd wa-page-description wa-page-description-whoisdd">
				Provide historic Whois Database download in both parsed and raw format as csv files.
			</div>
		</div>
	</div>
	<div id="wa-page-content">
		<div class="row wa-content-bg wa-content-bg-whoisdd">
			<div class="col-xs-12 wa-box-width-xs wa-auto-margin wa-col-xs-no-padding">
				<div class="wa-box wa-top-content-margin wa-box-xs-padding wa-box-whoisdd wa-box-whoisdatabased-whoisdd">
					<h2 class="wa-section-title wa-section-title-whoisdd wa-section-title-whoisdatabased-whoisdd text-center">Whois Database Download</h2>
					<div class="wa-content-text wa-content-text-whoisdd wa-content-spacing wa-content-text-whoisdatabased-whoisdd text-center">
						We provide archived historic whois database in both parsed and raw format for download as database dumps(MYSQL or MYSSQL dump) or CSV files. Currently we provide downloads for the major gTLDs: .com, .net, .org, .us, .biz, .mobi, .info, .pro, .coop, .asia and <span class="wa-link wa-cursor wa-textDecoration">{{ HTML::link('https://www.whoisxmlapi.com/support/supported_ngtlds.php', 'hundreds of new gTLDs') }}</span>. Download a sample of the whois records with <span class="wa-link wa-cursor wa-textDecoration">{{ HTML::link('https://www.whoisxmlapi.com/download.php?file=documentation/sample_raw_db.csv', 'raw text only') }}</span> and with <span class="wa-link wa-cursor wa-textDecoration">{{ HTML::link('https://www.whoisxmlapi.com/download.php?file=documentation/sample_parsed_db.csv', 'both parsed data and raw text') }}</span>
						<span>In addition, we provide <span class="wa-link wa-cursor wa-textDecoration">{{ HTML::link('#wa-historicwhois-dd', 'cctld domain names list and cctld whois databases.') }} </span></span>
					</div>
				</div>
			</div>
			<div class="col-xs-12 wa-auto-margin wa-col-xs-no-padding">
				<div class="row">
					<div class="col-xs-12 ">
						<div class="wa-box wa-box-xs-padding wa-box-whoisdd wa-box-possibleapps-whoisdd">
							<h2 class="wa-section-title wa-section-title-whoisdd wa-section-title-possibleapps-whoisdd text-center">Possible applications and usages of historic whois database:</h2>
							<ul class="list-unstyled">
								<li class="wa-content-text wa-ourFeatures-lists wa-app-list-whoisddd "><span class="wa-list-no wa-list-no-whoisdd">01</span><span class="wa-ourFeatures-lbl wa-lbl-app-list-whoisdd">Cybersecurity analysis, Fraud detection</span></li>
								<li class="wa-content-text wa-ourFeatures-lists wa-section-title-possibleapps-whoisdd"><span class="wa-list-no">02</span><span class="wa-ourFeatures-lbl">Statistical research analysis</span></li>
								<li class="wa-content-text wa-ourFeatures-lists wa-section-title-possibleapps-whoisdd"><span class="wa-list-no">03</span><span class="wa-ourFeatures-lbl">Extract fine-grained information and gain insight from a comprehensive pool of whois records</span></li>
								<li class="wa-content-text wa-ourFeatures-lists wa-section-title-possibleapps-whoisdd"><span class="wa-list-no">04</span><span class="wa-ourFeatures-lbl">Much more... The possiblities are limitless</span></li>
							</ul>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-sm-6 col-xs-12 wa-box-width-xs">
						<!-- Pricing table for whois database download -->
						<div class="row">
							<div class="col-xs-12">
								<div class="wa-box wa-box-xs-padding wa-box-whoisdd wa-box-pricingwhois-whoisdd">
									<h2 class="wa-section-title wa-section-title-whoisdd wa-section-title-pricingwhois-whoisdd text-center">Pricing for Whois Database Download</h2>
									<div class="wa-content-text wa-content-text-whoisdd wa-content-spacing wa-content-pricingwhois-whoisdd">We offer partial or complete historic whois database download. We also offer a yearly plan with 4 quarterly downloads of complete whois databases. <span class="wa-link wa-cursor wa-textDecoration"> {{ HTML::link('order_paypal.php#wa-dd-ordernow', 'Order Now!') }}</span></div>

									<div class="wa-content-text wa-content-text-whoisdd wa-content-spacing wa-content-text-promotediscount-whoisdd">Promotional discount is only available until end of this month!</div>

									<div>
										<table class="table table-bordered table-striped wa-content-text wa-content-text-whoisdd wa-table-whoisdd wa-table-pricingwhois-whoisdd">
											<thead>
												<tr>
													<th>Number of whois records</th>
													<th>Price (Raw text only)</th>
													<th>Price (Raw text and parsed fields)</th>
												</tr>
											</thead>
											<tbody>
												<?php
												for($i=0;$i<$dbCount;$i++){
													?>
													<tr>
														<td><?php echo number_format($dbAmount[$i])?> million (randomly chosen)</td>
														<td><?php echo discount_new($dbRawPrices[$dbAmount[$i]],$dbDiscount) ?></td>
														<td><?php echo discount_new($dbParsedPrices[$dbAmount[$i]], $dbDiscount)?></td>
													</tr>
													<?php
												} ?>
												<tr>
													<td>> <?php echo number_format($dbAmount[$dbCount-1])?> million</td>
													<td class="wa-link wa-cursor wa-textDecoration">{{ HTML::link('mailto:support@whoisxmlapi.com', 'contact us' ,array('onclick' => "_gaq.push(['_trackEvent', 'mailto', 'clicked']);")) }}</td>
													<td class="wa-link wa-cursor wa-textDecoration">{{ HTML::link('mailto:support@whoisxmlapi.com', 'contact us') }}</td>
												</tr>
												<tr>
													<td>The complete database(155 million records)</td>
													<td class="wa-link wa-cursor wa-textDecoration">{{ HTML::link('mailto:support@whoisxmlapi.com', 'contact us',array('onclick' => "_gaq.push(['_trackEvent', 'mailto', 'clicked']);")) }}</td>
													<td class="wa-link wa-cursor wa-textDecoration">{{ HTML::link('mailto:support@whoisxmlapi.com', 'contact us',array('onclick' => "_gaq.push(['_trackEvent', 'mailto', 'clicked']);")) }}</td>
												</tr>
												<tr>
													<td>Yearly Subscription(4 quarterly downloads/year) of complete databases</td>
													<td class="wa-link wa-cursor wa-textDecoration">{{ HTML::link('mailto:support@whoisxmlapi.com', 'contact us',array('onclick' => "_gaq.push(['_trackEvent', 'mailto', 'clicked']);")) }}</td>
													<td class="wa-link wa-cursor wa-textDecoration">{{ HTML::link('mailto:support@whoisxmlapi.com', 'contact us',array('onclick' => "_gaq.push(['_trackEvent', 'mailto', 'clicked']);")) }}</td>
												</tr>
												<tr>
													<td>Yearly Subscription(daily updates!) of complete databases</td>
													<td class="wa-link wa-cursor wa-textDecoration">{{ HTML::link('mailto:support@whoisxmlapi.com', 'contact us',array('onclick' => "_gaq.push(['_trackEvent', 'mailto', 'clicked']);")) }}</td>
													<td class="wa-link wa-cursor wa-textDecoration">{{ HTML::link('mailto:support@whoisxmlapi.com', 'contact us',array('onclick' => "_gaq.push(['_trackEvent', 'mailto', 'clicked']);")) }}</td>
												</tr>
												<tr>
													<td>All Historic snapshots of the whois databases (about 2 billion whois records)</td>
													<td class="wa-link wa-cursor wa-textDecoration">{{ HTML::link('mailto:support@whoisxmlapi.com', 'contact us',array('onclick' => "_gaq.push(['_trackEvent', 'mailto', 'clicked']);")) }}</td>
													<td class="wa-link wa-cursor wa-textDecoration">{{ HTML::link('mailto:support@whoisxmlapi.com', 'contact us',array('onclick' => "_gaq.push(['_trackEvent', 'mailto', 'clicked']);")) }}</td>
												</tr>
											</tbody>
										</table>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="col-sm-6 col-xs-12 wa-box-width-xs">
						<!-- pricing table for Alexa & Quantcast Whois Database Download -->
						<div class="row">
							<div class="col-xs-12">
								<div class="wa-box wa-box-xs-padding wa-box-whoisdd wa-box-pricingAlexa-whoisdd">
									<h2 class="wa-section-title wa-section-title-whoisdd wa-section-title-pricingAlex-whoisdd text-center" >Pricing for alexa & quantcast Whois Database Download</h2>
									<div  class="wa-content-text wa-content-text-whoisdd wa-content-pricingAlex-whoisdd wa-content-spacing">Historic whois databases for top 1 million Alexa & Quantcast domains. <span class="wa-link wa-cursor wa-textDecoration"> {{ HTML::link('order_paypal.php#wa-alexquant-dd', 'Order Now!') }} </span></div>
									<div>
										<table class="table table-bordered table-striped wa-content-text wa-content-text-whoisdd wa-table-whoisdd wa-table-pricingAlex-whoisdd">
											<thead>
												<tr>
													<th>Description</th>
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
														<td colspan="2" class="wa-td-heading"><?=$pkey?></td>

													</tr>
													<?php
													foreach($product_group as $p){
														$id=$p['id'];
														$name=$p['name'];
														$description=$p['description'];
														$detail=$p['detail'];
														$price=format_price($p['price']);
														$checked = ($custom_wdb_ids ? in_array($id, $custom_wdb_ids) : false);
														?>
														<tr>
															<?php if($custom_db_show_input){ ?>
															<td>
																<input type="checkbox" value="<?php echo $id?>" name="custom_wdb_ids[]" <?=($checked ?"checked":"")?> price="<?=$p['price']?>"/>
															</td>
															<?php }?>
															<td> <?=$description?></td>
															<td><?=$price?></td>

														</tr>
														<?php
													} ?>
													<?php
												} ?>
												<?php if($custom_db_show_input): ?>
													<tr>
														<td>Total Price: </td>
														<td id="custom_db_total_price"><?=$total_price?></td>
													</tr>
												<?php endif; ?>
											</tbody>
										</table>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<!-- pricing table for historic cctld Whois Database Download -->
				<div class="row">
					<div class="col-xs-12 wa-box-width-xs">
						<div class="wa-box wa-bottom-content-margin wa-box-xs-padding wa-box-whoisdd wa-box-pricingHistoric-Whoisdd" id="wa-historicwhois-dd">
							<h2 class="wa-section-title wa-section-title-whoisdd wa-section-title-pricinghistoric-whoisdd text-center">Pricing for historic ccTLD Whois Database Download</h2>
							<div  class="wa-content-text wa-content-text-whoisdd wa-content-spacing wa-content-pricinghistoric-whoisdd">The following ccTLDs are offered as a one-time download only. You may choose to buy domain names list only or domains with whois records.
								Bulk discounts up to 50% off are given when you purchase 2 or more databases.
								For ccTLD where the whois database is not immediately available, you may wait anywhere between 3 business days to a month for it to be delivered. All number of domains are estimation only.</div>
								<div  class="wa-content-text wa-content-spacing wa-content-text-whoisdd"><span class="wa-link wa-cursor wa-textDecoration">{{ HTML::link('mailto:support@whoisxmlapi.com', 'contact us') }} </span>   if you want a custom cctld whois database that's not listed here or want a combo quote to get all the ccTLDs listed. There are a total of 28 cctld databases listed below. </div>
								<div class="wa-link wa-cursor wa-textDecoration wa-content-text wa-content-text-whoisdd">{{ HTML::link('order_paypal.php#wa-historic-dd', 'Order Now!') }}</div>
								<div>
									<table class="table table-bordered table-striped wa-content-text wa-content-text-whoisdd wa-table-whoisdd wa-table-pricinghistoric-whoisdd">
										<thead>
											<tr>
												<th>
													<?php if($cctld_db_show_input): ?>
														<input class="select_all" type="checkbox" title="select all"/>
													<?php endif?>
												</th>
												<th>Description</th>
												<th>Price (Domains)
													<?php if($cctld_db_show_input): ?>
														<input type="radio" name="cctld_whois_db_type" value="domain_names" <?=$cctld_whois_db_type=='domain_names'?'checked':''?>>
													<?php endif ?>
												</th>
												<th>Price (Whois)
													<?php if($cctld_db_show_input): ?>
														<input type="radio" name="cctld_whois_db_type" value="whois_records" <?=$cctld_whois_db_type=='whois_records'?'checked':''?>>
													<?php endif?>
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
												$available=false;
												foreach($product_group as $p){
													if(!$p['whois_unavailable'])$available=true;
												}
												?>
												<tr>
													<td colspan="4" class="wa-td-heading"><?php echo $available?"<b>":""?> <?=$pkey?></td>
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
														<?php if($cctld_db_show_input){ ?>
														<td>
															<input type="checkbox" value="<?php echo $id?>" name="cctld_wdb_ids[]" <?=($checked ?"checked":"")?> domain_names_price="<?=$p['domain_names_price']?>"  whois_records_price="<?=$p['parsed_whois_price']?>"/>
														</td>
														<?php }?>
														<td <?php if(!$cctld_db_show_input) echo "colspan=2";?> > <?=$description?></td>

														<td><?=$domains_price?></td>
														<td><?=$parsed_whois_price?></td>
													</tr>
													<?php
												} ?>
												<?php
											} ?>


											<?php if($cctld_db_show_input): ?>
												<tr>
													<td colspan=1><input class="select_all" type="checkbox" title="select all"/></td>
													<td colspan=1>Total Price: </td>
													<td id="cctld_db_domain_names_total_price" <?php if($cctld_whois_db_type!='domain_names') echo 'style=\"color:#D3D3D3\"'; ?> > <?=$total_price?></td>
													<td id="cctld_db_whois_records_total_price" <?php if($cctld_whois_db_type!='whois_records') echo 'style=\"color:#D3D3D3\"'; ?> ><?=$total_price?></td>
												</tr>
											<?php endif; ?>
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
	<?php if($cctld_db_show_input): ?>
		<script type="text/javascript">
			$(document).ready(function(){
				var inputs=$('input:checkbox[name=cctld_wdb_ids[]]');
				inputs.click(function(){
					update_cctld_whois_db_price();
				});
				var type_radio=$('input:radio[name=cctld_whois_db_type]');
				type_radio.click(function(){
					update_cctld_whois_db_price();
				});

				$('.select_all').click(function(){
					var $this = $(this);

					if ($this.is(':checked')) {
						$('input:checkbox[name=cctld_wdb_ids[]]').attr('checked','checked');
					} else {
						$('input:checkbox[name=cctld_wdb_ids[]]').attr('checked','');
					}
					update_cctld_whois_db_price();
				});
			});
			function update_cctld_whois_db_price(){
				var inputs=$('input:checked[name=cctld_wdb_ids[]]');
				var type=$('input:checked[name=cctld_whois_db_type]').val();
				var types=['domain_names','whois_records'];
				for(var i=0;i<types.length;i++){
					var total_price = PriceUtil.compute_cctld_wdb_items_total_price(inputs, types[i]);
					var field=$('#cctld_db_'+types[i]+'_total_price');
					field.html('$'+total_price);
					if(types[i]==type){
						field.css("color","white");
					}
					else field.css("color","#D3D3D3");
				}
			}
		</script>

	<?php endif;?>
	<?php if($custom_db_show_input): ?>
		<script type="text/javascript">
			$(document).ready(function(){
				var inputs=$('input:checkbox[name=custom_wdb_ids[]]');
				inputs.click(function(){
					update_custom_whois_db_price();
				});

			});
			function update_custom_whois_db_price(){
				var inputs=$('input:checked[name=custom_wdb_ids[]]');
				var total_price = PriceUtil.compute_custom_wdb_items_total_price(inputs);
				$('#custom_db_total_price').html('$'+total_price);
			}
		</script>

	<?php endif;?>
	@stop
