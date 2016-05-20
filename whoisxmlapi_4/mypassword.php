<?php 
require_once("util.php");
require_once("httputil.php");
require_once( __DIR__. "/users/whois_server.inc");
my_session_start();

$errors ="";
$password = isset($_REQUEST['ch_password']) ? $_REQUEST['ch_password'] : "";
$password2 = isset($_REQUEST['ch_password2']) ? $_REQUEST['ch_password2'] : "";
$username=false;
if(isset($_SESSION['myuser'])){
	$user= $_SESSION['myuser'];
	$username=$user->username;
}
if (isset($_POST['submit'])) {
	if(!$username){
		$errors="Session has expired";
		
	}
	if(strlen($errors)<=0){
		if(strlen($password) <4)$errors="Password length must be greater or equal to 4.";
		if ($password != $password2) { $errors .= "The passwords you entered do not match<br>"; }
	}
	if(strlen($errors)<=0){

  		if(!connect_to_whoisserver_db($username)){
  			$errors = 'unable to connect to database.';
  		}
  		else{
			
 			if(strlen($errors)<=0){
 			
  				$sql = "
        			UPDATE users
                    	SET PASSWORD='".mysql_escape_string($password) ."' where username= '". mysql_escape_string($username)."'";
  				mysql_query($sql) or ($errors = "Error in query: $sql - ".mysql_error());
				
  				
			}
  		}
	}
	
}
?>
<?php
	$pages = array('right' => 'mypassword_main.php');	
	include "template.php";
?>
