<?php
  require_once  dirname(__FILE__)."/users/utils.inc";
   require_once dirname(__FILE__)."/payment/payment_util.php";
   
   //print_r($_REQUEST);
   $order_id = $REQUEST['invoice'];//not working
   $payment_util = new payment_util();
   if(!$payment_util->pend_order($order_id)) $error =  $payment_util->error;
	$order_type = (isset($_REQUEST['order_type']) ? $_REQUEST['order_type'] : false);
?>

<p class="rightTop"></p>
<?php if(isset($error)){
  show_error($error);
}
?>
<h2>Thank you</h2> 
<div class="rightTxt2" style="font-weight:bold">
	<?php if($order_type=='report'){?>
		Your order has been processed.  Please login using the account username that you ordered for, click on "Reverse Whois" on the top, and then click on "My Reverse Whois Reports" on the left to see
		your reports.
		
	<?}
	else if($order_type=='credit'){?>
		Your order has been processed.  Please login using the account username that you ordered for and start using reverse whois searches to utilize your credits.
	<?php 
	}
	else{?>
		
		Your order will be processed and filled shortly.   
	<?php }?>
	Please email <a href="mailto:support@whoisxmlapi.com">support@whoisxmlapi.com</a> if you have any questions.
	
	<br class="spacer" />
	

</div>





