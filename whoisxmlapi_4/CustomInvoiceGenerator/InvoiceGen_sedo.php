<?php 
require_once __DiR__ . "/CustomInvoiceGen.php";
require_once __DIR__ . "/../users/account.php";
require_once __DIR__ . "/../admin_utils/PriceCalc.php";
require_once __DIR__ ."/payment_options.php";

$invoice_date2=date('Y_m_d');
$invoice_date=date('M d, Y');
$username="sedoproduction";

$query_quantity=10000000-getAccountBalance($username);
echo "balance for $username is $query_quantity<br/>";
$usage_date=date("Y-m", strtotime("first day of previous month") );

$item_name="$query_quantity whois queries to account $username for $usage_date" .
"\n($2600 if < 5 million queries, $3800 if < 10 million queries)";


$email_to="accounts@sedo.de";
#$email_to='topcoder1@gmail.com';
$email_bccs='topcoder1@gmail.com, accounting@whoisxmlapi.com, laimonas.selenis@sedo.com, gregor.paw@sedo.com';
$invoice_num="$username"."_".$invoice_date2;
$price=($query_quantity<=5000000?2600:3800);
$invoice_desc="";
$sendTo="Sedo GmbH<br/>
lm Mediapark 6B<br/>
50670 Cologne(Germany)";


$invoiceContent = array(
                       
                        "sendTo"=>$sendTo,
                        "paymentOption1"=>$PAYMENT_OPTION_WIRE,
                        "paymentOption2"=>$PAYMENT_OPTION_CHECK
                        
                        );
                        
                        
$params=array(
	      'item_name'=>$item_name,
	      'price'=>$price,
	      'email_to'=>$email_to,
	      'email_bccs'=>$email_bccs,
	      'username'=>$username,
	      'invoice_date'=>$invoice_date,
	      'invoice_num'=>$invoice_num,
	      'invoice_desc'=>$invoice_desc,
	      'invoice_content'=>$invoiceContent

);
CustomInvoiceGen::generateSimpleUnPaidInvoice($params);
echo "finished";

function getAccountBalance($username){
	$userAccount = new UserAccount($username);
	$userAccount->load_from_db();
	return $userAccount->balance;
}

?>