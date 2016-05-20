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
// $Id: users_form.php,v 1.2 2003/06/06 05:32:11 byrnereese Exp $

$STATUSES = array('unconfirmed','enabled','disabled');
$USER_TYPES = array('normal','admin');
$BOOLEAN = array('true','false');
$phonetypes = array("home","mobile","pager","work");
$imtypes = array("MSN","Yahoo","Jabber","AIM/ICQ","Other");

$sql = "SELECT DISTINCT department FROM $USERS_DB.Profiles";
$query = mysql_query($sql) or die ("The query $sql failed!</table></td></tr></table>"); 
$num_of_rows = mysql_num_rows ($query) or die ("The query: '$query' did not return any data</table></td></tr></table>"); 
$DEPTS = array();
while (list($dname) = mysql_fetch_row($query)) {
  $DEPTS[]=$dname;
}
$formCount = 1;
?>
<form action="<?=$_SERVER['SCRIPT_NAME']?>" method="POST">
  <input type=hidden name="id" value="<?=$user_id ?>">
  <input type=hidden name="user_id" value="<?=$user_id ?>">
<?php
if (isset($_REQUEST['returnto'])) { 
  echo "<input type=hidden name=\"returnto\" value=\"".$_REQUEST['returnto']."\">"; 
} 
?>

  <p><table cellpadding="2" cellspacing="0" border="0" width="100%">

    <tr>
      <td colspan="2" width="33%">
        <b><?php echo $formCount++; ?>. Account Details</b>
        <hr noshade="noshade" size="1" width="100%" />
        <span class="formDesc">The fields below help to define the basic information about a user's account.</span><br /><br />
      </td>
    </tr>

    <tr>
      <td align="right"><b>Email:</b></td>
      <td><?=$email?></td>
    </tr>

    <tr>
      <td align="right"><b>Password:</b></td>
      <td><a href="change_password.php?id=<?=$user_id?>">Change Password</a></td>
    </tr>

<?php
if ($_CURRENT_USER->can_edit_users()) {
?>
    <tr>
      <td align="right"><b>Status:</b></td>
      <td><select name="status">
          <?php pull_down($STATUSES,$status,0); ?>
          </select>
      </td>
    </tr>
<?php
}
?>
    <tr>
      <td colspan="2">&nbsp;</td>
    </tr>

<?php
if ($_CURRENT_USER->can_set_perm()) {
?>
    <tr>
      <td colspan="2" width="33%">
        <b><?php echo $formCount++; ?>. Permissions</b>
        <hr noshade="noshade" size="1" width="100%" />
        <span class="formDesc">The fields below define the user's permissions for all known "domains." To grant or revoke a permission, simply check the corresponding box.</span><br /><br />
      </td>
    </tr>
    <tr>
      <td></td>
      <td>
        <table cellpadding="5" cellspacing="0" border="0">
          <tr class="tblHeading">
            <td>Domain</td>
            <td>Permission</td>
            <td align="center">Granted?</td>
          </tr>
<?php
  $PERMS = get_permissions_for_user($user_id);
  while ( list($up_domain,$up_labels) = each($__PERMISSIONS) ) {
    while ( list($up_label,$up_value) = each($__PERMISSIONS[$up_domain]) ) {
      if (isset($PERMS[$up_domain][$up_label])) {
        $granted = $PERMS[$up_domain][$up_label];
      } else {
        $granted = $__PERMISSIONS[$up_domain][$up_label];
      }
?>
    <tr>
      <td><?php echo $up_domain;?></td>
      <td><?php echo $up_label;?></td>
      <td align="center" class="propValue"><input type="checkbox" name="permission[<?php echo $up_domain;?>][<?php echo $up_label;?>]" value="1"<?php echo ($granted ? " checked" : "");?> /></td>
    </tr>
<?php
    }
  }
?>
        </table>
      </td>
    </tr>
    <tr>
      <td colspan="2">&nbsp;</td>
    </tr>
<?php
}
?>
    <tr>
      <td colspan="2" width="33%">
        <b><?php echo $formCount++; ?>. Profile</b>
        <hr noshade="noshade" size="1" width="100%" />
        <span class="formDesc">The fields below help to define the user's profile information.</span><br /><br />
      </td>
    </tr>
    <tr>
      <td align="right"><b>Last Name:</b></td>
      <td><input type="text" size="30" name="lname" value="<?=$lname?>" /></td>
    </tr>
    <tr>
      <td align="right"><b>First Name:</b></td>
      <td><input type="text" size="30" name="fname" value="<?=$fname?>" /></td>
    </tr>
    <tr>
      <td align="right"><b>Title:</b></td>
      <td><input type="text" size="30" name="title" value="<?=$title?>" /></td>
    </tr>
    <tr valign="top">
      <td align="right"><b>Department:</b></td>
      <td>
        <table cellpadding="2" cellspacing="0" border="0">
          <tr>
            <td><input type="radio" name="dept_type" value="new"<?=(!isset($department) ? " checked" : "")?> /></td>
            <td>Enter a new department:</td>
            <td><input type="text" name="new_department" size="30" /></td>
         </tr>
         <tr>
           <td><input type="radio" name="dept_type" value="existing"<?=(isset($department) ? " checked" : "")?> /></td>
           <td>Select an existing department:</td>
           <td>
             <select name="existing_department">
               <option>Select a category</option>
               <?php pull_down($DEPTS,$department,0); ?>
             </select>
           </td>
         </tr>
        </table>
      </td>
    </tr>  
    <tr>
      <td align="right"><b>Start Date:</b></td>
      <td valign="middle">
        <select name="startm">
<?php
  list($starty,$startm,$startd) = split("-",$start);
  for ($i=1;$i<=12;$i++) {
    if ($i == $startm) {
      echo "<option value=\"$i\" selected>$i\n";
    } else {
      echo "<option value=\"$i\">$i\n";
    }
    echo "</option>\n";
  }
?>
        </select>/<select name="startd">
<?php
  for ($i=1;$i<=31;$i++) {
    if ($i == $startd) {
      echo "<option value=\"$i\" selected>$i\n";
    } else {
      echo "<option value=\"$i\">$i\n";
    }
    echo "</option>\n";
  }
?>
        </select>/<select name="starty">
<?php
  for ($i=2000;$i<=2005;$i++) {
    if ($i == $starty) {
      echo "<option value=\"$i\" selected>$i\n";
    } else {
      echo "<option value=\"$i\">$i\n";
    }
    echo "</option>\n";
  }
?>
        </select><br />(MM/DD/YYYY)
      </td>
    </tr>
<?php
  for ($i=1;$i<=3;$i++) { 
?>
    <tr>
      <td align="right"><b>Phone <?=$i?>:</b></td>
      <td>
        <select name="p<?=$i?>type">
          <option value="">Select Type</option>
<?php
  $varname = "p".$i."type";
  while ( list($key,$val) = each($phonetypes) ) {
    if (${$varname} == $val) {
      echo "<option value=\"$val\" selected>$val\n";
    } else {
      echo "<option value=\"$val\">$val\n";
    }
    echo "</option>\n";
  }
  reset($phonetypes);
?>
        </select>&nbsp;
        <input type="text" size="15" name="phone<?=$i?>" value="<? $varname = "p$i"; echo ${$varname}; ?>" />
      </td>
    </tr>
<?php
}
?>
    <tr>
     <td align="right"><b>Instant Messaging:</b></td>
     <td>
       <select name="im_type">
         <option value="">Select Type</option>
<?php
  while ( list($key,$val) = each($imtypes) ) {
    if ($im_type == $val) {
      echo "<option value=\"$val\" selected>$val\n";
    } else {
      echo "<option value=\"$val\">$val\n";
    }
    echo "</option>\n";
  }
  reset($imtypes);
?>
        </select>&nbsp;
        <input type="text" size="15" name="im_username" value="<?=$im_username?>" />
      </td>
    </tr>

    <tr valign=top>
      <td align="right"><b>Biography:</b></td>
      <td><textarea cols="60" rows="10" name="biography" wrap="virtual"><?=$bio?></textarea></td>
    </tr>

    <tr>
      <td align="right"><b>Photo URL:</b></td>
      <td><input type="text" size="30" name="photourl" value="<?=$photourl?>" /></td>
    </tr>

    <tr>
      <td align="right"><b>Photo Dimensions:</b></td>
      <td><input type="text" size="5" name="photow" value="<?=$photow?>"> x <input type="text" size="5" name="photoh" value="<?=$photoh?>" /> (width x height)</td>
    </tr>

    <tr><td colspan="2">&nbsp;</td></tr>

    <tr>
      <td align="right"></td>
      <td><input type="submit" name="submit" value="Save Settings" /></td>
    </tr>
  </table>
</form>