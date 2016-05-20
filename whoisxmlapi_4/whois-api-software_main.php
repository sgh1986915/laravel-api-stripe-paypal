<?php include "price.php";
$features = array("Raw query"=>array(true, true, true),
"Automatic registry/registrar selection" => array(true, true, true),
"Basic Whois record parsing in XML" => array(false, true, true),
"Advanced Whois record parsing in XML & JSON" => array(false, false, true),
"Domain availability check" => array(false, true, true),
"Scalable Proxy servers support to allow unlimited querying" => array(false, false, true),
"Standardalone Java Whois API Library" => array(true, true, true),
"Number of standalone Whois API installable licenses" => array("1", "2", "5"),
"Running as webservice to support clients in any technology" => array(false, true, true),
"Number of Whois API webservice installable licenses" => array(false, "1", "3"),

);

$helps=array("Basic Whois record parsing in XML" => "created date, expires date, updated date, name servers, stripped text, header, footer",
"Advanced Whois record parsing in XML & JSON" => "detailed contacts parsing including street level address, email address, phone number, etc"
);

$supports = array("Minor version upgrades and updates" => array(true, true, true),
"Help with installation and license issues"=> array(true, true, true),
"Technical support incidents" => array("5", "20", "unlimited"),
"Direct access to key developers" => array(false, true, true),
"Priority response to feature requests" => array(false, false, true)
);

$spk_sel = (isset($_REQUEST['spk_sel']) ? $_REQUEST['spk_sel'] : 1);
$spks = array("Lite", "Professional", "Enterprise");

function wrap($s){
	if($s === true){
		return "<img src=\"images/check.gif\">";
	}
	else if ($s === false) return "";
	return $s;
}
?>
<form action="order_process.php" class="ignore_jssm">
<input type="hidden" name="order_type" value="spk"/>
<p class="rightTop"/>

<div class="right_sec">
<h2>Pricing of Whois API Software Packages</h2>
<p class="rightTxt3">
	We offer 3 software editions.   Licenses cover  <a href="#license_terms" class="ignore_jssm">non-redistributable use only.</a> Please <a href="mailto:support@whoisxmlapi.com">contact us</a> for other options including 
	a quote for complete <b>source code license</b>.
	Simply check the edition you want and click on next to purchase.
</p>

	


<table cellspacing="1" cellpadding="7" border="0" class="colored" align="center">
              <tr>
                <td align="left" class="header">Features</td>
                <?php for($i=0;$i<count($spks);$i++){
                	
                	$spk =$spks[$i];
                
                	$sel = ($spk_sel == $i ? "checked" : "");
                	 echo "<td align=\"center\" class=\"header\"><div style=\"padding-bottom:3px\"><input type=\"radio\" name=\"spk_sel\" value=\"$i\" $sel/></div>$spk</td>";
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

              <tr>
                <td align="left" class="header" colspan="4">Support (included for one year)</td> 
              </tr>
                <?php 
			  	$i = 0;
			  	foreach($supports as $key=>$val){
			  	
					$cl = ($i%2==0?"evcell":"oddcell");
					$i++;
				?>
              	<tr>
              	
                	<td align="left" class="<?php echo $cl?>"><?php echo $key ?></td>
                	<td align="center" class="<?php echo $cl?>"><?php echo wrap($val[0])?></td>
                	<td align="center" class="<?php echo $cl?>"><?php echo wrap($val[1])?></td>
                	<td align="center" class="<?php echo $cl?>"><?php echo wrap($val[2])?></td>
              	</tr>
			<?php }
			
				$cl = ($i%2==0?"evcell":"oddcell");
			?>
              	             				
              	<tr>
              	
                	<td align="left" class="<?php echo $cl?>" style="font-weight:bold">Price</td>
                	<?php for($i=0;$i<count($spkPrices);$i++){
                		$spkPriceDisp = '$'.$spkPrices[$i];
                		if($spkDiscounts[$i] > 0){
                
                			$discountPrice = sprintf("%d", $spkPrices[$i] * (1.0-$spkDiscounts[$i]));
                			$discountPerc = sprintf("%d",$spkDiscounts[$i] * 100);
                			
                			$spkPriceDisp = "<div style=\"color:red\" title=\"$discountPerc% off\"><del >" . $spkPriceDisp. "</del><br/>". "$".$discountPrice . " </div>";
                		}
                		
                	?>
                		<td align="center" class="<?php echo $cl?>" style="font-weight:bold">
                			<?php echo $spkPriceDisp ?>
                	
                		</td>
                
              		<?php }?>
              	</tr>	
              	
              			
		      
</table>

<p style="color:red;font-weight:bold;">
	Promotional discount is only available until end of this month!
</p>
<br/>
<input type="image" class="next_but" src="images/next.png">


<a name="license_terms"></a>
<h2>License Terms</h2>
<p class="rightTxt3">
	Non-redistributable means that the license covers installation only on your own machines (or machines that you exclusively lease) 
	up to the number specified by the license type. 
	With this license, you may not include the Whois interface as part of a product sent to other parties.
</p>



<h2>Payment Policy</h2>


	
		<span><img src="images/paypal_cc.png"/><br> <b style="color:#444444">Paypal accepts credit card</b></span>
	
	
	<input id="choice_cc" name="pay_choice" type="hidden" value="pp"/>
	

<p class="rightTxt3">
All transactions are done via paypal for safety and security. 
Please <a class="ignore_jssm" href ="mailto:support@whoisxmlapi.com">contact us</a> if you encounter any issue with the checkout proccess.<br/>


</p>



	
</div>

</form>