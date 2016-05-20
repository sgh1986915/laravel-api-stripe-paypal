<?php 
	require_once __DIR__ . "/../util/product_util.php";
	require_once __DIR__ . "/../models/whois_database_product.php";
	
	class CustomWhoisDatabaseProduct extends WhoisDatabaseProduct{
		private static $custom_db_products=array(
			'Alexa Top 1 million domains with parsed whois records'=> array(
									array('id'=>'alexa1mil_current',
										'name'=>'Alexa Top 1 million domains with parsed whois records',
										'description'=>'Current',
										'detail'=>'',
										'price'=>500,
										'num_domains'=>'~1 million'
									),
									array('id'=>'alexa1mil_2014_05_06',
										'name'=>'Alexa Top 1 million domains with parsed whois records',
										'description'=>'collected in May,2014',
										'detail'=>'',
										'price'=>200,
										'num_domains'=>'~1 million'
									),									
									array('id'=>'alexa1mil_2013_11_13',
										'name'=>'Alexa Top 1 million domains with parsed whois records',
										'description'=>'collected in Nov,2013',
										'detail'=>'',
										'price'=>200,
										'num_domains'=>'~1 million'
									),
									array('id'=>'alexa1mil_2012_11_17',
										'name'=>'Alexa Top 1 million domains with parsed whois records',
										'description'=>'collected in Nov,2012',
										'detail'=>'',
										'price'=>200,
										'num_domains'=>'~1 million'
									),
								),
		
			'Quantcast Top 1 million domains with parsed whois records'=>array(
										array('id'=>'quantcast1mil_current',
											'name'=>'Quantcast Top 1 million domains with parsed whois records',
											'description'=>'Current',
											'detail'=>'',
											'price'=>500,
											'num_domains'=>'~1 million'
										),
										array('id'=>'quantcast1mil_2012_09_30',
											'name'=>'Quantcast Top 1 million domains with parsed whois records',
											'description'=>'collected in Sept,2012',
											'detail'=>'',
											'price'=>200,
											'num_domains'=>'~1 million'
										)								
									)
		);
		private $apply_discount=true;
		public function get_products(){
			return self::$custom_db_products;
		}
		
		public function get_product_items_price($items, $price_field_name='price'){
			$total_price = ProductUtil::get_product_items_total($items,$price_field_name);
			return $this->apply_discount ? $this->apply_discount($total_price,$items) : $total_price;
		}
		private function apply_discount($total_price, $items){
			$discount_rate=0.2;
			if(count($items)>1){
				$total_price = $total_price * (1-$discount_rate);
			}
			
			return $total_price;
		}
	}
?>