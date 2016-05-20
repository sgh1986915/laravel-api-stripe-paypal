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
// $Id: profile_data.php,v 1.1.1.1 2003/06/03 14:12:24 byrnereese Exp $

require_once("users.inc");
connect_to_users_db();

$sql = sprintf("
     SELECT u.email,photoUrl,photoWidth,photoHeight,b.userId,
            startDate,firstName,lastName,title,'department',
            biography,phone1Type,phone1,phone2Type,phone2,
            phone3Type,phone3,im_type,im_userid 
       FROM $USERS_DB.Profiles b, $USERS_DB.Users u 
      WHERE u.userId=b.userId 
        AND b.userId=%d",
	       $USER_ID);
$query = mysql_query($sql) or die ("The query $sql failed: $sql. ".mysql_error()); 
if (list($email,$photoUrl,$photow,$photoh,$id,$start,$fname,$lname,$title,$department,$bio,$p1type,$p1,$p2type,$p2,$p3type,$p3,$im_type,$im_userid) = mysql_fetch_row($query)) {
} else {
  echo "There was an error: <pre><tt>$sql</tt></pre>".mysql_error();
}
heading("Profile for $fname $lname"); 
echo "<br /><br />";
?>

      <table cellpadding="3" cellspacing="0" border="0" width="100%">
        <tr>
          <td width="20%" class="propName"><b>Name:</td>
          <td width="80%" class="propValue"><?="$fname $lname"?>
<?php
if ($ext != "") { 
  echo " (x$ext)";
}
?>
          </td>
        </tr>
        <tr>
          <td class="propName">Email:</td>
          <td class="propValue"><a href="mailto:<?=$email?>"><?=$email?></a></td>
        </tr>
        <tr>
          <td class="propName">Title:</td>
          <td class="propValue"><?=$title?></td>
        </tr>
<?php
if ($start != "") {
?> 
        <tr>
          <td class="propName">Start Date:</td>
          <td class="propValue">
            <?php list($starty,$startm,$startd) = split("-",$start); echo "$startm/$startd/$starty"; ?>
          </td>
        </tr>
<?php
}
?>
        <tr>
          <td class="propName">Department/Team:</td>
          <td class="propValue"><?=$teamName?></td>
        </tr>
<?php 
for ($i=1;$i<=3;$i++) { 
  $type = "p".$i."type";
  $phone = "p".$i;
  if (${$phone} != "") {
?>
        <tr>
          <td class="propName">Phone <?=$i?>:</td>
          <td class="propValue"><?="${$phone} (${$type})"?></td>
        </tr>
<?php
  } 
} 
?>
        <tr>
          <td class="propName">Preferred IM:</td>
          <td class="propValue"><?=$im_type?>: <?=$im_userid?></td>
        </tr>
        <tr valign="top">
          <td class="propName">Biography:</td>
          <td class="propValue"><?=$bio?></td>
        </tr>

        <tr valign="top">
          <td class="propName">Photo:</td>
	  <td>
<?php
if ($photoUrl != "") { 
?>
            <img src="<?=$photoUrl?>"<?=($photow != 0 ? " width=\"$photow\"" : "")?><?=($photoh != 0 ? " width=\"$photoh\"" : "")?> />
<?php
} else { 
?>
            No photo available.
<?php
} 
?>
          </td>
        </tr>
      </table>
