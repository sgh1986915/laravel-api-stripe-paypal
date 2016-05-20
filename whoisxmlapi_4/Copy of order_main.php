<?php

	require_once "price.php";
	require_once "httputil.php";
	require_once "util.php";
	
	require_once "users/utils.inc";
	require_once "users/users.inc";
?>
<?php
	$pay_choice = get_from_post_get("pay_choice");
	if(!$pay_choice) $pay_choice = "pp";
	$query_quantity = get_from_post_get("query_quantity");
	if(!$query_quantity) $query_quantity = 25000;
	my_session_start();
	$order_username = "";
	if(isset($_REQUEST['order_username'])){
		$order_username = $_REQUEST['order_username'];
	}
	else if(isset($_SESSION['myuser'])){
		$order_username = $_SESSION['myuser']->username;
		
	}

	
?>
<p class="rightTop"/>
<?php if(isset($order_error)){
	show_error($order_error);
}
?>
<div class="right_sec">
<h3>Secure Order Form</h3>
<div class="rightTxt3">
	We offer 2 pricing models, <a href="#pay_as_you_go">pay as you go</a> or <a href="monthly_memberships">monthly memberships</a>.
	<ul><li>
		pricing model is based on the amount of whois queries you need.  
	</li>
		<li>
			
		</li>
	</ul>	
</div>
<form action="order_process.php" class="ignore_jssm">
		<!--<input type="hidden" name="sandbox" value="1"/>-->
		<div>
			<label for="order_username" class="description">Username of the account to make purchase for:</label>
			<input type="text"  maxlength="255" class="element text medium" name="order_username" id="order_username" value="<?php echo $order_username?>"/>
		</div>	
<h3>Pay as you go Purchase Plan</h3>
<p>
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
<h3>Membership Plans</h3>
<p>
	By purchasing a membership plan, you may use up to a certain maximum number of queries each month, this is recommended if you use Whois API on a regular basis.  You may cancel/change your plan anytime.
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
            				<input type="submit" value="Bill Monthly" class="button" name="<?php echo 'bill_monthly_' . $membershipAmount[$i] ?>"/>  
						
					</td>
					
                	<td align="right" class="<?php echo $cl?>">
                		
							<b>$<?php echo 	10 * $membershipPrices[$membershipAmount[$i]]?></b>
        		
            				<input type="submit" value="Bill Yearly" class="button" name="rebill_month_Bronze"/>  
						
					</td>
										
              	</tr>
			<?php }?>			
		      	
</table>



<h3>Payment Policy</h3>


	
		<span><img src="images/paypal_cc.png"/><br> <b>paypal accepts credit card</b></span>
	
	
	<input id="choice_cc" name="pay_choice" type="hidden" value="paypal"/>
	


<p class="fineprint">
All transactions are done via paypal for safety and security. Unused Query purchases never expire but are not refundable.   You may change or cancel your membership at any time by simply sending an email with your username to support@whoisxmlapi.com.
</p>



</form>		
</div>