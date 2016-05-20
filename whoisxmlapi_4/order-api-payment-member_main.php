<?php
	$pay_choice=$_REQUEST['pay_choice'];
	if(!$pay_choice)$pay_choice="cc";
	$PAYMENT_TEST=0;
?>
	<img id="top" src="<?php echo $app_root?>/images/top.png" alt="">
	<div class="form_container">
<form id="order-api-payment-form" class="appnitro ignore_jssm payment-form"  action="order-api-process.php" method="post">
		
		<?php if(isset($errors) && strlen($errors)>0){
				show_error($errors);
		}?>
		
		<?php 
			
			include __DIR__ . "/shopping-cart.php";
			
		?>
		
		
<h3>Payment Options</h3>
<table width="100%">
<tr>
	<td><input  id="pp_pay_choice" name="pay_choice" type="radio" value="pp" <?php if($pay_choice=='pp')echo 'checked'?> /></td>
	<td><span><img src="<?php echo $app_root?>/images/paypal_2.gif"/><br> <b style="color:#444444"></b></span></td>
</tr>
<tr>
	
	<td><input  id="cc_pay_choice" name="pay_choice" type="radio" value="cc" <?php if($pay_choice=='cc')echo 'checked'?> /></td>
	<td><span><img src="<?php echo $app_root?>/images/cc.png"/><br> <b style="color:#444444"></b></span></td>	
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
        <input type="text" size="20" autocomplete="off" class="card-number element text medium"  value="<?php echo $PAYMENT_TEST?'4242424242424242':''?>"/>
    </div>
    <div class="form-row">
        <label class="description">CVC</label>
        <input type="text" size="4" autocomplete="off" class="card-cvc element text xsmall" value="<?php echo $PAYMENT_TEST?'123':''?>"/>
    </div>
    <div class="form-row">
        <label class="description">Expiration (MM/YYYY)</label>
        <input type="text" size="2" class="card-expiry-month element text xsmall" value="<?php echo $PAYMENT_TEST?'12':''?>"/>
        <span> / </span>
        <input type="text" size="4" class="card-expiry-year element text xsmall" value="<?php echo $PAYMENT_TEST?'2016':''?>"/>
    </div>
   	
	</div>

	</td>
</tr>
</table>

<br/>	
<table width="95%">
	<tr>
		<td align="left">
			<a href="<?php echo build_url('order-api.php', $link_params)?>" class="ignore_jssm"><img style="align:left" src="<?php echo $app_root?>/images/cancel.png" /></a>
		</td>
		<td align="right">
			<a href="<?php echo build_url('order-api-summary.php', $link_params)?>" class="ignore_jssm"><img style="align:left" src="<?php echo $app_root?>/images/previous.png" /></a>
			<a id="next" class="ignore_jssm" href="javascript:next();"><img style="align:left" src="<?php echo $app_root?>/images/next.png" class="submit-button"/></a>
			<!--<input type="image" class="next_but submit-button" src="<?php echo $app_root?>/images/next.png">-->
		</td>
	<tr>
	
</table>
			

		</form>

	</div>
	<img id="bottom" src="<?php echo $app_root?>/images/bottom.png" alt="">

<?php include __DIR__ . "/order_forms/creditcard_form_scripts.php"
?>
<script type="text/javascript">
	function next(){
		
			var form = $("#order-api-payment-form");
			form.attr('action','order-api-process.php');
			form.find("input[name=update]").val(1);  
			
			form.submit();
			
	
	}
	
$(document).ready(function(){
	


	$('#cc_pay_choice').click(function(evt){
		$('#cc_form').show();
	});
	$('#pp_pay_choice').click(function(evt){
		$('#cc_form').hide();
	});	

});

</script>





