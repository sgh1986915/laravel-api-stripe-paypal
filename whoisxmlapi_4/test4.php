<?php

	
	
?>

<p class="rightTop"/>
<?php if(isset($order_error)){
	show_error($order_error);
}
?>
<p class="errorMsg" id="payment-error">
</p>
<div class="right_sec">
<h3>Secure Order Form</h3>
<p class="rightTxt3">
	
</p>
<?php
	$form_action="order_process.php";
	if($pay_choie=='cc'){
		$form_action="cc.php";	
	}
?>
<form action="cc_process.php"  id="payment-form" onsubmit="return fuck();">
	<input id="pay_choice" name="pay_choice" type="hidden" value="<?php echo $pay_choice?>"/>
		
		
    <div class="form-row">
        <label class="description">Card Number</label>
        <input type="text" size="20" autocomplete="off" class="card-number element text medium"  value="4242424242424242"/>
    </div>
    <div class="form-row">
        <label class="description">CVC</label>
        <input type="text" size="4" autocomplete="off" class="card-cvc element text xsmall" value="2323"/>
    </div>
    <div class="form-row">
        <label class="description">Expiration (MM/YYYY)</label>
        <input type="text" size="2" class="card-expiry-month element text xsmall" value="12"/>
        <span> / </span>
        <input type="text" size="4" class="card-expiry-year element text xsmall" value="2013"/>
    </div>
    <button type="submit" class="submit-button">Submit Payment</button>		
</form>
	
</div>
  <script src="http://www.google.com/jsapi"></script>
  <script type="text/javascript">

     // Load jQuery
     google.load("jquery", "1.4.2");
    google.load("jqueryui", "1.7.3");
     google.setOnLoadCallback(function() {
         init();
     });

 </script>
 
<script type="text/javascript" src="https://js.stripe.com/v1/"></script>


<script type="text/javascript">
	function fuck(){
		alert('fuck');
		return false;
	}
    // this identifies your website in the createToken call below
    Stripe.setPublishableKey('pk_Bo9UVWn1xjt5AhrE2tpTCkB2WN8m4');
	function init(){ 
  $("#payment-form").bind('submit',function(event) {
  	alert('submit');
  	event.preventDefault();
  	try{ 
    // disable the submit button to prevent repeated clicks
    $('.submit-button').attr("disabled", "disabled");

    Stripe.createToken({
        number: $('.card-number').val(),
        cvc: $('.card-cvc').val(),
        exp_month: $('.card-expiry-month').val(),
        exp_year: $('.card-expiry-year').val()
    }, stripeResponseHandler);
	
    // prevent the form from submitting with the default action
     
  }catch(e){
  	alert(e);
  }
    return false;
  });
}  

function stripeResponseHandler(){
	
}
</script>

