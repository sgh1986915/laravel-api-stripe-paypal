<?php require_once "users/users.conf";
	require_once __DIR__ . "/httputil.php";
	require_once __DIR__ . "/whois-database-price.php";
	require_once __DIR__ . "/models/cctld_whois_database_product.php";
?>
<p class="rightTop"></p>
<h1>Whois Database Download</h1> 
<p class="rightTxt1">
	We provide archived historic whois database in both parsed and raw format for download as database dumps(MYSQL or MSSQL dump) or CSV files.
	Currently we provide downloads for the major GTLDs:
	.com, .net, .org, .us, .biz, .mobi, .info, .pro, .coop, .asia and <a href="support/supported_ngtlds.php" class="ignore_jssm" target=_blank> hundreds of new GTLDs.</a>  
	Download a sample of the whois records with <a  class="ignore_jssm"  href="download.php?file=documentation/sample_raw_db.csv" title="Raw Text Only">raw text only</a> and with <a class="ignore_jssm" href="download.php?file=documentation/sample_parsed_db.csv" title="Both Parsed Data And Raw Text">both parsed data and raw text</a>
	<br/>
	In addition, we provide  <a  class="ignore_jssm" href="#cctld_database" title="CCTLD Domain Names List And CCTLD Whois Database"> cctld domain names list and cctld whois databases </a>
</p>

<div class="rightTxt2">
	<span>Possible applications and usages of historic whois database:</span> 
	<ul>
		<li>
			Cybersecurity analysis, Fraud detection
		</li>
		<li>
			Statistical research analysis
		</li>
		<li>
			Extract fine-grained information and gain insight from a comprehensive pool of whois records.
		</li>
		<li>
			Much more... The possiblities are limitless
		</li>						
	</ul>	
	

</div>

<div class="right_sec">
<a name="whois_db_download"></a>
<h2>Pricing for Whois Database Download</h2>
<p class="rightTxt3">
	We offer partial or complete historic whois database download.   We also offer a yearly plan with 4 quarterly downloads of complete whois databases.
	<a class="ignore_jssm" href="<?php echo build_ssl_url('order_paypal.php?goto=whois_database');?>" class="ignore_jssm" title="Order Now">Order Now!</a>
	
</p>
<p style="color:red;font-weight:bold;">
	Promotional discount is only available until end of this month!
</p>
<table cellspacing="1" cellpadding="7" border="0" class="colored" align="center">
              <tr>
               
                <td align="right" class="header">Number of whois records</td>        
                <td align="right" class="header">Price(Raw text only)</td>
                <td align="right" class="header">Price(Raw text and parsed fields)</td>
              
              </tr>
            
              
              
			  <?php 
			  
			  	for($i=0;$i<$dbCount;$i++){
			  		//$avg_price = $dbPrices[$dbAmount[$i]] / $dbAmount[$i] * 1000;
					$cl = ($i%2==0?"evcell":"oddcell");
				?>
              	<tr>
                	<td align="right" class="<?php echo $cl?>"><?php echo number_format($dbAmount[$i])?> million (randomly chosen)</td>
                	<td align="right" class="<?php echo $cl?>"><?php echo discount($dbRawPrices[$dbAmount[$i]],$dbDiscount) ?></td>
                	<td align="right" class="<?php echo $cl?>"><?php echo discount($dbParsedPrices[$dbAmount[$i]], $dbDiscount)?></td>
              	</tr>
			<?php }
			
			?>	
				
		      	<tr>
                	<td align="right" class="oddcell"> > <?php echo number_format($dbAmount[$dbCount-1])?> million</td>
                	<td align="right" class="oddcell"><a onclick="_gaq.push(['_trackEvent', 'mailto', 'clicked']);" href="mailto:support@whoisxmlapi.com" class="ignore_jssm">contact us</a></td>
                	<td align="right" class="oddcell""><a onclick="_gaq.push(['_trackEvent', 'mailto', 'clicked']);"  href="mailto:support@whoisxmlapi.com" class="ignore_jssm">contact us</a></td>
              	</tr>	
		      	<tr>
                	<td align="right" class="<?php echo $cl?>"> The complete database(155 million records)</td>
                	<td align="right" class="<?php echo $cl?>"><a onclick="_gaq.push(['_trackEvent', 'mailto', 'clicked']);"  href="mailto:support@whoisxmlapi.com" class="ignore_jssm">contact us</a></td>
                	<td align="right" class="<?php echo $cl?>"><a onclick="_gaq.push(['_trackEvent', 'mailto', 'clicked']);"  href="mailto:support@whoisxmlapi.com" class="ignore_jssm">contact us</a></td>
              	</tr>   
              	<tr>
                	<td align="right" class="oddcell"> Yearly Subscription(4 quarterly downloads/year) of complete databases</td>
                	<td align="right" class="oddcell"><a onclick="_gaq.push(['_trackEvent', 'mailto', 'clicked']);"  href="mailto:support@whoisxmlapi.com" class="ignore_jssm">contact us</a></td>
                	<td align="right" class="oddcell"><a onclick="_gaq.push(['_trackEvent', 'mailto', 'clicked']);" href="mailto:support@whoisxmlapi.com" class="ignore_jssm">contact us</a></td>
              	</tr>
              	<tr>
                	<td align="right" class="evcell"><b> Yearly Subscription(daily updates!) of complete databases</b></td>
                	<td align="right" class="evcell"><a onclick="_gaq.push(['_trackEvent', 'mailto', 'clicked']);"  href="mailto:support@whoisxmlapi.com" class="ignore_jssm">contact us</a></td>
                	<td align="right" class="evcell"><a onclick="_gaq.push(['_trackEvent', 'mailto', 'clicked']);"  href="mailto:support@whoisxmlapi.com" class="ignore_jssm">contact us</a></td>
              	</tr>  
              	<tr>
                	<td align="right" class="evcell"> All Historic snapshots of the whois databases (about 2 billion whois records)</td>
                	<td align="right" class="evcell"><a onclick="_gaq.push(['_trackEvent', 'mailto', 'clicked']);"  href="mailto:support@whoisxmlapi.com" class="ignore_jssm">contact us</a></td>
                	<td align="right" class="evcell"><a onclick="_gaq.push(['_trackEvent', 'mailto', 'clicked']);"  href="mailto:support@whoisxmlapi.com" class="ignore_jssm">contact us</a></td>
              	</tr>               	            	
</table>


<br/>

<a name="cctld_database"></a>
<h2>Pricing for historic cctld Whois Database Download</h2>

<p class="rightTxt3">
	<?=CCTLDWhoisDatabaseProduct::$order_description?> 
	There are a total of <?php echo count(CCTLDWhoisDatabaseProduct::get_products())?> cctld databases listed below. <br/>
	<a class="ignore_jssm" href="order_paypal.php?goto=cctld_whois_database" class="ignore_jssm" title="Order Now">Order Now!</a>
	
</p>

<?php include 'cctld_db_pricing_table.php'; ?>   
  
<br/>  
<a name="custom_database"></a>
<h2>Pricing for Alexa & Quantcast Whois Database Download</h2>

<p class="rightTxt3">
	Historic whois databases for top 1 million Alexa & Quantcast domains.  
	<a class="ignore_jssm" href="order_paypal.php?goto=custom_whois_database" class="ignore_jssm" title="Order Now">Order Now!</a>
	
</p>

<?php include 'custom_db_pricing_table.php'; ?>               

</div>
<p class="rightBottom"></p>
<br class="spacer" />


