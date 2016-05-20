<?php
require_once(__DIR__ ."/../CONFIG.php");
if(isset($V2)) require_once dirname(__FILE__) . "/../reverse-ip-v2/config.php";

else require_once dirname(__FILE__) . "/config.php";
require_once(__DIR__ ."/../util.php");
require_once(__DIR__ ."/../users/whois_server.inc");
require_once(__DIR__ ."/../httputil.php");
require_once(__DIR__ ."/../models/reverse_ip_report.php");
require_once(__DIR__ ."/../models/report_util.php");
require_once(__DIR__ ."/../util/base_conversion.php");

function output_json_error($obj){
  echo json_encode($obj);
}
function get_num_tokens($str, $delim){
  return count(explode($delim, $str));
}
function get_report_price($options){
  return $options['num_domains'] * 10;  
}


$report_id = $_REQUEST['report_id'];
if(empty($report_id)){
  output_json_error(array("error"=>"report_id is missing."));
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
  output_json_error(array("error"=>"you must login to view your whois reports."));
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
$report_util = new report_util();
$report = $report_util->get_report_by_id(StringUtil::dehash_dn($report_id), 'RI');
if($report === false){
  output_json_error(array("error"=>"Failed to get report ".$report_util->error));
  exit;
}


$report_id = StringUtil::hash_dn($report->report_id);



   $index = 0;   
   $max = min($start+$limit, count($report->domain_names)-1);
   $domains_details = $report->getDomainsDetails();

   for($i=$start;$i<=$max;$i++){
      $domain_name = $report->domain_names[$i];
      $encoded_domain = StringUtil::hash_dn($domain_name);

      $response->rows[$index]['id']=$i;
       $whois_url="http://whois.whoisxmlapi.com/$domain_name";
       $view_link="<a href=\"$whois_url\"  class=\"ignore_jssm new_window\"  target=_blank>View</a>";
       $view_ips="";

       if($domains_details && $domains_details->$domain_name){

           $domain_detail=$domains_details->$domain_name;

           if($domain_detail->ips){
               $view_ips=implode(' ',$domain_detail->ips);
           }
       }


       $response->rows[$index++]['cell']=array($domain_name, $view_link, $view_ips);
      
   }

$response->page = $page; 

$response->records = count($report->domain_names); 
$response->total = ceil($response->records/$limit);
$response->report_name=$report->input;
if(!$report->isInputIP()){

    $response->report_name=$report->input . " hosted on ". implode(", ",$report->getIPs());
}
echo json_encode($response); 
 
?>
