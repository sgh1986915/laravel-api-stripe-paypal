<div class="right_sec">
<h3>Order Summary</h3>

<?php
	require_once __DIR__ . "/order_util.php";
	require_once __DIR__ . "/util.php"; // modified
	require_once __DIR__. "/price.php";
	require_once __DIR__. "/util/number_util.php";
	require_Once __DIR__. "/business_def.php";

	global $STRIP_API_CURRENT_PUBLIC_KEY;
	$PAYMENT_TEST =0;

	//order summary

	$order_type = $_REQUEST['order_type'];
	$order_username=$_REQUEST['order_username'];


	//whoisapi
	$query_quantity=$_REQUEST['query_quantity'];
	$membership=getMembership();
	//bwl
	$num_domains=$_REQUEST['num_domains'];
	$bwl_speed=$_REQUEST['bwl_speed'];
	//wc
	$wc_license_type=$_REQUEST['wc_license_type'];
	$num_user_license=$_REQUEST['num_user_license'];
	//wdb
	$wdb_quantity=$_REQUEST['wdb_quantity'];
	$wdb_type=$_REQUEST['wdb_type'];
	//cctld_wdb
	$cctld_wdb_ids = $_REQUEST['cctld_wdb_ids'];
	$cctld_whois_db_type=$_REQUEST['cctld_whois_db_type'];
	//custom_wdb
	$custom_db_ids=$_REQUEST['custom_db_ids'];
	//domain ip db
	$dip_db_quantity=$_REQUEST['dip_db_quantity'];

	$back_url_params=array('order_username'=>$order_username, 'order_type'=>$order_type);
	if($order_type=='whoisapi'){
		 $back_url_params['query_quantity']=$query_quantity;
	}
	else if($order_type=='wc'){
		$back_url_params['wc_license_type']=$wc_license_type;
		$back_url_params['num_user_license']=$num_user_license;
	}
	else if($order_type=='bwl'){
		$back_url_params['num_domains']=$num_domains;
		$back_url_params['bwl_speed']=$bwl_speed;
	}
	else if($order_type=='wdb'){
		$back_url_params['wdb_quantity']=$wdb_quantity;
		$back_url_params['wdb_type']=$wdb_type;
	}
	else if($order_type=='cctld_wdb'){
		$back_url_params['cctld_wdb_ids']  = $_REQUEST['cctld_wdb_ids'];
		$back_url_params['cctld_whois_db_type']=$cctld_whois_db_type;
	}
	else if($order_type=='custom_wdb'){
		$back_url_params['custom_wdb_ids']=$_REQUEST['custom_wdb_ids'];
	}

	//print_r($_REQUEST);
	$back_url=build_ssl_url("order.php?".http_build_query($back_url_params));

	if($order_type=='whoisapi'){

		if($membership){
				$queryAmount=$membership['query_amount'];
				$payperiod=$membership['payperiod'];
				$s=ucfirst($payperiod)."ly membership subscription of $queryAmount queries/month for $order_username";
				$price = (strcasecmp($payperiod, 'month')===0? $membershipPrices[$queryAmount] : $membershipPrices[$queryAmount]*10);
				$price = format_price($price) . "/$payperiod";

		}
		else if($query_quantity){
			$s= "$query_quantity whois queries for $order_username";
			$price=$queryPrices[$query_quantity];
		}
	}
	else if($order_type=='bwl'){
		require_once __DIR__ . "/bulk-whois-lookup-price.php";
		$price = compute_bwl_price($num_domains, $bwl_speed);
		$days_to_complete = compute_bwl_time($num_domains, $bwl_speed);
		$s = "Bulk Whois Lookup for $num_domains domains on a $bwl_speed schedule with $days_to_complete days to complete." ;

	}
	else if($order_type=='wc'){
		require_once __DIR__ . "/wc_price.php";
		$price = compute_wc_license_price($wc_license_type, $num_user_license);
		$s = '';
		if($wc_license_type == 'sourcecode_license')$s = "Whois API Client Application Source Code License";
		else if($wc_license_type =='group_license')$s="Whois API Client Application Group License";
		else $s = "$num_user_license Whois API Client User License";
	}
	else if($order_type=='wdb'){
		require_once __DIR__ . "/whois-database-price.php";
		$price = compute_real_db_price($wdb_quantity, $wdb_type);
		$s = "Whois Database download: $wdb_quantity million ".($wdb_type=='raw' ? "Raw" : "Parsed and Raw ") . " whois records" ;

	}
	else if($order_type=='cctld_wdb'){
		require_once __DIR__ . "/models/cctld_whois_database_product.php";
		$cctldWhoisDatabaseProduct = new CCTLDWhoisDatabaseProduct();

		$s = ($cctld_whois_db_type=='domain_names' ? 'Domain Names Only: ' : 'Whois Records: ' ) . "<pre>" .$cctldWhoisDatabaseProduct->concat_product_item_names_by_ids($cctld_wdb_ids, '<br/>') . "</pre>";

		$items = $cctldWhoisDatabaseProduct->get_product_items_by_ids($cctld_wdb_ids);

		$price=$cctldWhoisDatabaseProduct->get_product_items_price($items, ($cctld_whois_db_type=='domain_names'?'domain_names_price' : 'parsed_whois_price'));


	}
	else if($order_type=='custom_wdb'){
		require_once __DIR__ . "/models/custom_whois_database_product.php";
		$customWhoisDatabaseProduct = new CustomWhoisDatabaseProduct();
		$s=$customWhoisDatabaseProduct->concat_product_item_names_by_ids($_REQUEST['custom_wdb_ids'],"<br/>");
		$price=$customWhoisDatabaseProduct->get_product_items_price_by_ids($_REQUEST['custom_wdb_ids']);

	}
	else if($order_type=='dipdb'){
		require_once __DIR__ . "/domain-ip-database-price.php";
		$s="$dip_db_quantity million domain name to ip addresses mappings";
		$price=compute_real_dip_db_price($dip_db_quantity);

	}
	if($PAYMENT_TEST){
		$price=1;
	}

	if(!$membership){
		$price=format_price($price);
	}
?>
<table cellspacing="1" cellpadding="7" border="0" class="colored" align="center" width="95%">
	<tr>
		<td class="header">
			Product
		</td>
		<td class="header">
			Price
		</td>
	</tr>
	<tr>
		<td class="evcell">
			<?php echo $s;?>
		</td>
		<td class="evcell">
			<?php echo $price?>
		</td>
	</tr>
</table>

<form action="order_process.php" class="ignore_jssm payment-form"  method="post">
<input type="hidden" name="pay_choice" value="cc"/>
<input type="hidden" name="order_username" value="<?php echo $order_username;?>" />
<input type="hidden" name="order_type" value="<?php echo $order_type;?>" />
<?php
	if($order_type=='whoisapi'){
		if($membership){
			foreach($_REQUEST as $rkey=>$rval){
				if($rkey!=null){
					if(stripos($rkey, "bill_yearly_") ===0 || stripos($rkey, "bill_monthly_") ===0){
						echo "<input type=\"hidden\" name=\"$rkey\" value=\"$rval\"/>";
					}
				}
			}
		}
		else if($query_quantity){
			echo "<input type=\"hidden\" name=\"query_quantity\" value=\"$query_quantity\"/>";
		}

	}
	else if($order_type=='bwl'){
		echo "<input type=\"hidden\" name=\"num_domains\" value=\"$num_domains\"/>";
		echo "<input type=\"hidden\" name=\"bwl_speed\" value=\"$bwl_speed\"/>";
	}
	else if($order_type=='wc'){
		echo "<input type=\"hidden\" name=\"wc_license_type\" value=\"$wc_license_type\"/>";
		echo "<input type=\"hidden\" name=\"num_user_license\" value=\"$num_user_license\"/>";
	}
	else if($order_type=='wdb'){
		echo "<input type=\"hidden\" name=\"wdb_quantity\" value=\"$wdb_quantity\"/>";
		echo "<input type=\"hidden\" name=\"wdb_type\" value=\"$wdb_type\"/>";
	}
	else if($order_type=='cctld_wdb'){
		$cctld_wdb_ids = $_REQUEST['cctld_wdb_ids'];
		foreach($cctld_wdb_ids as $cctld_wdb_id){
			echo "<input type=\"hidden\" name=\"cctld_wdb_ids[]\" value=\"$cctld_wdb_id\"/>";
		}
		echo "<input type=\"hidden\" name=\"cctld_whois_db_type\" value=\"$cctld_whois_db_type\"/>";
	}
	else if($order_type=='custom_wdb'){
		if($custom_wdb_ids){
			foreach($custom_wdb_ids as $custom_wdb_id){
				echo "<input type=\"hidden\" name=\"custom_wdb_ids[]\" value=\"$custom_wdb_id\"/>";
			}
		}
	}

?>


<?php if($order_error){
	show_error($order_error);
}
?>


<?php include "cc_form.php";?>
<br/>
<a href="<?php echo $back_url?>" class="ignore_jssm"><img style="align:left" src="images/previous.png"></a>
<input type="image" class="next_but submit-button" src="images/next.png" name="submit">
</form>

<h3>Payment Security and Policy</h3>
		<span><img src="images/cc.png"/></span>

<p class="rightTxt3">
We take great care to use physical, electronic and procedural precautions,
including the use of up to 256-bit data encryption and secure socket layer (SSL) technology.
Our precautionary measures meet or exceed all industry standards. <br/>
We do not collect your credit card number, your payment information are encrypted and passed on directly to either paypal or other industry leading
PCI-compliant credit card payment processor that uses the highest standard and encryptions.
<br/>
Unused Query purchases never expire but are not refundable.
You may change or cancel your membership at any time by simply <a class="ignore_jssm" href ="mailto:support@whoisxmlapi.com">sending us an email</a> with your username.  Please <a class="ignore_jssm" href ="mailto:support@whoisxmlapi.com">contact us</a> if you encounter any issue with the checkout proccess.<br/>


</p>

<span> <b style="color:#444444">Need an alternative payment option?</b></span>
<span><a href ="order_paypal.php" class="ignore_jssm"><img src="images/paypal_2.gif"/></a></span>
<p class="rightTxt3">
You may <a href="order_paypal.php" class="ignore_jssm">use paypal</a> to checkout.
Please <a class="ignore_jssm" href ="mailto:support@whoisxmlapi.com">send us an email</a> for instruction on how to pay by check, wire-transfer, or other options
</p>

</div>

 <p class="rightBottom"></p>
<script type="text/javascript" src="https://js.stripe.com/v1/"></script>



<script type="text/javascript">

    // this identifies your website in the createToken call below
    Stripe.setPublishableKey('<?php echo $STRIP_API_CURRENT_PUBLIC_KEY?>');


$(document).ready(function() {

  $(".payment-form").bind('submit',function(event) {

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
		alert(e);
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

