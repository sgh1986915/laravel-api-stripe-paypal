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
// $Id: processlogin.php,v 1.2 2003/06/06 05:32:11 byrnereese Exp $

require_once("users/users.inc");
require_once("util.php");
require_once("httputil.php");
require_once dirname(__FILE__). '/db_cart/cart_util.php';

$_aj = false;
if(array_key_exists('_aj', $_POST))$_aj = $_POST['_aj'];
else if(array_key_exists('_aj',$_GET))$_aj= $_GET['_aj'];

$error=false;
$errMsg = array();
if(login($_POST['username'], $_POST['password'], $errMsg)){
  $cart_util = new cart_util();
  $er = $cart_util->merge_shopping_cart();
  //print_r($er);
  $returnto =  get_return_to_url();
  //echo " return to is $returnto";
  Header("Location: $returnto");
  //http_redirect($returnto, false,true);
  //exit;
} else{
	$error = "Login failed ";
	
	if(array_key_exists('msg',$errMsg)) $error .=': '.$errMsg['msg'];
	
	if($_aj){
		include "login_main.php";
	}
	else include "login.php";
	
	
	//this produces issues as get_return_to_url might already have login_error param, it would render dup error messages
	//Header("Location: ".get_return_to_url() . "?". http_build_query(array("login_error"=>$error)));  //not include _REQUEST as it would expose password plaintext
	
}
?>