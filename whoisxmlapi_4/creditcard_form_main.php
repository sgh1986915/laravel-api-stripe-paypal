<?php require_once __DIR__ . "/business_def.php"; 
	$item_des = $_REQUEST['item_des'];
	$item_price = $_REQUEST['item_price'];
	$plan_id=$_REQUEST['plan_id'];
	$form_action="creditcard_handler.php";
	$public_key=$_REQUEST['public_key'];
	if(!$public_key)$public_key=$STRIP_API_CURRENT_PUBLIC_KEY;
	
?>

<div class="right_sec">
<h3>Payment</h3>
<form action="<?php echo $form_action?>" class="ignore_jssm payment-form" method="post">
<input type="hidden" name="public_key" id="public_key" value="<?php echo $public_key?>"/>
<?php
	if($plan_id){
		echo "<input type=\"hidden\" name=\"plan_id\" id=\"plan_id\" value=\"$plan_id\"/>";
	}
?>
	
<table width="100%">

<tr>
	
	<td></td>
	<td><span><img src="images/cc.png"/><br> <b style="color:#444444"></b></span></td>	
</tr>
<tr>
<td></td>
	<td>
	
	<a name="cc_option"/>
	<div id="cc_form">	
	<span class="errorMsg payment-errors" <?php if(!$order_cc_error) echo "style=\"display:none;\"" ?>>
		<?php echo $order_cc_error?>
	</span>
    <div class="form-row">
        <label class="description">Card Number</label>
        <input name="card_number" type="text" size="20" autocomplete="off" class="card-number element text medium"  value=""/>
    </div>
    
    <div class="form-row">
        <label class="description">CVC</label>
        <input name="cvc" type="text" size="4" autocomplete="off" class="card-cvc element text xsmall" value=""/>
    </div>
    <div class="form-row">
        <label class="description">Expiration (MM/YYYY)</label>
        <input name="expiry_month"type="text" size="2" class="card-expiry-month element text xsmall" value=""/>
        <span> / </span>
        <input name="expiry_year" type="text" size="4" class="card-expiry-year element text xsmall" value=""/>
    </div>
   	 <div class="form-row">
        <label class="description">Email (where payment confirmation will be sent)</label>
        <input id="customer_email" name="customer_email" type="text" size="4" autocomplete="off" class="card-cvc element text medium" value="<?php echo $customer_email?>"/>
    </div>
    <div class="form-row">
 
  		<label class="description" style="display:inline">Item description</label>
  		<input id="item_des" name="item_des" type="text" size="4" autocomplete="off" class="card-cvc element text medium" value="<?php echo $item_des?>"/>
	
	</div>
    <div class="form-row">
 
  		<label class="description" style="display:inline">Item Price</label>
  		<input id="item_price" name="item_price" type="text" size="4" autocomplete="off" class="card-cvc element text medium" value="<?php echo $item_price?>"/>
	
	</div>	
	
	</div>

	</td>
</tr>
</table>


<br/>	
<input type="image" class="next_but submit-button" src="images/next.png"/>

</form>
</div>





<script type="text/javascript" src="https://js.stripe.com/v1/"></script>


<script type="text/javascript">

    // this identifies your website in the createToken call below
    Stripe.setPublishableKey('<?php echo $public_key;?>');
    
  
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
	
	if(!$('#plan_id').val() && !$('#item_des').val()){
		showPaymentError('Please enter a valid item description');
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