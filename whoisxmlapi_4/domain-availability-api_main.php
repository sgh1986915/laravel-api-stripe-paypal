
<p class="rightTop"></p>
<h1>Domain Availability Check</h1> 
<div class="rightTxt2">

	If you require domain name availability information, rely on our service. Our domain availability check service returns well-parsed information about domain availability in popular formats (XML & JSON) per http request. The API works for a majority of the tlds. 

	The first 100 domain names availability lookups are offered for free and you just need to <a id="newact2" href="newaccount.php" class="ignore_jssm" title="Register Your Free Developer Account">register free developer account</a>. View <a id="hosted_pricing" href="hosted_pricing.php" class="ignore_jssm" title="Pricing Chart">pricing chart</a> for advanced offerings or <a href="<?php echo build_ssl_url('order_paypal.php')?>" id="order" class="ignore_jssm new_bold" title="Order Now">Order now</a> 
	

</div>



<div class="rightTxt2">
<h2>How to make a webservice call to Domain Availability API?</h2>
	
		<b>http://www.whoisxmlapi.com/whoisserver/WhoisService?cmd=GET_DN_AVAILABILITY&domainName=test.com&username=xxxxx&password=xxxxx</b>
<br/>

<img src="images/dna_snap.jpg"/>
<pre>
  <b>input parameters:</b> 
  cmd = GET_DN_AVAILABILITY  (required)
  domainName = (required)
  userName
  password
  getMode = DNS_AND_WHOIS | DNS_ONLY (default: DNS_ONLY)
            the default getMode DNS_ONLY is the quickest way, 
            DNS_AND_WHOIS mode is slower but the most accurat way.
</pre>
	<br class="spacer" />


<h2>how to query for my account balance?</h2>
	http://www.whoisxmlapi.com/accountServices.php?servicetype=accountbalance&username=xxxxx&password=xxxxx
		<br class="spacer" />


<h2>Will I receive warning if my account balance is low or zero?</h2>
	Yes, the system will send you a warning email when your account balance falls below a pre-determined level. Default is 10 and it is customizable. When account balance is zero, another warning email is sent. 

	To set the warning level, go to 
	http://www.whoisxmlapi.com/accountServices.php?servicetype=accountUpdate&username=xxxxx&password=xxxxx&warn_threshold=30<br/>
	supported input parameters are:
	<ul>
		<li>
			warn_threshold = the account balance at which a warning email will be sent to you<br/>
			value: a positive number<br/>
			default value: 10
		</li>
		<li>
			warn_threshold_enabled = indicate whether a warning letter should be sent to you when the account balance reaches warn_threshold<br/>
			positive values: 1, true, on<br/>
			negative values: 0, false, off<br/>
			default value: 1
		</li>
		<li>
			warn_empty_enabled = indicate whether a warning letter should be sent to you when the account balance reaches 0<br/>
			positive values: 1, true, on<br/>
			negative values: 0, false, off<br/>
			default value: 1
		</li>
	</ul>
	<br class="spacer" />
	
<a name="sample_code"/>
<h2>How can I make query in Java or PHP?</h2>

Find here the <a href="code/java/DomainAvailabilityAPIQuery.java" target="_blank" class="ignore_jssm" title="Sample Java Code">sample Java code</a> of making a query to Domain Availability API web service in java using <a href="http://hc.apache.org/user-docs.html" rel="nofollow" class="ignore_jssm" target="_apache_http_comp" title="Apache Http Component">Apache Http Component</a>. Download here the <a href="code/java/WhoisAPITest.zip" target="_sample_java_complete" class="ignore_jssm" title="Complete NEtbean Project">complete netbean project</a> with the necessary libraries.
  
  
  <br/>
  Here is the <a href="code/php/domain_availability_api_test.txt" target="_blank" class="ignore_jssm" title="Sample PHP Code">sample PHP code</a> of of making a query. 

<br class="spacer" />

<h2>Can https be used?</h2>
	Yes, you can use https in place of http, the connection will be more secure but slower.
	<br class="spacer" />
	
<h2>What tlds (gtlds and cctlds) do you support?</h2>
    Please check the <a class="ignore_jssm" href="support/supported_tlds.php" target="_blank" title="List of supported TLDs">list of TLDs</a> that we support.

	<br class="spacer" />

<a name="domain_schema"/>		
<h2>Is there an xml schema for the domain name availability api result?</h2>
 Yes, download here the <a class="ignore_jssm" href="documentation/DomainInfoSchema.xsd" target="_blank" title="XML Schema">xml schema</a> and a <a class="ignore_jssm" href="documentation/DomainInfo.xml" target="_blank" title="Sample XML Result">sample xml result</a>.

  <br class="spacer" />
  
<a name="domain_sla"/>		
<h2>Is there a term of service for using WHOIS API/Domain Availability Check?</h2>
   Please view the <a class="ignore_jssm" href="terms-of-service.php" target="_blank">Terms of Service</a> here 

  <br class="spacer" />

</div>




