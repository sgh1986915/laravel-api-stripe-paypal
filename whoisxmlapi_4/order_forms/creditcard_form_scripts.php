<script type="text/javascript" src="https://js.stripe.com/v1/"></script>



<script type="text/javascript">
	<?php require_once __DIR__ . "/../business_def.php"; 
	global $STRIP_API_CURRENT_PUBLIC_KEY;
	?>
    // this identifies your website in the createToken call below
    Stripe.setPublishableKey('<?php echo $STRIP_API_CURRENT_PUBLIC_KEY?>');
    
   
$(document).ready(function() {	
	
  $(".payment-form").bind('submit',function(event) {
  	if(!$('#cc_pay_choice').attr('checked'))return true;
  	event.preventDefault();
  	<?php if($validate_input_func){
  		echo "if(! ". $validate_input_func . "())return false;";
  	}?>
  	try{
    	// disable the submit button to prevent repeated clicks
    	$('.submit-button').attr("disabled", "disabled");
    	var link = $('.submit-button').parent('a');
    	
    	if(link){
    		href=link.attr('href');
    		link.attr("old_href",href);
    		link.removeAttr('href');
    	}
		$('.submit-button').attr("src", "<?php echo $app_root?>/images/next_disable.png");
    		Stripe.createToken({
        		number: $('.card-number').val(),
        		cvc: $('.card-cvc').val(),
        		exp_month: $('.card-expiry-month').val(),
        		exp_year: $('.card-expiry-year').val()
    		}, stripeResponseHandler);
	
    		// prevent the form from submitting with the default action
     
  	}catch(e){
  		$('.submit-button').removeAttr("disabled");
		$('.submit-button').attr("src", "<?php echo $app_root?>/images/next.png");
		if(link){
			link.attr('href',link.attr('old_href'));
			link.removeAttr('old_href');
		}
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
		$('.submit-button').attr("src", "<?php echo $app_root?>/images/next.png");
		var link = $('.submit-button').parent('a');
		if(link){
			link.attr('href',link.attr('old_href'));
			link.removeAttr('old_href');
		}
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

function showPaymentError(err){
	$(".payment-errors").show().text(err);
}
</script>