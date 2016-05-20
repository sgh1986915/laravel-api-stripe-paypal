<?php
$libPath = base_path(). "/whoisxmlapi_4";
?>
@extends('layouts.master')

@section('title')
Terms of Service
@stop

@section('styles')
@parent
{{ HTML::style('css/termsofservice.css') }}
@stop

@section('content')
<div class="main-content">
	<div class="row wa-searchbox-radio">
		<div class="col-xs-12 wa-auto-margin">
			<div class="row">
				<div class="col-sm-6 col-xs-12 wa-search-eg-box wa-search-eg-box-termsofservice">
					<form id="whoisform" name="whoisform" action="{{ URL::to('whoislookup.php') }}">
						<div class="form-group has-feedback wa-search-box wa-search-box-termsofservice">
							<input type="text" class="form-control wa-search wa-search-termsofservice" name="domainName" id="wa-search-whoislookup" placeholder="Whois Lookup">
							<span class="glyphicon glyphicon-search form-control-feedback wa-cursor" id="wa-search-icon-whoislookup"></span>
							<div class="wa-exapple wa-example-termsofservice">Example: google.com or 74.125.45.100</div>
							<div class="wa-radio-inputs wa-radio-xml-Josn wa-radio-xml-Josn-termsofservice">
								<div class="wa-radio-input wa-radio-input-xml wa-radio-input-xml-termsofservice">
									<input type="radio" value="xml" checked id="wa-radio-xml" class="wa-cursor wa-radio-orange-bar wa-field-input-selection wa-field-input-selection-termsofservice wa-api-res-type" name="outputFormat">
									<label for="wa-radio-xml" class="wa-cursor wa-field-value-selection wa-field-value-selection-xml wa-field-value-selection-termsofservice" id="wa-lbl-XMl">XML</label>
									<div class="wa-home-radio-outerCircle">
										<div class="wa-home-radio-innerCircle"></div>
									</div>
								</div>
								<div class="wa-radio-input wa-radio-input-json wa-radio-input-json-termsofservice">
									<input type="radio" value="json" id="wa-radio-json" class="wa-cursor wa-radio-orange-bar wa-field-input-selection wa-field-input-selection-termsofservice wa-api-res-type" name="outputFormat">
									<label for="wa-radio-json" class="wa-cursor wa-field-value-selection wa-field-value-selection-json wa-field-value-selection-termsofservice" id="wa-lbl-JSON">JSON</label>
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
				<div class="col-sm-6 col-xs-12 wa-btn wa-btn-registraAlert">
					<div class="row">
						<div class="col-sm-offset-4 col-sm-4 col-xs-12 wa-btn-order">
							<a href={{ URL::to('order_paypal.php') }}><button type="button" class="btn btn-default wa-btn wa-btn-orderNow wa-btn-orderNow-termsofservice center-block" id="wa-btn-orderNow-termsofservice">ORDER NOW</button></a>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="row wa-page-title-content-bg">
		<div class="col-xs-12 wa-about-whoisApi wa-auto-margin wa-col-xs-no-padding">
			<h1 class="text-center wa-title wa-title-termsofservice wa-title-termsAPIhosted-termsofservice">Terms of Service</h1>
		</div>
	</div>
	<div id="wa-page-content">
		<div class="row wa-content-bg">
			<div class="col-xs-12 wa-auto-margin wa-col-xs-no-padding wa-marginbox-termsofservice">
				<div class="wa-box wa-box-xs-padding wa-top-content-margin wa-bottom-content-margin wa-termsof-service-box">
					<h2 class="text-center wa-section-title wa-section-title-tos">
						Terms of Service for Whois API Hosted Webservice
					</h2>
					<div class="wa-content-text wa-content-text-tos">
						Whois API LLC ("Whois API") provides Whois API Hosted Webservice (defined below) to you subject to the following conditions. This is a legal agreement between you and Whois API. In consideration of Whois API allowing you to use Whois API Hosted Webservice and by accessing Whois API Hosted Webservice you agree to be bound by the terms of this agreement ("Agreement").
					</div>
					<ol>
						<li class="wa-content-text wa-content-text-tos">Definition of Whois API Hosted Webservice</li>
						<div class="wa-content-text wa-content-text-tos">Whois API Hosted Webservice consist of all services and information made available by Whois API via the Internet, including the whoisxmlapi.com web site, the Whois API web service, and any other properties not covered by separate agreements.</div>
						<li class="wa-content-text wa-content-text-tos">Grant of License</li>
						<div class="wa-content-text wa-content-text-tos">You are granted a limited, revocable, nonexclusive license to use any data, images, text, content, tools or other information (collectively referred to as the "Whois API Properties") received via Whois API Hosted Webservice in accordance with the terms and conditions described herein. You agree that you will provide accurate information when signing up for a Whois API Hosted Webservice account. Whois API retains all rights not expressly granted by this Agreement.</div>
						<div class="wa-content-text wa-content-text-tos">You may only use Whois API Hosted Webservice and the Whois API Properties as follows:</div>
						<ul>
							<li class="wa-content-text wa-content-text-tos">Human Interactive Use. You may interact with Whois API Services as a human being in a non-automated fashion.</li>
							<li class="wa-content-text wa-content-text-tos">Automated Use. You may write an application that interfaces automatically with Whois API Hosted Webservice provided that you obtain and use a Whois API Services account and that you do not exceed the following usage limits without explicit, prior authorization from Whois API:</li>
							<ol>
								<li class="wa-content-text wa-content-text-tos">No more than 300 HTTP requests every minute</li>
								<li class="wa-content-text wa-content-text-tos">No more than 10 HTTP requests outstanding (pending) at a time</li>
								<div class="wa-content-text wa-content-text-tos">If you build and distribute an application that uses Whois API Hosted Webservice, the same limitations apply to the collective behavior of all installed copies of the application.</div>
							</ol>
							<li class="wa-content-text wa-content-text-tos">No Abuse. You may not use Whois API Hosted Webservice for abusive purposes, nor may the you use Whois API Hosted Webservice from applications designed to abuse computer resources accessible through any network—local, wide-area, or internet. Whois API does not collect your queries information and is not responsible for any potential abuse stemming from your client application. Abuse includes but is not limited to: unauthorized data mining, denial of service attacks, unauthorized computer system incursions, and any actions that violate applicable laws or regulations. You may not use Whois API Hosted Webservice in any manner that could damage, disable, overburden, or impair Whois API Hosted Webservice (or the network(s) connected to Whois API Hosted Webservice) or interfere with any other party's use and enjoyment of Whois API Hosted Webservice. You may not attempt to gain unauthorized access to Whois API Hosted Webservice, other accounts, computer systems or networks connected to Whois API Hosted Webservice, through hacking, password mining, or any other means. You may not obtain or attempt to obtain any materials or information through any means not intentionally made available by Whois API through Whois API Hosted Webservice.</li>
							<li class="wa-content-text wa-content-text-tos">Whois Data. </li>
								<ol>
									<li class="wa-content-text wa-content-text-tos">Data usage. You may not use Whois contact information obtained through Whois API Hosted Webservice to for any usage where prohibited by applicable law. If you use Whois data from Whois API Hosted Webservice in your own application or service, you agree not to allow your users to use the Whois contact information to in a manner that voilates applicable law. In any case, Whois API does not collect or analyze your query, you must be responsible for complying with all regulations and laws.</li>
									<li class="wa-content-text wa-content-text-tos">Display and Transfer. Whois API Hosted Webservice provide Whois data primarily for your internal use. You may display to third parties up to ten (10) fields parsed from any given Whois record. You may also display complete records that are raw and unparsed. You may also use parsed data internally in applications or services that you provide to third parties. Except in the aforementioned cases, you agree not to transfer or sell parsed Whois data to third parties. "Parsed data" refers to specific data fields separated from the original record. Examples of such fields include, but are not limited to, registrant name, postal address, postal code, country code, phone number, and email address.</li>
								</ol>
							<li class="wa-content-text wa-content-text-tos">No Resale or Sublicensing. Except as allowed above, you may not modify, copy, distribute, transmit, display, perform, reproduce, publish, license, create derivative works from, sublicense, transfer, assign, rent, sell or otherwise convey any information, software, products or services obtained from Whois API Hosted Webservice or Whois API Properties without prior written consent from Whois API. You may not allow other parties to use your Whois API Hosted Webservice account or login credentials.</li>
						</ul>
						<li class="wa-content-text wa-content-text-tos">Monitoring</li>
						<div class="wa-content-text wa-content-text-tos">You understand and agree that Whois API only logs basic information from your query for up to 2 weeks to ensure proper function of your usage of Whois API Hosted Webservice. Whois API does not use data from user queries for any purpose other than the ones stated above.</div>
						<li class="wa-content-text wa-content-text-tos">Uptime</li>
						<div class="wa-content-text wa-content-text-tos">Whois API Webservice's historic uptime has been 99.9% over the past year.	It is Whois API's goal to maintain an uptime of 99.9% or greater. You understand and agree that service outtage can occur due to unexpected reasons, in which case Whois API will restore service in a reasonable and timely fashion. Whois API is not responsible for any loss as a result of service outtage.</div>
						<li class="wa-content-text wa-content-text-tos">Response Time</li>
						<div class="wa-content-text wa-content-text-tos">You understand and agree that Whois API Webservice's response time can vary depending on many factors including server load, network latency, and technical reasons. Response time can be as low as 500 ms or much longer due to specified reasons above. A "typical" average response time is about 2 to 3 seconds.</div>
						<li class="wa-content-text wa-content-text-tos">Response Data validity</li>
						<div class="wa-content-text wa-content-text-tos">You understand and agree that Data returned by Whois API Webservice undergo a sequence of backend processes and gets parsed by our intelligent parse system which is constantly improving. Given the freeform nature of Whois Data, parsing error can occur. Raw whois data is always returned for you to validate. Raw whois data can also be incomplete in rare circumstances, in which case it will be stated in the response and you would not be charged any credit.</div>
						<li class="wa-content-text wa-content-text-tos">Indemnification</li>
						<div class="wa-content-text wa-content-text-tos">You agree to defend, indemnify, and hold harmless Whois API and its affiliates, and each of their officers, directors, employees, agents, representatives, information providers and licensors, from any claims, costs, losses, damages, judgments and expenses, including but not limited to reasonable attorney's fees, relating to or arising out of any breach of this Agreement or any use of Whois API Hosted Webservice by you, or by any other person using Whois API Hosted Webservice through you or using your computer.</div>
						<li class="wa-content-text wa-content-text-tos">Warranty and Disclaimer</li>
						<div class="wa-content-text wa-content-text-tos">You understand and agree that Whois API Hosted Webservice are provided on an "as is" and "as available" basis. You expressly agree that use of Whois API Hosted Webservice is at your sole risk. To the maximum extent permitted by applicable law, Whois API disclaims all warranties of any kind, either express or implied, including without limitation any implied warranties of merchantability, fitness for a particular purpose, and noninfringement. Without limiting the foregoing, neither Whois API nor any of its affiliates, nor any of their officers, directors, licensors, employees or representatives represent or warrant (a) that Whois API Hosted Webservice, including its content, will meet your requirements or be accurate, complete, reliable, or error free; (b) that the service will always be available or will be uninterrupted, accessible, timely, or secure; (c) that any defects will be corrected, or that the service will be free from viruses, "worms," "trojan horses" or other harmful properties; (d) the availability for sale, or the reliability or quality of any products discussed or referenced in the service; (e) any implied warranty arising from course of dealing or usage of trade; and (f) that the service is noninfringing. Whois API and its affiliates hereby disclaim, and you hereby waive and release Whois API and its affiliates from, any and all obligations, liabilities, rights, claims or remedies in tort arising out or in connection with this Agreement, Whois API Hosted Webservice, and Whois API Properties, whether or not arising from the negligence (active, passive or imputed) of Whois API or its affiliates. You acknowledge and agree that any content downloaded or otherwise obtained through Whois API Hosted Webservice is done at your own discretion and risk and that you will be solely responsible for any damage to your computer system or loss of data that results from use of Whois API Hosted Webservice. Some jurisdictions do not allow the exclusion of implied warranties, so the above exclusions may not apply to you. You may also have other legal rights, which vary from jurisdiction to jurisdiction.</div>
						<li class="wa-content-text wa-content-text-tos">Limitation of Liability</li>
						<div class="wa-content-text wa-content-text-tos">In no event shall Whois API, its affiliates, it suppliers, or any of their officers, directors, employees, agents, representatives, information providers, or licensors be liable for any consequential, incidental, direct, indirect, special, punitive, or other damages (including, without limitation, damages for loss of business profits, business interruption, loss of business information, or other pecuniary loss) arising out of the use or inability to use Whois API Hosted Webservice, even if Whois API has been advised of the possibility of such damages. In any event, Whois API's cumulative liability to any user for any and all claims relating to the use of Whois API Hosted Webservice shall not exceed the total amount paid by the user for Whois API Hosted Webservice during the preceding three month period.</div>
						<li class="wa-content-text wa-content-text-tos">Termination</li>
						<div class="wa-content-text wa-content-text-tos">You may terminate this Agreement by ceasing to use Whois API Hosted Webservice and the Whois API Properties. Whois API reserves the right to terminate this Agreement (and/or your account) or discontinue Whois API Hosted Webservice or any portion or feature thereof for any reason and at any time at its sole discretion. Upon any termination or notice of any discontinuance, you must immediately stop and thereafter desist from using Whois API Hosted Webservice, including the Whois API Properties, and any applicable portions or features thereof, and delete all Whois API Properties in your possession or control (including from your application and your servers).</div>
						<li class="wa-content-text wa-content-text-tos">Breach of Agreement</li>
						<div class="wa-content-text wa-content-text-tos">This Agreement will terminate automatically if you fail to comply with the terms of this Agreement. Upon termination of this Agreement, all rights and licenses granted in the Agreement shall immediately terminate. You will immediately delete any Whois API Properties or other Whois API proprietary information in your possession or control.</div>
						<li class="wa-content-text wa-content-text-tos">Modification of Agreement</li>
						<div class="wa-content-text wa-content-text-tos">Whois API may modify any of the terms and conditions contained in this Agreement, at any time and at its sole discretion, by posting a change notice or a new Agreement on the Whois API web site. If any modification is unacceptable to you, your only recourse is to terminate this Agreement. Your continued use of Whois API Hosted Webservice or the Whois API Properties following Whois API's posting of a change notice or new Agreement on the web site will constitute binding acceptance of the change. Whois API may modify any of the terms and conditions contained in this Agreement, at any time and at its sole discretion, by posting a change notice or a new Agreement on the Whois API web site. If any modification is unacceptable to you, your only recourse is to terminate this Agreement. Your continued use of Whois API Hosted Webservice or the Whois API Properties following Whois API's posting of a change notice or new Agreement on the web site will constitute binding acceptance of the change.</div>
						<li class="wa-content-text wa-content-text-tos">Governing Law</li>
						<div class="wa-content-text wa-content-text-tos">This Agreement shall be governed by the laws of the State of California, U.S.A. Exclusive jurisdiction over disputes arising under this Agreement or regarding use of Whois API Services will be in the state and federal courts of and sitting in Collin County, Texas, U.S.A. If any provision of this Agreement is held invalid, the remainder of this Agreement will continue in full force and effect. In any litigation involving this Agreement or the use of Whois API Hosted Webservice, the prevailing party will be entitled to recover reasonable attorneys' fees.</div>
						<li class="wa-content-text wa-content-text-tos">Entire Agreement</li>
						<div class="wa-content-text wa-content-text-tos">This Agreement constitutes the entire agreement between you and Whois API regarding Whois API Hosted Webservice and supersedes any and all prior or contemporaneous representation, understanding, agreement or communication between you and Whois API regarding Whois API Services. Except as provided by Section 9, this Agreement may not be amended, varied or supplemented except by a writing executed by both you and Whois API that specifically references this Agreement and the provision(s) to be amended, varied or supplemented, and, without limitation of the foregoing, no provision of this Agreement shall be varied, contradicted or explained by any oral agreement, course of dealing or performance, or any other matter not set forth in a writing executed as provided above in this sentence.</div>
					</ol>

				</div>
			</div>
		</div>
	</div>
</div>
@stop