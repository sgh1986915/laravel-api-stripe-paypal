<?php
//require_once("users/setup.php");
require_once("users/users.inc");
require_once("util.php");
require_once("httputil.php");
require_once dirname(__FILE__)."/db_cart/cart_util.php";

$DEFAULT_RESERVE = 500;
$DEFAULT_THRESHOLD = 10;


my_session_start();

$errors ="";
$username = isset($_REQUEST['nc_username']) ? $_REQUEST['nc_username'] : "";
$password = isset($_REQUEST['nc_password']) ? $_REQUEST['nc_password'] : "";
$password2 = isset($_REQUEST['nc_password2']) ? $_REQUEST['nc_password2'] : "";
$email = isset($_REQUEST['nc_email']) ? $_REQUEST['nc_email'] : "";
$firstname = isset($_REQUEST['firstname']) ? $_REQUEST['firstname'] : "";
$lastname = isset($_REQUEST['lastname']) ? $_REQUEST['lastname'] : "";



if (isset($_POST['submit'])) {
	if(!isset($_SESSION['tries']))$_SESSION['tries'] = 30;
	$_SESSION['tries']--;
	if(!isset($_SESSION['last_try']))$_SESSION['last_try'] = time();
	if($_SESSION['tries'] <=0){
		$dur = 300 - (time() - $_SESSION['last_try']);	
		if($dur >0){
			$errors .="You have tried too many times, please wait for $dur seconds before you can attempt again.";
		}
		else{
			$_SESSION['tries'] = 30;
		}
	}
	$_SESSION['last_try'] = time();
	if(strlen($errors)<=0){
	
		
		
		if (strlen($username) < 4) { $errors .= "Your username must be greater than 4 characters<br>"; }
  		else if(!validate_username($username)){
			$errors .= "Your username must contain only letters, numbers, underscore, dot,dash and @.";
		}
  		if ($password != $password2) { $errors .= "The passwords you entered do not match<br>"; }
  		if (strlen($password) < 4) { $errors .= "Your password must be greater than 4 characters<br>"; }
  		else if(!validate_password($password)){
  			$errors .="Your password must contain only letters, numbers, undescore, dot dash, ! and @.";
  		}
  		if (strlen($email) == 0) { $errors .= "You must enter an email address<br>"; }
		
		
  		include("captcha/securimage.php");
  		$img = new Securimage();
  
  		if(stripos($email, "spidnet")!==false || !($img->check($_POST['captcha']))){//special check for hacker.. remove later
  			$errors .="You must type in the correct code.<br>";
  		}
		/*
  		if($_SESSION["captcha"]!=$_POST["captcha"]){
			$errors .="You must type in the correct code. <br>";
		}*/
	}
	if(strlen($errors)<=0){

  		if(!connect_to_whoisserver_db($username)){
  			$errors = 'unable to connect to database.';
  		}
  		else{
			if(strlen($errors)<=0){
  				$sql = "SELECT username FROM users WHERE username='".mysql_escape_string($username)."'";
  				$query = mysql_query($sql);
  				if ($query && (mysql_num_rows($query) > 0)) {
   	 				$errors .= "The username " .$username . " is already in use<br>";
  				}
			}
 			if(strlen($errors)<=0){
  				$sql = "
        			INSERT INTO users
                    	(username, email,status,password,createdDate)
             			VALUES ('".mysql_escape_string($username) ."', '" . mysql_escape_string($email)."','unconfirmed','".mysql_escape_string($password)."',NOW())";
  				mysql_query($sql) or ($errors = "Error in query: $sql - ".mysql_error());
 				if(strlen($errors)<=0){
 				  $sql = "SELECT username FROM user_account WHERE username='".mysql_escape_string($username)."'";
          $query = mysql_query($sql) or ($errors = "Error in query: $sql - ".mysql_error());
        }
        if(strlen($errors)<=0){
           if ($query && (mysql_num_rows($query) === 0)) { //if user account is already set, possibly during order, then leave it alone
            
            $sql = "INSERT INTO user_account (username, balance, reserve, warn_threshold) VALUES ('" .mysql_escape_string($username) . "', $DEFAULT_RESERVE, $DEFAULT_RESERVE, $DEFAULT_THRESHOLD )"; 
    
            mysql_query($sql) or ($errors = "Error in query: $sql - ".mysql_error());
          }
        }
         
				
  				if(strlen($errors)<=0){
  					$sql = "INSERT INTO profiles (username, firstName, lastName) VALUES ('" .mysql_escape_string($username). "', '".mysql_escape_string($firstname) . "', '". mysql_escape_string($lastname) ."')";
  					mysql_query($sql) or ($errors = "Error in query: $sql - ".mysql_error());
				}
  				if(strlen($errors)<=0){
					//$token = time() . "::$username";
  					$token =md5(uniqid(rand(), true));
  					$sql = "INSERT INTO login_tokens (username,token) VALUES ('".mysql_escape_string($username) ."','".mysql_escape_string($token)."')";
  					mysql_query($sql) or ($errors = "Error in query: $sql - ".mysql_error());
  				}
			}
  		}
	}

  	if(strlen($errors)<=0){

  		send_activation($email,$token, $username, $password);

  		$returnto = "preactivation.php";
		$loginErr="";
		//autologin($username, $password, $loginErr);

  		Header("Location: ".$returnto);
  		//http_redirect($returnto, false, true);
		exit();
  	}

  

}
function autologin($username, $password, &$err){
	//my_session_start();
	if(login($username,$password, $err)){
	  	$cart_util = new cart_util();
  		$er = $cart_util->merge_shopping_cart();
	}
}
//include $PHP_USERS_HEADER_FILE;
?>
<?php
	$pages = array('right' => 'newaccount_main.php'
		,'title'=>'Whois API Create New Account'
		, 'description'=>'Create a new account at Whois API'
	);

	include "template.php";
?>
