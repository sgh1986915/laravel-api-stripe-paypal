<?php

	require_once "price.php";
	
	
	require_once "httputil.php";
	require_once "util.php";
	
	require_once "users/utils.inc";
	require_once "users/users.inc";
	
	require_once __DIR__ . "/wc_price.php";
	require_once __DIR__ . "/whois-database-price.php";
	require_once __DIR__ .'/models/cctld_whois_database_product.php';
?>
<?php require_once "affiliate_track.php"?>
<?php
	if(!session_id())session_start();
	
	global $group_license_price, $source_license_price;
	global $queryCount,$queryPrices,$queryAmount ;
	global $membershipCount, $membershipAmount, $membershipPrices;
	global $dbDiscount, $dbRawPrices, $dbParsedPrices, $dbCount,$dbAmount;
	
	
	global $order_error;
	
	$pay_choice = "pp";
	if(isset($cc))$pay_choice="cc";

	$query_quantity = get_from_post_get("query_quantity");
	if(!$query_quantity) $query_quantity = 25000;
	
	$wdb_quantity = $_REQUEST['wdb_quantity'];
	if(!$wdb_quantity) $wdb_quantity = 1;
	
	$wdb_type = isset($_REQUEST['wdb_type']) ? $_REQUEST['wdb_type'] : false;
	if(!$wdb_type) $wdb_type = 'both';
	
	
	$cctld_whois_db_name = isset($_REQUEST['cctld_whois_db_name']) ? $_REQUEST['cctld_whois_db_name'] : false;
	if(!$cctld_whois_db_name) $cctld_whois_db_name = '.co.uk';
	$cctld_whois_db_type =  isset($_REQUEST['cctld_whois_db_type']) ? $_REQUEST['cctld_whois_db_type'] : false;
	if(!$cctld_whois_db_type) $cctld_whois_db_type ="whois_records";
	
	my_session_start();
	$order_username = "";
	if(isset($_REQUEST['order_username'])){
		$order_username = $_REQUEST['order_username'];
	}
	else if(isset($_SESSION['myuser'])){
		$order_username = $_SESSION['myuser']->username;
		
	}
	
	$wc_order_username = "";
	if(isset($_REQUEST['wc_order_username'])){
		$wc_order_username = $_REQUEST['wc_order_username'];
	}
	else if(isset($_SESSION['myuser'])){
		$wc_order_username = $_SESSION['myuser']->username;
		
	}	
	$wc_license_type = (isset($_REQUEST['wc_license_type'])? $_REQUEST['wc_license_type'] : false);
	if(!$wc_license_type){
		$wc_license_type = 'group_license'; 
	}
	$num_user_license = (isset($_REQUEST['num_user_license'])? $_REQUEST['num_user_license'] : false);
	if(!$num_user_license){
		$num_user_license = 1;
	}
	
	$user_license_unit_price = compute_user_license_unit_price($num_user_license);
	$user_license_total_price = compute_user_license_price($num_user_license);
	

?>

<?php 

if(isset($_REQUEST['goto']) && $_REQUEST['goto']){?>
	<script type ="text/javascript">
		$(document).ready(function(){
			location.href=location.href+"#<?php echo $_REQUEST['goto']?>";	
		});
		
	</script>
	
<?php 
}?>
<?php 
	$err_map = array('wc_order_error'=>'whois_api_client',
		'cctld_wdb_order_error'=>'cctld_whois_database',
		'wdb_order_error' =>'whois_database',
		'bwl_order_error' =>'bulk_whois_lookup_service',
		'custom_wdb_order_error'=>'custom_whois_database',
		'db_db_order_error'=>'domain_ip_database'
	);

	foreach($err_map as $key=>$val){
		global ${$key};
		if(isset(${$key})){?>
			<script type ="text/javascript">
				$(document).ready(function(){
				location.href=location.href+"#<?php echo $val?>";	
			});
		
			</script>
	
<?php 
	}
}?>

<p class="rightTop"/>
<?php if(isset($order_error)){
	show_error($order_error);
}
?>
<div class="right_sec">
<h3>Paypal Order Form</h3>
<p class="rightTxt3">
	We offer 2 pricing models, <a class="ignore_jssm" href="#pay_as_you_go">pay as you go</a> or <a class="ignore_jssm"  href="#monthly_memberships">monthly memberships</a>. 
	
</p>
<?php
	$form_action="order_process.php";
	if($order_test)$form_action="order_test_process.php";
	if($pay_choice=='cc'){
		$form_action="cc.php";	
	}
	
	
?>
<form action="<?php echo $form_action?>" class="ignore_jssm">
	<input id="pay_choice" name="pay_choice" type="hidden" value="<?php echo $pay_choice?>"/>
		<!--<input type="hidden" name="sandbox" value="1"/>-->
		<div>
			<label for="order_username" class="description">Username of the account to make purchase for:</label>
			<input type="text"  maxlength="255" class="element text medium" name="order_username" id="order_username" value="<?php echo $order_username?>" />
		</div>	
<a name="pay_as_you_go"></a>
<h3>Pay as you go Purchase Plan</h3>

<p class="rightTxt3">
	Simply purchase the number of whois queries you require and they will be added to your account instantly.
	You will receive a notification email before your account reaches empty.  You can buy more queries or 
	replenish your account any time.
</p>
<table cellspacing="1" cellpadding="7" border="0" class="colored" align="center">
              <tr>
			  	
                <td colspan="2" align="right" class="header">Number of queries</td>
           
                <td align="right" class="header">Price (USD)</td>
              </tr>
			  <?php 
			  	for($i=0;$i<$queryCount;$i++){
			  		$avg_price = $queryPrices[$queryAmount[$i]] / $queryAmount[$i] * 1000;
					$cl = ($i%2==0?"evcell":"oddcell");
					$checked = ($queryAmount[$i]==$query_quantity);
				
				?>
              	<tr>
              		<td align="right" class="<?php echo $cl?>"><input type="radio" value="<?php echo $queryAmount[$i];?>" name="query_quantity" <?php echo $checked?"checked":"";?> /></td>
                	<td align="right" class="<?php echo $cl?>"><?php echo number_format($queryAmount[$i])?></td>
                	
                	<td align="right" class="<?php echo $cl?>"><b>$<?php echo $queryPrices[$queryAmount[$i]]?></b></td>
              	</tr>
			<?php }
				$cl = ($i%2==0?"evcell":"oddcell");
			?>	
			<tr>
				<td align="right" class="<?php echo $cl?>"></td>
                	<td align="right" class="<?php echo $cl?>">> <?php echo number_format($queryAmount[$queryCount-1]);?></td>
                	
                	<td align="right" class="<?php echo $cl?>"><a href="mailto:support@whoisxmlapi.com"  class="ignore_jssm">contact us</a></td>
              	</tr>			
		      	
</table>

<input type="image" src="images/next.png" class="next_but"/>
<br/>

<a name="monthly_memberships"></a>
<h3>Membership Plans</h3>
<p class="rightTxt3">
	By purchasing a membership plan, you may use up to a certain maximum number of queries each month, this is recommended if you use Whois API on a regular basis.  You may cancel/change your plan anytime.
	<!--<br/><b>Attention membership subscribers:</b> Paypal requires you to link&confirm your credit card to a new paypal account for subscription, the confirmation process may take 2-3 days.  Therefore we recommend choosing <a class="ignore_jssm" href="#pay_as_you_go">"pay as you go"</a>  as the simplest option if you don't already have a paypal account which is linked to a confirmed credit card account/bank account.-->
</p>
<table cellspacing="1" cellpadding="7" border="0" class="colored" align="center">
              <tr>
			  	
                <td  width="30%" align="right" class="header">Maximum number of queries/month</td>
           
                <td align="right" class="header">Price/Month (USD)</td>
				<td align="right" class="header">Price/Year (USD)</td>
              </tr>
			  <?php for($i=0;$i<$membershipCount;$i++){
			  		$avg_price = $membershipPrices[$membershipAmount[$i]] / $membershipAmount[$i] * 1000;
					$cl = ($i%2==0?"evcell":"oddcell");
					$checked = (strcmp($membershipAmount[$i], $membershipAmount[$i]) == 0);
				
				?>
              	<tr>
              	
                	<td align="right" class="<?php echo $cl?>"><?php echo number_format($membershipAmount[$i])?></td>
                	
                	<td align="right" class="<?php echo $cl?>">
                		
				
        				<b>$<?php echo $membershipPrices[$membershipAmount[$i]]?></b> 
            				<input type="submit" value="Bill Monthly" class="mbutton" name="<?php echo 'bill_monthly_' . $membershipAmount[$i] ?>"/>  
						
					</td>
					
                	<td align="right" class="<?php echo $cl?>">
                		
							<b>$<?php echo 	10 * $membershipPrices[$membershipAmount[$i]]?></b>
        		
            			<input type="submit" value="Bill Yearly" class="mbutton" name="<?php echo 'bill_yearly_' . $membershipAmount[$i] ?>"/>   
						
					</td>
										
              	</tr>
			<?php }?>			
		      	<tr>
				
                	<td align="right" class="<?php echo $cl?>"> ><?php echo number_format($membershipAmount[$membershipCount-1]);?></td>
                	
                	<td align="right" colspan="2" class="<?php echo $cl?>"><a href="mailto:support@whoisxmlapi.com" class="ignore_jssm">Bulk Discount, Contact Us</a></td>
              	</tr>		
</table>

</form>
<br/>
<style type="text/css">
.clickable{
	cursor:pointer;
}
</style>

<a name="bulk_whois_lookup_service"></a>
<?php if(isset($bwl_order_error)){
	show_error($bwl_order_error);
}
?>
<h3>Bulk Whois Lookup Service</h3>
<p class="rightTxt3">
	Our fast-track bulk whois lookup service provides the quickest whois lookup on a large number of domain names(hundreds of thousands or millions) directly
	via our backend processes for a fee.   Results will be downloaded as csv or mysqldump files in bulk. 
	

</p>



<form id='bwl_form' name="bwl_form" action="order_process.php" class="ignore_jssm">
<input type="hidden"  name="order_type" value="bwl"/>
<input name="pay_choice" type="hidden" value="<?php echo $pay_choice?>"/>
	


	<?php 
		$include_choice=true;
		$selected_choice=true;
		include "bulk_whois_lookup_pricing_table.php"
	?>
	<p style="margin-bottom:10px;">
	</p>
	<input type="image" src="images/next.png" class="next_but"/>
</form>
<br/>


<a name="whois_api_client"></a>
<?php if(isset($wc_order_error)){
	show_error($wc_order_error);
}
?>
<h3>Bulk Whois Lookup Application</h3>
<p class="rightTxt3">
	Bulk Whois Lookup Application is a desktop graphical user interface application that communicates with Whois API Webservice. It allows users to bulk load, mass query, import and export whois data.
	All licenses are non-redistributable. You need to purchase Whois API queries in order to use it beyond the 100 free lookups.  All minor updates(binary or source code) within one major version are for free.
	The source code for Bulk Whois Client is under the terms and conditions of a separate <a class="ignore_jssm" href="wc_sourcecode_license.php" target="_wc_src_lic">Source Code License Agreement</a>.
</p>
<form id='wpc_form' name="wpc_form" action="order_process.php" class="ignore_jssm">
<input type="hidden"  name="order_type" value="wc"/>
<input name="pay_choice" type="hidden" value="<?php echo $pay_choice?>"/>
	
<br/>
<table cellspacing="1" cellpadding="7" border="0" class="colored" align="center">
              <tr>
              	<td colspan="2" align="right" class="header">Number of Licenses</td>
                <td  align="right" class="header">License Type</td>
           		<td  align="right" class="header">Description</td>
                <td align="right" class="header">Unit Price (USD)</td>
              	<td align="right" class="header">Total Price (USD)</td>
              </tr>
              
				<tr>
              		<td align="right" class="evcell"><input type="radio"  value="per_user_license" name="wc_license_type" <?php echo $wc_license_type=='per_user_license'?'checked':''?> /></td>
              		<td align="right" class="evcell" ><input id="num_user_license" type="text" style="text-align:right" value="<?php echo $num_user_license?>" name="num_user_license" size="3"/></td>
                	<td align="right" class="evcell">Per User License</td>
                	<td align="right" class="evcell">1-5 users $59/license<br/>6-10 users $49/license</td>
                	<td align="right" class="evcell"><b>$<span id="user_license_unit_price"><?php echo  number_format($user_license_unit_price)?></span></b></td>
                	<td align="right" class="evcell"><b>$<span id="user_license_total_price"><?php echo  number_format($user_license_total_price)?></span></b></td>
              	</tr>
              	
				<tr>
					<td align="right" class="oddcell"><input type="radio" value="group_license" name="wc_license_type" <?php echo $wc_license_type=='group_license'?'checked':''?>/></td>
					<td align="right" class="oddcell">1</td>
              		
                	<td align="right" class="oddcell">Group License</td>
                	<td align="right" class="oddcell" style="text-align:left">Unlimited user licenses for all members of your organization</td>
                	<td align="right" class="oddcell"><b>$<?php echo  number_format($group_license_price)?></b></td>
                	<td align="right" class="oddcell"><b>$<?php echo  number_format($group_license_price)?></b></td>
              	</tr>              	
 				<tr>
					<td align="right" class="evcell"><input type="radio" value="sourcecode_license" name="wc_license_type" <?php echo $wc_license_type=='sourcecode_license'?'checked':''?>/></td>
					<td align="right" class="evcell">1</td>
              		
                	<td align="right" class="evcell">Source Code</td>
                	<td align="right" class="evcell"  style="text-align:left">Source code allows customization to meet your own need. </td>
                	<td align="right" class="evcell"><b>$<?php echo  number_format($source_license_price)?></b></td>
                	<td align="right" class="evcell"><b>$<?php echo  number_format($source_license_price)?></b></td>
              	</tr>                	              
</table>  
<br/>            
<input type="image" src="images/next.png" class="next_but"/>

</form>
<br/>



<a name="whois_database"></a>
<?php if(isset($wdb_order_error)){
	show_error($wdb_order_error);
}
?>
<h3>Historic Whois Database Download</h3>
<p class="rightTxt3">
	We provide archived historic whois database in both parsed and raw format for download as databases dumps(Mysql or Mssql dump) or CSV files. Data will be delievered between 1 to 3 business days(depending on the volume).  


</p>
<form id='wdb_form' name="wdb_form" action="order_process.php" class="ignore_jssm">
<input type="hidden"  name="order_type" value="wdb"/>
<input  name="pay_choice" type="hidden" value="<?php echo $pay_choice?>"/>
<table cellspacing="1" cellpadding="7" border="0" class="colored" align="center">
              <tr>
               
                <td colspan="2" align="right" class="header">Number of whois records</td>        
                <td align="right" class="header">Price(Raw text only)<br/> <input type="radio" name="wdb_type" value="raw" <?php echo $wdb_type=='raw' ? "checked": ""?>/> </td>
                <td align="right" class="header">Price(Raw text and parsed fields)<br/><input type="radio" name="wdb_type" <input name="wdb_type" value="both" <?php echo $wdb_type!='raw' ? "checked": ""?> /></td>
              
              </tr>
            
              
              
			  <?php 
			 
			  	for($i=0;$i<$dbCount;$i++){
			  		//$avg_price = $dbPrices[$dbAmount[$i]] / $dbAmount[$i] * 1000;
					$cl = ($i%2==0?"evcell":"oddcell");
					$checked = ($dbAmount[$i]==$wdb_quantity);
				?>
              	<tr>
              		<td align="right" class="<?php echo $cl?>">
              			<input type="radio" value="<?php echo $dbAmount[$i];?>" name="wdb_quantity" <?php echo $checked?"checked":"";?> />
              		</td>
                	<td align="right" class="<?php echo $cl?>"><?php echo number_format($dbAmount[$i])?> million randomly chosen</td>
                	<td align="right" class="<?php echo $cl?>"><?php echo discount($dbRawPrices[$dbAmount[$i]], $dbDiscount) ?></td>
                	<td align="right" class="<?php echo $cl?>"><?php echo discount($dbParsedPrices[$dbAmount[$i]], $dbDiscount) ?></td>
              	</tr>
			<?php }?>	
			
		      	<tr>
                	<td colspan="2" align="right" class="oddcell"> > 5 million</td>
                	<td align="right" class="oddcell"><a href="mailto:support@whoisxmlapi.com">contact us</a></td>
                	<td align="right" class="oddcell"><a href="mailto:support@whoisxmlapi.com">contact us</a></td>
              	</tr>	
		      	<tr>
                	<td colspan="2" align="right" class="evcell"> The complete database(155 million records)</td>
                	<td align="right" class="evcell"><a href="mailto:support@whoisxmlapi.com">contact us</a></td>
                	<td align="right" class="evcell"><a href="mailto:support@whoisxmlapi.com">contact us</a></td>
              	</tr>  
             	<tr>
                	<td colspan="2" align="right" class="oddcell">Yearly Subscription(4 quarterly downloads/year) of complete databases</td>
                	<td align="right" class="oddcell"><a href="mailto:support@whoisxmlapi.com">contact us</a></td>
                	<td align="right" class="oddcell"><a href="mailto:support@whoisxmlapi.com">contact us</a></td>
              	</tr>   
              	<tr>
                	<td colspan="2" align="right" class="evcell">Yearly Subscription(Daily updates!) of complete databases</td>
                	<td align="right" class="evcell"><a href="mailto:support@whoisxmlapi.com">contact us</a></td>
                	<td align="right" class="evcell"><a href="mailto:support@whoisxmlapi.com">contact us</a></td>
              	</tr>             	
</table>
<br/>            
<input type="image" src="images/next.png" class="next_but"/>

</form>
<br/>


<a name="cctld_whois_database"></a>
<?php 
global $cctld_wdb_order_error;
if(isset($cctld_wdb_order_error)){
	show_error($cctld_wdb_order_error);
}
?>
<h3>historic cctld Whois Database Download</h3>
<p class="rightTxt3">
<?=CCTLDWhoisDatabaseProduct::$order_description?>
There are a total of <?php echo count(CCTLDWhoisDatabaseProduct::get_products())?> cctld databases listed below. <br/>
Check the products you want to see updated total price.

</p>
<form id='cctld_wdb_form' name="cctld_wdb_form" action="order_process.php" class="ignore_jssm">
<input type="hidden"  name="order_type" value="cctld_wdb"/>
<input  name="pay_choice" type="hidden" value="<?php echo $pay_choice?>"/>
<?php 
$cctld_db_show_input=1;
include 'cctld_db_pricing_table.php';

?>         
            
<br/>            
<input type="image" src="images/next.png" class="next_but"/>

</form>
<br/>

<?php include __DIR__ ."/order_forms/custom_wdb_order_form.php"; ?>
<br/>


<?php include __DIR__ ."/order_forms/dipdb_order_form.php"; ?>

<h3>Payment Policy</h3>


	
		<span><img src="images/paypal.gif"/><br></span>
	
	

	

<p class="rightTxt3">
All transactions are done via paypal for safety and security. Unused Query purchases never expire but are not refundable.   You may change or cancel your membership at any time by simply <a class="ignore_jssm" href ="mailto:support@whoisxmlapi.com">sending us an email</a> with your username.  Please <a class="ignore_jssm" href ="mailto:support@whoisxmlapi.com">contact us</a> if you encounter any issue with the checkout proccess.<br/>


</p>

<span> <b style="color:#444444">Need an alternative payment option?</b></span>
<span><a href ="<?php echo build_ssl_url('order.php')?>" class="ignore_jssm"><img src="images/cc.png"/></a></span>
<p class="rightTxt3">
You may also <a href="<?php echo build_ssl_url('order.php')?>" class="ignore_jssm">use a credit card</a> to checkout.
Please <a class="ignore_jssm" href ="mailto:support@whoisxmlapi.com">send us an email</a> for instruction on how to pay by check, wire-transfer, or other options
</p>

	
</div>

<script type="text/javascript">
	Number.prototype.formatMoney = function(c, d, t){
		var n = this, c = isNaN(c = Math.abs(c)) ? 2 : c, d = d == undefined ? "," : d, t = t == undefined ? "." : t, s = n < 0 ? "-" : "", i = parseInt(n = Math.abs(+n || 0).toFixed(c)) + "", j = (j = i.length) > 3 ? j % 3 : 0;
   		return s + (j ? i.substr(0, j) + t : "") + i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + t) + (c ? d + Math.abs(n - i).toFixed(c).slice(2) : "");
 	};
	
	$(document).ready(function(){
		$('#num_user_license').blur(function(evt){
			fix_user_license_price();
		});
	
		$('#wpc_form').submit(function(evt){
			fix_user_license_price();
		});
		
		
		
		

	});
	function toggle_text(el, texts){
		for(var i=0;i<texts.length;i++){
			if(texts[i]==el.text()){
				el.text(texts[(i+1)%texts.length]);	
			}
		}
		
	}
	
	function fix_user_license_price(){
			var n = get_num($('#num_user_license').val());
			$('#num_user_license').val(n);
			var price = comp_user_license_price(n);
			$('#user_license_total_price').text(price);
			var unit_price = comp_user_unit_price(n);
			$('#user_license_unit_price').text(unit_price);
	}
	function get_num(n){
		n = parseInt(n);
		if(!n)return 1;
		return n;
	}
	function comp_user_license_price(n){
		var unit_price = comp_user_unit_price(n);
		return n*unit_price;
	}
	function comp_user_unit_price(n){
		if(n<6)return 59;
		return 49;
	}
	
	
	
	
</script>
