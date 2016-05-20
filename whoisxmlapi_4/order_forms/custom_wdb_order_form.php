<a name="custom_whois_database"></a>
<?php 
global $custom_wdb_order_error;
if(isset($custom_wdb_order_error)){
	show_error($custom_wdb_order_error);
}
?>
<h3>Pricing for Alexa & Quantcast Whois Database Download</h3>
<p class="rightTxt3">
	Historic whois databases for top 1 million Alexa & Quantcast domains.  Receives 20% discount when you purchase more than one database.
</p>
<form id='custom_wdb_form' name="custom_wdb_form" action="<?php echo $form_action?>" class="ignore_jssm">
<input type="hidden"  name="order_type" value="custom_wdb"/>
<input  name="pay_choice" type="hidden" value="<?php echo $pay_choice?>"/>
<input type="hidden" name="submit" value="1"/>
<?php 
$custom_db_show_input=1;
include 'custom_db_pricing_table.php';
?>      
<br/>            
<input type="image" src="images/next.png" class="next_but"/>
</form>
<br/>