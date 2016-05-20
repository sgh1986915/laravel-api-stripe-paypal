<?php require_once __DIR__ . "/bulk-whois-lookup-price.php";
	$bwl_speed=$_REQUEST['bwl_speed'];
	if(!$bwl_speed)$bwl_speed="regular";
	$num_domains=$_REQUEST['num_domains'];
	if(!$num_domains)$num_domains=500000;
	
?>
	<table cellspacing="1" cellpadding="7" border="0" class="colored" align="center">
              <tr>
              	<?php if(isset($include_choice)) echo "<td class=\"header\" width=\"10px\"></td>";?>
                <td align="right" class="header">Number of domain names</td>        
                <td align="right" class="header" colspan="3">
                	Price & Time to completion*
                	<table>
                		<tr>
                			<td> <input name="bwl_speed" type="radio" value="regular" <?php echo $bwl_speed=='regular'?'checked':''?>/> Regular </td>
                			<td> <input name="bwl_speed" type="radio" value="expedited" <?php echo $bwl_speed=='expedited'?'checked':''?>/> Expedited</td>
                		</tr>
                	</table>
                </td>
                
              </tr>		
			  <?php 
			  	$i=0;
			  	foreach($regular_bwl_prices as $amount=>$price){
			  		$price2=$expedited_bwl_prices[$amount];
			  		//$avg_price = $queryPrices[$queryAmount[$i]] / $queryAmount[$i] * 1000;
					$cl = ($i%2==0?"evcell":"oddcell");
					
					if(isset($include_choice))$checked = ($amount==$num_domains);
				
				?>
              	<tr>
              		<td align="right" class="<?php echo $cl?>"><?if($include_choice){ ?>
              			 <input type="radio" value="<?php echo $amount;?>" name="num_domains" <?php echo $checked?"checked":"";?> /> 
              		<?}?>	 
              		</td>
                	<td align="right" class="<?php echo $cl?>"><?php echo number_format($amount)?></td>
                	
                	<td align="right" class="<?php echo $cl?>">
                		
                		<b>$<?php echo $price ?></b><br/>
                		<?php echo $regular_bwl_speed[$amount]?> days
                	</td>
                	<td align="right" class="<?php echo $cl?>">
                		<b>$<?php echo $price2 ?></b><br/>
                		<?php echo $expedited_bwl_speed[$amount]?> days
                		
                	</td>
              	</tr>
			<?php 
				$i++;
				$cl = ($i%2==0?"evcell":"oddcell");
			  }
			?>
			<tr>
				<?php if(isset($include_choice)) echo "<td class=\"$cl\"></td>";?>
				<td align="right" class="<?php echo $cl?>">
					> $<?php echo number_format($amount);?>
				</td>
				<td align="right" class="<?php echo $cl?>" colspan="3">
					<a href="mailto:support@whoisxmlapi.com">Contact Us</a>
				</td>
			</tr>
             <tr>
             	<td colspan="5" class="oddcell">
             		Note: time to completion are measured in business days.
             	</td>
             </tr>
     </table>   