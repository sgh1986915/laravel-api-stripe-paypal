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
// $Id: users_edit.php,v 1.1.1.1 2003/06/03 14:12:24 byrnereese Exp $

require_once("users.inc");
connect_to_users_db();

if ($_CURRENT_USER->is_anonymous) {
  show_error("You must be logged in to edit a profile.",
	     $PHP_USERS_HEADER_FILE,
	     $PHP_USERS_FOOTER_FILE);
}

$id = $_GET['id'];
if (!isset($id)) {
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
  $sql = "
  SELECT userId,email
    FROM $USERS_DB.users
   WHERE email='".$_POST['email']."'";
  //echo "<pre><tt>$sql</tt></pre>";
  $query = mysql_query($sql);

  if ($query && (mysql_num_rows($query) > 0)) {
    list($uid,$uemail) = mysql_fetch_row($query);
    if ($uemail != $_POST['email']) {
      echo "That email address already is in use.<br>";
      exit;
    }
  }

  if ($_CURRENT_USER->can_edit_users()) {
    $sql = sprintf("
 UPDATE $USERS_DB.users
    SET seclev=%d,
        status='%s'
  WHERE userId=%d",
		   $_POST['seclev'],
		   $_POST['status'],
		   $_POST['user_id']);
    //echo "<pre><tt>$sql</tt></pre>";
    mysql_query($sql);
  }

  if ($_CURRENT_USER->can_set_perm()) {
    $sql = sprintf("DELETE FROM $USERS_DB.permissions WHERE user_id=%d",$_POST['user_id']);
    mysql_query($sql);
    //echo "<pre><tt>$sql</tt></pre>";
    while (list($domain,$labels) = each($_POST['permission'])) {
      while (list($label,$value) = each($labels)) {
	$sql = sprintf("INSERT INTO $USERS_DB.permissions (value,user_id,label,domain) VALUES (%d,%d,'%s','%s')",
		       $value,
		       $_POST['user_id'],
		       mysql_escape_string($label),
		       mysql_escape_string($domain)
		       );
	//echo "<pre><tt>$sql</tt></pre>";
	mysql_query($sql);
      }
    }
  }

  $photow = $_POST['photow'];
  $photoh = $_POST['photoh'];

  $start = $_POST['starty']."-".$_POST['startm']."-".$_POST['startd'];
  if (!is_numeric($photoh)) { $photoh = 0; }
  if (!is_numeric($photow)) { $photow = 0; }
  $department = ${$dept_type."_department"};

  $sql = sprintf("
   UPDATE $USERS_DB.Profiles
      SET photoUrl='%s',
          photoWidth=%d,
          photoHeight=%d,
          startDate='%s',
          lastModified=NOW(),
          department='%s',
          firstName='%s',
          lastName='%s',
          title='%s',
          biography='%s',
          phone1='%s',
          phone1Type='%s',
          phone2='%s',
          phone2Type='%s',
          phone3='%s',
          phone3Type='%s',
          im_type='%s',
          im_userid='%s'
    WHERE userId=%d",
		 mysql_escape_string($_POST['photourl']),
		 $photow,
		 $photoh,
		 $start,
		 mysql_escape_string($_POST['department']),
		 mysql_escape_string($_POST['fname']),
		 mysql_escape_string($_POST['lname']),
		 mysql_escape_string($_POST['title']),
		 mysql_escape_string($_POST['biography']),
		 mysql_escape_string($_POST['phone1']),
		 mysql_escape_string($_POST['p1type']),
		 mysql_escape_string($_POST['phone2']),
		 mysql_escape_string($_POST['p2type']),
		 mysql_escape_string($_POST['phone3']),
		 mysql_escape_string($_POST['p3type']),
		 mysql_escape_string($_POST['im_type']),
		 mysql_escape_string($_POST['im_username']),
		 $_POST['user_id']
		 );
  //echo "<pre><tt>$sql</tt></pre>";
  $query = mysql_query($sql) or die ("The query failed (".mysql_error().") " . $sql);

  $returnto = $_POST['returnto'];
  if (!isset($returnto)) {
    $returnto = "$USERS_BASE_URL/users.php";
  }
  Header("Location: $returnto");
  exit;
}

$sql = "
    SELECT userId, email, status, seclev
      FROM users
     WHERE userId = $id";
$query = mysql_query($sql) or die ("The query failed! (".mysql_error()."): $sql");

if (mysql_num_rows($query) > 0) {
  list($user_id, $email, $status, $seclev) = mysql_fetch_row($query);
  $sql = "
    SELECT photoUrl,photoWidth,photoHeight,startDate,firstName,lastName,
           title,b.department,biography,phone1Type,phone1,phone2Type,phone2,
           phone3Type,phone3,im_type,im_userid
      FROM $USERS_DB.Profiles b
     WHERE b.userId=$id";
  $query2 = mysql_query($sql) or die ("The query failed! (".mysql_error()."): $sql");
  list($photourl,$photow,$photoh,$start,$fname,$lname,$title,$department,$bio,$p1type,$p1,$p2type,$p2,$p3type,$p3,$im_type,$im_username) = mysql_fetch_row($query2);
}
include $PHP_USERS_HEADER_FILE;
if (mysql_num_rows($query) > 0) {
  include "users_form.php";
} else {
  echo "<p>No user found.</p>";
}
include $PHP_USERS_FOOTER_FILE;
?>