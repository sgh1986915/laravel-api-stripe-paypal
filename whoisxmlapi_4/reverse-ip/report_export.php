<?php
require_once(__DIR__ ."/../CONFIG.php");
if(isset($V2)) require_once dirname(__FILE__) . "/../reverse-ip-v2/config.php";

else require_once dirname(__FILE__) . "/config.php";
require_once(__DIR__ ."/../util.php");
require_once(__DIR__ ."/../users/whois_server.inc");
require_once(__DIR__ ."/../httputil.php");

require_once(__DIR__ ."/../models/reverse_ip_report.php");
require_once(__DIR__ ."/../models/report_util.php");
require_once(__DIR__ ."/../util/file_util.php");

  define ('PDF_HEADER_LOGO', false);
  define ('PDF_HEADER_LOGO_WIDTH', 0);
 
  
  
function get_num_tokens($str, $delim){
  return count(explode($delim, $str));
}

function output_error($array){
  echo $array['error'];
}
function export_to_file($dest_file, $response, $report, $format){ 
  if($format =='csv'){
    export_to_file_csv($dest_file,$response,$report, $format);
  }
else if($format =='pdf'){
  export_to_file_pdf($dest_file,$response,$report, $format);
}
}
function export_to_file_pdf($dest_file, $response, $report, $format){
  require_once dirname(__FILE__). "/report_pdf.php";
  // create new PDF document

  $pdf = new report_pdf(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

  // set document information
  //$pdf->SetCreator(PDF_CREATOR);
  $pdf->SetAuthor('Whois API LLC');
  //$pdf->SetTitle('TCPDF Example 011');
  //$pdf->SetSubject('TCPDF Tutorial');
  //$pdf->SetKeywords('TCPDF, PDF, example, test, guide');

  // set default header data

  $title = "Reverse IP Report";
  $ips = implode(",", $report->getIPs());
  $host_info=$report->isInputIP() ?  "" :" hosted on IP Addresses $ips ";
  $header = "Search result for ".$report->getReportName() ."$host_info\nCompiled on ".date('m/d/Y H:i:s',$report->created_date_long);
  $pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, $title, $header);

  // set header and footer fonts
  $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
  $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

  // set default monospaced font
  $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

  //set margins
  $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
  $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
  $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

  //set auto page breaks
  $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

  //set image scale factor
  $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

  //set some language-dependent strings
  $pdf->setLanguageArray($l);

  // ---------------------------------------------------------

  // set font
  $pdf->SetFont('helvetica', '', 12);

  // add a page
  $pdf->AddPage();

  //Column titles
  $header = $response->header;


  // print colored table

  $pdf->ColoredTable($header, $response);

// ---------------------------------------------------------
  ob_end_clean();
  //Close and output PDF document
  $pdf->Output($dest_file, 'F');
}
function export_to_file_csv($dest_file, $response, $report, $format){
  $fhandle = fopen($dest_file,'w+') or die('unable to open tmp file');
  $n = $response->records;
    fputcsv($fhandle, array("Domain Name", "IP Addresses"));
  for($i=0;$i<$n;$i++){
    fputcsv($fhandle, $response->rows[$i]['cell']);
  }
}

function output_export($response, $report){
  global $known_mime_types;
  global $report_export_dir;

  ini_set('memory_limit', '1024M');
  $format = isset($_REQUEST['f']) ? strtolower($_REQUEST['f']) : 'csv';
  $n=$response->records;
  $tmp_file = tempnam($report_export_dir, "rw_");
  export_to_file($tmp_file, $response, $report, $format);
  
    
  $file_path=$tmp_file;
  
  output_file($file_path, create_report_fname($report, $format), $known_mime_types[$format]);
}
function create_report_fname($report, $format){
    $name=$report->getReportName();
  $date = date('Y_m_d', $report->created_date_long);

  $s = "ReverseIP"  ."_$name". "_$date";
  return "$s.$format";
}


$report_id = $_REQUEST['r'];
if(empty($report_id)){
  output_error(array("error"=>"report_id is missing."));
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
  output_error(array("error"=>"you must login to view your reports."));
  exit;
}


set_time_limit(0);

$page = $_GET['page']; // get the requested page 
$limit = $_GET['rows']; // get how many rows we want to have into the grid 
$sidx = $_GET['sidx']; // get index row - i.e. user click to sort 
$sord = $_GET['sord']; // get the direction 
if(!$sidx) $sidx = 'id'; 
if(!$limit)$limit=10000;

// connect to the database 
if(!connect_to_whoisserver_db())return;

//if ($page > $total_pages) 
//  $page=$total_pages; 
$start = $limit*$page - $limit; 
if($start<0)$start=0;
$report_util = new report_util();

$plain_report_id =StringUtil::dehash_dn($report_id);

$report = $report_util->get_report_by_id($plain_report_id, 'RI');
if($report === false){
  output_error(array("error"=>"Failed to get report ".$report_util->error));
  exit;
}

$whois_record_path =  'reverse-ip/whois_record.php';
$report_id = StringUtil::hash_dn($report->report_id);


   $index = 0;
    $domains_details = $report->getDomainsDetails();

    $max = min($start+$limit, count($report->domain_names)-1);
   $response->header=array('Domain Name', "Hosting IPs");
   $start=0;
   $max=count($report->domain_names);
   for($i=$start;$i<$max;$i++){
      $domain_name = $report->domain_names[$i];

       $view_ips="";

       if($domains_details && $domains_details->$domain_name){

           $domain_detail=$domains_details->$domain_name;

           if($domain_detail->ips){
               $view_ips=implode(' ',$domain_detail->ips);
           }
       }

      $response->rows[$index]['id']=$i;
      $response->rows[$index++]['cell']=array($domain_name, $view_ips);
      
   }


$response->page = $page; 

$response->records = count($report->domain_names); 
$response->total = ceil($response->records/$limit); 

output_export($response, $report); 
 
?>
