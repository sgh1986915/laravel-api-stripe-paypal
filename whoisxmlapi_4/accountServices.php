<?php 
require_once __DIR__. "/util/common_init.php";
require_once __DIR__. "/httputil.php";
require_once __DIR__."/users/users.inc";
require_once __DIR__."/users/account.php";
require_once __DIR__. "/util/array_util.php";
// require_once 'XML/Serializer.php'; // modified
function serviceAccountBalance(){
	$username = $_REQUEST['username'];
	$password = $_REQUEST['password'];
	if(empty($username) || empty($password)){
		errorMsg("Username or password cannot be empty");
		return;
	}
	$act = getUserAccount($username, $password);
	$accountBalance =  $act->balance;
	 $accountReserve= $act->reserve;
	 if(!is_object($act) || $act->loadErr!=false){
	 	errorMsg("invalid username and password combination");
	 	return;
	 }
	$ret=filter_array($act->data, array('reserve','balance'));
	//echo "<Account><balance>".$accountBalance.'</balance><reserve>' .  $accountReserve. '</reserve></Account>';
	outputRes($ret);
}
function outputRes($ret){
	$outputFormat=$_REQUEST['output_format'];
	if(!$outputFormat)$outputFormat="XML";
	if(strcasecmp($outputFormat, "XML") === 0){
		outputXML($ret);
	}
	else outputJSON($ret);
}
function outputXML($ret){
	header("Content-Type: text/xml; charset=UTF-8");
		/*$options = array(
  XML_SERIALIZER_OPTION_INDENT        => '    ',
  XML_SERIALIZER_OPTION_RETURN_RESULT => true,
   "encoding"        => "UTF-8",
   "rootName"=>"Account",
   "defaultTagName"=>"item"
  );
	$serializer = new XML_Serializer($options);
	$ret=removeXMLCtrlChars($ret);
	//print_r($ret);
	$str = $serializer->serialize($ret);
	echo $str;*/

	print array2xml($ret, false, 'Account');
}

// function to convert an array to XML using SimpleXML
function array2xml($array, $xml = false, $rootName = ''){
    if($xml === false){
        $xml = new SimpleXMLElement('<'.$rootName.'/>');
    }
    foreach($array as $key => $value){
        if(is_array($value)){
            array2xml($value, $xml->addChild($key));
        }else{
            $xml->addChild($key, $value);
        }
    }
    return $xml->asXML();
}

function removeXMLCtrlChars($a){

	foreach($a as $key=>$val){
		$a[$key]=stripInvalidXml($val);
	}

	return $a;
}
function outputJSON($ret){
	//header("Content-Type: text/json; charset=UTF-8");
	echo json_encode($ret);
}

function serviceAccountUpdate(){
	$username = $_REQUEST['username'];
	$password = $_REQUEST['password'];
	if(empty($username) || empty($password)){
		errorMsg("Username or password cannot be empty");
		return;
	}
	$act = getUserAccount($username, $password);

	if(!is_object($act) || $act->loadErr!=false){
	 	errorMsg("invalid username and password combination");
	 	return;
	 }
	 $updates=array();
	 $reset_thresh = false;
	 $reset_empty = false;
	 if(isset($_REQUEST['warn_threshold_enabled'])){
	 	$act->warn_threshold_enabled = getBool($_REQUEST['warn_threshold_enabled']);
	 	$updates[]='warn_threshold_enabled';
	 	$reset_thresh = true;

	 }
	if(isset($_REQUEST['warn_empty_enabled'])){
	 	$act->warn_empty_enabled = getBool($_REQUEST['warn_empty_enabled']);
	 	$updates[]='warn_empty_enabled';
		$reset_empty=true;
	}
	if(isset($_REQUEST['warn_threshold'])){
		if(!is_numeric($_REQUEST['warn_threshold'])){
			errMsg("Invalid warn_threshold ".$_REQUEST['warn_threshold']);
			return;
		}
	 	$act->warn_threshold = intval($_REQUEST['warn_threshold']);
	 	$updates[]='warn_threshold';
	 	$reset_thresh = true;
	}
	if(sizeof($updates)>0){
		global $USERS_DB;
		$act_ar = get_object_vars($act);
		$sql = "update $USERS_DB.user_account set ";
		$n=sizeof($updates);
		$i=0;
		foreach($updates as $key){
			$sql .= "$key = " .$act_ar[$key];
			if($i++<$n-1)$sql .=",";
		}
		if($reset_thresh) $sql .=", threshold_email_count = 0 ";
		if($reset_empty) $sql .=", empty_email_count = 0 ";
		$sql .=" where username= '" . mysql_escape_string($username) . "'";
		if(!mysql_query($sql)){

			$errors = "Error in query: $sql - ".mysql_error();
			errorMsg($errors);
			return;
		}
		//echo $sql;
		$act->reload_from_db();
	}

	printAccountInfo($act);
}

function getBool($s){
	if(empty($s) || strcasecmp($s,'0')===0 || strcasecmp($s,'off') ===0 || strcasecmp($s,'false') ===0) return 0;
	return 1;
}
function printAccountInfo($act){
	/*
	$params=array("balance", "reserve", "warn_threshold_enabled", "warn_threshold", "warn_empty_enabled");
	$ar=get_object_vars($act);
	//echo $ar['balance'];
	//print_r($ar);

	$s='';
	foreach($params as $p){
		$s.= "<".$p.">" . $ar[$p] . "</".$p.">";
	}
	echo "<Account>" . $s . "</Account>";*/
	$ret=filter_array($act->data, array('reserve','balance', 'warn'));
	outputRes($ret);
}
$serviceType=$_REQUEST['servicetype'];
if(strcasecmp('accountBalance', $serviceType) == 0){
	serviceAccountBalance();
}
else if (strcasecmp('accountUpdate', $serviceType) == 0){
	serviceAccountUpdate();
}
else{
	errorMsg("Account service (".$serviceType.") is unsupported.");
}


?>
<?php
function errorMsg($msg){
	outputRes(array("error"=>$msg));
}

function stripInvalidXml($value)
{
    $ret = "";

    if (empty($value))
    {
        return $ret;
    }

    $length = strlen($value);
    for ($i=0; $i < $length; $i++)
    {
        $current = ordutf8($value{$i});
        if (($current == 0x9) ||
            ($current == 0xA) ||
            ($current == 0xD) ||
            (($current >= 0x20) && ($current <= 0xD7FF)) ||
            (($current >= 0xE000) && ($current <= 0xFFFD)) ||
            (($current >= 0x10000) && ($current <= 0x10FFFF)))
        {
            $ret .= chr($current);
        }
        else
        {
            $ret .= " ";
        }
    }
    return $ret;
}

function ordutf8($string, $offset=0) {
    $code = ord(substr($string, $offset,1));
    if ($code >= 128) {        //otherwise 0xxxxxxx
        if ($code < 224) $bytesnumber = 2;                //110xxxxx
        else if ($code < 240) $bytesnumber = 3;        //1110xxxx
        else if ($code < 248) $bytesnumber = 4;    //11110xxx
        $codetemp = $code - 192 - ($bytesnumber > 2 ? 32 : 0) - ($bytesnumber > 3 ? 16 : 0);
        for ($i = 2; $i <= $bytesnumber; $i++) {
            $offset ++;
            $code2 = ord(substr($string, $offset, 1)) - 128;        //10xxxxxx
            $codetemp = $codetemp*64 + $code2;
        }
        $code = $codetemp;
    }
    $offset += 1;
    if ($offset >= strlen($string)) $offset = -1;
    return $code;
}


?>