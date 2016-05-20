<?php require_once "util/string_util.php";
  require_once "payment/payment_util.php";
  require_once dirname(__FILE__) . "/users/whois_server.inc";
  require_once "util/base_conversion.php";
  
  if(isset($_REQUEST['order_id']))fill_order($_REQUEST['order_id']);
  
  function fill_order($order_id){
    $payment_util = new payment_util();
    if(!$payment_util->fill_order($order_id)) echo $payment_util->error;
    
  }
  
  ?>