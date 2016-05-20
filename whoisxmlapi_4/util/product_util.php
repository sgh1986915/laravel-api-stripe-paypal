<?php
class ProductUtil{
	public static function get_product_price($products, $id, $child_id=false, $price_field_name='price'){
 		$product=$products[$id];
 		if($child_id!==false){
 			$ch=$product[$child_id];
 			return $ch[$price_field_name];
 		}
 		return $product[$price_field_name];
 	}
 
 	public static function get_product_item_by_id($products, $item_id){
 		foreach($products as $p){
 			foreach($p as $item){
 				if($item['id'] == $item_id ){
 					return $item;	
 				}
 			}
 		}
 		return false;
 	}
 	
 	public static function get_product_item_attr_by_id($item_id, $attr_name){
 		$item=get_product_item_by_id($item_id);
 		if($item){
 			return $item[$attr_name];
 		}
 		return false;
 	}
 	public static function concat_product_item_names($items, $sep=",", $item_field_name='name'){
 		$s="";
 		$n=count($items);
 		for($i=0;$i<$n;$i++){
 			$item=$items[$i];
 			if($i>0)$s.=$sep;
 			$s.=$item[$item_field_name];
 		}
 		return $s;
 		
 	}
 	public function get_product_items_total($items, $field_name){
			$total=0;
			foreach($items as $item){
				$subtotal=$item[$field_name];
				$total+=$subtotal;
			}
			return $total;
	}
}

?>