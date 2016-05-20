<?php
	require_once "httputil.php";
	require_once dirname(__FILE__) . "/users/users.inc";
	
	
	function build_url($base, $params){
    return $base . (count($params) > 0 ? ("?".http_build_query($params)):"");
  }
  function array_key_pick($a, $key_start){
    $b=array();
    foreach($a as $key=>$val){
      if(stripos($key, $key_start) === 0)$b[$key]=$val;
    }
    return $b;
  }
          function arrayMap($callback,$arr1) {
            $results       =    array();
            $args          =    array();
            if(func_num_args()>2)
                $args          =    (array) array_shift(array_slice(func_get_args(),2));
            foreach($arr1 as $key=>$value) {
                if(is_array($value)) {
                    array_unshift($args,$value);
                    array_unshift($args,$callback);
                    $results[$key]    =    call_user_func_array(array('self','arrayMap'),$args);
                }
                else {
                    array_unshift($args,$value);
                    $results[$key]    =    call_user_func_array($callback,$args);
                }
            }
            return $results;
        } 
          
	function element_exists($array, $index){
	
  		return isset($array) && is_array($array) && array_key_exists($index, $array);
	}
	function get_from_post_get($name){
		if(array_key_exists($name, $_POST)) return $_POST[$name];
		if(array_key_exists($name, $_GET)) return $_GET[$name];
		return false;
	}
	function isLoggedIn(){
	  my_session_start();
	  if(isset($_SESSION['myuser'])){
	     return true;
	  }
    return false;
	}
	
	function display_login_msg($msg=false, $center=false){
		if(!$msg){
			$msg = "Please login first from the top right hand corner";
		}
		$msg = "<span  style=\"font-size:24px;color:red;font-family:Arial, Helvetica, sans-serif;\" >". $msg . "</span>";
		if($center){
			$msg = "<div style=\"text-align:center\">$msg</div>";
		}
		echo $msg;
	}
	
	function login($username, $password, &$err){
		global $USERS_DB;
		if(!connect_to_whoisserver_db($username)){
			$err['msg'] = 'unable to connect to database';
			
			return false;
		}
		$sql = sprintf("
   SELECT username, email, seclev, status
     FROM users 
    WHERE username='%s'
      AND password='%s'",
	       mysql_real_escape_string($username),
	       mysql_real_escape_string($password));
	$query = mysql_query($sql);
	if(!$query){
		$err['msg'] = "Error in query ($sql) - " . mysql_error();
		return false;
	}
	
	if ($query && (mysql_num_rows($query) > 0)) {
  		list($username,$email,$seclev, $status) = mysql_fetch_row($query);  
		if($status=='disabled' || $status=='unconfirmed'){
			$err['msg'] = "account $username is $status";
			return false;
		}
		
  		$user = new User($username, array('password'=>$password,'email'=>$email, 'seclev'=>$seclev, 'status'=>$status));
  	
  		//$user->set_user_cookie();
  		my_session_start();
  		$_SESSION['myuser'] = $user;
		return true;
	}
	$err['msg'] = 'the username and password combination is incorrect';
	return false;
	}
	

    function in_arrayi( $needle, $haystack ) {
        $found = false;
        foreach( $haystack as $value ) {
            if( strtolower( $value ) == strtolower( $needle ) ) {
                $found = true;
            }
        }   
        return $found;
    }
    function in_arraye( $needle, $haystack ) {
      
        foreach( $haystack as $value ) {
            if(call_user_func(array($needle, "equals"), $value) !==false){
                return true;
                
            }
        }   
        return $false;
    }    
    
  function array_diff_i($a, $b){
    $a = array_map(function($el){
      return strtolower($el);
    }, $a);
    $b = array_map(function($el){
      return strtolower($el);
    }, $b); 
    return array_diff($a,$b);   
    
  }
  
	function str2int($s){
		if(!is_numeric($s)){
				return false;
		}
		return intval($s);
	}
  
	function validate_username($str){
		  return preg_match('/^[A-Za-z0-9_@\-\.]+$/',$str);
	}
	function validate_password($str){
		  return preg_match('/^[A-Za-z0-9_@\-\.!~]+$/',$str);
	}  
	function is_numeric_int($s){
		return is_numeric($s) && round($s)==$s;
	}
?>
