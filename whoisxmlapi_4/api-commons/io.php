<?php

function get_search_terms_from_request($max_search_terms=4){
    $include=array();
    $exclude=array(); 
    for($i=1;$i<=$max_search_terms;$i++){
      if(isset($_REQUEST["term$i"]) && ($s=trim(urldecode($_REQUEST["term$i"])))){
          $include[]=$s;
      }
      if(isset($_REQUEST["exclude_term$i"]) && ($s=trim(urldecode($_REQUEST["exclude_term$i"])))){
          $exclude[]=$s;
      }      
    }

    return array('include'=>$include, 'exclude'=>$exclude, 'max_search_terms'=>$max_search_terms);
  }
	


	//output functions
	function output_error($error_code, $msg){
		global $output_format;
		$res = array('ErrorMessage'=>array('error_code'=>$error_code, 'msg'=>$msg));
		if(strcasecmp('xml',$output_format)==0) output_xml($res);
		else output_json($res);
	}
	
function output_json($s){
	echo json_encode($s);
}
function output_xml($s){
	$options = array(
  XML_SERIALIZER_OPTION_INDENT        => '    ',
  XML_SERIALIZER_OPTION_RETURN_RESULT => true,
   "encoding"        => "UTF-8",
   "rootName"=>"response",
   "defaultTagName"=>"item"
  );
 
	$serializer = new XML_Serializer($options);
	$str = $serializer->serialize($s);
	header ("content-type: text/xml");
	echo $str;
}
?>