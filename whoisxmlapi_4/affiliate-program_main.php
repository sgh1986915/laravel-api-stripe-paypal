<?php
require_once "price.php";
?>

<p class="rightTop"></p>
<h1>Affiliate Program</h1> 
<!-- 
<div class="rightTxt2">
	<div style="color:red;font-weight:bold;">Interested in buying us out?   Please <a href="mailto:dev@whoisxmlapi.com" class="ignore_jssm" style="color:inherit;text-decoration:none;">contact us</a> </div> 
	<a href="ceo-message.php" style="text-decoration:none;">Why are we selling?</a>
</div>

<div class="rightTxt2">
	<div style="font-weight:bold;">Interested in a strategic partnership?  Interested in licensing the complete source code behind Whois API?   
	Are you a registrar or business that need to setup and manage public whois servers? 
	Do you need to outsource your organization's whois or domain management services?
	
	Please <a href="mailto:dev@whoisxmlapi.com" class="ignore_jssm" style="color:inherit;text-decoration:none;">contact us for
	a customized quote</a></div> 
</div>
-->
<div class="rightTxt2">
	Join the whoisxmlapi.com affiliate program administered through <a class="ignore_jssm" href="http://www.clickbank.com">clickbank</a>.  We offer affiliate program on the following products with 10% commission for our affiliates.
	due to limitation imposed by clickbank we are currently only able to offer the following product tiers, future expansion on higher product tiers is possible.
	<a class="ignore_jssm" href="mailto:general@whoisxmlapi.com"> Contact us</a> for detail on how to join.
	
	<div class="styleh3">
		Whois API Queries
	</div>
	<p style="font-weight:bold">
		Pay as you go plan
	</p>
	<table cellspacing="1" cellpadding="7" border="0" class="colored" align="center">
              <tr>
			  	
                <td  align="right" class="header">Number of queries</td>
           
                <td align="right" class="header">Price (USD)</td>
                
              </tr>
			  <?php 
			  	for($i=0;$i<3;$i++){
			  		$avg_price = $queryPrices[$queryAmount[$i]] / $queryAmount[$i] * 1000;
					$cl = ($i%2==0?"evcell":"oddcell");
					$checked = ($queryAmount[$i]==$query_quantity);
				
				?>
              	<tr>
              	
                	<td align="right" class="<?php echo $cl?>"><?php echo number_format($queryAmount[$i])?></td>
                	
                	<td align="right" class="<?php echo $cl?>"><b>$<?php echo $queryPrices[$queryAmount[$i]]?></b></td>
              	</tr>
			<?php }
				$cl = ($i%2==0?"evcell":"oddcell");
			?>	
			
</table>
	<p style="font-weight:bold">
		Monthly plans
	</p>
<table cellspacing="1" cellpadding="7" border="0" class="colored" align="center">
              <tr>
			  	
                <td   align="right" class="header">Maximum number of queries/month</td>
           
                <td align="right" class="header">Price/Month (USD)</td>
              </tr>
			  <?php for($i=0;$i<2;$i++){
			  		$avg_price = $membershipPrices[$membershipAmount[$i]] / $membershipAmount[$i] * 1000;
					$cl = ($i%2==0?"evcell":"oddcell");
					$checked = (strcmp($membershipAmount[$i], $membershipAmount[$i]) == 0);
				
				?>
              	<tr>
              	
                	<td align="right" class="<?php echo $cl?>"><?php echo number_format($membershipAmount[$i])?></td>
                	
                	<td align="right" class="<?php echo $cl?>">
                		
				
        				<b>$<?php echo $membershipPrices[$membershipAmount[$i]]?></b> 
            				
						
					</td>
					
                	
										
              	</tr>
			<?php }?>			
		      
</table>
	
</div>




