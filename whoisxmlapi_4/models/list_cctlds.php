<?php require_once __DIR__."/cctld_whois_database_product.php";
$obj=new CCTLDWhoisDatabaseProduct();

$products=$obj->get_products();
foreach($products as $p){
	foreach($p as $pt){
		echo $pt['name']."<br/>";
	}
}
?>