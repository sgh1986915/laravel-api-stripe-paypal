<?php 
require_once __DiR__ . "/CustomInvoiceGen.php";
require_once __DIR__ . "/../users/account.php";
require_once __DIR__ . "/../admin_utils/PriceCalc.php";
require_once __DIR__ ."/payment_options.php";

$invoice_date=date('Y_m_d');
$username="emeraldgrs";

$query_quantity=100000;


$begin_date=date("m/09/Y");
$end_date=date("m/09/Y", strtotime("next month") );

$item_name="$query_quantity whois queries to account $username <br/>".
"Term: $begin_date to $end_date";

$email_to="accountspayable@afilias.info";
$email_bccs='topcoder1@gmail.com, accounting@whoisxmlapi.com';

$invoice_num="$username"."_".$invoice_date;
$price=PriceCalc::calculateWhoisQueryPrice($query_quantity,array('one_time'=>0));
$pay_link="https://www.whoisxmlapi.com/custom_order.php?special_order=1&price=$price&item_name=$item_name";
$invoice_desc="Please pay your invoice <a href=\"$pay_link\">here</a>";
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
	      	"sendTo"=>'Emerald Registrar Limited.
2 La Touche House
IFSC
Dublin 1
Ireland
Tel: + 353.1.854.1100',
					"paymentOption1"=>$PAYMENT_OPTION_WIRE,
            "paymentOption2"=>$PAYMENT_OPTION_CHECK
		),


);
CustomInvoiceGen::generateSimpleUnPaidInvoice($params);
echo "finished";



?>