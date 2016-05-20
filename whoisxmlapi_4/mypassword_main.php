

<img id="top" src="images/top.png" alt="">
<div class="form_container">
<h1><a>Password Management</a></h1>
	<?php if(isset($errors) && strlen($errors)>0){
				show_error($errors);
		}
		else if (isset($_POST['submit'])) echo "<p class=\"errorMsg\"> Password is successfully updated. </p>";
		?>

	
	<br class="spacer" />
	<form id="changepwdform" class="appnitro ignore_jssm"  action="mypassword.php" method="post">
					<div class="form_description">
		

		
		</div>
			<ul >

					<li id="li_2" >
		<label class="description" for="ch_password">*New Password: </label>
		<div>
			<input id="ch_password" name="ch_password" class="element text medium" type="password" maxlength="255" <?php echo isset($password)?'value="'.$password.'"':""?>  "/>
		</div>
		</li>		<li id="li_3" >
		<label class="description" for="ch_password2">*New Password Confirmation: </label>
		<div>
			<input id="ch_password2" name="ch_password2" class="element text medium" type="password" maxlength="255" <?php echo isset($password2)?'value="'.$password2.'"':""?>/>
		</div>
		</li>
		</ul>
	<li class="buttons">
			

				<input  class="button_text" type="submit" name="submit" value="Change Password" />
		</li>
			</ul>
		</form>
</div>


<img id="bottom" src="images/bottom.png" alt="">



<script type="text/javascript">
$(document).ready(function(){
	$('#changepwdform').validate({

		rules:{
			
			ch_password:{
				required:true,
				minlength: 4
			},
			ch_password2:{
				required:true,
				minlength : 4,
				equalTo:"#ch_password"
			}
		}
	});





});
	//ajax_reg();
</script>

