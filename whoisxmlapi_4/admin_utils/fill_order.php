<?php  require_once __DIR__ . "/../util/string_util.php";
  require_once __DIR__ . "/../payment/payment_util.php";
  
  function fill_order($order_id){
    $payment_util = new payment_util();
    if(!$payment_util->fill_order($order_id)) echo $payment_util->error;
    
  }
  
  fill_order($_REQUEST['order_id']);
  
?>