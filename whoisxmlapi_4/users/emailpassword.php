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
// $Id: emailpassword.php,v 1.2 2003/06/06 05:32:11 byrnereese Exp $

require_once("users.inc");
$PAGE_TITLE = "Forgotten Password";

connect_to_users_db();
$sql = "SELECT email, password FROM $USERS_DB.Users WHERE email='".$_REQUEST['email']."'";
$query = mysql_query($sql);

if ($query && (mysql_num_rows($query) > 0)) {
  while (list($email,$password) = mysql_fetch_row($query)) {
    mail($email, "Your Password", "Below is the username and password information you requested.\n\nUsername: '$email'\nPassword: '$password'.\n\n","From: $WEB_MASTER <$WEB_MASTER_EMAIL>\n");
  }
}

include $PHP_USERS_HEADER_FILE; 

heading("Forgot Your Password?"); 
echo "<p>";
if ($query && mysql_num_rows($query) > 0) { 
?>
<p>We've emailed your password. You should receive it within a minute. If you don't, please send mail to <a href="mailto:<?=$WEB_MASTER_EMAIL?>"><?=$WEB_MASTER_EMAIL?></a>.</p>
<?php
} else { 
?>
<p>We could not find an email and password corresponding with the email address you entered. Perhaps you registered with a different email address. Use the form below to try again.</p>

<form method="post" action="emailpassword.php">
  <b>Your Email Address:</b><br />
  <input type="text" name="email" />&nbsp;<input type="submit" value="Submit" />
</form>

<p>If you believe you have not registered before, you are welcome to <a href="newaccount.php">sign up</a> now.</p>

<?php
}
?>

<p>Return <a href="/">Home</a>.</p>

<? include $PHP_USERS_FOOTER_FILE; ?>