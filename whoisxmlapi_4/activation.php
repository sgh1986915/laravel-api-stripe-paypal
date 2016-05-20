<?php
require_once("CONFIG.php");
require_once("users/users.inc");
require_once("users/whois_server.inc");
require_once("util.php");
require_once("httputil.php");
$username=$_GET['username'];
$activation_code=$_GET['activation_code'];

if(!validate_username($username)){
	echo "Invalid username";
	exit;
}
if(strlen($activation_code)<32){
	echo "Invalid activation_code";
	exit;
}


if(!connect_to_whoisserver_db($username)){
  	echo 'unable to connect to database.  please try again later or contact support@whoisxmapi.com';
  	exit;
}
 // Update the database to set the "activation" field to null

 $query_activate_account = "update users U, login_tokens L set U.status='enabled' where U.username='" . mysql_real_escape_string($username) . "' and U.username=L.username and L.token='" . mysql_real_escape_string($activation_code) . "'";
 $result_activate_account = mysql_query( $query_activate_account);

 // Print a customized message:
 if (! mysql_error() ) //if update query was successfull
 {
 echo "<div>Your account $username is now active. You may now <a href=\"$app_root/login.php\">Login</a></div>";

 } else {
 echo '<div>Oops!  Your account could not be activated. Please recheck the link or contact the technical support at support@whoisxmlapi.com. ' . '</div>';

 }





?>