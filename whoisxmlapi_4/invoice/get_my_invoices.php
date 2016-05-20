<?php require_once(__DIR__ ."/../CONFIG.php");
require_once __DIR__ ."/../util.php";
require_once __DIR__ ."/../httputil.php";
require_once __DIR__. "/../users/whois_server.inc";
require_once __DIR__ ."/invoice.php";


function output_json_error($obj){
  echo json_encode($obj);
}

function linkfy_invoice_detail($text, $invoice){
  global $app_root;
  

  $url =  build_url("$app_root/invoice/invoice_detail.php", array('invoice_num'=>$invoice->getInvoiceRawData('invoice_num'))) ;
  
  return "<a href=\"$url\" class=\"ignore_jssm\" >$text</a>";
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
  output_json_error(array("error"=>"you must login to view your invoices."));
  exit;
}



$page = $_GET['page']; // get the requested page 
$limit = $_GET['rows']; // get how many rows we want to have into the grid 
$sidx = $_GET['sidx']; // get index row - i.e. user click to sort 
$sord = $_GET['sord']; // get the direction 
if(!$sidx) $sidx = 'id'; 
if(!$limit)$limit=10;

// connect to the database 
if(!connect_to_whoisserver_db())return;

//if ($page > $total_pages) 
//  $page=$total_pages; 
$start = $limit*$page - $limit; 
if($start<0)$start=0;

$SQL = sprintf("select  count(rep.invoice_id) from invoice rep where username = '%s' and generate = 0 ", mysql_real_escape_string($username));
$result = mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error() . " <br/>query:$SQL");
$response=new StdClass();
if($row=mysql_fetch_array($result)){
	$response->records=$row[0];
}

$filter="";
if($sidx){
	$filter=" order by $sidx $sord";
}
if(!$filter){
	$filter=" order by invoice_date desc";
}
$SQL = sprintf("select  rep.* from invoice rep where username = '%s' and generate = 0  $filter LIMIT $start , $limit", mysql_real_escape_string($username));

$result = mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error() . " <br/>query:$SQL");

$i=0; 
while($row = mysql_fetch_array($result,MYSQL_ASSOC)) {
   $invoice = Invoice::get_invoice_from_db_row($row);
   $response->rows[$i]['id']=$row['invoice_id'];
   
   $response->rows[$i]['cell']=array($invoice->getInvoiceContentData('invoiceDate'), linkfy_invoice_detail($invoice->getInvoiceContentData('invoiceNumber'), $invoice), $invoice->getDescription(), $invoice->getInvoiceAmount());
   $i++; 
}


$response->page = $page; 

$response->total = ceil($response->records/$limit); 

echo json_encode($response); 
 
?>
