<?php 
$term = (isset($_REQUEST['term']) ? $_REQUEST['term'] : false);
if(empty($term))return;
require_once(__DIR__ ."/config.php");
require_once(__DIR__ ."/util.php");

$_debug_noncryptic_ =  $_GET['_debug_noncryptic'];
$search_type = 1;//current
if(isset($_REQUEST['search_type'])) $search_type = $_REQUEST['search_type'];


$page = $_GET['page']; // get the requested page 
$limit = $_GET['rows']; // get how many rows we want to have into the grid 
$sidx = $_GET['sidx']; // get index row - i.e. user click to sort 
$sord = $_GET['sord']; // get the direction 
if(!$sidx) $sidx = 'id'; 
if(!$limit)$limit=10;

// connect to the database 
if(!connect_to_whoiscrawler_index_db())return;

//if ($page > $total_pages) 
//  $page=$total_pages; 
$start = $limit*$page - $limit; 
if($start<0)$start=0;


//echo "limit is $limit, page is $page";
// do not put $limit*($page - 1) 
//$SQL = "select domain_name from whois_index where match ('" . $term. "') ORDER BY $sidx $sord LIMIT $start , $limit";
$term = mysql_escape_string($term);
$search_type = 2;
if($search_type == 2){

  output_history_whois_records(array('start'=>$start,'page'=>$page,'term'=>$term,'limit'=>$limit));
}
else{
  output_current_whois_records(array('start'=>$start,'page'=>$page,'term'=>$term,'limit'=>$limit));
 
}

  
?>
<?php
  function output_history_whois_records($options){
    $term = $options['term'];
    $page = $options['page'];
    $start = $options['start'];
    $limit = $options['limit'];
    
    $SQL = "select * from whois_history_index where match ('@raw_text " . $term. "') group by domain_id LIMIT $start , $limit";
    $result = mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error() . " <br/>query:$SQL");
    $whois_records_map = array();
    $response->rows=array();
    $i=0; 
    while($row = mysql_fetch_array($result,MYSQL_ASSOC)) {
      
      $domain_name = $row[domain_name];
      echo $domain_name. " ";
      if(!array_key_exists($domain_name, $whois_records_map)){ 
        $response->rows[$i] = array();
        $response->rows[$i]['id']=$row[id]; 
        $response->rows[$i]['cell'] = array($domain_name);
       
        $whois_records_map[$domain_name] = array();
        $i++;
      } 
      array_push($whois_records_map[$domain_name],
                array('audit_updated_date'=>$row['audit_updated_date'],
                     'whois_record_id'=>$row['id']
                )
      );
                     
      
     
    }
    //print_r($whois_records_map);
   //print_r("whois_records_map is ". print_r($whois_records_map,1));
    //print_r($response);
    foreach($response->rows as $index => $row){
      $cell = $row['cell'];
      
      $domain_name=$cell[0];
     //echo $domain_name. " ".$whois_records_map["$domain_name"];
     
      if(array_key_exists($domain_name,$whois_records_map)){
    
        $whois_records = $whois_records_map[$domain_name];
      
       
        $updatedDates = implode_array_element(' ', $whois_records, 'audit_updated_date');
        
        array_push($response->rows[$index]['cell'], $updatedDates);
        
      } 
     // echo "cell is ".print_r($cell,1);
    }
    /*
    $SQL ="show meta";
    $result = mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error() . " <br/>query:$SQL");
    $response->page = $page; 
    $meta_data=array();
    if($row = mysql_fetch_array($result,MYSQL_ASSOC)) {
      $meta_data[$row['Variable_name']] = $row['Value'];
    }
    $response->records = $meta_data['total']; 
    
     * 
     */
     
    $response->records = $i; 
    $response->total = ceil($response->records/$limit); 
    echo json_encode($response);     
   
    
  }
  function implode_array_element($delim, $array, $key){
    $res=array();
    foreach($array as $a){
      $res[]=$a[$key];
    }
    //print_r($res);
    return implode($delim, $res);
  }
  function output_current_whois_records($options){
    $term = $options['term'];
    $page = $options['page'];
    $start = $options['start'];
    $limit = $options['limit'];
    
    $SQL = "select domain_name from whois_current_index where match ('@raw_text " . $term. "') LIMIT $start , $limit";
    $result = mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error() . " <br/>query:$SQL");
    $i=0; 
    while($row = mysql_fetch_array($result,MYSQL_ASSOC)) {
      $response->rows[$i]['id']=$row[id]; 
      if($_debug_noncryptic_) {
        $domain_name = $row[domain_name];
      }
      else{
        $domain_name = crypt_dn($row[domain_name]);
        $whois_records = '<a href="javascript:view_current_whois();">Current</a>';
      }
      $response->rows[$i]['cell']=array($domain_name, $whois_records); 
      $i++; 
    }

    $SQL ="show meta";
    $result = mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error() . " <br/>query:$SQL");
    $response->page = $page; 
    $meta_data=array();
    if($row = mysql_fetch_array($result,MYSQL_ASSOC)) {
      $meta_data[$row['Variable_name']] = $row['Value'];
    }
    $response->records = $meta_data['total']; 
    $response->total = ceil($response->records/$limit); 
    echo json_encode($response); 
  }
?>