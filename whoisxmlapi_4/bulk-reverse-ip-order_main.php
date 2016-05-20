<?php

	require_once __DIR__. "/reverse-ip/prices.php";
	require_once __DIR__. "/httputil.php";
	require_once  __DIR__. "/util.php";
	
	require_once  __DIR__. "/users/utils.inc";
	require_once  __DIR__. "/users/users.inc";
	
	
	
?>
<?php
	if(!session_id())session_start();
	
	global $group_license_price, $source_license_price;
	
	$pay_choice = get_from_post_get("pay_choice");
	if(!$pay_choice) $pay_choice = "pp";
	$query_quantity = get_from_post_get("query_quantity");
	if(!$query_quantity) $query_quantity = 10000;
	my_session_start();
	$order_username = "";
	if(isset($_REQUEST['order_username'])){
		$order_username = $_REQUEST['order_username'];
	}
	else if(isset($_SESSION['myuser'])){
		$order_username = $_SESSION['myuser']->username;
		
	}
	
	

	
	
?>

<?php if(isset($_REQUEST['goto']) && $_REQUEST['goto']){?>
	<script type ="text/javascript">
		$(document).ready(function(){
			location.href=location.href+"#<?php echo $_REQUEST['goto']?>";	
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
<p class="rightTxt3">
	We offer 2 pricing models for bulk reverse ip credits, <a class="ignore_jssm" href="#pay_as_you_go">pay as you go</a> or <a class="ignore_jssm"  href="#monthly_memberships">monthly memberships</a>. 
	Each  reverse ip lookup result cost 1 reverse ip lookup credit.
</p>
<form action="bulk-ri-order_process.php" class="ignore_jssm">
	<input id="choice_cc" name="pay_choice" type="hidden" value="pp"/>
		<!--<input type="hidden" name="sandbox" value="1"/>-->
		<div>
			<label for="order_username" class="description">Username of the account to make purchase for:</label>
			<input type="text"  maxlength="255" class="element text medium" name="order_username" id="order_username" value="<?php echo $order_username?>" />
		</div>	
<a name="pay_as_you_go"></a>
<h3>Pay as you go Purchase Plan</h3>

<p class="rightTxt3">
	Simply purchase the number of reverse ip credits you require and they will be added to your account instantly.
	<!--You will receive a notification email before your account reaches empty.-->  You can buy more credits or 
	replenish your account any time.
</p>
<table cellspacing="1" cellpadding="7" border="0" class="colored" align="center">
              <tr>
			  	
                <td colspan="2" align="right" class="header">Number of credits</td>
           		<td align="right" class="header">Price/Credit</td>
                <td align="right" class="header">Price (USD)</td>
              </tr>
			  <?php for($i=0;$i<$riQueryCount;$i++){
			  		$avg_price = $riQueryPrices[$riQueryAmount[$i]] / $riQueryAmount[$i] ;
					$cl = ($i%2==0?"evcell":"oddcell");
					$checked = ($riQueryAmount[$i]==$query_quantity);
				
				?>
              	<tr>
              		<td align="right" class="<?php echo $cl?>"><input type="radio" value="<?php echo $riQueryAmount[$i];?>" name="query_quantity" <?php echo $checked?"checked":"";?> /></td>
                	<td align="right" class="<?php echo $cl?>"><?php echo number_format($riQueryAmount[$i])?></td>
                	<td align="right" class="<?php echo $cl?>">$<?php echo $avg_price?></td>
                	<td align="right" class="<?php echo $cl?>"><b>$<?php echo $riQueryPrices[$riQueryAmount[$i]]?></b></td>
              	</tr>
			<?php }
			
			$cl = ($i%2==0?"evcell":"oddcell");	?>			
		      <tr>
              		<td align="right" class="<?php echo $cl?>"></td>
                	<td align="right" class="<?php echo $cl?>">><?php echo number_format($riQueryAmount[$riQueryCount-1])?></td>
                	<td align="right" class="<?php echo $cl?>">Bulk Discount</td>
                	<td align="right" class="<?php echo $cl?>"><b><a href="mailto:support@whoisxmlapi.com" class="ignore_jssm">Contact Us</a></b></td>
              	</tr>
		      	
		      	
</table>

<input type="image" src="images/next.png" class="next_but"/>
<br/>

<a name="monthly_memberships"></a>
<h3>Membership Plans</h3>
<p class="rightTxt3">
	By purchasing a membership plan, you may use a fixed number of Credit each month, this is recommended if you use Whois API on a regular basis.  You may cancel/change your plan anytime.
	
</p>
<table cellspacing="1" cellpadding="7" border="0" class="colored" align="center">
              <tr>
			  	
                <td  width="30%" align="right" class="header">Number of credits/month</td>
           <td align="right" class="header">Price/Credit</td>
                <td align="right" class="header">Price/Month </td>
				<td align="right" class="header">Price/Year </td>
              </tr>
			  <?php for($i=0;$i<$riMembershipCount;$i++){
			  		$avg_price =  $riMembershipAmount[$i] >0 ? $riMembershipPrices[$riMembershipAmount[$i]] / $riMembershipAmount[$i] : 0;
					$cl = ($i%2==0?"evcell":"oddcell");
					$checked = (strcmp($riMembershipAmount[$i], $riMembershipAmount[$i]) == 0);
					$yearlyPrice=10 * $riMembershipPrices[$riMembershipAmount[$i]];
				?>
              	<tr>
              		<?php if($riMembershipAmount[$i] == 'unlimited'){?>
              			<td colspan="2" align="right" class="<?php echo $cl?>">* Unlimited</td>
              		<?php 
              		}else {?>
              			
                		<td align="right" class="<?php echo $cl?>"><?php echo number_format($riMembershipAmount[$i])?></td>
                		<td align="right" class="<?php echo $cl?>">$<?php echo $avg_price?></td>
                	<?php }?>
                	<td align="right" class="<?php echo $cl?>">
                		
						
        				<b>$<?php echo $riMembershipPrices[$riMembershipAmount[$i]]?></b> 
            				<input type="submit" value="Bill Monthly" class="mbutton" name="<?php echo 'bill_monthly_' . $riMembershipAmount[$i] ?>"/>  
						
					</td>
					
                	<td align="right" class="<?php echo $cl?>">
                		
							<b>$<?php echo 	$yearlyPrice?></b>
        				<?php if ($yearlyPrice>10000){?>
        					<br/><a href="mailto:support@whoisxmlapi.com" class="ignore_jssm">Contact Us</a>
        					
        				<?php } else{?>
            				<input type="submit" value="Bill Yearly" class="mbutton" name="<?php echo 'bill_yearly_' . $riMembershipAmount[$i] ?>"/>   
						<?php }?>
					</td>
											
              	</tr>
			<?php }
				$cl = ($i++%2==0?"evcell":"oddcell");
			
			?>			
		   		
              
              	<!--
              	<tr>
              		
                	<td align="right" colspan="2" class="<?php echo $cl?>">Custom Plans</td>
                	
                	<td align="center" colspan=3 class="<?php echo $cl?>"><b><a href="mailto:support@whoisxmlapi.com" class="ignore_jssm">Contact Us</a></b></td>
              	</tr> 
              	-->
              	<tr>
              		
                	<td align="right" colspan="4" class="<?php echo $cl?>">
                	
                	*user of the unlimited plan may not exceed the query rate of 1 query per  second
                	
              	</tr> 
              	  	
</table>

</form>
<br/>


      


</form>
<br/>
<h3>Payment Policy</h3>


	
		<span><img src="images/paypal_cc.png"/><br> <b style="color:#444444">Paypal accepts credit card</b></span>
	
	

	

<p class="rightTxt3">
All transactions are done via paypal for safety and security. Unused credits never expire but are not refundable.   You may change or cancel your membership at any time by simply <a class="ignore_jssm" href ="mailto:support@whoisxmlapi.com">sending us an email</a> with your username.  Please <a class="ignore_jssm" href ="mailto:support@whoisxmlapi.com">contact us</a> if you encounter any issue with the checkout proccess.<br/>


</p>

<span> <b style="color:#444444">Need to write a check?</b></span>
<p class="rightTxt3">
Please <a class="ignore_jssm" href ="mailto:support@whoisxmlapi.com">send us an email</a> for instruction on how to pay by check, wire-transfer, or other options
</p>

	
</div>

