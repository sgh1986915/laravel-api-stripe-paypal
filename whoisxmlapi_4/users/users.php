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
// $Id: users.php,v 1.1.1.1 2003/06/03 14:12:24 byrnereese Exp $

require_once("users.inc");
connect_to_users_db();

if (!$_CURRENT_USER->get_permission('php_users','list_users')) {
  show_error("You do not have permission to view this page.",
	     $PHP_USERS_HEADER_FILE,$PHP_USERS_FOOTER_FILE);
  //  echo "You do not have permission to view this page.";
  //  exit;
}

$alpha = $_GET['alpha'];
$order = $_GET['order'];

if ($alpha == "") {
  unset($alpha);
}
if (!isset($order)) {
  $order = "email";
}

$sql = "SELECT u.userId, title, status, firstName, lastName, email
          FROM users u, Profiles p
         WHERE u.userId=p.userId ".
               ($_CURRENT_USER->seclev == 9999 ? "" : "AND status='enabled'");

if (isset($alpha)) {
  $sql .= "
           AND (lastName LIKE '$alpha%'
             OR lastName LIKE '".strtoupper($alpha)."%')";
}

$sql .= " ORDER BY $order";

$query = mysql_query($sql) or die("Error: $sql<p>" . mysql_error());

$PAGE_TITLE = "User Listing";
include $PHP_USERS_HEADER_FILE;

$ALPHA = array('a','b','c','d','e','f','g','h','i','j','k','l','m','n','o','p','q','r','s','t','u','v','w','x','y','z');

echo "<center><a href=\"".$_SERVER['SCRIPT_NAME']."?order=$order\">All</a>";
while ( list($key,$val) = each($ALPHA) ) {
  echo " | <a href=\"".$_SERVER['SCRIPT_NAME']."?alpha=$val&order=$order\">";
  if ($alpha == $val) { echo "<b>".strtoupper($val)."</b>"; }
  else { echo strtoupper($val); }
  echo "</a>";
}
echo "</center><p>";

if (mysql_num_rows($query) > 0) {
?>

<table cellspacing="0" cellpadding="3" border="0" width="100%">
  <tr bgcolor="#CCCCCC">
    <td><b><a href="<?=$_SERVER['SCRIPT_NAME']."?alpha=$alpha&order=lastName"?>">Last Name</a></b></td>
    <td><b><a href="<?=$_SERVER['SCRIPT_NAME']."?alpha=$alpha&order=firstName"?>">First Name</a></b></td>
    <td><b><a href="<?=$_SERVER['SCRIPT_NAME']."?alpha=$alpha&order=email"?>">Email</a></b></td>
    <td><b><a href="<?=$_SERVER['SCRIPT_NAME']."?alpha=$alpha&order=title"?>">Title</a></b></td>
<?php
   if ($_CURRENT_USER->can_edit_users()) {
     echo "<td><b><a href=\"".$_SERVER['SCRIPT_NAME']."?alpha=$alpha&order=status\">Status</a></b></td>";
     echo "<td>&nbsp;</td>";
   }
?>
  </tr>
<?php
  if ($query && (mysql_num_rows($query) > 0)) {
    $i = 0;
    while (list($user_id, $title, $status, $fname, $lname, $email) = mysql_fetch_row($query)) {
      echo"  <tr".(++$i % 2 == 0 ? " class=\"altRowBg\"" : "").">\n";
      echo "    <td>$lname</td>\n";
      echo "    <td>$fname</td>\n";
      echo "    <td><a href=\"profile.php?id=$user_id\">$email</a></td>\n";
      echo "    <td>$title</td>\n";
      if ($_CURRENT_USER->can_edit_users()) {
	echo "    <td>$status</td>\n";
	echo "    <td><a href=\"users_edit.php?id=$user_id\">Edit</a></td>\n";
      }
      echo "  </tr>\n";
    }
  }
  echo "</table>\n";
} else {
  echo "<p>No users found.</p>\n";
}
include $PHP_USERS_FOOTER_FILE;
?>


