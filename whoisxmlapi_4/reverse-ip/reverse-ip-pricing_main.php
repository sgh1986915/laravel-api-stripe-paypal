<?php require_once dirname(__FILE__).'/prices.php';
function discount($price, $discount){
	if($discount<=0)return "$" . $price;
	$new_price = $price * (1-$discount);
	$per = $discount * 100;
	$s = "<div style=\"color:red; font-weight:bold;background:transparent;\"><del>$" . "$price</del><br/> $" . "$new_price ($per % off)</div>";
	return $s;
}

?>
<br/>
<h1>
Reverse IP Lookup Pricing
</h1>
<br/>
<p class="rightTxt3">
There are 2 ways you can pay for Reverse IP reports: <br/>
<ol>
	<li> Purchase credits in bulk.  
		Each report cost 1 Reverse IP lookup credit. Credits can be purchased using either <a class="ignore_jssm" href="#ri_pay_as_you_go"> pay-as-you-go plan</a> or <a class="ignore_jssm" href="#ri_monthly_memberships">monthly plan</a>.
	</li>
	<li>
		Pay for a individual report using <a class="ignore_jssm" href="#standard_reverse_whois_report_pricing"> standard report pricing </a>.
		
	</li>
</ol>
Purchasing credits in bulk yields a very siginicant saving over the individual report pricing model.


</p>
<a name="ri_pay_as_you_go"></a>
<h3>Bulk "Pay as you go" Purchase Plan</h3>

<p class="rightTxt3">
	Simply purchase the number of Reverse IP credits you require and they will be added to your account instantly.
	You will receive a notification email before your account reaches empty.  You can buy more queries or 
	replenish your account any time.  <a class="ignore_jssm" href="<?php echo  $app_root . '/bulk-reverse-ip-order.php#pay_as_you_go'?>" class="ignore_jssm" title="Order Now">Order Now!</a>
</p>

<table cellspacing="1" cellpadding="7" border="0" class="colored" align="center">
              <tr>
                <td align="right" class="header">Number of credits</td>
                <td align="right" class="header">Price per credit</td>
                <td align="right" class="header">Price (USD)</td>
              </tr>
              
			  <?php 
			  
			  	for($i=0;$i<$riQueryCount;$i++){
			  		$avg_price = $riQueryPrices[$riQueryAmount[$i]] / $riQueryAmount[$i] ;
					$cl = ($i%2==0?"evcell":"oddcell");
				?>
              	<tr>
                	<td align="right" class="<?php echo $cl?>"><?php echo number_format($riQueryAmount[$i])?></td>
                	<td align="right" class="<?php echo $cl?>">$<?php echo $avg_price ?></td>
                	<td align="right" class="<?php echo $cl?>"><b>$<?php echo $riQueryPrices[$riQueryAmount[$i]]?></b></td>
              	</tr>
			<?php }
					$cl = ($riQueryCount%2==0?"evcell":"oddcell");
			?>	
			
		      	<tr>
                	<td align="right" class="<?php echo $cl?>">> <?php echo $riQueryAmount[$riQueryCount-1] ?></td>
                	<td align="right" class="<?php echo $cl?>">customized</td>
                	<td align="right" class="<?php echo $cl?>"><a href="mailto:support@whoisxmlapi.com">contact us</a></td>
              	</tr>	
</table>


<br/>
<a name="ri_monthly_memberships"></a>
<h3>Bulk Membership Plans</h3>
<p class="rightTxt3">
	By purchasing a membership plan, you may use up to a certain maximum number of Reverse IP credits each month, this is recommended if you use Reverse IP on a regular basis.  You may cancel/change your plan anytime.  <a class="ignore_jssm" href="<?php echo  $app_root . '/bulk-reverse-ip-order.php?#monthly_memberships'?>" title="Order Now">Order Now!</a>
	<!--<br/><b>Attention membership subscribers:</b> Paypal requires you to link&confirm your credit card to a new paypal account for subscription, the confirmation process may take 2-3 days.  Therefore we recommend choosing <a class="ignore_jssm" href="#pay_as_you_go">"pay as you go"</a>  as the simplest option if you don't already have a paypal account which is linked to a confirmed credit card account/bank account.
	-->
</p>
<table cellspacing="1" cellpadding="7" border="0" class="colored" align="center">
              <tr>
			  	
                <td  width="30%" align="right" class="header">Maximum Number of credits/month</td>
           		 <td align="right" class="header">Price/Credit (USD)</td>
                <td align="right" class="header">Price/Month (USD)</td>
				<td align="right" class="header">Price/Year (USD)</td>
              </tr>
			  <?php for($i=0;$i<$riMembershipCount;$i++){
			  		$avg_price = ($riMembershipAmount[$i] > 0 ? $riMembershipPrices[$riMembershipAmount[$i]] / $riMembershipAmount[$i] : 0 );
					$cl = ($i%2==0?"evcell":"oddcell");
					$checked = (strcmp($riMembershipAmount[$i], $riMembershipAmount[$i]) == 0);
				
				?>
              	<tr>
              		<?php if($riMembershipAmount[$i] == 'unlimited'){?>
              			<td colspan="2" align="right" class="<?php echo $cl?>">* Unlimited</td>
              		<?php }else{?>
                	<td align="right" class="<?php echo $cl?>"><?php echo number_format($riMembershipAmount[$i])?></td>
                	<td align="right" class="<?php echo $cl?>">$<?php echo $avg_price?></td>
                	<?php }?>
                	<td align="right" class="<?php echo $cl?>">
                		
				
        				<b>$<?php echo $riMembershipPrices[$riMembershipAmount[$i]]?></b>
            				
						
					</td>
					
                	<td align="right" class="<?php echo $cl?>">
                		
							<b>$<?php echo 	10 * $riMembershipPrices[$riMembershipAmount[$i]]?></b>
        		
            		
					</td>
										
              	</tr>
			<?php }
				$cl = ($riMembershipCount%2==0?"evcell":"oddcell");
			?>
              	
                	
                	<tr>
              		
                	<td align="right" colspan="4" class="<?php echo $cl?>">
                	
                	*user of the unlimited plan may not exceed the query rate of 1 query per second
                	
              	</tr> 
										
              						
		         	
</table>   

<a name="standard_reverse_whois_report_pricing"/>
<h3>
Standard Reverse IP Report Pricing
</h3>
<p class="rightTxt3">
The price of any Reverse IP Report is always $19 disregard the number of domains in the result(up to 1000)
</p>
<table cellspacing="1" cellpadding="7" border="0" class="colored" align="left" width="90%" style="margin-left:5%">
              <tr>
                <td align="right" class="header">Number of Domains in Report</td>
                <td align="right" class="header">Price (USD)</td>
              </tr>
        <?php 
          $n = count($price_interval);
          for($i=0;$i<$n;$i++){
            $num_domains = "";
            if($price_interval[$i] < 0){
              $num_domains = $price_interval[$i-1]. " + ";
            }
            else $num_domains = ($i-1<0?"0":$price_interval[$i-1]) . " - " .$price_interval[$i];
            
            $price = ($current_price[$i] < 1 ? ($current_price[$i] . " per domain") : $current_price[$i]);
           
            $cl = ($i%2==0?"evcell":"oddcell");
        ?>
                <tr>
                  <td align="right" class="<?php echo $cl?>"><?php echo $num_domains?></td>
                  <td align="right" class="<?php echo $cl?>"><?php echo discount($price, $current_price_discount)?></td>
                </tr>
      <?php }?> 

</table>      



<div style="clear:both">

</div>