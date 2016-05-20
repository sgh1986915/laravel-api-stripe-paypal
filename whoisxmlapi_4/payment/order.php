<?php require_once __DIR__ ."/order_config.php";
require_once __DIR__ ."/../util/error_util.php";
require_once __DIR__ ."/../util/string_util.php";
require_once __DIR__ ."/../api-products.php";
require_once __DIR__ ."/../email/Email.php";
class Order{
	public static function test_fill_api_order_paypal(){
		$test_ipn=array(
			'custom'=>'api-orders',
			'payment_status'=>'Completed',
			'payment_date'=>'00:01:08 Feb 03, 2013 PST'
		);
		$order_info="6gTDFIcZj8RohrIYwQXSH3UBTf6firsKAApYghSm+lqWF4i3YPuEmjkMLWxKEd/rjq77W7QR7IiQIECPY+6ui35SwS6uQTVlpzEYIwYpz3e/kc3ubeCavcOTrTsr6gPVm+SRiW1QHGezcwLRpqoia/9QNd8OOb5A8nI5vWPgDkJAQb5R99mPWRFh/Jp64ECWVYA9H5UTFTNd2PmPQamnIgS7sOov+1gztXpITq5dbGYqi+GcG5nNxWlL685Biy5HugvcXwXaRfrBeqTiHrNz5UKhXx2kfKWuKNVg57QGU8E0YV6h3jDiYBn2br7pM57Dg3tH0nbZ3/FMTP3Wru8n8DdnBkiqmyR5FmATTeq8P3K1gmR+Rw1NkdAaG67YfjcLOcpjvC5DS/cipYUsiQuW73b0RRC2mP8gXqggPxhn+qg=";
		if(self::fill_api_order_paypal($test_ipn,$order_info)){
			echo "filled";
		}
	}
	public static function fill_api_order_paypal($ipn_data, $order_info, &$invoice_data){
			
		//if(!$order_info || $ipn_data['custom'] !='api-orders'|| $ipn_data['payment_status'] != 'Completed') return false;
		echo $order_info;
		echo "dehash to ".StringUtil::dehash_dn($order_info);
		$order_info = json_decode(StringUtil::dehash_dn($order_info), true);
		if(!$order_info)return false;
		
		$products = $order_info['products'];
		if(!$products)return false;
		$user=$order_info['user'];
		$agg=APIProducts::get_product_aggregate_info($products);
		$skip_onetime_items=false;
		if($order_info['first_payment_date']){
			$diff = $ipn_data['payment_date'] - $order_info['first_payment_date'];
			if($diff > 3600*24*10){//greater than 10 days
				$skip_onetime_items=true;
			}
		}
		$invoice_data['username'] = $user->username;
		
		try{
			self::fill_api_order($products,$agg,$user,$skip_onetime_items);
		}catch(Exception $e){
			 echo 'Order::fill_api_order_paypal Caught exception: ',  $e->getMessage(), "\n";
		}
		return true;
		
	}
	public static function fill_api_order($products,$agg,$user, $skip_onetime_items=false){
		self::remote_fill_api_order($products, $agg, $user, $skip_onetime_items);
	}
	public static function remote_fill_api_order($products,$agg,$user, $skip_onetime_items=false){
		if(is_object($user))$user=get_object_vars($user);
		$errs=array();
		foreach($products as $p){
			if($p['subscription']){
				list($response_code, $response) = self::set_service_start($p, $user);
				
			}
			else if (!$skip_onetime_items){
				if(isset($p['queries'])){ 
					//&cmd=quota&action=addBAQueryBalance&quantity=1000
					$action = 'add'.self::product_id_to_service_name($p['product_id']) . "QueryBalance";
					list($response_code, $response) = self::set_remote_query(array('cmd'=>'quota', 'action'=>$action, 'quantity'=>$p['queries'], 'accountUserName'=>$user['username']), $p, $user);
					echo "response is ".print_r($response,1);
				}
			}
			if(isset($response_code) && $response_code==false){
				$errs[]=$response;
			}
		}
		if(count($errs)>0){
			self::fill_api_order_error($errs, $products,$agg,$user);	
		}
		else{
			
			self::fill_api_order_success($products,$agg, $user);
		}
	}
	public static function set_remote_query($params, $product, $user){
		$url=self::get_remote_query_url($params); //echo "<br/>$url</br/>";
		return self::exec_url($url,$product,$user);
	}
	protected static function get_remote_query_url($params){
		$url = OrderConfig::$remote_order_fill_base_url . http_build_query( array_merge(array('username'=>OrderConfig::$remote_order_admin_username, 'password'=>OrderConfig::$remote_order_admin_password), $params));
		return $url;
	}
	public static function set_service_start($product, $user){
		
		$product_id=$product['product_id'];
		$accountUserName=$user['username'];
		$url = OrderConfig::$remote_order_fill_base_url . "username=" . OrderConfig::$remote_order_admin_username . "&password=" .OrderConfig::$remote_order_admin_password;
		$url = $url . "&cmd=subscriptions&accountUserName=$accountUserName&status=1&service=".self::product_id_to_service_name($product_id) . "Subscription";
		return self::exec_url($url, $product, $user);
		
	}
	protected static function exec_url($url, $product, $user){
		handle_warning_as_error();
		try{
			$resp=file_get_contents($url);
			restore_error_handler();
			return array(true, $resp);	
		}catch(Exception $exp){
			
			restore_error_handler();
			return array(false, "error when fill product ".$product['id']. " for ".$user['username']. ": ".$exp->getMessage());
		}
		
		
		
	}
	protected static function product_id_to_service_name($product_id){
		$a=explode("-", $product_id);
		foreach($a as $key=>$val){
			if($val=='api'){
				unset($a[$key]);
			}
		}
		$b = array_map("ucfirst", $a);
		return implode("",$b) ;
	}
	protected static function fill_api_order_error($errors, $products, $agg,$user){
		self::email_error($errors);
	}
	protected static function fill_api_order_success($products,$agg,$user){
		self::email_order_processed($products,$agg,$user);
		self::email_admin_order_processed($products,$agg,$user);
	}
	protected static function email_admin_order_processed($products, $agg, $user){
		 $subject = 'Order was processed for '.$user['username'];
		 $body = "The following order was processed for ". $user['username']. ":\n" .  $agg['description'];
		 $body .="\n\n-Whois API LLC";
         $body .= "\n ". date('m/d/Y g:i A') . "\n";
         $emailer=new Email;
		 $emailer->from=OrderConfig::$support_email;
		 $emailer->send_mail(OrderConfig::$order_processed_receiving_email, $subject,$body,null);
		
	}
	protected static function email_order_processed($products, $agg, $user){
		 $subject = 'Your order was processed.';
		 $body = "Dear user,\nThank you for ordering the following product(s):\n" .  $agg['description'];
		 $body .="\n\n-Whois API LLC";
         $body .= "\n ". date('m/d/Y g:i A') . "\n";
         $emailer=new Email;
		 $emailer->from=OrderConfig::$support_email;
		 $emailer->send_mail($user['email'], $subject,$body,null);
		
	}
	protected static function email_error($errors){
    	
    	$emailer=new Email;
		$emailer->from=OrderConfig::$support_email;
		$subject = "Whois API Fill Order Error";
		$error_msg=implode("\n",$errors);
	
		$emailer->send_mail(OrderConfig::$order_error_receiving_email,$subject,$error_msg,null);
  	}
}
//Order::test_fill_api_order_paypal();