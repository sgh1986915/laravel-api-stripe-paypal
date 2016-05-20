	<img id="top" src="images/top.png" alt="">
	<div class="form_container">

		<h1><a>Registration</a></h1>
		<?php if(isset($errors) && strlen($errors)>0){
				show_error($errors);
		}?>
		<form id="regform" class="appnitro ignore_jssm"  action="newaccount.php" method="post">
					<div class="form_description">
		

		
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

		
			<li>
		
				<label class="description" for="captcha">*Type the code shown:
				<div style="width: 430px; float: left; height: 90px">
      <img id="siimage" align="left" style="padding-right: 5px; border: 0" src="captcha/securimage_show.php?sid=<?php echo md5(time()) ?>" />

        <object classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,0,0" width="19" height="19" id="SecurImage_as3" align="middle">
			    <param name="allowScriptAccess" value="sameDomain" />
			    <param name="allowFullScreen" value="false" />
			    <param name="movie" value="captcha/securimage_play.swf?audio=captcha/securimage_play.php&bgColor1=#777&bgColor2=#fff&iconColor=#000&roundedCorner=5" />
			    <param name="quality" value="high" />
			
			    <param name="bgcolor" value="#ffffff" />
			    <embed src="captcha/securimage_play.swf?audio=captcha/securimage_play.php&bgColor1=#777&bgColor2=#fff&iconColor=#000&roundedCorner=5" quality="high" bgcolor="#ffffff" width="19" height="19" name="SecurImage_as3" align="middle" allowScriptAccess="sameDomain" allowFullScreen="false" type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer" />
			  </object>

        <br />
        
        <!-- pass a session id to the query string of the script to prevent ie caching -->
        <a tabindex="-1" style="border-style: none" href="#" title="Refresh Image" onclick="document.getElementById('siimage').src = 'captcha/securimage_show.php?sid=' + Math.random(); return false"><img src="captcha/images/refresh.gif" alt="Reload Image" border="0" onclick="this.blur()" align="bottom" /></a>
</div>

				
				</label>
				
				<div>
					
			
		
					<input id="captcha" name="captcha" class="element text medium" type="text" maxlength="255"/>
				</div>
		
		</li>

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





});
	//ajax_reg();
</script>



