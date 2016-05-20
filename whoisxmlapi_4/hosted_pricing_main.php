<?php
    require_once __DIR__."/price.php";
    require_once __DIR__ ."/httputil.php";
?>
<p class="rightTop"/>
<h2>Pricing of hosted whois webservice</h2>
<div class="right_sec">
<p class="rightTxt3">
	We offer 2 pricing models, pay as you go or monthly memberships.    <a href="<?php echo build_ssl_url('order_paypal.php')?>" class="ignore_jssm" title="Order Now">Order Now!</a>
	
</p>

	
<a name="pay_as_you_go"></a>
<h3>Pay as you go Purchase Plan</h3>

<p class="rightTxt3">
	Simply purchase the number of whois queries you require and they will be added to your account instantly.
	You will receive a notification email before your account reaches empty.  You can buy more queries or 
	replenish your account any time.  <a class="ignore_jssm" href="<?php echo build_ssl_url('order_paypal.php#pay_as_you_go')?>" class="ignore_jssm" title="Order Now">Order Now!</a>
</p>

<table cellspacing="1" cellpadding="7" border="0" class="colored" align="center">
              <tr>
                <td align="right" class="header">Number of queries</td>
                <td align="right" class="header">Price per thousand</td>
                <td align="right" class="header">Price (USD)</td>
              </tr>
			  <?php for($i=0;$i<$queryCount;$i++){
			  		$avg_price = $queryPrices[$queryAmount[$i]] / $queryAmount[$i] * 1000;
					$cl = ($i%2==0?"evcell":"oddcell");
				?>
              	<tr>
                	<td align="right" class="<?php echo $cl?>"><?php echo number_format($queryAmount[$i])?></td>
                	<td align="right" class="<?php echo $cl?>">$<?php echo $avg_price ?></td>
                	<td align="right" class="<?php echo $cl?>"><b>$<?php echo $queryPrices[$queryAmount[$i]]?></b></td>
              	</tr>
			<?php }
				$cl = ($i%2==0?"evcell":"oddcell");
			?>	
			
		      	<tr>
                	<td align="right" class="<?php echo $cl?>">> <?php echo number_format($queryAmount[$queryCount-1]);?></td>
                	<td align="right" class="<?php echo $cl?>">customized</td>
                	<td align="right" class="<?php echo $cl?>"><a href="mailto:support@whoisxmlapi.com">contact us</a></td>
              	</tr>	
</table>


<br/>
<a name="monthly_memberships"></a>
<h3>Membership Plans</h3>
<p class="rightTxt3">
	By purchasing a membership plan, you may use up to a certain maximum number of queries each month, this is recommended if you use Whois API on a regular basis.  You may cancel/change your plan anytime.  <a class="ignore_jssm" title="Order Now" href="<?php echo build_ssl_url('order_paypal.php#pay_as_you_go')?>">Order Now!</a>
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
            				
						
					</td>
					
                	<td align="right" class="<?php echo $cl?>">
                		
							<b>$<?php echo 	10 * $membershipPrices[$membershipAmount[$i]]?></b>
        		
            		
					</td>
										
              	</tr>
			<?php }
				$cl = ($i%2==0?"evcell":"oddcell");
			?>			
		     <tr>
				
                	<td align="right" class="<?php echo $cl?>"> > <?php echo number_format($membershipAmount[$membershipCount-1]);?></td>
                	
                	<td align="center" colspan="2" class="<?php echo $cl?>"><a href="mailto:support@whoisxmlapi.com" class="ignore_jssm">Bulk Discount, Contact Us</a></td>
              	</tr> 	
</table>



<h3>Payment Policy</h3>


	
		<span><img src="images/paypal_cc.png"/><br> <b style="color:#444444">Paypal accepts credit card</b></span>
	
	
	<input id="choice_cc" name="pay_choice" type="hidden" value="pp"/>
	

<p class="rightTxt3">
All transactions are done via paypal for safety and security. Unused Query purchases never expire but are not refundable.   You may change or cancel your membership at any time by simply <a class="ignore_jssm" href ="mailto:support@whoisxmlapi.com">sending us an email</a> with your username.  Please <a class="ignore_jssm" href ="mailto:support@whoisxmlapi.com">contact us</a> if you encounter any issue with the checkout proccess.<br/>


</p>



	
</div>

