<?php 
require_once __DIR__ . "/../util/common_init.php";
require_once __DIR__ . "/config.php";
require_once __DIR__ . "/../util/string_util.php";
require_once __DIR__ . '/../email/Email.php';
	class DomainNameDataAccountManager{
		public static function createAccount($username, $password, $user_type, &$output){
			global $createAccountCmd;

			$output=array();
			$return_val=0;
			echo "$createAccountCmd $username $password $user_type$EOL";
			exec("$createAccountCmd $username $password $user_type", $output, $return_val);
			echo "return val=$return_val, $output is ".print_r($output,1);
			if($return_val == 0){
				return true;
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
					if(DomainNameDataAccountManager::createAccount($username,$password,$user_type,$output)){
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
		
		public static function emailCustomer($email, $params){
			global $EOL, $DOMAIN_DATA_SERVER_URL;
			$username=$params['username'];
			$password=$params['password'];
			$user_type=$params['user_type'];
		
			$body="Dear customer,$EOL" .
				"The following is your access information for downloading daily domain name data $user_type. $EOL" .
     			"-username: $username$EOL" .
     			"-password: $password$EOL" .
				"-type: newly registered domains $EOL". 
				"-url: $DOMAIN_DATA_SERVER_URL/domain_name_data/domain_names_new/ $EOL";
			if($user_type=='professional'  || $user_type=='enterprise'){
				$body.="-type: recently dropped domains$EOL" .
						"-url: $DOMAIN_DATA_SERVER_URL/domain_name_data/domain_names_dropped/ $EOL";
			}
			if($user_type=='enterprise'){	
				$body.="-type: whois data$EOL". 
					"-url: $DOMAIN_DATA_SERVER_URL/domain_name_data/domain_names_whois/ $EOL";
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
			echo"email to $supportNotificationEmail";
			$body=$params['body'];
			$subject=$params['subject'];
       		
         	$emailer=new Email;
		 	$emailer->from="support@whoisxmlapi.com";
		  	$emailer->send_mail($supportNotificationEmail,$subject,$body,null);
			
		}
	}
?>