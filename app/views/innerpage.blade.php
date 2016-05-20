<?php
$libPath = base_path(). "/whoisxmlapi_4";
?>
@extends('layouts.master')

@section('title')
Reverse Whois API
@stop

@section('styles')
@parent
{{ HTML::style('css/innerpage.css') }}
{{ HTML::style('css/reverseWhoisLookup.css') }}
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
					<div class="col-sm-6 col-xs-12 wa-search-eg-box wa-search-eg-box-innerpg">
						<div class="form-group has-feedback wa-search-box wa-search-box-innerpg">
							<input type="text" class="form-control wa-search wa-search-innerpg"  name="term1" id="wa-search-reversewhoislookup" placeholder="Reverse Whois Lookup">
							<span class="glyphicon glyphicon-search form-control-feedback wa-cursor" id="wa-search-icon-reverselookup"></span>
							<div class="wa-exapple wa-example-innerpg">(Example: John Smith, test@gmail.com)</div>
							<div class="wa-checkbox-inputs wa-checkbox-moreOptions wa-checkbox-moreOptions-innerpg">
								<div class="wa-checkbox-input wa-checkbox-input-moreOption wa-checkbox-input-innerpg">
									<input type="checkbox" value="moreoptions" id="wa-checkbox-moreOption" class="wa-cursor wa-field-input-selection wa-field-input-selection-innerpg">
									<label for="wa-checkbox-moreOption" class="wa-cursor  wa-field-value-selection wa-field-value-selection-innerpg" id="wa-checkbox-moreOption">More Options</label>
								</div>
							</div>
							<div id="wa-user-stats" class="wa-user-stats"><?php include_once $libPath . "/user_stats.php"; ?>
							</div>
						</div>
					</div>
					<div class="col-sm-6 col-xs-12 wa-btn wa-btn-innerpg">
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
						<div class="col-xs-12 wa-historicRecord-innerpg">
							<div class="wa-margin-checkbox wa-historic-checkbox wa-cursor">
								<label class="wa-field-value-selection"><input type="checkbox" class="wa-checkbox-innerpg" name="search_type" value="" id="wa-checkbox-historic-records">Include Historic Records</label>
							</div>
						</div>
						<div class="col-xs-12">
							<label class="wa-field-value-selection wa-field-value-selection-checkbox">Include whois records containing ALL of the following terms in addition to the primary search term above:</label>
						</div>
						<div class="col-sm-6 col-xs-12 ">
							<input type="text" class="form-control wa-search-form wa-search-form-innerpg wa-search-form1" id="wa-input-type-include-1" name="term2">
							<input type="text" class="form-control wa-search-form wa-search-form-innerpg wa-search-form2"  id="wa-input-type-include-2"name="term4">
						</div>
						<div class="col-sm-6 col-xs-12">
							<input type="text" class="form-control wa-search-form wa-search-form-innerpg wa-search-form3" id="wa-input-type-include-3"name="term3">
						</div>
					</div>
					<div class="row">
						<div class="col-sm-12 col-xs-12 wa-searchBox-exclude-innerpg">
							<div class ="wa-field-value-selection">
								<label class="wa-field-value-selection">Exclude whois records containing ANY of following terms:</label>
							</div>
						</div>
						<div class="col-sm-6 col-xs-12">
							<input type="text" class="form-control wa-search-form wa-search-form-innerpg wa-search-form4" id="wa-input-type-exclude-1"name="exclude_term1">
							<input type="text" class="form-control wa-search-form wa-search-form-innerpg wa-search-form5" id="wa-input-type-exclude-2"name="exclude_term3">
						</div>
						<div class="col-sm-6 col-xs-12">
							<input type="text" class="form-control wa-search-form wa-search-form-innerpg wa-search-form6" id="wa-input-type-exclude-3"name="exclude_term2">
						</div>
					</div>
				</div>
			</form>
		</div>
	</div>

	<div class="row wa-page-title-content-bg">
		<div class="col-xs-12 wa-about-whoisApi wa-xs wa-auto-margin">
			<h1 class="text-center wa-title wa-title-innerpage ">Reverse Whois Search</h1>
			<div class="text-center wa-content-text wa-content-text-innerpg wa-content-spacing wa-page-description wa-page-description-innerpg wa-content-spacing">
				Reverse Whois lets you find all the domain names registered in the name of any specific owner.
				Bulk Lookup allow you you perform a fixed number of lookups regardless of how many domain names appear in results (lookup that yields 0 domain names does not count).
			</div>
		</div>
	</div>
	<div id="wa-page-content">
		<div class="row wa-content-bg wa-content-bg-innerpg">
			<div class="col-xs-12 wa-box-width-xs wa-auto-margin">
				<div class="wa-box wa-box-reverse-innerpg wa-box-innerpg wa-top-content-margin">
					<h2 class="wa-section-title wa-section-title-innerpg wa-section-title-reverse-innerpg text-center">Reverse Whois Search</h2>
					<div class="wa-content-text wa-content-text-innerpg wa-content-text-reverse-innerpg wa-content-spacing text-center">
						Reverse Whois (Registrant Search) lets you perform the most comprehensive wild card search on all whois records.
						Reverse whois Lookup allows you to find every domain name ever owned by a specific company or person. You just need to enter one or more unique identifiers such as the person's or the company's name, phone number, or email address, and you can find all the domain names they ever owned.
						Type in your term above to make a quick search.
					</div>
				</div>
			</div>
		</div>
		<!-- Video -->
		<div class="row wa-video-section">
			<div class="col-xs-12 wa-auto-margin">
				<div class="embed-responsive embed-responsive-16by9 wa-video-innerpage">
					<iframe width="578" height="360" src="//www.youtube.com/embed/RBEnA3mk0q4" frameborder="0" allowfullscreen></iframe>
				</div>

			</div>
		</div>
		<!-- Key features -->
		<div class="row">
			<div class="col-xs-12 wa-auto-margin">
				<div class="row">
					<div class="col-xs-12 text-center">
						<h2 class="wa-section-title wa-section-title-keyfeatures-innerPage wa-section-title-innerPage">Key Features</h2>
					</div>
				</div>
				<div class="row">
					<div class="col-xs-12">
						<div class="wa-check-background">
							{{ HTML::image('images/check-img.png', 'Responsive image', array('class'=>'img-responsive wa-img  wa-img-check-innerPage')) }}

							<span class="keyFeature-content">This allows searching by a unique identifier such as registrant name, email, address, etc. It can be any piece of text in a whois record</span>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-sm-3 wa-kf-col">
						<div class="wa-kf-background">
							{{ HTML::image('images/kf4.png', 'Responsive image', array('class'=>'img-responsive wa-img wa-img-check wa-img-kf-innerPage')) }}
							<div class="keyFeature-Subcontent">Access results in online form & other formats (CSV, PDF)</div>
						</div>
					</div>
					<div class="col-sm-3 wa-kf-col">
						<div class="wa-kf-background">
							{{ HTML::image('images/kf3.png', 'Responsive image', array('class'=>'img-responsive wa-img wa-img-check wa-img-kf-innerPage')) }}
							<div class="keyFeature-Subcontent">Resulting whois records highlight the search terms</div>
						</div>
					</div>
					<div class="col-sm-3 wa-kf-col">
						<div class="wa-kf-background">
							{{ HTML::image('images/kf2.png', 'Responsive image', array('class'=>'img-responsive wa-img wa-img-check wa-img-kf-innerPage')) }}
							<div class="keyFeature-Subcontent">Advanced search allows to include & exclude multiple search terms</div>
						</div>
					</div>
					<div class="col-sm-3 wa-kf-col">
						<div class="wa-kf-background">
							{{ HTML::image('images/kf1.png', 'Responsive image', array('class'=>'img-responsive wa-img wa-img-check wa-img-kf-innerPage')) }}
							<div class="keyFeature-Subcontent">Search for current as well as historic whois records</div>
						</div>
					</div>
				</div>
				<div class="col-xs-12">
					<a href={{ URL::to('bulk-reverse-whois-order.php') }}><button type="button" class="btn btn-default center-block wa-btn wa-btn-form-action wa-btn-viewBulk-innerpage " id="wa-btn-viewBulk">VIEW BULK PRICING</button></a>
				</div>
			</div>
		</div>
		<div class="row wa-ourFeatures-bg">
			<div class="col-xs-12 wa-auto-margin">
				<div class="row">
					<div class="col-sm-6 col-xs-12 wa-ourFeatures-firstPart">
						<div class="wa-ourFeatures-box wa-reverse-box-innerpg">
							<div class="row">
								<div class="col-xs-12 wa-ourFeatures-heading wa-lbl-heading wa-reverse-innepg">When reverse whois lookup is helpful</div>
								<div class="col-xs-12">
									<ul class="list-unstyled">
										<li class="wa-content-text wa-ourFeatures-lists wa-ourFeatures-list-innerpg"><span class="wa-list-no">01</span><span class="wa-ourFeatures-lbl">It is used by brand agents for protection of intellectual property. They can find instances of potential trademark infringements before costs of enforcement grow.</span></li>
										<li class="wa-content-text wa-ourFeatures-lists wa-ourFeatures-list-innerpg"><span class="wa-list-no">02</span><span class="wa-ourFeatures-lbl">A useful tool for domain investors, it helps in learning about the competition, identifying domains with investment potential, and locating potential buyers.</span></li>
										<li class="wa-content-text wa-ourFeatures-lists wa-ourFeatures-list-innerpg"><span class="wa-list-no">03</span><span class="wa-ourFeatures-lbl">Webmasters use it for identifying new business and partnership opportunities, and locating potential buyers.</span></li>
									</ul>
								</div>
							</div>
						</div>
					</div>
					<!-- Desktop image -->
					<div class="col-sm-6 col-xs-12 wa-desktop-bg">
						<div class="row">
							<div class="col-xs-12">
								{{ HTML::image('images/merge-1.png', 'Responsive image', array('class'=>'img-responsive wa-img wa-img-desktop ')) }}
							</div>
						</div>
					</div>
				</div>
				<div class="wa-buffer"></div>
			</div>
		</div>
	</div>
</div>
@stop