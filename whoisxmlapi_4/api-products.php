<?php require_once __DIR__ . "/util/db_util.php";
	
require_once __DIR__ . "/util/array_util.php";
class APIProducts{
	
	public static $api_products=array(
		'brand-alert-api'=>array('name'=>'Brand Alert API', 'description'=>'Get a list of active and deleted domain names that match a query string.', 'monthly_price'=>5, 'unit_price'=>0.02),
		'registrant-alert-api'=>array('name'=>'Registrant Alert API', 'description'=>'Receive notification whenever specific people register, renew or delete domain names.', 'monthly_price'=>5, 'unit_price'=>0.02)
	);
	private static $product_id_to_db_subscription_field_map=array(
		'brand-alert-api'=>"ba_subscription",
		'registrant-alert-api'=>'ra_subscription'
	);
	public static function removeProductById(&$products, $product_id, $has_attributes=array()){
		$n=count($products);
		
		for($i=0;$i<$n;$i++){
			$product = $products[$i];
			if($product['product_id'] == $product_id  && array_has_keys($product, $has_attributes)){ 
				array_splice($products, $i, 1);
				break;
				
			}
		}
		
		
	}
	public static function computeTotalPrice($products){
		$t=0;
		foreach($products as $p){
			$product_info = self::$api_products[$p['product_id']];
			if($p['subscription']){
				$t+=$product_info['monthly_price'];	
			}
			else if($p['queries']){
				$t+=self::getQueryPrice($p['product_id'], $p['queries']);
			}
		}
		return $t;
	}
	public static function getQueryPrice($product_id, $n){
		
		$item= self::$api_products[$product_id] ;
		return $item['unit_price'] * $n;
	}
	public static function isUserSubscribed($user,$product_id){
		if(!$user)return false;
		$subscription_field = self::product_id_to_db_subscription_field($product_id);
		$sql="select * from user_account2 where username = ? and $subscription_field>0";
		$username=$user->username;
		$params=array(&$username);
		$param_types='s';
		$err=false;
		$ret=DBUtil::executeQuery($sql,$params,$param_types, DBConfig::$WhoisAPIUserAccountDatabaseID, $err);
		
		return $ret && count($ret)>0;
	}
	
	private static function product_id_to_db_subscription_field($product_id){
		if(isset(self::$product_id_to_db_subscription_field_map[$product_id])){
			return self::$product_id_to_db_subscription_field_map[$product_id];
		}
		return false;
	}
	
	public static function check_remove_cart_product(){
		if($_REQUEST['remove']){
			$remove=$_REQUEST['remove'];
			if(!StringUtil::endsWith($remove,"_queries")){
				$remove=$remove."_subscription";
			}
			$types=array("subscription", "queries");
			foreach($types as $type){
				if(StringUtil::endsWith($remove,"_$type")){
					$product_id = substr($remove,0, strlen($remove)-strlen("_$type"));
					$products= &$_SESSION['products'];
					
					APIProducts::removeProductById($products,$product_id, array($type));
					
					break;
				}
			}
			
		}
	}
	public static function check_update_cart_product(){
		if($_REQUEST['update']){
		
			$products = &$_SESSION['products'];
			foreach($products as &$p){
				if(isset($p['queries'])){
					$product_id=$p['product_id'];
					$param_name=$product_id."_queries";
					if(isset($_REQUEST[$param_name])){
						$num = intval($_REQUEST[$param_name]);
						if($num>=0){
							$p['queries']=$num;
						}
					}
				}
			}
		}
		
	}
	public static function shopping_cart_empty(){
		$products=$_SESSION['products'];
		return !$products || count($products)==0;
	}
	public static function get_product_aggregate_info($products){
		$api_products = APIProducts::$api_products;
		$has_subscription=false;
		$has_onetime_items=false;
		$subscription_price=0;
		$onetime_items_price=0;
		$subscription_ids=array();
		$subscription_descriptions = array();
		$onetime_items_descriptions = array();
		$onetime_items=array();
		
		foreach($products as $p){
			if($p['subscription']){
				$has_subscription=true;
				$subscription_price+=$api_products[$p['product_id']]['monthly_price'];
				$subscription_ids[]=$p['product_id'];
				$subscription_descriptions[] = $api_products[$p['product_id']]['name'];
			}
			else {
				if($p['queries']){
					$has_onetime_items=true;
					$onetime_items_price+=APIProducts::getQueryPrice($p['product_id'], $p['queries']);
					$onetime_items_descriptions[] = $p['queries'] . " ". $api_products[$p['product_id']]['name'] . ' queries';
					$onetime_items[]=array('product_id'=>$p['product_id'], 'queries'=>$p['queries']);
					 
				}
			}
		}
		$subscription_id=implode('|', $subscription_ids);
		$subscription_description = implode(' AND ', $subscription_descriptions);
		$onetime_items_description = implode(' AND ', $onetime_items_descriptions);
		$descriptions=array();
		if(strlen($subscription_description) > 0){
			$descriptions[]=$subscription_description;
		}
		if(strlen($onetime_items_description) > 0){
			$descriptions[]=$onetime_items_description;
		}
		$description = implode(" AND ", $descriptions);
		$product_info_compact=array(
			'subscription_ids'=>$subscription_ids,
			'onetime_items'=>$onetime_items,
			
		);
		$res=array( 
			'has_subscription'=>$has_subscription,
			'has_onetime_items'=>$has_onetime_items,
			'subscription_price'=>$subscription_price,
			'onetime_items_price'=>$onetime_items_price,
			'subscription_id'=>$subscription_id,
			'subscription_description'=>$subscription_description,
			'onetime_items_description'=>$onetime_items_description,
			'description'=>$description,
			'product_info_compact'=>$product_info_compact	
		);
		
		return $res;
	}	
}	
?>
