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
// $Id: users.inc,v 1.1.1.1 2003/06/03 14:12:24 byrnereese Exp $
include_once(dirname(__FILE__). '/../CONFIG.php');
include_once("users.conf");
require_once("utils.inc");
require_once(dirname(__FILE__). "/../email/Email.php");

function require_login() {
  global $USERS_BASE_URL;
  Header("Location: $app_root/login.php?returnto=".returnto_url_enc());
}

function connect_to_users_db() {
  global $USERS_DBHOST,$USERS_DB, $USERS_DBUSER, $USERS_DBPASS;
  if (!mysql_connect($USERS_DBHOST, $USERS_DBUSER, $USERS_DBPASS)) {
    // couldn't connect
    error_log( "could not connect ($USERS_DBHOST, $USERS_DBUSER, $USERS_DBPASS)");
	return false;
  }
  if (!mysql_select_db($USERS_DB)) {
    // couldn't connect
    error_log("could not select ($USERS_DB)");
	return false;
  }
  return true;
}

function send_confirmation($to,$token, $username, $password) {
  global $COMPANY_NAME,$USERS_BASE_URL,$WEB_MASTER,$WEB_MASTER_EMAIL;
  try{
  	//mail($email, "Welcome to $COMPANY_NAME", "Thank you for signing up. This email has been sent to you automatically. Please click the link below in order to confirm your account.\n\n$USERS_BASE_URL/confirm_account.php?token=".base64_encode($token)."\n\nEmail: $email\nToken: ".base64_encode($token)."\n\nThanks,\n$WEB_MASTER","From: $WEB_MASTER <$WEB_MASTER_EMAIL>\n");
  	$emailer=new Email();
	$emailer->from="support@whoisxmlapi.com";
	$subject = "Welcome to $COMPANY_NAME";
	$body = "Thank you for signing up with $COMPANY_NAME. This email has been sent to you automatically.  Your username is $username.  Please contact {$emailer->from} if you have any questions.";
	$emailer->send_mail($to,$subject,$body,null);

  }catch(Exception $e){
  	var_dump($e->getMessage());
  }
}
function send_activation($to,$activation_code, $username, $password) {
  global $COMPANY_NAME,$USERS_BASE_URL,$WEB_MASTER,$WEB_MASTER_EMAIL;
  try{
  	//mail($email, "Welcome to $COMPANY_NAME", "Thank you for signing up. This email has been sent to you automatically. Please click the link below in order to confirm your account.\n\n$USERS_BASE_URL/confirm_account.php?token=".base64_encode($token)."\n\nEmail: $email\nToken: ".base64_encode($token)."\n\nThanks,\n$WEB_MASTER","From: $WEB_MASTER <$WEB_MASTER_EMAIL>\n");
  	$emailer=new Email();
	$emailer->from="support@whoisxmlapi.com";
	$subject = "Welcome to $COMPANY_NAME";
	//$body = "Thank you for signing up with $COMPANY_NAME. This email has been sent to you automatically.  Your username is $username.  Please contact {$emailer->from} if you have any questions.";
	$activation_url="http://www.whoisxmlapi.com/activation.php?username=$username&activation_code=" . $activation_code;

	$body = "Thank you for signing up with $COMPANY_NAME. To confirm your email and activate your account, please click the below link:\n"
		. "$activation_url\n"
		. "If the above link does not work, please copy and paste the link on your web browser.\n"
		. "If you have any questions, please direct them to support@whoisxmlapi.com";

	$emailer->send_mail($to,$subject,$body,null);

  }catch(Exception $e){
  	var_dump($e->getMessage());
  }
}


// class User {
class User1 { // modified
  var $username;
  var $password;
  var $email;
  var $seclev;
  var $status='mother';
  var $is_anonymous;

  var $_PERMISSION_DATA;

#  function isAdmin() {
#    return ($this->utype == "admin");
#  }
	function __construct($username, $params=null) {
       $this->username=$username;
	   if($params!=null){
	   		foreach($params as $key=>$val){
	   			$this->$key = $val;
			}
	   }
   }
   /*
  function load_from_db() {
    global $USERS_DB;
    $sql = "
   SELECT email,password,status,seclev
     FROM $USERS_DB.users
    WHERE username=". $this->username;
    $query = mysql_query($sql);
    if(!$query){
    	echo ("The query failed! (".mysql_error()."): $sql");
    	return;
	}
	if ($query && (mysql_num_rows($query) > 0)) {
      list($this->email,
	  	$this->password,
	   $this->status,
	   $this->seclev) = mysql_fetch_row($query);
    }
  }*/

  function init() {
    global $USER_COOKIE,$_COOKIE,$COOKIE_KEY;
    $this->is_anonymous = 0;
    $cookie = $_COOKIE[$USER_COOKIE];
    if (isset($COOKIE_KEY)) {
      $iv = mcrypt_create_iv(mcrypt_get_iv_size (MCRYPT_BLOWFISH, MCRYPT_MODE_ECB), MCRYPT_RAND);
      $cookie = mcrypt_decrypt(MCRYPT_BLOWFISH, $COOKIE_KEY, base64_decode($cookie), MCRYPT_MODE_ECB, $iv);
    }
    $cookie = rtrim($cookie);
    $a = split('&', $cookie);
    $i = 0;
    while ($i < count($a)) {
      $b = split ('=', $a[$i]);
      $key = urldecode($b[0]);
      $value = urldecode($b[1]);
      $this->{$key} = $value;
      $i++;
    }
    $this->id = $this->{'uid'};
  }

  function can_edit_users() {
    return $this->get_permission('php_users','edit_users');
  }
  function can_list_users() {
    return $this->get_permission('php_users','list_users');
  }
  function can_edit_self() {
    return $this->get_permission('php_users','edit_self');
  }
  function can_set_perm() {
    return $this->get_permission('php_users','set_perm');
  }
  function can_edit_perm() {
    return $this->get_permission('php_users','edit_perm');
  }

  function set_user_cookie() {
    global $USER_COOKIE,$COOKIE_PATH,$COOKIE_DOMAIN,$COOKIE_KEY;
    $cookie = "email=".urlencode($this->email)."&uid=$this->id&seclev=$this->seclev";
    if (isset($COOKIE_KEY)) {
      $iv = mcrypt_create_iv(mcrypt_get_iv_size (MCRYPT_BLOWFISH, MCRYPT_MODE_ECB), MCRYPT_RAND);
      $cookie = base64_encode(mcrypt_encrypt (MCRYPT_BLOWFISH, $COOKIE_KEY, $cookie, MCRYPT_MODE_ECB, $iv));
    }
    $expire = (time() + (3600 * 24 * 365 * 5));
    setcookie($USER_COOKIE,$cookie,$expire,$COOKIE_PATH,$COOKIE_DOMAIN,0);
  }

  function unset_user_cookie() {
    global $USER_COOKIE,$COOKIE_PATH,$COOKIE_DOMAIN;
    setcookie($USER_COOKIE,"",(time() - 3600),$COOKIE_PATH,$COOKIE_DOMAIN);
  }

  function get_permission($domain,$label) {
    global $__PERMISSIONS;
    if ($this->is_anonymous) {
      return $__PERMISSIONS[$domain][$label];
    }
    // fetch permissions in database if they have not already been fetched
    if (!isset($this->_PERMISSION_DATA)) {
      $this->_PERMISSION_DATA = get_permissions_for_user($this->id);
    }
    if (!isset($__PERMISSIONS[$domain][$label])) {
      echo "Unknown permission $domain, $label";
      exit;
    } elseif (isset($this->_PERMISSION_DATA[$domain][$label])) {
      return $this->_PERMISSION_DATA[$domain][$label];
    } else {
      return 0; // this should never happen
    }
  }

}

function get_permissions_for_user($user_id) {
  global $USERS_DB;
  if(!connect_to_users_db())return;
  $sql = "
  SELECT domain,label,description,value
    FROM $USERS_DB.permissions
   WHERE user_id=".$user_id;
  $query = mysql_query($sql) or die ("The query failed! (".mysql_error()."): <pre><tt>$sql</tt></pre>");
  while ($query && (list($domain,$label,$description,$value) = mysql_fetch_row($query))) {
#   $PERMS[<domain>][<permission lavel>] = <value>;
    $PERMS[$domain][$label] = $value;
  }
  return $PERMS;
}
/*  //got rid of cookie
// This code is run every single time and initializes the
// $_CURRENT_USER object
$_CURRENT_USER = new User;
$_CURRENT_USER->is_anonymous = 1;
if (isset($_COOKIE[$USER_COOKIE])) {
  $_CURRENT_USER->init();
}
*/
?>