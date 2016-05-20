<?php 
require_once __DiR__ . "/CustomInvoiceGen.php";
require_once __DIR__ . "/../users/account.php";
require_once __DIR__ . "/../admin_utils/PriceCalc.php";
require_once __DIR__ ."/payment_options.php";

$invoice_date=date('Y_m_d');
$username="whodat";


$begin_date=date("m/10/Y");
$end_date=date("m/10/Y", strtotime("next month") );

$item_name="Daily Domain Name Data Enterprise for $username <br/>".
"Term: $begin_date to $end_date";

$email_to="norm@webcatcher.ca";
//$email_to="topcoder1@gmail.com";
$email_bccs='topcoder1@gmail.com, accounting@whoisxmlapi.com';

$invoice_num="$username"."_".$invoice_date;
$price=109;

$params=array(
	      'item_name'=>$item_name,
	      'price'=>$price,
	      'email_to'=>$email_to,
	      'email_bccs'=>$email_bccs,
	      'username'=>$username,
	      'invoice_date'=>$invoice_date,
	      'invoice_num'=>$invoice_num,
	      'invoice_desc'=>$invoice_desc,
	      'invoice_content'=>array(
	      	"sendTo"=>"$email_to"
		)


);
CustomInvoiceGen::generateSimplePaidInvoice($params);
echo "finished";



?>