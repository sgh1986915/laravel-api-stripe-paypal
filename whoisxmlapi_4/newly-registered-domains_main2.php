<?php require_once "users/users.conf";?>
<p class="rightTop"></p>
<h2>Newly Registered Domains & Just Expired Domains</h2> 
<p class="rightTxt1">
	We provide Newly Registered Domains and Just Expired Domains as daily downloads.  Whois data are also provided as an option.  
	&nbsp;&nbsp;Supported tlds are ".com, .net, .org, .us, .biz, .mobi".  &nbsp;&nbsp;
	Simply check the edition you want and click on next to purchase.
</p>

<?php require_once "price.php";
$features = array("Newly Registered Domains"=>array(true, true, true),
"Just Expired Domains" => array(false, true, true),
"Daily downloads" => array(true, true, true),
//"Daily email list" => array(false, false, true),
"Whois Data" => array(false, false, true)
);

$spk_sel = (isset($_REQUEST['data_edition']) ? $_REQUEST['data_edition'] : 1);
$spks = array( "Bronze", "Silver", "Gold");

function wrap($s){
	if($s === true){
		return "<img src=\"images/check.gif\">";
	}
	else if ($s === false) return "";
	return $s;
}
$form_action="order_process.php";
if(isset($cc)){
	$form_action="cc.php";
}
?>
<form action="<?php echo $form_action?>" class="ignore_jssm">
<input type="hidden" name="order_type" value="domain_data"/>
<p class="rightTop"/>
<div class="right_sec">
<p class="rightTxt3">
	
</p>


<table cellspacing="1" cellpadding="7" border="0" class="colored" align="center">
              <tr>
                <td align="left" class="header">Features</td>
                <?php for($i=0;$i<count($spks);$i++){
                	
                	$spk =$spks[$i];
                
                	$sel = ($spk_sel == $i ? "checked" : "");
                	 echo "<td align=\"center\" class=\"header\"><div style=\"padding-bottom:3px\"><input type=\"radio\" name=\"data_edition\" value=\"$i\" $sel/></div>$spk</td>";
                }?>
               
              </tr>
			  <?php 
			  	$i = 0;
			  	foreach($features as $key=>$val){
			  	
					$cl = ($i%2==0?"evcell":"oddcell");
					$i++;
					$help = "";
					if($helps[$key]){ 
						$help="<span style=\"padding-left:5px\"><image title=\"" . $helps[$key] . "\" src=\"images/help_16x16.gif\"></span>";
					}
				?>
              	<tr>
              	
                	<td align="left" class="<?php echo $cl?>"><?php echo $key  . $help ?></td>
                	<td align="center" class="<?php echo $cl?>"><?php echo wrap($val[0])?></td>
                	<td align="center" class="<?php echo $cl?>"><?php echo wrap($val[1])?></td>
                	<td align="center" class="<?php echo $cl?>"><?php echo wrap($val[2])?></td>
              	</tr>
			<?php }?>

             <?php
			
				$cl = ($i%2==0?"evcell":"oddcell");
			?>
              	             				
              	<tr>
              	
                	<td align="left" class="<?php echo $cl?>" style="font-weight:bold">Price/Month</td>
                	<?php for($i=0;$i<count($domainNameDataPrices);$i++){
                		$spkPriceDisp = '$'.$domainNameDataPrices[$i];
                		if($domainNameDataDiscounts[$i] > 0){
                
                			$discountPrice = sprintf("%d", $domainNameDataPrices[$i] * (1.0-$domainNameDataDiscounts[$i]));
                			$discountPerc = sprintf("%d",$domainNameDataDiscounts[$i] * 100);
                			
                			$spkPriceDisp = "<div style=\"color:red\" title=\"$discountPerc% off\"><del >" . $spkPriceDisp. "</del><br/>". "$".$discountPrice . " </div>";
                		}
                		
                	?>
                		<td align="center" class="<?php echo $cl?>" style="font-weight:bold">
                			<?php echo $spkPriceDisp ?>
                	
                		</td>
                
              		<?php }?>
              	</tr>	
              	
              			
		      
</table>
<!--
<p style="color:red;font-weight:bold;">
	Promotional discount is only available until end of this month!
</p>
-->

<br/>
<input type="image" class="next_but" src="images/next.png">


<a name="license_terms"></a>
<h3>License Terms</h3>
<p class="rightTxt3">
	All domain name data/list provided are Non-redistributable.  It means that you may not redistribute or resell these data to other parties.
</p>



<h3>Payment Policy</h3>


	
		<span><img src="images/paypal_cc.png"/><br> <b style="color:#444444">Paypal accepts credit card</b></span>
	
	
	<input id="choice_cc" name="pay_choice" type="hidden" value="pp"/>
	

<p class="rightTxt3">
All transactions are done via paypal for safety and security. 
Please <a class="ignore_jssm" href ="mailto:support@whoisxmlapi.com">contact us</a> if you encounter any issue with the checkout proccess.<br/>


</p>



	
</div>

</form>




















