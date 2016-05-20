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
// $Id: confirm_account.php,v 1.1.1.1 2003/06/03 14:12:24 byrnereese Exp $

require_once("users.inc");

if (isset($_POST['submit'])) {
  connect_to_users_db();
  $sql = sprintf("
    SELECT u.userId,email,seclev 
      FROM Users u, login_tokens t 
     WHERE t.token='%s'
       AND t.userId = u.userId 
       AND u.password = '%s'",
		 base64_decode($token),
		 mysql_escape_string($password));
  $query = mysql_query($sql) or die("error in query: $sql - ".mysql_error());
  if (!$query || (mysql_num_rows($query) == 0)) {
    echo "Incorrect! Either the token or your password is incorrect, or this account has already been confirmed.";
    exit;
  } else {
    list($user_id,$email,$seclev) = mysql_fetch_row($query);
  }
  $sql = "UPDATE Users SET status='enabled' WHERE userId=$user_id";
  mysql_query($sql);
  $sql = "DELETE FROM login_tokens WHERE userId=$user_id";
  mysql_query($sql);
  
  $_CURRENT_USER->id     = $user_id;
  $_CURRENT_USER->seclev = $seclev;
  $_CURRENT_USER->email  = $email;
  $_CURRENT_USER->set_user_cookie();
  Header("Location: users_edit.php?id=$user_id");
  exit;
}
include $PHP_USERS_HEADER_FILE; 
?>

<form action="<?=$_SERVER['SCRIPT_NAME']?>" method="POST">
  <table cellpadding="3" cellspacing="0" border="0">
    <tr>
      <td align="right">Token:</td>
      <td><input type="text" size="30" name="token" value="<?=$token?>" /></td>
    </tr>
    <tr>
      <td align="right">Password:</td>
      <td><input type=password size=30 name="password" /></td>
    </tr>
    <tr>
      <td align="right">&nbsp;</td>
      <td><input type="submit" size="30" name="submit" value="Submit" /></td>
    </tr>
  </table>
</form>

<?php 
include $PHP_USERS_FOOTER_FILE;
?>


