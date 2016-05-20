<?php require_once __DIR__ . "/../invoice/invoice.php";
class CustomInvoiceGen{
	public static function generateSimpleUnPaidInvoice($invoice_data){
		return self::generateSimpleInvoice($invoice_data,0);
	}
	public static function generateSimplePaidInvoice($invoice_data){
		return self::generateSimpleInvoice($invoice_data,1);
	}	
  public static	function generateSimpleInvoice($invoice_data, $paid){
    $item_name=$invoice_data['item_name'];
    $username=$invoice_data['username'];
    $email_to=$invoice_data['email_to'];
    $email_ccs=$invoice_data['email_ccs'];
    $email_bccs=$invoice_data['email_bccs'];
    $invoice_num=$invoice_data['invoice_num'];
    $price=$invoice_data['price'];
	$invoiceDate=Invoice::normalizeInvoiceDate($invoice_data['invoice_date']);
	
    $invoice_desc=$invoice_data['invoice_desc'];
    $invoice_file_path=Invoice::generateInvoiceFilePath(array('username'=>$username, 'email_to'=>$email_to, 'invoice_num'=>$invoice_num));

    //content data                                                                                                                                                                                                               
    $sendTo=$email_to;

    

$invoiceContent = array(
                        "invoiceNumber"=>$invoice_num,
                        "invoiceDate"=>$invoiceDate,
                        "sendTo"=>$sendTo,
                        "item1Quantity"=> "1",
                        "item1Number"=>"1",
                        "item1Description"=>$item_name,
                        "item1UnitPrice"=>$price,
                        "item1Price"=>$price,
                        "subtotal"=>$price,
                        "totalPrice"=>$price
                        );
 if($invoice_data['invoice_content']){
 	$invoiceContent = array_merge($invoiceContent, $invoice_data['invoice_content']);
 }       
 $invoice_create_param=array('invoice_num'=>$invoice_num,
                                   'invoice_desc'=>$invoice_desc,
                                   'username'=>$username,
                                   'invoice_content'=>json_encode($invoiceContent),
                                   'invoice_file_path'=>$invoice_file_path,
                                    'email_to'=>$email_to,
                                    'email_ccs'=>$email_ccs,
                                    'email_bccs'=>$email_bccs
                                   );
 if($paid){
 	return Invoice::markInvoiceToCreate($invoice_create_param);
 }
 else                
	return Invoice::markUnPaidInvoiceToCreate($invoice_create_param);
  }
 }
?>