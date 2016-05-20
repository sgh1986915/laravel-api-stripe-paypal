<?php
class StringUtil{
  public static  $dn_hash_key="123456";
  public static function randString($length, $charset='ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789')
    {
        $str = '';
        $count = strlen($charset);
        while ($length--) {
            $str .= $charset[mt_rand(0, $count-1)];
        }
        return $str;
    }
  public static function startsWith($haystack, $needle)
{
    $length = strlen($needle);
    return (substr($haystack, 0, $length) === $needle);
}
function endsWith($haystack, $needle)
{
  $length = strlen($needle);
  if ($length == 0) {
    return true;
  }

  $start  = $length * -1; //negative
  return (substr($haystack, $start) === $needle);
}
  public static function strpos_multi($haystack, $needles, $offset=0){
 	foreach($needles as $needle){
 		$index=strpos($haystack,$needle, $offset);
 		if($index!==false){
 			return $index;
 		}
 	}
 	return false;
  }
  public static function is_positive_integer($i){
  	
  	return (is_numeric($i) && $i > 0 && ctype_digit((string)$i));
  	
  }
  public static function first_nonempty_line($lines){
  	$array  = explode("\n" , $lines);
  	if(count($array)>0){
  		foreach($array as $line){
  			if(strlen(trim($line))>0)return trim($line);
  		}
  	}
  	return $lines;
  }
  public static function is_nonnegative_integer($i){
  	
  	return (is_numeric($i) && $i >= 0 && ctype_digit((string)$i));
  	
  }
   public static function copy_obj($from, $to, $transform=array()){
     foreach($from as $key=>$val){
        // echo "key is $key";
       if(isset($transform[$key])){
         if(is_object($to))
          $to->$key = call_user_func($transform[$key],$val);
         else $to[$key] = call_user_func($transform[$key],$val);
       }
       else{

          if(is_object($to)) $to->$key=$val; 
          else $to[$key] = $val;

       }
     }

   }
  public static function wrap_implode($ar, $wrap, $delim){
   
    return $wrap.implode($wrap.$delim.$wrap, $ar).$wrap;

  }
  public static function explode_trim($delim, $str){
    if($str = trim($str)){
      return explode($delim, $str);
    }
    return array();
    
  }
  public static function explode_trim_strlower($delim, $str){
    if($str = trim($str)){
      return array_map('strtolower',explode($delim, $str));
    }
    return array();
    
  }
    
  public static function crypt_dn($s){
  
    if(!$s || strlen($s)==0)return $s;
    $res=$s[0];
    $n=strlen($s);
    for($i=1;$i<$n;$i++){
      if($s[$i]!='.')$res.='_';
      else $res.=$s[$i];
    }
    return $res;
  }
  public static function hash_dn($s){
    if(!$s || strlen($s)==0)return $s;
    $key = self::$dn_hash_key;
    return base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, md5($key), $s, MCRYPT_MODE_CBC, md5(md5($key))));
      
    
  }
  public static function dehash_dn($s){
    if(!$s || strlen($s)==0)return $s;
    $key = self::$dn_hash_key;
    return rtrim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, md5($key), base64_decode($s), MCRYPT_MODE_CBC, md5(md5($key))), "\0");
    
  }
  
  /*
  public static function hash_dn($s){
    
    if(!$s || strlen($s)==0)return $s;
    global $dn_hash_key;
    $iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB);
    $iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);  
    $crypttext = mcrypt_encrypt(MCRYPT_RIJNDAEL_256, StringUtil::$dn_hash_key, $s, MCRYPT_MODE_ECB, $iv);
    return base64_encode($crypttext);    
  
     
  }
  
  public static function dehash_dn($s){
    if(!$s || strlen($s)==0)return $s;
    $s=base64_decode($s);
   
    $iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB);
    $iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);  
    $s = mcrypt_decrypt(MCRYPT_RIJNDAEL_256, StringUtil::$dn_hash_key, $s, MCRYPT_MODE_ECB, $iv);
    return trim($s);  
  }  
  */
  //sphinx indexer doesn't work on - '
  public static function hyphen_fix($s){
  	//return str_replace("-", " ", $s);
  	return $s;
  }
}

?>