<?php 
require_once __DiR__ . "/CustomInvoiceGen.php";
require_once __DIR__ . "/../users/account.php";
require_once __DIR__ . "/../admin_utils/PriceCalc.php";

$invoice_date=date('Y_m_d');
$username="cybersquared";

$usage_date=date("Y-m", strtotime("first day of previous month") );

$item_name="registrant alerts for $username for $usage_date";

$email_to="invoices@cybersquared.com";
$email_bccs='topcoder1@gmail.com, accounting@whoisxmlapi.com';
$invoice_num="$username"."_".$invoice_date;
$price=250;//PriceCalc::calculateWhoisQueryPrice($query_quantity,array('one_time'=>0));
$invoice_desc="Please pay your invoice <a href=\"$pay_link\">here</a>";
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
echo "finished";

function getAccountBalance($username){
	$userAccount = new UserAccount($username);
	$userAccount->load_from_db();
	return $userAccount->balance;
}

?>