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
// $Id: change_password.php,v 1.1.1.1 2003/06/03 14:12:24 byrnereese Exp $

require_once("users.inc");
connect_to_users_db();

$id = $_GET['id'];

if ($_CURRENT_USER->is_anonymous) {
  show_error("You must be logged in to edit a profile.",
	     $PHP_USERS_HEADER_FILE,
	     $PHP_USERS_FOOTER_FILE);
} else if (!isset($id)) {
  $id = $_CURRENT_USER->id;
}

if (!$_CURRENT_USER->can_edit_users() && ($id == $_CURRENT_USER->id && !$_CURRENT_USER->can_edit_self())) {
  show_error("
  You are not allowed to edit this user. Either: 
  <ul>
    <li>you are not the owner of this account</li>
    <li>you have not been granted permission to edit other people's accounts</li>
    <li>the account has not been confirmed and enabled yet</li>
  </ul>",$PHP_USERS_HEADER_FILE,$PHP_USERS_FOOTER_FILE);
} 

if (isset($_POST['submit'])) {
  $sql = "SELECT password FROM Users WHERE userId=".$_POST['user_id'];
  $query = mysql_query($sql) or die ("The query failed! (".mysql_error()."): $sql"); 
  if (mysql_num_rows($query) > 0) { 
    list($user_password) = mysql_fetch_row($query);
  } else {
    echo "No user found with that user id."; return;
  }
  
  if ($_POST['password'] != $user_password) {
    echo "The password you entered for your existing password is incorrect."; return;
  }	
  if ($_POST['password1'] != $_POST['password2']) {
    echo "The passwords you entered do not match."; return;
  }	
  
  $sql = sprintf("UPDATE Users SET password='%s' WHERE userId=%d",
		 mysql_escape_string($_POST['password1']),
		 $_POST['user_id']);
  mysql_query($sql);
  
  $returnto = $_POST['returnto'];
  if (!isset($returnto)) {
    $returnto = "$USERS_BASE_URL/users_edit.php?id=".$_POST['user_id']; 
  }
  Header("Location: $returnto");
  exit;
}

include $PHP_USERS_HEADER_FILE; 
?>

<form action="<?=$_SERVER{'SCRIPT_NAME'}?>" method=post>
<input type=hidden name="id" value="<?=$id ?>">
<input type=hidden name="user_id" value="<?=$id ?>">
<? if (isset($returnto)) { echo "<input type=hidden name=\"returnto\" value=\"$returnto\">"; } ?>

<p><table cellpadding=2 cellspacing=0 border=0 width="100%">
<tr>
<td colspan=2 width="33%"><font size=-1>
<b>1. Passwords</b>
<hr noshade size=1 width="100%">
<font size=-2>The fields below help to set your password.<p></font>
</font></td></tr>

<tr>
<td align=right><font size=-1><b>Existing Password:</b></font></td>
<td><input type=password name=password></td>
</tr>

<tr>
<td align=right><font size=-1><b>New Password:</b></font></td>
<td><input type=password name=password1></td>
</tr>

<tr>
<td align=right><font size=-1><b>Confirm New Password:</b></font></td>
<td><input type=password name=password2></td>
</tr>

<tr><td colspan=2>&nbsp;</td></tr>
<tr>
<td align=right></td>
<td><font size=-1><input type=submit name="submit" value="Save Password"></font></td>
</tr>

</table>
</form>
<? 
  include $PHP_USERS_FOOTER_FILE;
?>


