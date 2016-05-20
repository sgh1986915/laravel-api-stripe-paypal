<?php
function construct_query_match($search_terms){


    $res="";
    $include = $search_terms['include'];
    $exclude = $search_terms['exclude'];

    foreach($include as $s){
        $res .= ' "' .  StringUtil::hyphen_fix($s) . '"';

    }
    foreach($exclude as $s){
        $res .= ' !"' .  StringUtil::hyphen_fix($s) . '"';

    }
    //return $res = "@raw_text $res";
    return $res;
  }
  function get_meta_data(){
    $SQL ="show meta";
    $result = mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error() . " <br/>query:$SQL");
    $meta_data=array();
    while($row = mysql_fetch_array($result,MYSQL_ASSOC)) {
      $meta_data[$row['Variable_name']] = $row['Value'];
    }
    return $meta_data;
  }
?>