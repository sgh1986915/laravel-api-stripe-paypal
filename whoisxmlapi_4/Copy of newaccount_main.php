<?php require_once("captcha.php");

	$number = rand(1,20);
	$question = generateCaptchaQuestion($number);

	$captcha_ans = generateCaptchaAns($number, $secretKey);



?>
	<img id="top" src="images/top.png" alt="">
	<div class="form_container">

		<h1><a>Registration</a></h1>
		<?php if(isset($errors) && strlen($errors)>0){
				show_error($errors);
		}?>
		<form id="regform" class="appnitro ignore_jssm"  action="newaccount.php" method="post">
					<div class="form_description">
			<!--<h2>Registration</h2>-->
				<input type="hidden" name="submit1" value="submit1"/>


				<input type="hidden" name="captcha_ans" value="<?php echo $captcha_ans?>"/>
		</div>
			<ul >

					<li id="li_1" >

		<div>
			<label class="description" for="nc_username">*Username: </label>
			<input id="nc_username" name="nc_username" class="element text medium" type="text" maxlength="255" <?php echo isset($username)?'value="'.$username.'"':""?> "/>
		</div>
		</li>		<li id="li_2" >
		<label class="description" for="password">*Password: </label>
		<div>
			<input id="nc_password" name="nc_password" class="element text medium" type="password" maxlength="255" <?php echo isset($password)?'value="'.$password.'"':""?>  "/>
		</div>
		</li>		<li id="li_3" >
		<label class="description" for="nc_password2">*Password Confirmation: </label>
		<div>
			<input id="nc_password2" name="nc_password2" class="element text medium" type="password" maxlength="255" <?php echo isset($password2)?'value="'.$password2.'"':""?>/>
		</div>
		</li>		<li id="li_4" >
		<label class="description" for="nc_email">*Email: </label>
		<div>
			<input id="nc_email" name="nc_email" class="element text medium" type="text" maxlength="255" <?php echo isset($email)?'value="'.$email.'"':""?>/>
		</div>
		</li>		<li id="li_5" >
		<label class="description" for="lastname">Last name: </label>
		<div>
			<input id="lastname" name="lastname" class="element text medium" type="text" maxlength="255" <?php echo isset($lastname)?'value="'.$lastname.'"':""?> />
		</div>
		</li>
		<li>
		<label class="description" for="firstname">First name:</label>
		<div>
					<input id="firstname" name="firstname" class="element text medium" type="text" maxlength="255" <?php echo isset($firstname)?'value="'.$firstname.'"':""?> />
		</div>
		</li>

<noscript>
			<li>
		<label class="description" for="firstname">*Human test (What is <?php echo $question[0] . " + ". $question[1]."? )" ?></label>
		<div>
					<input id="ur_ans" name="ur_ans" class="element text medium" type="text" maxlength="255"/>
		</div>
		</li>

</noscript>

					<li class="buttons">
			    <input type="hidden" name="form_id" value="25883" />

				<input id="saveForm" class="button_text" type="submit" name="submit" value="Submit" />
		</li>
			</ul>
		</form>

	</div>
	<img id="bottom" src="images/bottom.png" alt="">

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



	if(!$('#regform :input[name=ur_a]').length){
		$('#regform').append('<input type="hidden" name="ur_a" value="<?php echo $question[0];?>"/>');
	}
	if(!$('#regform :input[name=ur_b]').length){
		$('#regform').append('<input type="hidden" name="ur_b" value="<?php echo $question[1];?>"/>');
	}
	if(!$('#regform :input[name=ur_ans]').length){
		$('#regform').append('<input type="hidden" name="ur_ans" value=""/>');
	}

	$('#regform').submit(function(evt){
		var a =parseInt($('#regform :input[name=ur_a]').fieldValue());
		var b =parseInt($('#regform :input[name=ur_b]').fieldValue());
		var c = a + b;

		$('#regform :input[name=ur_ans]').val(c);


	});


});
	//ajax_reg();
</script>



