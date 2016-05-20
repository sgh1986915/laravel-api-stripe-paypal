<?php
require_once(__DIR__ ."/../CONFIG.php");
if(isset($V2)) require_once dirname(__FILE__) . "/../reverse-whois-v2/config.php";
  else if(isset($V1)){ 
  	require_once dirname(__FILE__) . "/../reverse-whois-v1/config.php";
  }
else require_once dirname(__FILE__) . "/config.php";
require_once(__DIR__ ."/../util.php");
require_once(__DIR__ ."/../users/whois_server.inc");
require_once(__DIR__ ."/../httputil.php");
require_once(__DIR__ ."/../models/report.php");

function output_json_error($obj){
  echo json_encode($obj);
}
function get_num_tokens($str, $delim){
  return count(explode($delim, $str));
}
function get_report_price($options){
  return $options['num_domains'] * 10;  
}

function linkfy_report_detail($text, $report){
  global $app_root;
  
  $hashed_report_id = StringUtil::hash_dn($report->report_id);

  $url =  build_url("$app_root/reverse-whois/report_detail.php", array('report_id'=>$hashed_report_id)) ;
  
  return "<a href=\"$url\" class =\"tabfy\">$text</a>";
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

$SQL = sprintf("select  count(rep.report_id) from whois_report rep where username = '%s' and view_flag = 1", mysql_real_escape_string($username));
$result = mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error() . " <br/>query:$SQL");

if($row=mysql_fetch_array($result)){
	$responce->records=$row[0];
}

$filter="";
if($sidx){
	$filter=" order by $sidx $sord";
}
if(!$filter){
	$filter=" order by created_date desc";
}
$SQL = sprintf("select  rep.* from whois_report rep where username = '%s' and view_flag = 1 $filter LIMIT $start , $limit", mysql_real_escape_string($username));

$result = mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error() . " <br/>query:$SQL");

$i=0; 
while($row = mysql_fetch_array($result,MYSQL_ASSOC)) {
  $rep = Report::get_report_from_db_row($row);
   $responce->rows[$i]['id']=$rep->report_id; 
   $search_terms = linkfy_report_detail($rep->render_search_terms(), $rep);
   
   $responce->rows[$i]['cell']=array($rep->created_date, $search_terms, $rep->num_total_d, $rep->price); 
   $i++; 
}


$responce->page = $page; 

$responce->total = ceil($responce->records/$limit); 

echo json_encode($responce); 
 
?>
