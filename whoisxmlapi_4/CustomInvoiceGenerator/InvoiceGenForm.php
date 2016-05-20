<?php
	if(!$submit_action){
		$submit_action=basename(__FILE__);
	}
	$pages = array('right' => 'InvoiceGenForm_main.php');	
	include __DIR__."/../template.php";
?>
