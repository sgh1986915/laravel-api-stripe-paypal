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
// $Id: login.php,v 1.1.1.1 2003/06/03 14:12:24 byrnereese Exp $
//require_once("setup.php");
require_once("users.inc");
include($PHP_USERS_HEADER_FILE);
if ($_CURRENT_USER->is_anonymous) {
?>
  <p>The page you requested requires that you login first. Please enter your username and password in the fields below, or <a href="<?php echo $USERS_BASE_URL; ?>/newaccount.php">create an account</a> now.</p>
<?php
  include("fragments/loginbox.php");
} else {
?>
  <p>You are currently logged in as another user. If you wish to login as a different user, please <a href="logoffconfirm.php?returnto_url=<?php echo urlencode($_GET['returnto']);?>">logoff</a> and then login again.</p>
<?php
}
include($PHP_USERS_FOOTER_FILE);
?>