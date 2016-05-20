<?php require_once "users/users.conf";
require_once __DIR__. "/business_def.php";
?>
<p class="rightTop"></p>
<h1>List of New Registered & Just Expired Domains</h1> 
<p class="rightTxt1">
	We provide Newly Registered Domains and Just Expired Domains as daily downloads.  <a href="http://www.whoisxmlapi.com" title="Whois API" class="ignore_jssm">Whois data</a> are also provided as an option.  
	&nbsp;&nbsp;Supported tlds are <b>".com, .net, .org, .us, .biz, .mobi, .info, .pro, .coop, .asia, .name, .tel, .aero" plus <a href="support/supported_ngtlds.php" class="ignore_jssm" target=_blank>hundreds of new gTLDs!</a></b>.  &nbsp;&nbsp;
	Simply check the edition you want and click on next to purchase.  View the <a href="http://bestwhois.org/domain_name_data/sample" class="ignore_jssm" target=_blank rel="nofollow">sample data here</a>.
</p>

<?php require_once "price.php";

$features = array("Newly Registered Domains"=>array(true, true, true, true, true),
"Just Expired Domains" => array(false, true, true, true, true),
"Daily downloads" => array(true, true, true, true, true),
//"Daily email list" => array(false, false, true),
"Whois Data" => array(false, false, true, true, true),
"Categorized based on registrant country" => array(false, false, false, true, true),
"Removal of Whois proxies*" => array(false, false, false, false, true),
"Historic data in months"=>array("1", "1" , "1", "20+", "20+")
);

$spk_sel = (isset($_REQUEST['data_edition']) ? $_REQUEST['data_edition'] : 2);
$spks = array( "Lite", "Pro", "Enterprise", "Custom 1", "Custom 2");


$yearlyPriceDisplays=array();
for($yi=0;$yi<count($domainNameDataPrices);$yi++){
	$yearlyPriceDisplays[]=	"$".computeDomainNameDataYearlyPrice($yi) ."/year";
}


$yearlyPriceDisplay=$yearlyPriceDisplays[$spk_sel];

$pay_choice="cc";
if($_REQUEST['pay_choice']){
	$pay_choice=$_REQUEST['pay_choice'];
}
$customer_email="";
if($_REQUEST['customer_email']){
	$customer_email=$_REQUEST['customer_email'];
}
else{
	my_session_start();

	$user=$_SESSION['myuser'];
	if(is_object($user)){
		$customer_email=$user->email;
	}
}


function wrap($s){
	if($s === true){
		return "<img src=\"images/check.gif\">";
	}
	else if ($s === false) return "";
	return $s;
}
$form_action="order_process.php";

?>



<form action="<?php echo $form_action?>" class="ignore_jssm payment-form" method="post">
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
                	<?php foreach($val as $feature_flag){?>
                		<td align="center" class="<?php echo $cl?>"><?php echo wrap($feature_flag)?></td>
                	
              		<?php
                	}?>
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

<p style="font-style:italic">
	* "Removal of Proxies" means that all whois records with a whois guard/whois proxy(used by registrant to hide their identities) are removed.
</p>


<h2>Payment Options</h2>
<div><img src="images/Icon_LockSm.png"/>&nbsp;&nbsp;This is a secure site 

</div>
<table width="100%">
<tr>
<td>
<table>
<tr>
	<td><input  id="pp_pay_choice" name="pay_choice" type="radio" value="pp" <?php if($pay_choice=='pp')echo 'checked'?> /></td>
	<td><span><img src="images/paypal_2.gif"/><br> <b style="color:#444444"></b></span></td>
</tr>
<tr>
	
	<td><input  id="cc_pay_choice" name="pay_choice" type="radio" value="cc" <?php if($pay_choice=='cc')echo 'checked'?> /></td>
	<td><span><img src="images/cc.png"/><br> <b style="color:#444444"></b></span></td>	
</tr>
<tr>
<td></td>
	<td>
	
	<a name="cc_option"/>
	<div id="cc_form" <?php if($pay_choice!='cc') echo "style=\"display:none;\"" ?>>	
	<span class="errorMsg payment-errors" <?php if(!$order_cc_error) echo "style=\"display:none;\"" ?>>
		<?php echo $order_cc_error?>
	</span>
    <div class="form-row">
        <label class="description">Card Number</label>
        <input type="text" size="20" autocomplete="off" class="card-number element text medium"  value=""/>
    </div>
    <div class="form-row">
        <label class="description">CVC</label>
        <input type="text" size="4" autocomplete="off" class="card-cvc element text xsmall" value=""/>
    </div>
    <div class="form-row">
        <label class="description">Expiration (MM/YYYY)</label>
        <input type="text" size="2" class="card-expiry-month element text xsmall" value=""/>
        <span> / </span>
        <input type="text" size="4" class="card-expiry-year element text xsmall" value=""/>
    </div>
   	 <div class="form-row">
        <label class="description">Email (where payment confirmation will be sent)</label>
        <input id="customer_email" name="customer_email" type="text" size="4" autocomplete="off" class="card-cvc element text medium" value="<?php echo $customer_email?>"/>
    </div>
    
	</div>

	</td>
</tr>
</table>


</td>
<!--
<td valign="top">
<img src="images/nortonseal_180x96.gif"/>
</td>
-->
</tr>
</table>


<div>
   <input name="pay_yearly" type="checkbox" value="1" <?php echo $pay_yearly?"checked":""?> />
        <label class="description" style="display:inline">Pay yearly and save <?php echo $domainNameDataYearlyDiscount*100?>% (<span id="yearly_price"><?php echo $yearlyPriceDisplay?></span>)</label>
       
   
	
</div>
<br/>	
<input type="image" class="next_but submit-button" src="images/next.png">


<a name="license_terms"></a>
<h2>License Terms</h2>
<p class="rightTxt3">
	All domain name data/list provided are Non-redistributable.  It means that you may not redistribute or resell these data to other parties.
</p>



<h2>Payment Security and Policy</h2> 

<p class="rightTxt3">
We take great care to use physical, electronic and procedural precautions, 
including the use of up to 256-bit data encryption and secure socket layer (SSL) technology. 
Our precautionary measures meet or exceed all industry standards. <br/>
We do not collect your credit card number, your payment information are encrypted and passed on directly to either paypal or other industry leading 
PCI-compliant credit card payment processor that uses the highest standard and encryptions.

Please <a class="ignore_jssm" href ="mailto:support@whoisxmlapi.com" title="Contact Whois API">contact us</a> if you encounter any issue with the checkout proccess.<br/>


</p>



	
</div>

</form>




<script type="text/javascript" src="https://js.stripe.com/v1/"></script>


<script type="text/javascript">

    // this identifies your website in the createToken call below
    Stripe.setPublishableKey('<?php echo $STRIP_API_CURRENT_PUBLIC_KEY?>');
    var yearly_prices=<?php echo json_encode($yearlyPriceDisplays)?>; 
$(document).ready(function() {	
	$("input[name='data_edition']").change(function(evt){
		var data_edition = $(this).val();
		$("#yearly_price").html(yearly_prices[data_edition]);
	});
	$('#cc_pay_choice').click(function(evt){
		$('#cc_form').show();
	});
	$('#pp_pay_choice').click(function(evt){
		$('#cc_form').hide();
	});	
  $(".payment-form").bind('submit',function(event) {
  	var pay_choice=$(this).find('input[name=pay_choice]:checked').val();
  	if(pay_choice=='pp')return true;
  	event.preventDefault();
  	if(!validate_input())return false;
  	try{ 
    	// disable the submit button to prevent repeated clicks
    	$('.submit-button').attr("disabled", "disabled");
		$('.submit-button').attr("src", "images/next_disable.png");
    		Stripe.createToken({
        		number: $('.card-number').val(),
        		cvc: $('.card-cvc').val(),
        		exp_month: $('.card-expiry-month').val(),
        		exp_year: $('.card-expiry-year').val()
    		}, stripeResponseHandler);
	
    		// prevent the form from submitting with the default action
     
  	}catch(e){
  		$('.submit-button').removeAttr("disabled");
		$('.submit-button').attr("src", "images/next.png");
  	}
  
    return false;
  });
});

function stripeResponseHandler(status, response) {
	//test 4242424242424242 
    if (response.error) {
        $(".payment-errors").show().text(response.error.message);
        $('.submit-button').removeAttr("disabled");
		$('.submit-button').attr("src", "images/next.png");
    } else {
        var form = $(".payment-form");
        // token contains id, last4, and card type
        var token = response['id'];
        // insert the token into the form so it gets submitted to the server
        form.append("<input type='hidden' name='stripeToken' value='" + token + "'/>");
        // and submit
        form.get(0).submit();
    }
}
function validate_input(){
	if(!isValidEmailAddress($('#customer_email').val())){
		showPaymentError('Please enter a valid email.');
		return false;
	}
	return true;
	
}
function isValidEmailAddress(emailAddress) {
    var pattern = new RegExp(/^((([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+(\.([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+)*)|((\x22)((((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(([\x01-\x08\x0b\x0c\x0e-\x1f\x7f]|\x21|[\x23-\x5b]|[\x5d-\x7e]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(\\([\x01-\x09\x0b\x0c\x0d-\x7f]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))))*(((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(\x22)))@((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?$/i);
    return pattern.test(emailAddress);
};

function showPaymentError(err){
	$(".payment-errors").show().text(err);
}
</script>



<?php if(isset($order_cc_error)){?>
	<script type ="text/javascript">
		$(document).ready(function(){
			location.href=location.href+"#cc_option";	
		});
		
	</script>
	
<?php 
}?>












