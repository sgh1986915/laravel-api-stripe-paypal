<?php 
require_once("whois_server.inc");
require_once("dbutils.php");

function getUserAccount($username, $password=NULL){
	$act = new UserAccount($username, $password);
	$act->load_from_db();
	return $act;
}
class UserAccount{
	var $data;
	var $username;
	var $password;
	var $balance;
	var $reserve;
	var $warn_threshold_enabled;
	var $warn_threshold;
	var $warn_empty_enabled;
	
	var $reverse_whois_balance;
	var $reverse_whois_reserve;
	var $reverse_whois_monthly_balance;
	var $reverse_whois_monthly_reserve;


    var $reverse_ip_balance;
    var $reverse_ip_reserve;
    var $reverse_ip_monthly_balance;
    var $reverse_ip_monthly_reserve;

	var $loadErr = false;
	var $error = false;
	function __construct($username, $password=NULL) {
		$this->username = $username;
		$this->password=$password;
	}
	function deduct_rw_credits($credits){
		
		if($credits<=0){
			$this->error="credit to deduct is 0";
			return false;
		}
		$credits_before_deduction=0;
		$credits_before_deduction += max(0, $this->reverse_whois_monthly_balance);
  		$credits_before_deduction += max(0, $this->reverse_whois_balance);
  		
  		if($credits_before_deduction <$credits){
  			$this->error="account has $credits_before_deduction credit remaining.";
  			return false;
  		}
		 if($this->reverse_whois_monthly_balance > 0){
  			if($this->reverse_whois_monthly_balance > $credits){
  				$this->reverse_whois_monthly_balance-=$credits;
  				$credits = 0;
  				
  			}
  			else{
  				$credits -= $this->reverse_whois_monthly_balance;
  				$this->reverse_whois_monthly_balance = 0;
  				
  			}
  			
  		}
  		if($credits > 0){
  			if($this->reverse_whois_balance > 0){
  				if($this->reverse_whois_balance > $credits){
  					$this->reverse_whois_balance-=$credits;
  					$credits = 0;
  					
  				}
  				else{
  					$credits -= $this->reverse_whois_balance;
  					$this->reverse_whois_balance = 0;
  					
  				}	
  				
  			}
  		}	
		 
		return $this->save_reverse_whois_info_to_db();
		 
	}

    function deduct_ri_credits($credits){

        if($credits<=0){
            $this->error="credit to deduct is 0";
            return false;
        }
        $credits_before_deduction=0;
        $credits_before_deduction += max(0, $this->reverse_ip_monthly_balance);
        $credits_before_deduction += max(0, $this->reverse_ip_balance);

        if($credits_before_deduction <$credits){
            $this->error="account has $credits_before_deduction credit remaining.";
            return false;
        }
        if($this->reverse_ip_monthly_balance > 0){
            if($this->reverse_ip_monthly_balance > $credits){
                $this->reverse_ip_monthly_balance-=$credits;
                $credits = 0;

            }
            else{
                $credits -= $this->reverse_ip_monthly_balance;
                $this->reverse_ip_monthly_balance = 0;

            }

        }
        if($credits > 0){
            if($this->reverse_ip_balance > 0){
                if($this->reverse_ip_balance > $credits){
                    $this->reverse_ip_balance-=$credits;
                    $credits = 0;

                }
                else{
                    $credits -= $this->reverse_ip_balance;
                    $this->reverse_ip_balance = 0;

                }

            }
        }

        return $this->save_reverse_ip_info_to_db();

    }
	function save_reverse_whois_info_to_db(){
		$db_info = get_whoisserver_db_server($this->username);
		$WHOISSERVER_DB = $db_info['db'];
		connect_to_whoisserver_db($this->username);
		if(!$this->reverse_whois_monthly_balance)$this->reverse_whois_monthly_balance = 0;
		if(!$this->reverse_whois_monthly_reserve)$this->reverse_whois_monthly_reserve = 0;
		if(!$this->reverse_whois_balance)$this->reverse_whois_balance = 0;
		if(!$this->reverse_whois_reserve)$this->reverse_whois_reserve = 0;
		
		$sql =  " update $WHOISSERVER_DB.user_account a set reverse_whois_balance = " . $this->reverse_whois_balance .
		", reverse_whois_reserve = ". $this->reverse_whois_reserve .
		", reverse_whois_monthly_balance = " . $this->reverse_whois_monthly_balance .
		", reverse_whois_monthly_reserve = " . $this->reverse_whois_monthly_reserve .
    	" WHERE a.username=".quote($this->username);
    	
		$query = mysql_query($sql);
        if(!$query){
        	
        	$this->error = "Failed to save reverse whois info to db: ". mysql_error(). " ($sql)";
			return false;
		}
		else $this->error = false;
		return true;
	}

    function save_reverse_ip_info_to_db(){
        $db_info = get_whoisserver_db_server($this->username);
        $WHOISSERVER_DB = $db_info['db'];
        connect_to_whoisserver_db($this->username);
        if(!$this->reverse_ip_monthly_balance)$this->reverse_ip_monthly_balance = 0;
        if(!$this->reverse_ip_monthly_reserve)$this->reverse_ip_monthly_reserve = 0;
        if(!$this->reverse_ip_balance)$this->reverse_ip_balance = 0;
        if(!$this->reverse_ip_reserve)$this->reverse_ip_reserve = 0;

        $sql =  " update $WHOISSERVER_DB.user_account a set reverse_ip_balance = " . $this->reverse_ip_balance .
            ", reverse_ip_reserve = ". $this->reverse_ip_reserve .
            ", reverse_ip_monthly_balance = " . $this->reverse_ip_monthly_balance .
            ", reverse_ip_monthly_reserve = " . $this->reverse_ip_monthly_reserve .
            " WHERE a.username=".quote($this->username);

        $query = mysql_query($sql);
        if(!$query){

            $this->error = "Failed to save reverse ip info to db: ". mysql_error(). " ($sql)";
            return false;
        }
        else $this->error = false;
        return true;
    }
	function reload_from_db(){
		$this->load_from_db();
	}
	function load_from_db(){
		$this->data=array();
		 connect_to_whoisserver_db($this->username);
		//$db_info = get_whoisserver_db_server($this->username);
		//$WHOISSERVER_DB = $db_info['db'];
		$sql = sprintf("select * from user_account a join users u on a.username=u.username " .
				"left join user_account2 b on a.username=b.username where a.username='%s'", mysql_real_escape_string($this->username));
		
    	if($this->password != NULL){
    		$sql .=' and u.password = '. quote($this->password);
    	}
		$query = mysql_query($sql);
        if(!$query){
        	error_log("The query failed! (" . mysql_error(). "): $sql"); 
        	$this->loadErr = mysql_error();
			return;
		}
		else $this->loadErr = false;
		
    	if ($query){
    		if(mysql_num_rows($query) > 0) {
    		
      			$res = mysql_fetch_assoc($query);
      			
				$this->balance = $res['balance'];
				$this->reserve = $res['reserve'];
				$this->warn_threshold_enabled = $res['warn_threshold_enabled']+0;
				$this->warn_threshold = $res['warn_threshold'];
				$this->warn_empty_enabled = $res['warn_empty_enabled']+0;
				
				$this->reverse_whois_balance = $res['reverse_whois_balance'];
				$this->reverse_whois_reserve = $res['reverse_whois_reserve'];
				$this->reverse_whois_monthly_balance = $res['reverse_whois_monthly_balance'];
				$this->reverse_whois_monthly_reserve = $res['reverse_whois_monthly_reserve'];

                $this->reverse_ip_balance = $res['reverse_ip_balance'];
                $this->reverse_ip_reserve = $res['reverse_ip_reserve'];
                $this->reverse_ip_monthly_balance = $res['reverse_ip_monthly_balance'];
                $this->reverse_ip_monthly_reserve = $res['reverse_ip_monthly_reserve'];

				foreach($res as $key=>$val){
					$this->data[$key]=$val;
				}
				
    		}
    		else $this->loadErr = "no account found";
    	}
    	
	}	
	
}
?>