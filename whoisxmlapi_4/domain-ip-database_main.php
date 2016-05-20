<?php require_once "users/users.conf";
	require_once __DIR__ . "/httputil.php";
	require_once __DIR__ . "/whois-database-price.php";
	require_once __DIR__ . "/models/cctld_whois_database_product.php";
?>
<p class="rightTop"></p>
<h1>Domain IP Database Download</h1>
<p class="rightTxt1">
	A Domain IP database contains every domain name to its hosting ip addresses mapping.
	We provide archived Domain IP database for download as database dumps(MYSQL or MYSSQL dump) or CSV files.
	Currently we provide downloads for the major GTLDs:
	.com, .net, .org, .us, .biz, .mobi, .info, .pro, .coop, and .asia.  
	Download <a  class="ignore_jssm"  href="download.php?file=documentation/sample_raw_db.csv"> a sample of the domain records</a><br/>
</p>

<div class="rightTxt2">
	<span>Possible applications and usages of Domain IP database:</span> 
	<ul>
	
		<li>
			Cybersecurity analysis, Fraud detection
		</li>
		<li>
			Statistical research analysis
		</li>
		<li>
			Extract fine-grained information and gain insight from a comprehensive pool of domain name to ip address mappings.
		</li>
		<li>
			Much more... The possiblities are limitless
		</li>						
	</ul>	
	

</div>

<div class="right_sec">
<a name="whois_db_download"></a>
<h2>Pricing for Domain IP Database Download</h2>
<p class="rightTxt3">
	We offer partial or complete Domain IP database download.   We also offer a yearly plan with 4 quarterly downloads of complete domain ip databases.
	<a class="ignore_jssm" href="<?php echo build_ssl_url('order_paypal.php?goto=domain_ip_database');?>" class="ignore_jssm" title="Order Now">Order Now!</a>
	
</p>
<p style="color:red;font-weight:bold;">
	Promotional discount is only available until end of this month!
</p>
	<?php include __DIR__ ."/domain_ip_db_pricing_table.php";?>


<br/>

</div>
<p class="rightBottom"></p>
<br class="spacer" />


