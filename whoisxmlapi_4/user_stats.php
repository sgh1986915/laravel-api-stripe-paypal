<?php //require_once "users/quota.php"
// modified
error_reporting(0);
@ini_set('display_errors', 0);
?>

<?php
require_once "httputil.php";
require_once "users/users.inc";
require_once "users/account.php";
require_once "users/ipquota.php";
my_session_start();

function getRealIpAddr()
{
    if (!empty($_SERVER['HTTP_CLIENT_IP']))   //check ip from share internet
    {
      $ip=$_SERVER['HTTP_CLIENT_IP'];
    }
    elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))   //to check ip is pass from proxy
    {
      $ip=$_SERVER['HTTP_X_FORWARDED_FOR'];
    }
    else
    {
      $ip=$_SERVER['REMOTE_ADDR'];
    }
    return $ip;
}

$ip = getRealIpAddr();
$user = false;

//print_r($_SESSION);
if(isset($_SESSION['myuser'])){

	$user = $_SESSION['myuser'];
}
$stats_username ="anonymous";
$account_status = "";

if(is_object($user)){
	$stats_username = $user->username;
	$act = getUserAccount($stats_username);
}
else{
	$act = getIPQuota($ip);

}
if($act->balance =='' && $act->reserve =='')
	$account_status = "not available";

else $account_status = $act->balance . "/" . $act->reserve;
?>
user <?php echo $stats_username?> [<?php echo $ip ?>] account balance: <?php echo $account_status?>