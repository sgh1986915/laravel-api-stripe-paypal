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
// $Id: reconfirm.php,v 1.2 2003/06/06 05:32:11 byrnereese Exp $

require_once("users.inc");
$PAGE_TITLE = "Reconfirm Account";

connect_to_users_db();

if (isset($_REQUEST['conf_email'])) {
  $sql = "SELECT userId, email, status FROM $USERSB_DB.Users WHERE email = '".$_REQUEST['conf_email']."'";
  $query = mysql_query($sql);

  if ($query && (mysql_num_rows($query) > 0)) {
    if (list($user_id,$email,$status) = mysql_fetch_row($query)) {
      if ($status == "unconfirmed") {
	$token = time() . "::$user_id";
	$sql = "DELETE FROM $USERS_DB.login_tokens WHERE userId=$user_id";
	mysql_query($sql) or die("Error in query: $sql - ".mysql_error());
	$sql = "INSERT INTO $USERS_DB.login_tokens (userId,token) VALUES ($user_id,'".mysql_escape_string($token)."')";
	mysql_query($sql) or die("Error in query: $sql - ".mysql_error());
	send_confirmation($conf_email,$token);
      } else {
	show_error("Only unconfirmed accounts can be reconfirmed.",
		   $PHP_USERS_HEADER_FILE,$PHP_USERS_FOOTER_FILE);
      }
    } else {
      show_error("An error occured in your submission.",
		 $PHP_USERS_HEADER_FILE,$PHP_USERS_FOOTER_FILE);
    }
  } else {
    show_error("No user account exists with that email address.",
	       $PHP_USERS_HEADER_FILE,$PHP_USERS_FOOTER_FILE);
  } 
}

include $PHP_USERS_HEADER_FILE; 

heading("Reconfirm Your Account"); 

if ($query && mysql_num_rows($query) > 0) { 
?>
<p>You have been emailed a confirmation code for your account. Please follow the instructions in that email in order to activate your account. If you don't receive this email within a couple of minutes, please contact <a href="mailto:<?php echo $WEB_MASTER_EMAIL?>"><?php echo $WEB_MASTER_EMAIL?></a>.</p>
<?php
} else { 
?>
<p>Your account has not yet been confirmed. Until your email address has been verified you cannot login. Enter your email address in the field below in order to have an email sent to you in order for you to enable your account.</p>

<form method="post" action="<?php echo $_SERVER{'SCRIPT_NAME'}?>">
<b>Your Email Address:</b><br>
<input type="text" name="conf_email" value="<?php echo $_REQUEST['email']?>" />&nbsp;<input type="submit" value="Submit" />
</form>
<p>If you believe you have not registered before, you are welcome to <a href="newaccount.php">sign up</a> now.</p>
<? } ?>
<p>Return <a href="/">Home</a>.</p>

<?php
include $PHP_USERS_FOOTER_FILE;
?>
