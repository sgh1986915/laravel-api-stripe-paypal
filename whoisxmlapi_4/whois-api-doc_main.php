

<p class="rightTop"></p>
<h1>How to use Hosted Whois Web Service </h2> 

<p class="rightTxt1">
Our Hosted Whois Web Service can be used to query domain name or ip address to return its whois record in XML or JSON.  Both parsed fields and raw texts are included in the resulting whois record. 
</p>


<div class="rightTxt2">
<h2>How to make a webservice call to WhoisAPI?</h2>
	
		<b>http://www.whoisxmlapi.com/whoisserver/WhoisService?domainName=google.com&username=xxxxx&password=xxxxx</b>
<br/>
<!--
<p>
First time invocation will create a session and pass back a SESSIONID in the http header that you can use in the subsequent calls.
If you are calling the url from a cookie based browser, session management is taken care of for you so you don't have to worry about anything.  
If you are calling the webservice from any other standalone app, you would have to <a href="#session_management" class="ignore_jssm">maintain your session in one of the two
ways: cookie or url rewriting</a>.  If you are lazy and want to be inefficient, you may pass the username and password in each call, however it's not recommended.
</p>
-->

<img src="images/snap.gif"/>
<pre>
  <b>additional input parameters:</b> 
    outputFormat = XML|JSON  (defaults to XML)
    da = 0|1|2 (defaults to 0) 1 results in a quick check on domain availability, 2 is a slower but more accurate check on domain availability. 
    ip = 0|1 (defaults to 0) 1 results in returning ips for the domain name.
    callback = a javascript callback function used when outputFormat is JSON.  This is an impelmentation known as JSONP.  It invokes the callback function on the returned JSON response.	
    thinWhois = 0|1 (defaults to 0) 1 results in returning whois data from registry only without fetching whois data from registrar.  In schema registry data is returned under WhoisRecord->registryData.

</pre>
	<br class="spacer" />


<h2>how to query for my account balance?</h2>
	http://www.whoisxmlapi.com/accountServices.php?servicetype=accountbalance&username=xxxxx&password=xxxxx
		<br class="spacer" />


<h2>Will you warn me if my account balance is low or zero?</h2>
	Yes, you will receive a warning email when your account balance falls below a level(default to be 10 and customizable by you). You will receive
	another warning email when your account balance reaches 0.
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
		<li>
			output_format=JSON | XML(default)
		</li>
	</ul>
	<br class="spacer" />
	
<a name="sample_code"/>
<h2>How to make query to Whois API Webservice in Java, PHP, .Net(C#), Ruby, Python, or Javascript?</h2>
<b>Java Code :</b> <a href="code/java/SimpleQuery.java" target="_blank" class="ignore_jssm" title="Java Code Example 1"> Example 1</a> 
<a href="code/java/DomainAvailabilityAPIQuery.java" target="_blank" class="ignore_jssm" title="Java Code Example 2"> Example 2</a> 
<a href="code/java/WhoisAPITest.zip" target="_blank" class="ignore_jssm" title="Netbean project files">
  Netbean project files</a> with  <a href="http://hc.apache.org/user-docs.html" class="ignore_jssm" target="_apache_http_comp"  rel="nofollow" title="Apache Http Component">Apache Http Component</a>
<br/>   

<b>PHP Code : </b>
 	<a href="code/php/whois_api_test1.txt" target="_blank" class="ignore_jssm" title="PHP Code Example 1">Example 1</a>
 	<a href="code/php/whois_api_test2.txt" target="_blank" class="ignore_jssm" title="PHP Code Example 2">Example 2</a>
 	<a href="code/php/domain_availability_api_test.txt" target="_blank" class="ignore_jssm" title="PHP Code Example 3">Example 3</a>
<br/>

<b>.Net(C#) Code : </b>
 	<a href="code/dot_net/WhoisCSharp/whois.cs" target="_blank" class="ignore_jssm" title="C# Code Example 1">Example 1</a>
 	<a href="code/dot_net/WhoisCSharp/visualStudio.zip" target="_blank" class="ignore_jssm" title="Visual Studio solution">Visual Studio solution</a>
<br/>  

<b>Ruby Code : </b> 
 	<a href="code/ruby/whois.txt" target="_blank" class="ignore_jssm" title ="Ruby Code example 1">Example 1</a>
 	
<br/>  	

<b>Python Code : </b>
 	<a href="code/python/whois.txt" target="_blank" class="ignore_jssm" title="Python Code example 1">Example 1</a>
<br/>  	

<b>Javascript Code : </b>
 	<a href="code/javascript/whois.txt" target="_blank" class="ignore_jssm" title="Javascript Code example 1">Example 1</a>
 	<a href="code/javascript/whois_jquery_jsonp.html" target="_blank" class="ignore_jssm" title="Javascript Code example 2">Example 2</a>
<br/>   	
	 	
<br class="spacer" />

<!--	
<a name="session_management"/>
<h2>how do I maintain a session for faster query</h2>
	You may maintain your session in one of the two ways: cookie or url rewriting.  The server will return a header called SESSIONID in each
	response.
	<ul><li>
		url rewriting in the following format:
		<br/>URL;JSESSIONID=SESSIONID?domainName=DOMAINNAME
		<br/>For example:   
		http://www.whoisxmlapi.com/whoisserver/WhoisService;jsessionid=<br>
		24C8532A01B457156563388923AB6D91.server2?domainName=test.com
		</li>
		<li>
			use a cookie, this is likely your browser's way to communicate the session id to the server
		   <br/> add a header "COOKIE" with value "JSESSIONID=SESSIONID", 
		   for example "JSESSIONID=24C8532A01B457156563388923AB6D91.server2".  		  
		  
		</li>
	</ul>
	
	Click here to <a href="code/WhoisAPISessionTest.java" target="_sample_dl" class="ignore_jssm">download a simple example of client side session handling</a> in java using 
	<a href="http://hc.apache.org/user-docs.html" class="ignore_jssm" target="_apache_http_comp">Apache Http Component</a>.
		
<br class="spacer" />
-->
<h2>Can I use https?</h2>
	Yes, simply use https instead of http, the connection would be more secure but slower.
	<br class="spacer" />
	
<h2>What tlds (gtlds and cctlds) do you support?</h2>
	Yes, please check the <a class="ignore_jssm" href="support/supported_tlds.php" target="_blank" title="List of Supported TLDs">list of TLDs</a> that we support.
	<br class="spacer" />

<a name="whois_schema"/>		
<h2>Is there a xml schema/documentation for the whois query result?</h2>
  Yes, please download the <a class="ignore_jssm"  href="documentation/WhoisRecordSchema.xsd" target="_blank" title="Whois API XML Schema">xml schema</a>,  <a target="_blank" rel="nofollow" class="ignore_jssm" href="documentation/whoisapi_documentation/index.html" title="Whois API query result documentation">documentation on the query result</a>, and a <a class="ignore_jssm" href="documentation/WhoisRecord.xml" target="_blank" title="Whois API sample XML result">sample xml result</a>.
  
  <br class="spacer" />
  
<a name="whois_schema"/>		
<h2>Is there a term of service or SLA for using WHOIS API Webservice?</h2>
  Please view the  <a class="ignore_jssm" href="terms-of-service.php" target="_blank" title="Whois API Terms of Service">Terms of Service</a> here
  <br class="spacer" />

</div>




