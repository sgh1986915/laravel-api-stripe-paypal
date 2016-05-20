<?php 
require_once __DiR__ . "/CustomInvoiceGen.php";
require_once __DIR__ . "/../users/account.php";
require_once __DIR__ . "/../admin_utils/PriceCalc.php";

$invoice_date=date('Y_m_d');
$username="cyren";

$query_quantity=5000000;

$usage_date=date("Y-m", strtotime("first day of previous month") );

$item_name="$query_quantity whois queries to account $username and newly registered domains enterprise plan for $usage_date";

$email_to="Inga.Harpunsky@cyren.com";
//$email_to="topcoder1@gmail.com";

$email_bccs='topcoder1@gmail.com, accounting@whoisxmlapi.com';
$invoice_num="$username"."_".$invoice_date;
$price=800;

$invoice_desc="";
$params=array(
	      'item_name'=>$item_name,
	      'price'=>$price,
	      'email_to'=>$email_to,
	      'email_bccs'=>$email_bccs,
	      'username'=>$username,
	      'invoice_date'=>$invoice_date,
	      'invoice_num'=>$invoice_num,
	      'invoice_desc'=>$invoice_desc

);
CustomInvoiceGen::generateSimpleUnPaidInvoice($params);


function getAccountBalance($username){
	$userAccount = new UserAccount($username);
	$userAccount->load_from_db();
	return $userAccount->balance;
}

?>