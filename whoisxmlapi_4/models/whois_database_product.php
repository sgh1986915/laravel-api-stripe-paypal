<?php require_once __DIR__ . "/../util/product_util.php";
	abstract class WhoisDatabaseProduct{
		
		
		public abstract function get_products();
		public abstract function get_product_items_price($items, $price_field_name='price');
		
		public function get_product_items_by_ids($ids){
			
			$res=array();
			if(!$ids)return $res;
			$products = $this->get_products();
			foreach($ids as $id){
				$item=ProductUtil::get_product_item_by_id($products, $id);
				if($item)$res[]=$item;
			}
			return $res; 
		}
		public function concat_product_item_names($items, $sep=". ", $item_field_name='name'){
			return ProductUtil::concat_product_item_names($items, $sep, $item_field_name);
		}
		public function concat_product_item_names_by_ids($ids, $sep=". ", $item_field_name='name'){
			$items = $this->get_product_items_by_ids($ids);
			return $this->concat_product_item_names($items, $sep, $item_field_name);
			
		}
		public function get_product_items_price_by_ids($ids, $price_field_name='price'){
			$items = $this->get_product_items_by_ids($ids);
			return $this->get_product_items_price($items, $price_field_name);
		}
		
	
		

		
	}
?>