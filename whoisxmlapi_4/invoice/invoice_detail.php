<?php 
	require_once __DIR__ . "/../users/users.inc";
	require_once __DIR__ . "/invoice.php";
	require_once __DIR__ . "/../util/file_util.php";
	require_once __DIR__ . "/../httputil.php";
	
$invoice_num=$_REQUEST['invoice_num'];

if(empty($invoice_num)){
  echo json_encode(array("error"=>"invoice_num is missing."));
  exit;
}
my_session_start();
$username = false;
if(isset($_SESSION['myuser'])){
   $user = $_SESSION['myuser'];

   if($user){
    
     $username = $user->username;
   }
}
if(!$username){
  echo json_encode(array("error"=>"you must login to view your invoices. ".print_r($_SESSION, 1)));
  exit;
}
$invoice = Invoice::getInvoice(array('invoice_num'=>$invoice_num));
if($invoice){
	$file=$invoice->getInvoiceRawData('invoice_file_path');
	$name=basename($file);
	output_file($file, $name, "pdf");
}

?>