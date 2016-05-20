<?php
require_once("users/setup.php");
require_once("users.inc");
require_once("util.php");
require_once("httputil.php");
require_once("captcha.php");


my_session_start();

$errors ="";
$username = isset($_REQUEST['nc_username']) ? $_REQUEST['nc_username'] : "";
$password = isset($_REQUEST['nc_password']) ? $_REQUEST['nc_password'] : "";
$password2 = isset($_REQUEST['nc_password2']) ? $_REQUEST['nc_password2'] : "";
$email = isset($_REQUEST['nc_email']) ? $_REQUEST['nc_email'] : "";
$firstname = isset($_REQUEST['firstname']) ? $_REQUEST['firstname'] : "";
$lastname = isset($_REQUEST['lastname']) ? $_REQUEST['lastname'] : "";

$captcha_ans = isset($_REQUEST['captcha_ans'])?$_REQUEST['captcha_ans']:false;
$ur_ans = isset($_REQUEST['ur_ans'])?$_REQUEST['ur_ans']:false;

if (isset($_POST['submit']) or isset($_POST['submit1'])) {
  if(!connect_to_users_db()){
  	$errors = 'unable to connect to database.';
  }
  else{

	if (strlen($username) < 4) { $errors .= "Your username must be greater than 4 characters<br>"; }
  	if ($password != $password2) { $errors .= "The passwords you entered do not match<br>"; }
  	if (strlen($password) < 4) { $errors .= "Your password must be greater than 4 characters<br>"; }
  	if (strlen($email) == 0) { $errors .= "You must enter an email address<br>"; }

	if(!$ur_ans || (strlen($ur_ans)>0 && generateCaptchaAns($ur_ans,$secretKey) !== $captcha_ans)){
		$errors .="You must pass the human test<br>";
		error_log("urans is $ur_ans, expected ans is ".$captcha_ans);
	}
	error_log("ur_ans is $ur_ans, cap is $captcha_ans");
  	$sql = "SELECT username FROM $USERS_DB.users WHERE username='".mysql_escape_string($username)."'";
  	$query = mysql_query($sql);
  	if ($query && (mysql_num_rows($query) > 0)) {
   	 $errors .= "The username " .$username . " is already in use<br>";
  	}

 	if(strlen($errors)<=0){


  		$sql = "
        INSERT INTO $USERS_DB.Users
                    (username, email,status,password,createdDate)
             VALUES ('".mysql_escape_string($username) ."', '" . mysql_escape_string($email)."','unconfirmed','".mysql_escape_string($password)."',NOW())";
  		mysql_query($sql) or ($errors = "Error in query: $sql - ".mysql_error());

  		if(strlen($errors)<=0){
  			$sql = "INSERT INTO $USERS_DB.profiles (username) VALUES ('" .mysql_escape_string($username) . "')";
  			mysql_query($sql) or ($errors = "Error in query: $sql - ".mysql_error());
		}
  		if(strlen($errors)<=0){
			$token = time() . "::$username";
  			$sql = "INSERT INTO $USERS_DB.login_tokens (username,token) VALUES ('".mysql_escape_string($username) ."','".mysql_escape_string($token)."')";
  			mysql_query($sql) or ($errors = "Error in query: $sql - ".mysql_error());
  		}
  	}

  	if(strlen($errors)<=0){

  		send_confirmation($email,$token, $username, $password);

  		$returnto = "index.php";
		$loginErr="";
		autologin($username, $password, $loginErr);

  		Header("Location: ".$returnto);
  		//http_redirect($returnto, false, true);
		exit();
  	}

  }

}
function autologin($username, $password, &$err){
	//my_session_start();
	login($username,$password, $err);
}
//include $PHP_USERS_HEADER_FILE;
?>

<?php
	$pages = array('right' => 'newaccount_main.php');

	include "template.php";
?>
