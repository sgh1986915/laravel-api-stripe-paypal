<?php require_once __DIR__ . "/domain-ip-database-price.php";

?>
<table cellspacing="1" cellpadding="7" border="0" class="colored" align="center">
              <tr>
               
                <td align="right" class="header"  colspan="2" >Number of domain name to ip address mappings</td>        
                <td align="right" class="header">Price</td>
              
              </tr>
            
              
              
			  <?php 
			  	$dip_db_quantity=$_REQUEST['dip_db_quantity'];
			  	if(!$dip_db_quantity)$dip_db_quantity=1;
			  
			  	for($i=0;$i<$dipDBCount;$i++){
			  		//$avg_price = $dipDBPrices[$dipDBAmount[$i]] / $dipDBAmount[$i] * 1000;
					$cl = ($i%2==0?"evcell":"oddcell");
					$checked = ($dipDBAmount[$i]==$dip_db_quantity);
				?>
              	<tr>
              		<?php
              			if($dip_db_show_input){?>
              			<td align="right" class="<?php echo $cl?>">
              				<input type="radio" value="<?php echo $dipDBAmount[$i];?>" name="dip_db_quantity" <?php echo $checked?"checked":"";?> />
              			</td>
              				
              		<?php	
              			}
              		?>
                	<td <?php if(!$dip_db_show_input)echo "colspan=\"2\"";?> align="right" class="<?php echo $cl?>"><?php echo number_format($dipDBAmount[$i])?> million (randomly chosen)</td>
                	<td align="right" class="<?php echo $cl?>"><?php echo discount($dipDBPrices[$dipDBAmount[$i]],$dbDiscount) ?></td>
              	</tr>
			<?php }
			
			?>	
				
		      	<tr>
                	<td colspan="2" align="right" class="oddcell"> > <?php echo number_format($dipDBAmount[$dipDBCount-1])?> million</td>
                	<td colspan="2" a align="right" class="oddcell"><a onclick="_gaq.push(['_trackEvent', 'mailto', 'clicked']);" href="mailto:support@whoisxmlapi.com" class="ignore_jssm">contact us</a></td>
              	</tr>	
		      	<tr>
                	<td colspan="2" align="right" class="<?php echo $cl?>"> The complete database(155 million records)</td>
                	<td colspan="2" align="right" class="<?php echo $cl?>"><a onclick="_gaq.push(['_trackEvent', 'mailto', 'clicked']);"  href="mailto:support@whoisxmlapi.com" class="ignore_jssm">contact us</a></td>
              	</tr>   
              	<tr>
                	<td  colspan="2"  align="right" class="oddcell"> Yearly Subscription(4 quarterly downloads/year) of complete databases</td>
                	<td  colspan="2"  align="right" class="oddcell"><a onclick="_gaq.push(['_trackEvent', 'mailto', 'clicked']);"  href="mailto:support@whoisxmlapi.com" class="ignore_jssm">contact us</a></td>
              	</tr>
              	
              	<tr>
                	<td  colspan="2" align="right" class="evcell"> All Historic snapshots of the domain ip databases </td>
                	<td  colspan="2"  align="right" class="evcell"><a onclick="_gaq.push(['_trackEvent', 'mailto', 'clicked']);"  href="mailto:support@whoisxmlapi.com" class="ignore_jssm">contact us</a></td>
              	</tr>               	            	
</table>