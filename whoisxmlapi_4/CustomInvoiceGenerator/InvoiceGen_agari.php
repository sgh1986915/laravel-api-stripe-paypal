<?php 
require_once __DiR__ . "/CustomInvoiceGen.php";
require_once __DIR__ . "/../users/account.php";
require_once __DIR__ . "/../admin_utils/PriceCalc.php";
require_once __DIR__ ."/payment_options.php";

$invoice_date2=date('Y_m_d');
$invoice_date=date('M d, Y');
$username="aws@agari.com";


$base_queries=750000;
$cap_queries=27500000;
$rate=0.0013333;
$query_quantity=max(0,$cap_queries-getAccountBalance($username)-$base_queries);
//$query_quantity=getAccountBalance($username)-$base_queries;

echo "balance for $username is $query_quantity<br/>";
if($query_quantity<=0)return;


$usage_date=date("Y-m");

$item_name="$query_quantity whois queries to account $username for $usage_date" .
"\n(at a rate of $rate/query)";


$email_to="aws@agari.com";
//$email_to='topcoder1@gmail.com';
$email_bccs='topcoder1@gmail.com,accounting@whoisxmlapi.com,chaag@agari.com';
$invoice_num="$username"."_".$invoice_date2;
$price=$query_quantity*$rate;
$invoice_desc="";
$sendTo="AGARI<br/>";


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