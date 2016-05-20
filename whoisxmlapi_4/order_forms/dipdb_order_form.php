<a name="domain_ip_database"></a>
<?php 
global $dipdb_order_error;
if(isset($dipdb_order_error)){
	show_error($dipdb_order_error);
}
?>
<h3>Domain IP Database Download</h3>
<p class="rightTxt3">
	A Domain IP database contains every domain name to its hosting ip addresses mapping.
	We provide archived Domain IP database for download as database dumps(MYSQL or MYSSQL dump) or CSV files.
	Currently we provide IPs mapping for downloads for the major GTLDs:
	.com, .net, .org, .us, .biz, .mobi, .info, .pro, .coop, and .asia.  
</p>
<form id='dipdb_form' name="dipdb_form" action="<?php echo $form_action?>" class="ignore_jssm">
<input type="hidden"  name="order_type" value="dipdb"/>
<input  name="pay_choice" type="hidden" value="<?php echo $pay_choice?>"/>
<input type="hidden" name="submit" value="1"/>
<?php 
$dip_db_show_input=1;

include __DIR__.'/../domain_ip_db_pricing_table.php';
?>      
<br/>            
<input type="image" src="images/next.png" class="next_but"/>
</form>
<br/>