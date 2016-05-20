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
// $Id: users_header.php,v 1.1.1.1 2003/06/03 14:12:24 byrnereese Exp $

include_once("users.conf");
if (!isset($PAGE_TITLE)) {
  $PAGE_TITLE = "PHP users";
}
?>
<html>

<head>
  <title><?=$PAGE_TITLE?></title>
  <link rel="stylesheet" type="text/css" href="<?php echo $USERS_BASE_URL;?>/styles.css">
</head>

<body>

<table cellspacing="0" cellpadding="5" border="0" width="100%" bgcolor="#CCCCCC">
  <tr>
    <td><b>PHP users</b></td>
    <td align="right">
<?php
if ($_CURRENT_USER->is_anonymous) {
?>
      <a class="buttonLink" href="<?php echo $USERS_BASE_URL;?>/login.php?returnto=<?php echo returnto_url_enc();?>">[ Login ]</a>
<?php
} else {
  if ($_CURRENT_USER->can_edit_self()) {
?>
      <a class="buttonLink" href="users_edit.php?returnto=<?php echo returnto_url_enc();?>">[ My Settings ]</a>&nbsp;&nbsp;
<?php
  }
?>
      <a class="buttonLink" href="<?php echo $USERS_BASE_URL;?>/logoffconfirm.php">[ Logoff ]</a>
<?php
}
?>
    </td>
  </tr>
</table><br /><!--<br />-->

