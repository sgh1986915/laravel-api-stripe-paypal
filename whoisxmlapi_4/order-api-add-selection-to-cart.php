<?php 

	require_once __DIR__ . "/api-products.php";
	
	require_once __DIR__."/httputil.php";
	require_once __DIR__."/util.php";
	require_once __DIR__."/users/users.inc";
	require_once __DIR__."/users/utils.inc";
	$success_url="order-api-checkout.php";
	
		
	$products=array();
	$total_price=0;	

	foreach(APIProducts::$api_products as $product_id=>$p){
		if($_REQUEST[$product_id]){
			$products[]=array('product_id'=>$product_id, 'subscription'=>1);
			//$total_price+=get_query_price($product_id, $_REQUEST[$query]);
		}
		$product_query=$product_id."_queries";
		if($_REQUEST[$product_query]){
			$num_queries=$_REQUEST[$product_query];
			$products[]=array('product_id'=>$product_id,'queries'=>$num_queries);
		}
	}
	add_products_to_cart($products);
	header("location: " . build_url($success_url, $_REQUEST));
	
?>

<?php
	function add_products_to_cart($products){
		my_session_start();
		$_SESSION['products']=$products;
		
	}
	
?>
	
	

