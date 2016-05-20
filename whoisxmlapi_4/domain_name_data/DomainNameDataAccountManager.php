<?php 
set_include_path(get_include_path() . PATH_SEPARATOR . __DIR__ . '/../../phpseclib');
require_once __DIR__ . "/../util/common_init.php";
require_once __DIR__ . "/config.php";
require_once __DIR__ . "/../util/string_util.php";
require_once __DIR__ . '/../email/Email.php';
require_once 'Net/SSH2.php';
	class DomainNameDataAccountManager{
		public static function createAccount($username, $password, $user_type, &$output, $active_days=""){
		  global $createAccountCmd, $createAccountHost, $createAccountUser, $createAccountPassword;

			$output=array();
			$return_val=0;
			$ssh = new Net_SSH2($createAccountHost);
			if (!$ssh->login($createAccountUser,$createAccountPassword)){
			  return false;
			}
			
			echo $ssh->exec("$createAccountCmd $username $password $user_type $active_days");
			return true;
		}
        public static function deleteAccount($username,   $future_date, &$output){
            global $deleteAccountCmd, $createAccountHost, $createAccountUser, $createAccountPassword;

            $output=array();
            $return_val=0;
            $ssh = new Net_SSH2($createAccountHost);
            if (!$ssh->login($createAccountUser,$createAccountPassword)){
                return false;
            }
            //date format: "2:30 PM 9/21/2013"
            
            $cmd="$deleteAccountCmd $username \"$future_date\"";
           // echo "cmd is $cmd<br/>";
            echo $ssh->exec($cmd);
            return true;
        }
        ////txn_type=subscr_cancel, item_name=Daily Domain Name Data Enterprise, subscr_date=09:56:35 Jan 13, 2014 PST
        public static function deleteAccountFromOrderItem($params, &$output){
			
            $item_name=$params['item_name'];
            $customerEmail=$params['payer_email'];

            $item_name=strtolower($item_name);
            $payment_term='monthly';
            $days_to_add=31;
            if(strpos($item_name, "yearly")!==false){
                $payment_term='yearly';
                $days_to_add=365;
            }
           
            $subscr_start_date=$params['subscr_date'];
           
            $subscr_end_date=self::calculateSubscriptionEndDateFromItemName($subscr_start_date, $item_name);
            
            $end_date = $subscr_end_date;
		
            $domainNameDataNameVariations=array('domain_name_data_', 'domain name data ');
            $error = false;
            foreach($domainNameDataNameVariations as $val){
                $index=strpos($item_name, $val);

                if($index!==false){
                    $username=$customerEmail;

                    $next_index = StringUtil::strpos_multi($item_name, array(" ","_"), $index+strlen($val)+1);

                    if($next_index===false){
                        $next_index=strlen($item_name);
                    }

                    $user_type=substr($item_name,$index+strlen($val), $next_index- $index - strlen($val));
                    if(DomainNameDataAccountManager::deleteAccount($username,$end_date,$output)){

                        return true;
                    }
                    $error=print_r($output,1);
                }
            }
            return false;
        }

        public static function createAccountFromOrderItem($params, &$output){
			global $defaultUserPassword, $EOL;
			$item_name=$params['item_name'];
			$customerEmail=$params['customerEmail'];
			$username=$params['username'];
			$price=$params['price'];
			$payment_type=$params['payment_type'];
			$order_date=$params['order_date'];
			$active_days=$params['active_days'];
			if(!$active_days)$active_days="";
			
			$item_name=strtolower($item_name);
			$payment_term='monthly';
			if(strpos($item_name, "yearly")!==false){
				$payment_term='yearly';
			}
			
			$domainNameDataNameVariations=array('domain_name_data_', 'domain name data ');
			$error = false;
			foreach($domainNameDataNameVariations as $val){
				$index=strpos($item_name, $val);
				
				if($index!==false){
					$username=$customerEmail;
					$password=$defaultUserPassword;
					$next_index = StringUtil::strpos_multi($item_name, array(" ","_"), $index+strlen($val)+1);
					
					if($next_index===false){
						$next_index=strlen($item_name);
					}
					$user_type=substr($item_name,$index+strlen($val), $next_index- $index - strlen($val));
					if(DomainNameDataAccountManager::createAccount($username,$password,$user_type,$output, $active_days)){
						DomainNameDataAccountManager::emailCustomer($customerEmail, array("username"=>$username,"password"=>$password,"user_type"=>$user_type));
						
						$body="Order filled for $customerEmail:$EOL".
   							"-username: $username$EOL".
     						"-password: $password$EOL".
     						"-name:    $username$EOL". 
     						"-email:   $customerEmail$EOL".
     						"-type: daily domain name data $user_type$EOL".
     						"-cost: $price$EOL".
     						"-start: $order_date$EOL".
     						"-payment type: $payment_type$EOL".
     						"-payment term: $payment_term$EOL";

								
						$subject="Order filled for $customerEmail";
						
						DomainNameDataAccountManager::emailSupport(array("subject"=>$subject, "body"=>$body));
						
						return true;
					}
					$error=print_r($output,1);
				}
			}
			$body = "An error has occured while processing order for $customerEmail:$EOL" .
					"order detail:$EOL". 
					$item_name."$EOL".
					"error_detail:$EOL".
					"$error$EOL";
			$subject="Error processin $item_name for $customerEmail";		
			DomainNameDataAccountManager::emailSupport(array("subject"=>$subject, "body"=>$body));
			return false;
		}
		
		private static function construct_domain_data_string($user_type, $prefix){
			global $EOL, $DOMAIN_DATA_SERVER_URL;
			$body="-type: newly registered domains $EOL". 
				"-url: $DOMAIN_DATA_SERVER_URL/$prefix/domain_names_new/ $EOL";
			if($user_type=='professional'  || $user_type=='enterprise' || $user_type=='custom1' || $user_type=='custom2'){
				$body.="-type: recently dropped domains$EOL" .
						"-url: $DOMAIN_DATA_SERVER_URL/$prefix/domain_names_dropped/ $EOL";
			}
			if($user_type=='enterprise' || $user_type=='custom1' || $user_type=='custom2'){
				$body.="-type: whois data$EOL". 
					"-url: $DOMAIN_DATA_SERVER_URL/$prefix/domain_names_whois/ $EOL" .
					"-url: $DOMAIN_DATA_SERVER_URL/$prefix/domain_names_whois2/ $EOL";
			}
			if($user_type=='custom1' || $user_type=='custom2'){
				$body.="-type: whois data categorized by registrant country$EOL". 
					"-url: $DOMAIN_DATA_SERVER_URL/$prefix/domain_names_whois_filtered_reg_country/ $EOL" .
					"-url: $DOMAIN_DATA_SERVER_URL/$prefix/domain_names_whois_filtered_reg_country2/ $EOL";
				
	      
			}
			if($user_type=='custom2'){
			  $body.="-type: whois data categorized by registrant country with proxies removal$EOL". 
				  "-url: $DOMAIN_DATA_SERVER_URL/$prefix/domain_names_whois_filtered_reg_country_noproxy/ $EOL".
				  "-url: $DOMAIN_DATA_SERVER_URL/$prefix/domain_names_whois_filtered_reg_country_noproxy2/ $EOL";
			}			
			
			if($user_type=="custom1" || $user_type=='custom2'){//archived data
			  $body.="$EOL".
			    "Archived whois data$EOL".
			    "-type: archived whois data$EOL".
			    "-url: $DOMAIN_DATA_SERVER_URL/$prefix/domain_names_whois_archive/ $EOL".
			   "-type: archived whois data categorized by registrant country$EOL".
			    "-url: $DOMAIN_DATA_SERVER_URL/$prefix/domain_names_whois_filtered_reg_country_archive/ $EOL";
			  if($user_type='custom2'){
			   $body.="-type: archived whois data categorized by registrant country with proxies removed$EOL".
			     "-url: $DOMAIN_DATA_SERVER_URL/$prefix/domain_names_whois_filtered_reg_country_noproxy_archive $EOL";
			  }
			}
			$body.="$EOL$EOL";
			return $body;
		}
		public static function emailCustomer($email, $params){
			global $EOL, $DOMAIN_DATA_SERVER_URL;
			$INCLUDE_NGTLDS=true;
			
			$username=$params['username'];
			$password=$params['password'];
			$user_type=$params['user_type'];
		
			$body="Dear customer,$EOL" .
				"The following is your access information for downloading daily domain name data $user_type. $EOL" .
     			"-username: $username$EOL" .
     			"-password: $password$EOL" .
				self::construct_domain_data_string($user_type, "domain_name_data");
			if($INCLUDE_NGTLDS){
				$body.="The following is your access information for downloading new GTLDs: $EOL";
				$body.=self::construct_domain_data_string($user_type, "ngtlds_domain_name_data");
			}
			
			$body.="let us know if you have questions$EOL" .
				"thanks,$EOL";
				
			
			$body .="$EOL-Whois API LLC";
			$body .= "$EOL on ".date('m/d/Y');
        	$body .= " at ".date('g:i A').".\n";
        	
			$subject="Domain Name Data $user_type download information";
			
			$emailer=new Email;
		 	$emailer->from="support@whoisxmlapi.com";
		  	$emailer->send_mail($email,$subject,$body,null);
		}
		public static function emailSupport($params){
			global $supportNotificationEmail;	   
			//print_r($params);
			$body=$params['body'];
			$subject=$params['subject'];
       		
         	$emailer=new Email;
		 	$emailer->from="support@whoisxmlapi.com";
		  	$emailer->send_mail($supportNotificationEmail,$subject,$body,null);
			
		}
		
		public static function calculateSubscriptionEndDateFromItemName($subscr_start_date, $item_name){
		
			$days=self::get_days_to_add_from_item_name($item_name);
			
			return self::calculateSubscriptionEndDate($subscr_start_date, $days);
		}
				
		public static function calculateSubscriptionEndDate($subscr_start_date, $days_to_add){
			$subscr_start_date=new DateTime($subscr_start_date);
			$subscr_end_date=$subscr_start_date->add(date_interval_create_from_date_string("$days_to_add days"));
			$end_date = date_format($subscr_end_date, "g:i A m/d/Y");
			
			return $end_date;
		}
		public static function convertDateFormat($date_str){
			$date=new DateTime($date_str);
			$date_str = date_format($date, "g:i A m/d/Y");
			return $date_str;
		}
		public static function get_days_to_add_from_item_name($item_name){
		
			$item_name_lower=strtolower($item_name);
		
			$days_to_add=31;
			if(strpos($item_name_lower, "yearly")!==false){
		
				$days_to_add=365;
			}
			return $days_to_add;
		}
	}
?>