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
// $Id: utils.inc,v 1.1.1.1 2003/06/03 14:12:24 byrnereese Exp $

include_once("users.conf");

function show_error($error,$header =false,$footer=false) {
  global $WEB_MASTER_EMAIL,$_CURRENT_USER;
  if($header)include $header;
  
  echo "<br/><p class=\"errorMsg\">An error has occured:<br>$error <span>If you need further assistence, please contact your <a href=\"mailto:$WEB_MASTER_EMAIL\">technical support</a>.</span>\n</p>\n";
 
  if($footer)include $footer;
  //exit;
}

function returnto_url() {
  if ($_SERVER{'QUERY_STRING'})
    return $_SERVER{'SCRIPT_NAME'} . "?" . $_SERVER{'QUERY_STRING'};
  	//return  $_SERVER['PHP_SELF'] . "?" . $_SERVER{'QUERY_STRING'};
  else //return  $_SERVER['PHP_SELF'];
    return $_SERVER{'SCRIPT_NAME'};
}

function returnto_url_enc() {
  return rawurlencode(returnto_url());
}

function heading($str,$color='#CCCCCC') {
 echo "<table cellpadding=\"2\" cellspacing=\"0\" border=\"0\" width=\"100%\"><tr class=\"heading\"><td>&nbsp;".$str."</td></tr></table>\n";
}

function pull_down($list,$selected="",$two_dim=1) {
  while (list($key,$val) = each($list)) {
    echo "<option".($two_dim ? " value=\"$key\"" : "");
    if ((!$two_dim && $selected == $val) || ($two_dim && $selected == $key)) {
      echo " selected";
    }
    echo ">$val</option>\n";
  }
}

class Crumb {
  var $name;
  var $link;
  function toString() {
    if ($this->link == "") {
      return "$this->name";
    } else {
      return "<a href=\"$this->link\" class=\"bct\">$this->name</a>";
    }
  }
}

class BreadCrumbTrail {
  var $crumbs;
  function addCrumb($name,$link="") {
    if (!is_array($this->crumbs)) {
      $this->crumbs = array();
    }
    $crumb = new Crumb;
    if ($link != "") {
      $crumb->link = $link;
      $crumb->name = $name;
    } else {
      $crumb->link = "";
      $crumb->name = $name;
    }
    $this->crumbs[] = $crumb;
  }

  function size() {
    return count($this->crumbs);
  }

  function toString() {
    $str .= "<span class=\"bct\"><b><a href=\"/\" class=\"bct\">Home</a>";
    for ($i=0;$i<$this->size();$i++) {
      $crumb = $this->crumbs[$i];
      $str .= " &gt; " . $crumb->toString();
    }
    $str .= "</b></span>";
    return $str;
  }
}

class Menu {
  var $name;
  var $items;
  function addItem($item) { 
    if (!is_array($this->items)) {
      $this->items = array();
    }
    $this->items[] = $item;
  }
  function toString() { 
    global $MENU_BG_COLOR;
    if (count($this->items) == 0) { return; }
?>
<table cellpadding="4" cellspacing="0" border="0" width="100%">
  <tr>
    <td class="menuLabel" nowrap="nowrap"><b>[<?=$this->name?>]</b></td>
    <td align="center" width="100%" class="menuText">
<?php
    while (list($i,$item) = each ($this->items)) {
      if ($i > 0) { echo " | "; }
      echo $item;
    }
?>
    </td>
    <td nowrap="nowrap" align="right" class="menuLabel"><b>[<?=$this->name?>]</b></td>
  </tr>
</table>
<?php
  }
}

?>
