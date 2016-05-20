<?php
$libPath = base_path(). "/whoisxmlapi_4";
?>
@extends('layouts.master')

@section('title')
Privacy Policy
@stop

@section('styles')
@parent
{{ HTML::style('css/privacypolicy.css') }}
@stop

@section('content')
<div class="main-content">
	<div class="row wa-searchbox-radio">
		<div class="col-xs-12 wa-auto-margin">
			<div class="row">
				<div class="col-sm-6 col-xs-12 wa-search-eg-box wa-search-eg-box-privacypolicy">
					<form id="whoisform" name="whoisform" action="{{ URL::to('whoislookup.php') }}">
						<div class="form-group has-feedback wa-search-box">
							<input type="text" class="form-control wa-search wa-search-privacypolicy" name="domainName" id="wa-search-whoislookup" placeholder="Whois Lookup">
							<span class="glyphicon glyphicon-search form-control-feedback wa-cursor" id="wa-search-icon-whoislookup"></span>
							<div class="wa-exapple wa-example-privacypolicy">Example: google.com or 74.125.45.100</div>
							<div class="wa-radio-inputs wa-radio-xml-Josn wa-radio-xml-Josn-privacypolicy">
								<div class="wa-radio-input wa-radio-input-xml wa-radio-input-xml-privacypolicy">
									<input type="radio" value="xml" checked id="wa-radio-xml" class="wa-cursor wa-radio-orange-bar wa-field-input-selection wa-field-input-selection-privacypolicy wa-api-res-type" name="outputFormat">
									<label for="wa-radio-xml" class="wa-cursor wa-field-value-selection wa-field-value-selection-xml wa-field-value-selection-privacypolicy" id="wa-lbl-XMl">XML</label>
									<div class="wa-home-radio-outerCircle">
										<div class="wa-home-radio-innerCircle"></div>
									</div>
								</div>
								<div class="wa-radio-input wa-radio-input-json wa-radio-input-json-privacypolicy">
									<input type="radio" value="json" id="wa-radio-json" class="wa-cursor wa-radio-orange-bar wa-field-input-selection wa-field-input-selection-privacypolicy wa-api-res-type" name="outputFormat">
									<label for="wa-radio-json" class="wa-cursor wa-field-value-selection wa-field-value-selection-json wa-field-value-selection-privacypolicy" id="wa-lbl-JSON">JSON</label>
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
				<div class="col-sm-6 col-xs-12 wa-btn wa-btn-privacypolicy">
					<div class="row">
						<div class="col-sm-offset-4 col-sm-4 col-xs-12 wa-btn-order">
							<a href={{ URL::to('order_paypal.php') }}><button type="button" class="btn btn-default wa-btn wa-btn-orderNow wa-btn-orderNow-privacypolicy center-block" id="wa-btn-orderNow-privacypolicy">ORDER NOW</button></a>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="row wa-page-title-content-bg wa-page-title-content-bg-whoisApi">
		<div class="col-xs-12 wa-about-whoisApi wa-xs wa-auto-margin">
			<h1 class="text-center wa-title wa-title-privacypolicy">Privacy Policy</h1>
		</div>
	</div>
	<div id="wa-page-content">
		<div class="row wa-content-bg wa-content-bg-privacypolicy">
			<div class="col-xs-12 wa-col-xs-no-padding wa-box-margin-whoisapi wa-box-width-whoisApi wa-auto-margin">
				<div class="wa-box wa-top-content-margin wa-box-xs-padding wa-box-LLCAPI-privacypolicy wa-box-privacypolicy">
					<h2 class="wa-section-title wa-section-title-privacypolicy wa-section-title-LLCAPI-privacypolicy text-center">Whois API, LLC Privacy Policy</h2>
				</div>
			</div>
		</div>
		<div class="row wa-content-bg wa-content-bg-privacypolicy">
			<div class="col-xs-12 wa-col-xs-no-padding wa-auto-margin">
				<div class="row">
					<div class="col-sm-6 col-xs-12 wa-box-width-xs wa-box-margin-whoisapi wa-box-margin-makeWebservice-whoisApi">
						<div class="wa-box wa-box-xs-padding wa-box-infoprivacypolicy-privacypolicy wa-box-privacypolicy">
							<h2 class="wa-section-title wa-section-title-privacypolicy wa-section-title-infocollect-privacypolicy text-center">What information do we collect?</h2>
								<div class="wa-content-text wa-content-spacing wa-content-text-privacypolicy wa-content-text-infocollect-privacypolicy text-center">
									We collect information from you when you register on our site or place an order.
									<div>When ordering or registering on our site, as appropriate, you may be asked to enter your: name or e-mail address. You may, however, visit our site anonymously.</div>
								</div>
						</div>
						<div class="wa-box-width-xs wa-box-margin-whoisapi">
							<div class="wa-box wa-box-xs-padding wa-box-protectinfo-privacypolicy wa-box-privacypolicy">
								<h2 class="wa-section-title wa-section-title-privacypolicy wa-section-title-protectinfo-privacypolicy text-center">How do we protect your information?</h2>
									<div class="wa-content-text wa-content-spacing wa-content-text-privacypolicy wa-content-text-protectinfo-privacypolicy text-center">
										We implement a variety of security measures to maintain the safety of your personal information when you place an order or enter, submit, or access your personal information.
										<div>We offer the use of a secure server. All supplied sensitive/credit information is transmitted via Secure Socket Layer (SSL) technology and then encrypted into our Payment gateway providers database only to be accessible by those authorized with special access rights to such systems, and are required to keep the information confidential.
										After a transaction, your private information (credit cards, social security numbers, financials, etc.) will not be stored on our servers.</div>
									</div>
							</div>
						</div>
						<div class="wa-box-width-xs wa-box-margin-whoisapi">
							<div class="wa-box wa-box-xs-padding wa-box-childrenonline-privacypolicy wa-box-privacypolicy">
								<h2 class="wa-section-title wa-section-title-privacypolicy wa-section-title-childrenonline-privacypolicy text-center">Childrens Online Privacy Protection Act Compliance</h2>
									<div class="wa-content-text wa-content-spacing wa-content-text-privacypolicy wa-content-text-childrenonline-privacypolicy text-center">
										We are in compliance with the requirements of COPPA (Childrens Online Privacy Protection Act), we do not collect any information from anyone under 13 years of age. Our website, products and services are all directed to people who are at least 13 years old or older.
									</div>
							</div>
						</div>
						<div class="wa-box-width-xs wa-box-margin-whoisapi">
							<div class="wa-box wa-box-xs-padding wa-box-changeprivacy-privacypolicy wa-box-privacypolicy">
									<h2 class="wa-section-title wa-section-title-privacypolicy wa-section-title-changeprivacy-privacypolicy text-center">Changes to our Privacy Policy</h2>
									<div class="wa-content-text wa-content-spacing wa-content-text-privacypolicy wa-content-text-changeprivacy-privacypolicy text-center">
										 If we decide to change our privacy policy, we will post those changes on this page, and/or update the Privacy Policy modification date below.
										 <div>
											This policy was last modified on 07/01/2012
										 </div>
									</div>
								</div>
						</div>
						<div class="wa-box-width-xs wa-box-margin-whoisapi">
							<div class="wa-box wa-box-xs-padding wa-box-yourconsent-privacypolicy wa-box-privacypolicy">
								<h2 class="wa-section-title wa-section-title-privacypolicy wa-section-title-yourconsent-privacypolicy text-center">Your Consent</h2>
									<div class="wa-content-text wa-content-spacing wa-content-text-privacypolicy wa-content-text-yourconsent-privacypolicy text-center">
										By using our site, you consent to our websites <span class="wa-link wa-cursor wa-textDecoration">{{ HTML::link('privacy.php', ' privacy policy.') }}</span>
									</div>
							</div>
						</div>
					</div>
					<div class="col-sm-6 col-xs-12 wa-box-width-xs wa-box-margin-whoisapi">
						<div class="row">
							<div class="col-xs-12">
								<div class="wa-box wa-box-xs-padding wa-box-infoUse-privacypolicy wa-box-privacypolicy">
									<h2 class="wa-section-title wa-section-title-privacypolicy wa-section-title-infouse-privacypolicy text-center">What do we use your information for?</h2>
									<div class="wa-content-text wa-content-text-privacypolicy wa-content-text-infouse-privacypolicy text-center">
										Any of the information we collect from you may be used in one of the following ways:
										To process transactions
										<div class="wa-content-text wa-content-spacing wa-content-text-privacypolicy wa-content-text-useinfo-privacypolicy">
											Your information, whether public or private, will not be sold, exchanged, transferred, or given to any other company for any reason whatsoever, without your consent, other than for the express purpose of delivering the purchased product or service requested.
										</div>
									</div>
								</div>
								<div class="wa-box wa-box-xs-padding wa-box-dicloseinfo-privacypolicy wa-box-privacypolicy">
								<h2 class="wa-section-title wa-section-title-privacypolicy wa-section-title-dicloseinfo-privacypolicy text-center">Do we disclose any information to outside parties?</h2>
									<div class="wa-content-text wa-content-spacing wa-content-text-privacypolicy wa-content-text-protectinfo-privacypolicy text-center">
										We do not sell, trade, or otherwise transfer to outside parties your personally identifiable information. This does not include trusted third parties who assist us in operating our website, conducting our business, or servicing you, so long as those parties agree to keep this information confidential. We may also release your information when we believe release is appropriate to comply with the law, enforce our site policies, or protect ours or others rights, property, or safety. However, non-personally identifiable visitor information may be provided to other parties for marketing, advertising, or other uses.
									</div>
								</div>
								<div class="wa-box wa-box-xs-padding wa-box-californiaonline-privacypolicy wa-box-privacypolicy">
									<h2 class="wa-section-title wa-section-title-privacypolicy wa-section-title-californiaonline-privacypolicy text-center">California Online Privacy Protection Act Compliance</h2>
									<div class="wa-content-text wa-content-spacing wa-content-text-privacypolicy wa-content-text-californiaonline-privacypolicy text-center">
										Because we value your privacy we have taken the necessary precautions to be in compliance with the California Online Privacy Protection Act. We therefore will not distribute your personal information to outside parties without your consent.
										As part of the California Online Privacy Protection Act, all users of our site may make any changes to their information at anytime by logging into their account and going to the 'My Account' page.
									</div>
								</div>
								<div class="wa-box wa-box-xs-padding wa-box-termsandconditions-privacypolicy wa-box-privacypolicy">
									<h2 class="wa-section-title wa-section-title-privacypolicy wa-section-title-termsandconditions-privacypolicy text-center">Terms and Conditions</h2>
									<div class="wa-content-text wa-content-spacing wa-content-text-privacypolicy wa-content-text-termsandconditions-privacypolicy text-center">
										Please also visit our Terms and Conditions section establishing the use, disclaimers, and limitations of liability governing the use of our website at <span class="wa-link wa-cursor wa-textDecoration">{{ HTML::link('terms-of-service.php', 'http://www.whoisxmlapi.com/terms-of-services.php') }} </span>
									</div>
								</div>
								<div class="wa-box wa-box-xs-padding wa-box-usecookies-privacypolicy wa-box-privacypolicy">
									<h2 class="wa-section-title wa-section-title-privacypolicy wa-section-title-usecookies-privacypolicy text-center">Do we use cookies?</h2>
									<div class="wa-content-text wa-content-spacing wa-content-text-privacypolicy wa-content-text-usecookies-privacypolicy text-center">
										We do not use cookies.
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="col-xs-12 wa-col-xs-no-padding wa-auto-margin">
				<div class="row">
					<div class="col-xs-12">
						<div class="wa-box wa-box-xs-padding wa-box-contactingus-privacypolicy wa-box-privacypolicy">
							<h2 class="wa-section-title wa-section-title-privacypolicy wa-section-title-contactingus-privacypolicy text-center">Contact Us</h2>
							<div class="wa-content-text wa-content-spacing wa-content-text-privacypolicy wa-content-text-contactingus-privacypolicy   ">
							If there are any questions regarding this privacy policy you may contact us using the information below.</div>
							<div class="wa-address wa-content-text wa-content-spacing">www.whoisxmlapi.com
							<div itemscope itemtype="http://schema.org/LocalBusiness"> 
								<span itemprop="name">Whois API LLC</span>
								<div itemprop="address" itemscope itemtype="http://schema.org/PostalAddress">
									<span itemprop="streetAddress">340 S LEMON AVE, #1362,</span><br/>
									<span itemprop="addressLocality">WALNUT</span>, 
									<span itemprop="addressRegion">CA  91789,</span> 
								</div>
							</div>
							<div>USA</div>
							<div>support@whoisxmlapi.com</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

@stop