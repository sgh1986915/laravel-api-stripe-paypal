
<?php 
	$CC_TESTING=0;
	$customer_email=$_REQUEST['customer_email'];

	if(!$customer_email){
		my_session_start();	
		$customer_email = $_SESSION['myuser']->email;
	}
?>


<h3>Secure Payment Form</h3>
<div><img src="images/Icon_LockSm.png"/>&nbsp;&nbsp;This is a secure site 

</div>
<table width="100%">
<tr>
<td>
<table width="100%">

<tr>

	<td colspan="2"><span><img src="images/cc.png"/><br> <b style="color:#444444"></b></span></td>	
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
        <input type="text" size="20" autocomplete="off" class="card-number element text medium"  value="<?php echo $CC_TESTING?"4242424242424242":""?>"/>
    </div>
    <div class="form-row">
        <label class="description">CVC</label>
        <input type="text" size="4" autocomplete="off" class="card-cvc element text xsmall" value="<?php echo $CC_TESTING?"123":""?>"/>
    </div>
    <div class="form-row">
        <label class="description">Expiration (MM/YYYY)</label>
        <input type="text" size="2" maxlength="2" class="card-expiry-month element text xsmall" value="<?php echo $CC_TESTING?"12":""?>"/>
        <span> / </span>
        <input type="text" size="4" maxlength="4" class="card-expiry-year element text xsmall" value="<?php echo $CC_TESTING?"2013":""?>"/>
    </div>
   	 <div class="form-row">
        <label class="description">Email (where payment confirmation will be sent)</label>
        <input id="customer_email" name="customer_email" type="text" size="4"  class="card-cvc element text medium" value="<?php echo $customer_email?>"/>
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