<?php

	require_once "custom_price.php";
	require_once "httputil.php";
	require_once "util.php";
	
	require_once "users/utils.inc";
	require_once "users/users.inc";
	
	require_once __DIR__ . "/wc_price.php";
	require_once __DIR__ . "/whois-database-price.php";
	
?>
<?php
	if(!session_id())session_start();
	
	global $group_license_price, $source_license_price;
	
	$pay_choice = get_from_post_get("pay_choice");
	if(!$pay_choice) $pay_choice = "pp";
	$query_quantity = get_from_post_get("query_quantity");
	if(!$query_quantity) $query_quantity = 25000;
	
	$wdb_quantity = $_REQUEST['wdb_quantity'];
	if(!$wdb_quantity) $wdb_quantity = 5;
	
	$wdb_type = isset($_REQUEST['wdb_type']) ? $_REQUEST['wdb_type'] : false;
	if(!$wdb_type) $wdb_type = 'both';
	
	
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
	
	$wdb_prefix = $_REQUEST['wdb_prefix'];
?>

<?php if(isset($_REQUEST['goto']) && $_REQUEST['goto']){?>
	<script type ="text/javascript">
		$(document).ready(function(){
			location.href=location.href+"#<?php echo $_REQUEST['goto']?>";	
		});
		
	</script>
	
<?php 
}?>
<?php if(isset($wc_order_error)){?>
	<script type ="text/javascript">
		$(document).ready(function(){
			location.href=location.href+"#whois_api_client";	
		});
		
	</script>
	
<?php 
}?>
<p class="rightTop"/>
<?php if(isset($order_error)){
	show_error($order_error);
}
?>
<div class="right_sec">
<h3>Secure Order Form</h3>
<form action="custom_order_process.php" class="ignore_jssm">
	<input id="choice_cc" name="pay_choice" type="hidden" value="pp"/>
		<!--<input type="hidden" name="sandbox" value="1"/>-->
	<?php if($_REQUEST['payperiod_multiple']){?>
		<input type="hidden" name="payperiod_multiple" value="<?php echo $_REQUEST['payperiod_multiple'];?>" />
	<?php
		}
	?>
	<?php if($_REQUEST['payto']){?>
		<input type="hidden" name="payto" value="<?php echo $_REQUEST['payto'];?>" />
	<?php
		}
	?>	
<?php if($_REQUEST['special_order']){ 
	include "special_order.php";
}
else{ 
?>


<p class="rightTxt3">
	We offer 2 pricing models, <a class="ignore_jssm" href="#pay_as_you_go">pay as you go</a> or <a class="ignore_jssm"  href="#monthly_memberships">monthly memberships</a>. 
	
</p>

		<div>
			<label for="order_username" class="description">Username of the account to make purchase for:</label>
			<input type="text"  maxlength="255" class="element text medium" name="order_username" id="order_username" value="<?php echo $order_username?>" />
		</div>	


		
<?php if($queryCount > 0){?>		
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
			  <?php for($i=0;$i<$queryCount;$i++){
			  		$avg_price = $queryPrices[$queryAmount[$i]] / $queryAmount[$i] * 1000;
					$cl = ($i%2==0?"evcell":"oddcell");
					$checked = ($queryAmount[$i]==$query_quantity);
				
				?>
              	<tr>
              		<td align="right" class="<?php echo $cl?>"><input type="radio" value="<?php echo $queryAmount[$i];?>" name="query_quantity" <?php echo $checked?"checked":"";?> /></td>
                	<td align="right" class="<?php echo $cl?>"><?php echo number_format($queryAmount[$i])?></td>
                	
                	<td align="right" class="<?php echo $cl?>"><b>$<?php echo $queryPrices[$queryAmount[$i]]?></b></td>
              	</tr>
			<?php }?>			
		      	
</table>

<input type="image" src="images/next.png" class="next_but"/>
<br/>
<?php }?>
<?php if ($membershipCount > 0){?>
<a name="monthly_memberships"></a>
<h3>Membership Plans</h3>
<p class="rightTxt3">
	By purchasing a membership plan, you may use up to a certain maximum number of queries each month, this is recommended if you use Whois API on a regular basis.  You may cancel/change your plan anytime.
	<!--<br/><b>Attention membership subscribers:</b> Paypal requires you to link&confirm your credit card to a new paypal account for subscription, the confirmation process may take 2-3 days.  Therefore we recommend choosing <a class="ignore_jssm" href="#pay_as_you_go">"pay as you go"</a>  as the simplest option if you don't already have a paypal account which is linked to a confirmed credit card account/bank account.
	-->
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
		      	
</table>

</form>
<br/>
<?php }?>

<a name="whois_api_client"></a>
<?php if(isset($wc_order_error)){
	show_error($wc_order_error);
}
?>
<h3>Bulk Whois Client Application</h3>
<p class="rightTxt3">
	Bulk Whois Client Application is a desktop graphical user interface application that communicates with Whois API Webservice. It allows users to bulk load, mass query, import and export whois data.
	All licenses are non-redistributable.  All minor updates(binary or source code) within one major version are for free.
	The source code for Bulk Whois Client is under the terms and conditions of a separate <a class="ignore_jssm" href="wc_sourcecode_license.php" target="_wc_src_lic">Source Code License Agreement</a>.
</p>
<form id='wpc_form' name="wpc_form" action="order_process.php" class="ignore_jssm">
<input type="hidden"  name="order_type" value="wc"/>
<input  name="pay_choice" type="hidden" value="pp"/>
		<div>
			<label for="wc_order_username" class="description">Username of the account to make purchase for:</label>
			<input type="text"  maxlength="255" class="element text medium" name="wc_order_username" id="wc_order_username" value="<?php echo $wc_order_username?>"/>
		</div>	
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
                	<td align="right" class="evcell">1-5 users $99/license<br/>6-10 users $89/license</td>
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
	We provide archived historic whois database in both parsed and raw format for download as CSV files. Data will be delievered between 2 to 10 business days(depending on the volume).  
	The database is sorted in ascending alphabetical order.  You may choose to download the whois records with domain names starting with any alphabet letter or digit.  
	You may also choose the tlds.  For example, download 10 million whois records with .com and .net domain names starting from letter 'a'.

</p>
<form id='wdb_form' name="wdb_form" action="order_process.php" class="ignore_jssm">
<input type="hidden"  name="order_type" value="wdb"/>
<input  name="pay_choice" type="hidden" value="pp"/>

		<div>
			<label for="wdb_prefix class="description">Download starts with the letter or digit:</label>
		
			<input type="text"  maxlength="1" class="element text tiny" name="wdb_prefix" id="wdb_prefix" value="<?php echo $wdb_prefix?>"/> (optional)
		</div>	
<br/>
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
                	<td align="right" class="<?php echo $cl?>"><?php echo number_format($dbAmount[$i])?> million</td>
                	<td align="right" class="<?php echo $cl?>"><?php echo discount($dbRawPrices[$dbAmount[$i]], $dbDiscount) ?></td>
                	<td align="right" class="<?php echo $cl?>"><?php echo discount($dbParsedPrices[$dbAmount[$i]], $dbDiscount) ?></td>
              	</tr>
			<?php }?>	
			
		      	<tr>
                	<td colspan="2" align="right" class="<?php echo $cl?>"> > 5 million</td>
                	<td align="right" class="<?php echo $cl?>"><a href="mailto:support@whoisxmlapi.com">contact us</a></td>
                	<td align="right" class="<?php echo $cl?>"><a href="mailto:support@whoisxmlapi.com">contact us</a></td>
              	</tr>	
		      	<tr>
                	<td colspan="2" align="right" class="<?php echo $cl?>"> The complete database(125 million records)</td>
                	<td align="right" class="<?php echo $cl?>"><a href="mailto:support@whoisxmlapi.com">contact us</a></td>
                	<td align="right" class="<?php echo $cl?>"><a href="mailto:support@whoisxmlapi.com">contact us</a></td>
              	</tr>              	
</table>
<br/>            
<input type="image" src="images/next.png" class="next_but"/>

<?php }?>

</form>
<br/>



<h3>Payment Policy</h3>


	
		<span><img src="images/paypal_cc.png"/><br> <b style="color:#444444">Paypal accepts credit card</b></span>
	
	

	

<p class="rightTxt3">
All transactions are done via paypal for safety and security. Unused Query purchases never expire but are not refundable.   You may change or cancel your membership at any time by simply <a class="ignore_jssm" href ="mailto:support@whoisxmlapi.com">sending us an email</a> with your username.  Please <a class="ignore_jssm" href ="mailto:support@whoisxmlapi.com">contact us</a> if you encounter any issue with the checkout proccess.<br/>


</p>

<span> <b style="color:#444444">Need an alternative payment option?</b></span>
<p class="rightTxt3">
Please <a class="ignore_jssm" href ="mailto:support@whoisxmlapi.com">send us an email</a> for instruction on how to pay by check, wire-transfer, or other options
</p>

	
</div>

<script type="text/javascript">
	$(document).ready(function(){
		$('#num_user_license').blur(function(evt){
			fix_user_license_price();
		});
		$('#wpc_form').on('submit',function(evt){
			fix_user_license_price();
		});
	});
	function fix_user_license_price(){
			var n = get_num($('#num_user_license').val());
			$('#num_user_license').val(n);
			var price = comp_user_license_price(n);
			$('#user_license_total_price').text(price);
	}
	function get_num(n){
		n = parseInt(n);
		if(!n)return 1;
		return n;
	}
	function comp_user_license_price(n){
		
		if(n<6)return n*99;
		return n*89;
	}
</script>
