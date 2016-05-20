<?php
	$cctld_db_products=array(
		'.co.uk'=>array('id'=>'.co.uk',
						'name'=>'.co.uk whois records',
						'description'=>'',
						'detail'=>'',
						'domain_names_price'=>200,
						'parsed_whois_price'=>800,
						'num_domains'=>'5.6 million'
						),
		
		'.de'=>array('id'=>'.de',
						'name'=>'.de whois records',
						'description'=>'',
						'detail'=>'',
						'domain_names_price'=>200,
						'parsed_whois_price'=>800,
						'num_domains'=>'4.6 million'
						),				
		'.ca'=>array('id'=>'.ca',
						'name'=>'.ca whois records',
						'description'=>'',
						'detail'=>'',
						'domain_names_price'=>200,
						'parsed_whois_price'=>400,
						'num_domains'=>'1.1 million'
						),
		'.fr'=>array('id'=>'.ca',
						'name'=>'.ca whois records',
						'description'=>'',
						'detail'=>'',
						'domain_names_price'=>200,
						'parsed_whois_price'=>400,
						'num_domains'=>'778,317'
						),						
														
	);
	
 function get_cctld_whois_db_price($id, $type){
 	global $cctld_db_products;
 	$product=$cctld_db_products[$id];
 	if($type=='domain_names')return $product['domain_names_price'];
 	return $product['parsed_whois_price']; 
 	
 }
 
?>