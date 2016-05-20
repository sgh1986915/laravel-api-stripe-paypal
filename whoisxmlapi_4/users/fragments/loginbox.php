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
// $Id: loginbox.php,v 1.2 2003/06/06 05:32:16 byrnereese Exp $

require_once dirname(__FILE__) . '/../CONFIG.php';
require_once("users.inc");
if (isset($_REQUEST['returnto'])) {
  $returnto = $_REQUEST['returnto'];
} else if (isset($returnto)) {
  // do nothing
} else {
  $returnto = returnto_url();
}

if ($_CURRENT_USER->is_anonymous) { 
  heading("User Login"); 
?>
  <form method="POST" action="<?="$app_root/users/processlogin.php"?>">
    <input type="hidden" name="returnto" value="<?php echo $returnto; ?>">
    <table cellpadding="2" cellspacing="0" border="0">
      <tr><td>Email:</td><td><input name="email" type="text" size="20" /></td></tr>
      <tr><td>Password:</td><td><input name="password" type="password" size="20" /></td></tr>
      <tr><td align="right" colspan="2"><input type="submit" name="submit" value="Login" /></td></tr>
      <tr>
        <td colspan="2">
          <a href="<?php echo $app_root;?>/users/forgotpassword.php?returnto=<?php echo $returnto; ?>">Forgot your password?</a>
          <p>Not registered? <a href="<?php echo $app_root?>/users/newaccount.php?returnto=<?php echo $returnto; ?>">Create an Account</a>!</p>
        </td>
      </tr>
    </table>
  </form>
  <br /><br />
<?php
} 
?>
