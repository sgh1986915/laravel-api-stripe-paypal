<?php
require_once("httputil.php");
require_once("users/users.inc");


if (isset($_REQUEST['returnto'])) {
  $returnto = $_REQUEST['returnto'];
} else if (isset($returnto)) {
  // do nothing
} else {
  $returnto = returnto_url();
}

//if ($_CURRENT_USER->is_anonymous) { 
my_session_start();

if(!array_key_exists('user', $_SESSION)){  

?>
 
  <div class="styleh2">Member Login</div>

  <form  method="post" name="member_login" id="member_login" action="<?php echo $app_root.'/processlogin.php'?>" class="ignore_jssm">
  	<?php
  if(isset($error) && $error){
	echo "<span class=errorMsg>$error</span>";		
  }
?> 
    <input id="login_returnto" type="hidden" name="returnto" value="<?php echo $returnto; ?>">
    <label>Name:</label>
    <input type="text" name="username" class="txtBox" />
	<br style="clear:left">
    <label>Password:</label>
    <input type="password" name="password" class="txtBox" />
	<br style="clear:left">
	<input type="submit" name="go" value="" class="go" />
	<br style="clear:left">
	<a id="newact" href="<?php echo $app_root?>/newaccount.php"  class="ignore_jssm">Create new account</a>
	<br style="clear:left">
	<a id="forgotpwd" href="<?php echo $app_root?>/forgotpassword.php"  class="ignore_jssm">Forgot your password?</a>
	
    <br class="spacer" />
  </form>
  <br class="spacer" />
<?php
} 
?>

<script type="text/javascript">
$(document).ready(function(){	
	$('#member_login').validate({
		rules:{
			username: {
				required:true,
				minlength : 4
			},
			password:{
				required:true,
				minlength: 4
			}
		}
	});
	//ajax_login_box();
});
</script>	




