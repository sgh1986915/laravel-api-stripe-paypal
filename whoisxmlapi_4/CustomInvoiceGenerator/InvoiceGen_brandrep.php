<?php 
require_once __DiR__ . "/CustomInvoiceGen.php";
require_once __DIR__ . "/../users/account.php";
require_once __DIR__ . "/../admin_utils/PriceCalc.php";

$invoice_date=date('Y_m_d');
$username="brandrep";

$usage_date=date("m/17/Y", strtotime("first day of previous month") ) . " to ". date("m/17/Y");

$item_name="Monthly membership subscription of unlimited Reverse Whois lookups/month for BrandRep<br/>Term: $usage_date";

$email_to="vpopovici@brandrep.com";
$email_bccs='topcoder1@gmail.com, accounting@whoisxmlapi.com';
$invoice_num="$username"."_".$invoice_date;
$price=1500;

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
CustomInvoiceGen::generateSimplePaidInvoice($params);
echo "finished";

function getAccountBalance($username){
	$userAccount = new UserAccount($username);
	$userAccount->load_from_db();
	return $userAccount->balance;
}

?>