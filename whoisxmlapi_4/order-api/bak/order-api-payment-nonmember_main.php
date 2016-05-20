	<img id="top" src="<?php echo $app_root?>/images/top.png" alt="">
	<div class="form_container">

		<h3>Payment Information</h3>
		<?php if(isset($errors) && strlen($errors)>0){
				show_error($errors);
		}?>
		<form id="regform" class="appnitro ignore_jssm"  action="newaccount.php" method="post">
					<div class="form_description">
		

		
		</div>
			<ul >

					<li id="li_1" >

		<div>
			<li id="li_4" >
		<label class="description" for="nc_email">*Email: </label>
		<div>
			<input id="nc_email" name="nc_email" class="element text medium" type="text" maxlength="255" <?php echo isset($email)?'value="'.$email.'"':""?>/>
		</div>
		</li>		
		
		<li id="li_2" >
		<label class="description" for="password">*Password: </label>
		<div>
			<input id="nc_password" name="nc_password" class="element text medium" type="password" maxlength="255" <?php echo isset($password)?'value="'.$password.'"':""?>  "/>
		</div>
		</li>		<li id="li_3" >
		<label class="description" for="nc_password2">*Password Confirmation: </label>
		<div>
			<input id="nc_password2" name="nc_password2" class="element text medium" type="password" maxlength="255" <?php echo isset($password2)?'value="'.$password2.'"':""?>/>
		</div>
		</li>	

			</ul>
		
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
	<div id="cc_form" <?php if(!$order_cc_error) echo "style=\"display:none;\"" ?>>	
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
   	
	</div>

	</td>
</tr>
</table>

<br/>	
<input type="image" class="next_but submit-button" src="<?php echo $app_root?>/images/next.png">
			

		</form>

	</div>
	<img id="bottom" src="<?php echo $app_root?>/images/bottom.png" alt="">

<script type="text/javascript">
$(document).ready(function(){
	$('#regform').validate({

		rules:{
			nc_username: {
				required:true,
				minlength : 4
			},
			nc_password:{
				required:true,
				minlength: 4
			},
			nc_password2:{
				required:true,
				minlength : 4,
				equalTo:"#nc_password"
			},
			nc_email:{
				required:true,
				email:true
			}
		}
	});



	$('#cc_pay_choice').click(function(evt){
		$('#cc_form').show();
	});
	$('#pp_pay_choice').click(function(evt){
		$('#cc_form').hide();
	});	

});

</script>





