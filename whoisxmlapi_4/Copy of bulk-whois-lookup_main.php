<?php require_once "users/users.conf";?>
<p class="rightTop"></p>
<h2>Bulk Whois Lookup / Bulk Domain Checker</h2> 
<p class="rightTxt1">
	We offer a fast-track service and a software application for bulk whois lookup.  
	Our fast-track <a href="#bulk_whois_lookup_service" class="ignore_jssm">Bulk Whois Lookup Service</a> provides <b>the quickest whois lookup on a large number of domain names(hundreds of thousands or millions) directly
	via our backend processes for a manual processing fee.  </b>
	The <a href="#whois_api_client" class="ignore_jssm">Bulk Whois Lookup Application</a> is intended to perform whois lookup and export whois data at a slower pace on domain names with a convenient graphical user interface that allows complete control of the process.  
	Both the service and the sofware require a <a href="whois-api.php">Whois API webservice</a> account.
	
	
</p>










<div class="rightTxt2">
<a name="whois_api_client"/>
<h3>Bulk Whois Lookup Application</h3> 

	Bulk Whois Lookup Application is a desktop graphical user interface application that communicates with Whois API Webservice.  It allows users
	to bulk load, mass query, import and export whois data.  
	<a href="order.php?goto=whois_api_client" id="wc_order" class="ignore_jssm new_bold"> Order now</a> <br/>
	<br/>
	<a id="wc_screen1" rel="example1" href="images/whois_api_client_screen1.gif" class="ignore_jssm"  ><img src="images/whois_api_client_screen1.gif" width="580" height="327" /></a>
	<a id="wc_screen2" rel="example1" href="images/whois_api_client_screen2.gif" class="ignore_jssm" style="display:hidden"></a>
	
</div>

<div class="rightTxt2">
	<span>Key Features:</span> 
	<ul>
		<li>
			Perform mass query on a large number of domain names automatically
		</li>
		<li>
			Display parsed(Contact Email, Registrant, Registrar, Contacts, Name servers, Created date, Updated date, Expires Date, Status, etc) and raw whois data in a grid
		</li>
		<li>
			Import domain names from Comma Separated Values(.csv) or Excel(.xls and xlsx) files
		</li>
		
		<li>
			Export whois data(both parsed and raw) into Comma Separated Values(.csv) or Excel(.xls and xlsx) files
		</li>
		
		<li>
			Fine-grained control over the whois data query process  
		</li>			
		<li>
			Requires no installation and is delivered and updated over the web seamlessly(using Java Webstart)
		</li>
	</ul>	
	

</div>

<p class="rightBottom"></p>
<br class="spacer" />








<div class="rightTxt2">
<a name="bulk_whois_lookup_service"/>
<h3>Bulk Whois Lookup fast-track Service</h3> 

	We take your domain list of any size and extract fresh whois data directly from our backend processes.  The result will be database dump files(Mysql or Mssql-compatible) containing the whois data for you to download.  
	You may download <a href="download.php?file=documentation/sample_bulk_whois.csv" class="ignore_jssm">sample csv file</a> or database schema  in <a target="_blank" href="db_schemas/whoiscrawler_schema_mysql.sql" class="ignore_jssm" rel=nofollow>Mysql</a> and <a target="_blank" href="db_schemas/whoiscrawler_schema_mssql.sql" class="ignore_jssm" rel=nofollow>Mssql</a>.   
	
	If you require any other format extra delivery time might be needed.  The processing fee(<b>in addition to the regular whois api webservice fee</b>) and time to complete are based on the following table.
	 
	 
	<a href="order.php?goto=bulk_whois_lookup_service" id="wc_order" class="ignore_jssm new_bold"> Order now</a> <br/>
	<br/>
	<?php include "bulk_whois_lookup_pricing_table.php";?>
     <br/>   	
     <ul><b>Examples:</b>
     	<li><b>100k</b> domains take up to <b>2 days</b> to process and cost <b>$200</b> on a <b>regular</b> schedule, or takes up to <b>1 day</b> to process and cost <b>$500</b> on an <b>expedited</b> schedule</li>
     	<li><b>200k</b> domains take up to 2 + ceiling(200 x 0.005) = <b>3 days</b> to process and cost $500 + $0.5 x 200 = <b>$600</b> on a regular schedule, or takes up to 1 + ceiling($0.001 x 200) = <b>2 days</b> to process and cost $1000 + $1 x 200 = <b>$1200</b> on an <b>expedited</b> schedule</li>
     	<li><b>500k</b> domains take up to 2 + ceiling(500 x 0.005) = <b>5 days</b> to process and cost $500 + $0.5 x 500 = <b>$750</b> on a regular schedule, or takes up to 1 + ceiling($0.001 x 500) = <b>2 days</b> to process and cost $1000 + $1 x 500 = <b>$1500</b> on an <b>expedited</b> schedule</li>
     	<li><b>1 million</b> domains take up to 2 + ceiling(1,000 x 0.005) = <b>7 days</b> and cost $500 + $0.5 x 1000 = <b>$1000</b> on a regular schedule, or takes up to 1 + ceiling($0.001 x 1000) = <b>2 days</b> day to process and cost $1000 + $1 x 1000 = <b>$2000</b> on an <b>expedited</b> schedule</li>
     	<li><b>2 million</b> domains take up to 2 + ceiling(2,000 x 0.005) = <b>12 days</b> to process and cost $500 + $0.5 x 2000 = <b>$1500</b> on a regular schedule, or takes up to 1 + ceiling($0.001 x 2000) = <b>3 days</b> to process and cost $1000 + $1 x 2000 = <b>$3000</b> on an <b>expedited</b> schedule</li>
     	<li><b>5 million</b> domains take up to 2 + ceiling(5,000 x 0.005) = <b>22 days</b> and cost $500 + $0.5 x 5000 = <b>$3000</b> on a regular schedule, or takes up to 1 + ceiling($0.001 x 5000) = <b>6 days</b> to process and cost $1000 + $1 x 5000 = <b>$5000</b> on an <b>expedited</b> schedule</li>
     
     	<li><b>10 million</b> domains take up to 17 + ceiling(10,000 x 0.001) = <b>27</b> days and cost $2500 + $0.1 x 10,000 = <b>$5500</b> on a regular schedule, or takes up to 7 + ceiling(0.0005 x 10,000) =  <b>12 days</b> to process and cost $5000 + $0.2 x 10,000 = <b>$7000</b> on an <b>expedited</b> schedule</li>
     	<li><b>50 million</b> domains take up to 17 + ceiling(50,000 x 0.001) = <b>67</b> days to process and cost $2500 + $0.1 x 50,000 = <b>$7500</b> on a regular schedule, or takes up to 7 + ceiling(0.0005 x 50,000) =  <b>32 days</b> to process and cost $5000 + $0.2 x 50,000 = <b>$15,000</b> on an <b>expedited</b> schedule</li>
     	
    	
    </ul>
 
     
</div>


<script type="text/javascript">
$(document).ready(function(){
		
			$("a[rel=example1]").colorbox();
});
</script>
