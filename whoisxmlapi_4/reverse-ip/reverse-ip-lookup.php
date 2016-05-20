<?php
set_time_limit(100);
//print_r($_REQUEST);

$time = microtime(true);
function validate_reverseip_input(){

    return true;
}


if(!validate_reverseip_input())return;
if(isset($V2))
    require_once dirname(__FILE__) . "/../reverse-ip-v2/config.php";
else {
    require_once dirname(__FILE__) . "/config.php";
}


require_once(__DIR__ ."/../util.php");
//require_once(__DIR__ ."/prices.php");
require_once(__DIR__ ."/../models/reverse_ip_report.php");




$page = $_GET['page']; // get the requested page 
$limit = $_GET['rows']; // get how many rows we want to have into the grid 
$sidx = $_GET['sidx']; // get index row - i.e. user click to sort 
$sord = $_GET['sord']; // get the direction 
if(!$sidx) $sidx = 'id';
if(!$limit)$limit=10;

//if ($page > $total_pages) 
//  $page=$total_pages; 
$start = $limit*$page - $limit;
if($start<0)$start=0;
$input=$_REQUEST['input'];

$res = ReverseIPReport::get_reverseip_preview(array('start'=>$start,'page'=>$page,'limit'=>$limit,  'input'=>$input, 'showDomainsDetails'=>$_REQUEST['showDomainsDetails']));


if(is_object($res)){

    if(isset($SHOW_QUERY_TIME)){
        $res->time= number_format(microtime(true) - $time, 2);
    }

    $res = json_encode($res);
}

echo $res;

?>
