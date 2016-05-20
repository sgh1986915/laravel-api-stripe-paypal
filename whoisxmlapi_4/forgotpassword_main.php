<?php
/* vim: set expandtab tabstop=4 shiftwidth=4: */
// +----------------------------------------------------------------------+
// | PHP version 4                                                        |
// +----------------------------------------------------------------------+
// | Copyright (c) 1997-2002 The PHP Group                                |
// +----------------------------------------------------------------------+
// | This source file is subject to version 2.0 of the PHP license,       |
// | that is bundled with this package in the file LICENSE, and is        |
// | available through the world-wide-web at                              |
// | http://www.php.net/license/2_02.txt.                                 |
// | If you did not receive a copy of the PHP license and are unable to   |
// | obtain it through the world-wide-web, please send a note to          |
// | license@php.net so we can mail you a copy immediately.               |
// +----------------------------------------------------------------------+
// | Authors: Byrne Reese <byrne at majordojo dot com                     |
// +----------------------------------------------------------------------+
//
// $Id: forgotpassword.php,v 1.2 2003/06/06 05:32:11 byrnereese Exp $

require_once("users/users.inc");
//require_once("sendmail.php");
require_once(dirname(__FILE__). "/email/Email.php");

$PAGE_TITLE = "Forgotten Password";
//include $PHP_USERS_HEADER_FILE; 

$error = '';
$email_sent = false;
$email = (array_key_exists('pr_email',$_REQUEST)?$_REQUEST['pr_email']:false);
$username = (array_key_exists('pr_username',$_REQUEST)?$_REQUEST['pr_username']:false);

if(array_key_exists('submit', $_REQUEST) or array_key_exists('submit1',$_REQUEST)){
	if(empty($email)){
		$error = "Email address is empty.";
	}
	else if(empty($username)){
		$error = "Username is empty.";
	}
	else if(!connect_to_whoisserver_db($username)){
		$error = "unable to connect to the database.";
	}
	else{
		$sql = "SELECT username, password FROM users WHERE email='". mysql_real_escape_string($email) . "' and username ='". mysql_real_escape_string($username) . "'";
		$query = mysql_query($sql);

		if ($query && (mysql_num_rows($query) > 0)) {
  			if (list($username,$password) = mysql_fetch_row($query)) {
    			try{
					//sendmail($WEB_MASTER_EMAIL, $email, "Your Password", "Below is the username and password information you requested.\n\nUsername: '$username'\nPassword: '$password'.\n\n");		
						$emailer=new Email();
	$emailer->from="support@whoisxmlapi.com";
	$subject = "Your login information at WhoisAPI";
	$body = "Below is the username and password information you requested.\n\nUsername: '$username'\nPassword: '$password'.\n\n";	
	$emailer->send_mail($email,$subject,$body,null);
	
					$email_sent = true;
				}catch(Exception $exp){
					$error .= "<br>Failed to send email to ". $email .': '. $exp->getMessage();	
				}
			}
		}
		else{
			$error .= "We could not find an account corresponding with the email address and username you entered.  Use the form below to try again.";

		}
	}	
}


?>




	<img id="top" src="images/top.png" alt="">
	<div class="form_container">
	
		<h1><a>Password retrieval</a></h1>
		<?php if(strlen($error)>0){
				show_error($error);		
		}
		?>
		<?php if($email_sent){?>
			
			<p class="form_description">
				We've emailed your password. You should receive it within a minute. If you don't, please send an email to <a href="mailto:<?php echo $WEB_MASTER_EMAIL?>"><?php echo $WEB_MASTER_EMAIL?></a>
				
			</p>
		
		<?php
		}else{
		?>
		
		<form id="passwdform" class="appnitro ignore_jssm"  action="forgotpassword.php" method="post">
			<input type="hidden" name="submit1" value="submit1"/>
					<div class="form_description">
		
		</div>
			<ul >

					<li id="li_1" >
		
		<div>
			<label class="description" for="pr_email">Your Email Address: </label>
			<input id="pr_email" name="pr_email" class="element text medium" type="text" maxlength="255"  <?php echo $email?'value="'.$email.'"':''?>/>
		</div>
		<div>
			<label class="description" for="pr_username">Your Username: </label>
			<input id="pr_username" name="pr_username" class="element text medium" type="text" maxlength="255"  <?php echo $username?'value="'.$username.'"':''?>/>
		</div>
		</li>	

		<li class="buttons">
			    
				
				<input  class="button_text" type="submit" name="submit" value="Submit" />
		</li>
			</ul>
		</form>
		<?php } ?>
	</div>
	<img id="bottom" src="images/bottom.png" alt="">

<script type="text/javascript">
	
$(document).ready(function(){
	
	$('#passwdform').validate({
		rules:{
			
			pr_email:{
				required:true,
				email:true
			},
			pr_username:{
				required:true
			}			
		}
	});

});	
</script>



