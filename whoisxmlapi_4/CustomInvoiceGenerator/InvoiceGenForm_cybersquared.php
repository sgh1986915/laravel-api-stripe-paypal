<?php
	$submit_action=basename(__FILE__);
	$invoice_template_params=array(
		'username'=>'cybersquared',
		'email'=>'invoices@cybersquared.com',
		'item_name'=>'Reverse whois alerts for '.date('M,Y', strtotime("first day of previous month"))
	);
	include __DIR__."/InvoiceGenForm.php";
?>
