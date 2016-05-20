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
// $Id: logoffconfirm.php,v 1.2 2003/06/06 05:32:11 byrnereese Exp $

require_once("users.inc");
$PAGE_TITLE = "Logoff Confirmation";
include $PHP_USERS_HEADER_FILE; 
?>

<p>
  <b>You are about to log off of <?=$COMPANY_NAME?>?</b><br />
  &nbsp;&nbsp;<li>Once you sign out, you will not be able to access your personalized settings.</li>
  &nbsp;&nbsp;<li>To retrieve your settings, you will need to sign in by entering your email and password.</li>
  <form action="logoff.php" method="post">
    <input type="hidden" name="returnto" value="<? echo $_REQUEST['returnto']; ?>" /><input type="submit" value="Log Off" />
    <p>If you don't want to sign out, <a href="<? echo $_REQUEST['returnto']; ?>">go back</a>.</p>
  </form>
</p>

<?php
include $PHP_USERS_FOOTER_FILE; 
?>