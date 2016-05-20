<?php

  require_once(dirname(__FILE__) . '/../httputil.php');
  require_once(dirname(__FILE__) . '/../email/Email.php');
  require_once(dirname(__FILE__) . "/../users/whois_server.inc");
  require_once(dirname(__FILE__) . "/../models/report.php");
  require_once(dirname(__FILE__) . "/../external/ExternalAccountManager.php");
 if(isset($V2)){
  	require_once dirname(__FILE__) . "/../reverse-whois-v2/config.php";
  }
  else if(isset($V1)){ 
  	require_once dirname(__FILE__) . "/../reverse-whois-v1/config.php";
  }
  else require_once dirname(__FILE__) . "/../reverse-whois/config.php";
 require_once(dirname(__FILE__) . "/../db_cart/db_cart_class.php");
 if(isset($_REQUEST['unlimited']) && $_REQUEST['unlimited']){
	$MAX_DOMAIN_SEARCH_MATCH = 500000;
	$MAX_SEARCH_CUTOFF= 500000;
 }
 function processBulkWhoisLookupOrder($param){
     $email_to=$param['email_to'];
     $item_name=$param['item_name'];
     $link="http://www.whoisxmlapi.com/fileupload/index.php?userEmail=$email_to";
     $token="Bulk Whois Lookup for ";
     $bwl_index=stripos($item_name, $token);
     $max_lines=false;
     if($bwl_index>=0){
         $s=substr($item_name, strlen($token));
         $toks=explode(" ", $s);
         if(count($toks)>0){
             $max_lines=$toks[0];
         }
     }
     if($max_lines)$link.="&maxLines=$max_lines";
     $extraBody="Please <a href=\"$link\">visit this page to upload your domain names list</a>.  You may upload one or more files(.csv or .txt) with one domain name per line.<br/>";
     emailOrderProcessed($email_to, array("item_name"=>$item_name, "order_type"=>"bulk_whois_lookup", 'extraBody'=>$extraBody, 'mime_type'=>'text/html'));


 }
 	function notifyMeOrderProcessed($param, $to="topcoder1@gmail.com"){
 		
		$subject=$param['subject'];
		$body=$param['body'];
		$body.="\n" . date('m/d/Y g:i A')."\n";
		$emailer=new Email;
		$emailer->from="support@whoisxmlapi.com";
		$emailer->send_mail($to,$subject,$body,null);
		
	}
	function get_days_to_add_from_item_name($item_name){
	
		$item_name_lower=strtolower($item_name);
		
		$days_to_add=31;
		if(strpos($item_name_lower, "yearly")!==false){
		
			$days_to_add=365;
		}
		return $days_to_add;
	}
    //data: item_name=Daily Domain Name Data Enterprise, subscr_date=09:56:35 Jan 13, 2014 PST
    function emailOrderCancelled($to, $data, $order_type){
        $item_name=$data['item_name'];
        $subject="Order cancellation confirmation";
        if($order_type == 'domain_data'){
            $subscr_start_date=new DateTime($data['subscr_date']);
           
			$days_to_add=get_days_to_add_from_item_name($item_name);
			$start_date=date_format($subscr_start_date, 'm/d/Y');
            $subscr_end_date=$subscr_start_date->add(date_interval_create_from_date_string("$days_to_add days"));
           
            $end_date = date_format($subscr_end_date, 'm/d/Y');
            $body="Dear customer,\n Your subscription for $item_name was successfully cancelled. Your subscription started on $start_date, You can still use the service until $end_date";
        }

        if(isset($data['extraBody'])) $body .= "\n".$data['extraBody'] ;
        $body .="\n\n-Whois API LLC";
        $body .= "\n ". date('m/d/Y g:i A') . "\n";
        $emailer=new Email;
        $emailer->from="support@whoisxmlapi.com";
        $emailer->send_mail($to,$subject,$body,null);

    }

 	function emailOrderProcessed($to, $data){
 		$subject = 'Your Whois order was processed.';
 		$quantity = $data['orderQuantity'];
 		$accountUserName=$data['accountUserName'];
 		$order_type = $data['order_type'];
 		$item_name = $data['item_name'];
 		if($order_type=='spk')
 			$body = "Dear customer,\n Your order for $item_name was received.  We will prepare your software package and send to you within one business day.  ";
 		else if($order_type == 'reverse_whois_report'){
 			$body = "Dear customer,\n  $item_name was successfully processed.   The report(s) have been added to the user account $accountUserName.  ".
 			"please login(if you are not logged in already) using the username $accountUserName, click on \"Reverse Whois\" on the top, and then click on \"My Reverse Whois Reports\" on the left to see your reports.\n" .
 			"If You have not created an account with username $accountUserName, please visit http://www.whoisxmlapi.com/newaccount.php to do do.";
 		}	
 		else if($order_type == 'whois_api_client_license'){
            $username=$accountUserName;
            if(!$username){
                $username=$to;
            }
            $password=StringUtil::randString(6);
            if(ExternalAccountManager::createWhoisAPIBulkClientAccount($username, $password) == false){
                notifyMeOrderProcessed(array('body'=>"failed to create whois api bulk client account with username $username and password $password"));

            }

 			$body = "Dear customer,\n  Your order for \"$item_name\" was successfully processed.   Please go to http://www.whoisxmlapi.net/whoisapiclient/v1/launch.jnlp to launch the application.  The following is your login credentials:\n  " .
 			" Login: $username, password: $password";
 	        //create account here

 		}
 		else if($order_type == 'reverse_whois_credits' || $order_type == 'reverse_whois_credits_monthly'){
            $body =  "Dear customer,\n Your Reverse Whois order was successfully processed.  We have added $quantity reverse whois credits to account $accountUserName.  This will allow you to get $quantity reverse whois reports".'' .
                "After performing a search, simply click on \"order current\" or \"order historical\" on the Report Preview page, and then follow the check out instruction to redeem the report." .
                "Aftwards please login(if you are not logged in already) using the username $accountUserName, click on \"Reverse Whois\" on the top, and then click on \"My Reverse Whois Reports\" on the left to see your reports. ";
        }
        else if($order_type == 'reverse_ip_credits' || $order_type == 'reverse_ip_credits_monthly'){
            $body =  "Dear customer,\n Your Reverse IP order was successfully processed.  We have added $quantity reverse ip credits to account $accountUserName.  This will allow you to get $quantity reverse ip reports".'' .
                "After performing a search, simply click on \"order current\" or \"order historical\" on the Report Preview page, and then follow the check out instruction to redeem the report." .
                "Afterwards please login(if you are not logged in already) using the username $accountUserName, click on \"Reverse IP\" on the top, and then click on \"My Reverse IP Reports\" on the left to see your reports. ";
        }
 		else if($order_type == 'domain_data'){
 			$body="Dear customer,\n Your order for $item_name was successfully processed.  We will send you the download information within one business day.";
 		}
        else if($order_type == 'bulk_whois_lookup'){
            $body="Dear customer,\n Your order for $item_name was successfully processed. ";
        }
 		else if($order_type =='arbitrary'){
 			$body="Dear customer,\n The following order was received .  We will process your order within one business day.\n $item_name";
 		}
 		else $body =  "Dear customer,\n Your Whois order was successfully processed.  We have added $quantity whois queries to account $accountUserName";

		if(isset($data['extraBody'])) $body .= "\n".$data['extraBody'] ;
         $body .="\n\n-Whois API LLC";
         $body .= "\n ". date('m/d/Y g:i A') . "\n";
         $emailer=new Email;
		 $emailer->from="support@whoisxmlapi.com";
        if(isset($data['mime_type']))$emailer->send_mail($to,$subject,$body,null, $data['mime_type']);
		else $emailer->send_mail($to,$subject,$body,null);
	}
	function whoisAddQuantity($quantity,  $accountUserName, $param=null){
		return whoisFillQuantityHelper($quantity, $accountUserName, $param, true);
	}
	function whoisFillQuantity($quantity, $accountUserName, $param=null){
		return whoisFillQuantityHelper($quantity, $accountUserName, $param);
	} 
	function whoisFillQuantityHelper($quantity, $accountUserName, $param=null, $add=false){
		$subject="";
		$body="";
		if($add)$url = "http://www.whoisxmlapi.com/whoisserver/AdminService?username=jonathan&password=zhang1&cmd=quota&action=addBalance&quantity=$quantity&accountUserName=$accountUserName";
		else $url = "http://www.whoisxmlapi.com/whoisserver/AdminService?username=jonathan&password=zhang1&cmd=quota&action=refill&quantity=$quantity&accountUserName=$accountUserName";
		try{
			$resp=file_get_contents($url);
			$body="url: $url\n";
			if($resp){
				$subject="Whois Order ($quantity) was filled for $accountUserName";
				if($param!=null){
					if(isset($param['membership_payperiod'])) $subject = $param['membership_payperiod'] ."ly $subject";
				}
				$body="response: $resp\n";
				$body.=date('m/d/Y g:i A')."\n";
			}
		}catch(Exception $exp){
			$body .= $exp;
		}

		if(!$resp)$subject="Failed to fill Whois Order ($quantity) for $accountUserName";
		 $emailer=new Email;
		 $emailer->from="support@whoisxmlapi.com";
       try{
        $emailer->send_mail("topcoder1@gmail.com",$subject,$body,null);
       }catch(Exception $exp){

       }

		 return $resp;
	}
	
	function reverseWhoisAddQuantity($quantity,  $accountUserName, $param=null){
		return reverseWhoisFillQuantityHelper($quantity, $accountUserName, $param, true);
	}
    function reverseIPAddQuantity($quantity,  $accountUserName, $param=null){
        return reverseWhoisFillQuantityHelper($quantity, $accountUserName, $param, true);
    }
	function reverseWhoisFillQuantity($quantity, $accountUserName, $param=null){
		return reverseWhoisFillQuantityHelper($quantity, $accountUserName, $param);
	}
    function reverseIPFillQuantity($quantity, $accountUserName, $param=null){
        return reverseIPFillQuantityHelper($quantity, $accountUserName, $param);
    }

function reverseIPFillQuantityHelper($quantity, $accountUserName, $param=null, $add=false){
    $subject="";
    $body="";
    if($add)$url = "http://www.whoisxmlapi.com/whoisserver/AdminService?username=jonathan&password=zhang1&cmd=quota&action=addReverseIPBalance&quantity=$quantity&accountUserName=$accountUserName";
    else $url = "http://www.whoisxmlapi.com/whoisserver/AdminService?username=jonathan&password=zhang1&cmd=quota&action=refillReverseIP&quantity=$quantity&accountUserName=$accountUserName";
    try{
        $resp=file_get_contents($url);
        if($resp){
            $subject="Reverse IP Order ($quantity) was filled for $accountUserName";
            if($param!=null){
                if(isset($param['membership_payperiod'])) $subject = $param['membership_payperiod'] ."ly $subject";
            }
            $body="response: $resp\n";
            $body.=date('m/d/Y g:i A')."\n";
        }
    }catch(Exception $exp){
        $body .= $exp;
    }


    if(!$resp)$subject="Failed to fill Reverse IP Order ($quantity) for $accountUserName";
    $emailer=new Email;
    $emailer->from="support@whoisxmlapi.com";
    $emailer->send_mail("topcoder1@gmail.com",$subject,$body,null);

    return $resp;
}

function reverseWhoisFillQuantityHelper($quantity, $accountUserName, $param=null, $add=false){
		$subject="";
		$body="";
		if($add)$url = "http://www.whoisxmlapi.com/whoisserver/AdminService?username=jonathan&password=zhang1&cmd=quota&action=addReverseWhoisBalance&quantity=$quantity&accountUserName=$accountUserName";
		else $url = "http://www.whoisxmlapi.com/whoisserver/AdminService?username=jonathan&password=zhang1&cmd=quota&action=refillReverseWhois&quantity=$quantity&accountUserName=$accountUserName";
		try{
			$resp=file_get_contents($url);
			if($resp){
				$subject="Reverse Whois Order ($quantity) was filled for $accountUserName";
				if($param!=null){
					if(isset($param['membership_payperiod'])) $subject = $param['membership_payperiod'] ."ly $subject";
				}
				$body="response: $resp\n";
				$body.=date('m/d/Y g:i A')."\n";
			}
		}catch(Exception $exp){
			$body .= $exp;
		}


		if(!$resp)$subject="Failed to fill Reverse Whois Order ($quantity) for $accountUserName";
		 $emailer=new Email;
		 $emailer->from="support@whoisxmlapi.com";
		 $emailer->send_mail("topcoder1@gmail.com",$subject,$body,null);

		 return $resp;
	}

	function reverseWhoisAddMonthlyQuantity($quantity,  $accountUserName, $param=null){
		return reverseWhoisFillMonthlyQuantityHelper($quantity, $accountUserName, $param, true);
	}
	function reverseWhoisFillMonthlyQuantity($quantity, $accountUserName, $param=null){
		return reverseWhoisFillMonthlyQuantityHelper($quantity, $accountUserName, $param);
	} 
	function reverseWhoisFillMonthlyQuantityHelper($quantity, $accountUserName, $param=null, $add=false){
		$subject="";
		$body="";
		if($add)$url = "http://www.whoisxmlapi.com/whoisserver/AdminService?username=jonathan&password=zhang1&cmd=quota&action=addReverseWhoisMonthlyBalance&quantity=$quantity&accountUserName=$accountUserName";
		else $url = "http://www.whoisxmlapi.com/whoisserver/AdminService?username=jonathan&password=zhang1&cmd=quota&action=refillReverseWhoisMonthly&quantity=$quantity&accountUserName=$accountUserName";
		try{
			$resp=file_get_contents($url);
			if($resp){
				$subject="Reverse Whois Order ($quantity) was filled for $accountUserName";
				if($param!=null){
					if(isset($param['membership_payperiod'])) $subject = $param['membership_payperiod'] ."ly $subject";
				}
				$body="response: $resp\n";
				$body.=date('m/d/Y g:i A')."\n";
			}
		}catch(Exception $exp){
			$body .= $exp;
		}


		if(!$resp)$subject="Failed to fill Reverse Whois Order ($quantity) for $accountUserName";
		 $emailer=new Email;
		 $emailer->from="support@whoisxmlapi.com";
		 $emailer->send_mail("topcoder1@gmail.com",$subject,$body,null);

		 return $resp;
	}

function reverseIPAddMonthlyQuantity($quantity,  $accountUserName, $param=null){
    return reverseIPFillMonthlyQuantityHelper($quantity, $accountUserName, $param, true);
}
function reverseIPFillMonthlyQuantity($quantity, $accountUserName, $param=null){
    return reverseIPFillMonthlyQuantityHelper($quantity, $accountUserName, $param);
}
function reverseIPFillMonthlyQuantityHelper($quantity, $accountUserName, $param=null, $add=false){
    $subject="";
    $body="";
    if($add)$url = "http://www.whoisxmlapi.com/whoisserver/AdminService?username=jonathan&password=zhang1&cmd=quota&action=addReverseIPMonthlyBalance&quantity=$quantity&accountUserName=$accountUserName";
    else $url = "http://www.whoisxmlapi.com/whoisserver/AdminService?username=jonathan&password=zhang1&cmd=quota&action=refillReverseIPMonthly&quantity=$quantity&accountUserName=$accountUserName";
    try{
        $resp=file_get_contents($url);
        if($resp){
            $subject="Reverse IP Order ($quantity) was filled for $accountUserName";
            if($param!=null){
                if(isset($param['membership_payperiod'])) $subject = $param['membership_payperiod'] ."ly $subject";
            }
            $body="response: $resp\n";
            $body.=date('m/d/Y g:i A')."\n";
        }
    }catch(Exception $exp){
        $body .= $exp;
    }


    if(!$resp)$subject="Failed to fill Reverse IP Order ($quantity) for $accountUserName";
    $emailer=new Email;
    $emailer->from="support@whoisxmlapi.com";
    $emailer->send_mail("topcoder1@gmail.com",$subject,$body,null);

    return $resp;
}

		
  class payment_util{
    var $error = false;
    function init_db(){
      if(!connect_to_whoisserver_db()) {
        $this->error = 'db_cart->init_db(): '.mysql_error();
        return false;
      }
      return true;
    }
  
    function fill_order($order_id, $order_type='R'){
      if($order_type == 'R'){
        if(!$this->process_ordered_reports($order_id)) {
            $this->email_error(array('error'=>"Failed to process ordered reports for order $order_id: ". $this->error));
            return false;
        }
      }
        else if($order_type=='RI'){
            if(!$this->process_ordered_reverseip_reports($order_id)) {
                $this->email_error(array('error'=>"Failed to process ordered reports for order $order_id: ". $this->error));
                return false;
            }
        }
      if(!$this->set_order_status($order_id,'F')){
        $this->email_error(array('error'=>"Failed to fill order $order_id ".$this->error));  
        return false;
      }
      return true;
    }
    function pend_order($order_id){
      my_session_start();
      unset($_SESSION['cart']);
      unset($_SESSION['order_id']);  
       if(!$this->set_order_status($order_id,'P')){
        return false;
      }
      return true;
    }
      public function process_ordered_reverseip_reports($order_id){

          set_time_limit(0);

          $cart  = new db_cart();
          $ordered_rows = $cart->get_all_ordered_rows_by_type( 'RI', $order_id);

          if($ordered_rows===false){
              $this->error = $cart->error;
              return false;
          }
          foreach($ordered_rows as $row){
              $rep = $row['report'];
              if(is_object($rep)){

                  $cart = new db_cart();
                  if(!$cart->update_reverseip_report(array( 'view_flag'=>1), $rep->report_id)){
                      $this->error = $cart->error;
                      return false;
                  }
              }
          }
          return true;
      }

      public function process_ordered_reports($order_id){
    	global $MAX_DOMAIN_SEARCH_MATCH;
   	
   		set_time_limit(0);  
      $MAX_DOMAINS = $MAX_DOMAIN_SEARCH_MATCH;
      $cart  = new db_cart();
      $ordered_rows = $cart->get_all_ordered_rows_by_type( 'R', $order_id);
     
      if($ordered_rows===false){
        $this->error = $cart->error;  
        return false;
      }
      foreach($ordered_rows as $row){
        $rep = $row['report'];
        if(is_object($rep)){
          $search_terms = array('include'=>$rep->search_terms, 'exclude'=>$rep->exclude_terms);
          $start = 0;
          $limit = $MAX_DOMAINS;    
          if($rep->search_type == 2){
            $domain_names = Report::get_history_whois_records(array('start'=>$start,
            'limit'=>$limit, 'search_type'=>$rep->search_type, 'search_terms'=>$search_terms, 'domain_name_only'=>1), true);
            
            
          
          }
          else $domain_names = Report::get_current_whois_records(array('start'=>$start,
           'limit'=>$limit, 'search_type'=>$rep->search_type, 'search_terms'=>$search_terms, 'domain_name_only'=>1), true);
          
          echo "domains_names size is:<br/> ";
          echo count($domain_names);
          echo "<br/>";
          $cart = new db_cart();
          if(!$cart->update_report(array('domain_names'=>implode(Report::$TERM_GLUE, $domain_names), 'view_flag'=>1), $rep->report_id)){
            $this->error = $cart->error;  
            return false;
          }
        }
      }
      return true;
    }
  

  public function set_order_status($order_id, $status){
    if(!$this->init_db())return false;
    $SQL = sprintf("update db_cart_orders set status ='%s' where id = %d", $status, $order_id);
    if(!mysql_query($SQL)){
      $this->error ="fail to update order($order_id) status to $status: ($SQL) : ".mysql_error();
      return false;
    }
    return true;
  }
  /*
  public function get_ordered_rows_by_type($order_id, $type) {
    if(!$this->init_db())return false;
    if($type=='R'){  
      $sql = sprintf("SELECT rep.*, r.id, r.product_id, r.product_name, r.price, r.tax_perc, r.quantity FROM db_cart_rows AS r, db_cart_orders AS ord, whois_report as rep WHERE ord.id = r.order_id AND ord.id = %d AND ord.status = 'O' AND rep.report_id = r.product_id and r.product_type='%s'",  $order_id, $type);     
    }
    $order_array=array();
    if ($result = mysql_query($sql)) {
       
      if (mysql_num_rows($result) > 0) {
        $counter = 0;
        while ($row = mysql_fetch_assoc($result)) {
          foreach($row as $key => $val) {
            $order_array[$counter][$key] = $val;
          }
          $counter++;
        }
      } 
    } else {
      $this->error ="get_ordered_rows: ".mysql_error();
      return false;
    }
    if($type == 'R'){
      foreach($order_array as $index=>$row){
 
        $report = Report::get_report_from_db_row($row);
        $order_array[$index]['report'] = $report;
      }
    }
   
    return $order_array;
  }
  
   */
  public function email_error($options){
    echo "email_error ".$options['error'];
    $emailer=new Email;
	$emailer->from="support@whoisxmlapi.com";
	$subject = "Whois API Payment Error";
	$emailer->send_mail("topcoder1@gmail.com",$subject,$options['error'],null);
  }
  
}
?>