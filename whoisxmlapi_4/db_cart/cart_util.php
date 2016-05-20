<?php 
  require_once dirname(__FILE__) . '/db_cart_class.php';
  require_once dirname(__FILE__) . '/../httputil.php';
  class cart_util{
    function init_db(){
      if(!connect_to_whoisserver_db()) {
        $this->error = 'db_cart->init_db(): '.mysql_error();
        return false;
      }
      return true;
    }
    //not used
    function cart_not_empty(){
      my_session_start();
      if(isset($_SESSION['cart'])){
          $cart = $_SESSION['cart'];
         
          if(is_object($cart)){
            return count($cart->order_array) > 0 ;
          }
      }
      return false;
    }
    
    //merge anonymous order into user's order
    function merge_shopping_cart(){
      my_session_start();
      $order_id = $_SESSION['order_id'];
      echo "order is $order_id<br/>";
      if(is_object($_SESSION['myuser'])){
        $user = $_SESSION['myuser'];
        $order = $this->get_order_for_user($user->username);
        print_r($user);
        echo "<br/>";
        print_r($order);
         echo "<br/>";
        if($order){
          print_r($order);
          echo "<br/>";
          if($order_id == $order['id']) return true;
          if($this->move_order_items($order_id, $order['id'])){
            echo "item moved";
            //reload shopping cart
            unset($_SESSION['order_id']);
            $cart = new db_cart(array('customer_id' => $user->username));
            $_SESSION['cart'] = $cart;
            print_r($cart);
            return true;  
          }
        }
        else{
          if($this->set_order_owner($order_id, $user->username)){
            
            return true;
          }
        }
      }
      echo "error ".$this->error;
      return false;
    }
    function set_order_owner($order_id, $username){
      if(!$this->init_db()) return false;
      $sql = sprintf("update db_cart_orders set customer = '%s' where id=%d", $username, $order_id);
      if(mysql_query($sql)){
        return true;
      }
      $this->error = "error set_order_owner($order_id, $username), ". mysql_error();
      return false;
      
    }
    function move_order_items($from_order_id, $to_order_id){
      $db_cart = new db_cart();
      $from_order_items = $db_cart->get_ordered_rows_by_type('R', $from_order_id);
      if($from_order_items === false){
        $this->error = $db_cart->error;
        return false;
      }
    
      $to_order_items = $db_cart->get_ordered_rows_by_type('R', $to_order_id);
      if($to_order_items === false){
        $this->error = $db_cart->error;
        return false;
      }
      
      $add_order_rows=array();
      $from_reports = array_map(function($el){
        return $el['report'];
      }, $from_order_items);
      $to_reports = array_map(function($el){
        return $el['report'];
      }, $to_order_items);      
      for($i=0;$i<count($from_reports);$i++){
        $rep = $from_reports[$i];
        if(in_arraye($rep, $to_reports) === false){
          $add_order_rows[] = $from_order_items['id'];
        }
      }
      if(count($add_order_rows)>0){
        $sql ="update db_cart_rows set order_id = $to_order_id where id in (" . implode($add_order_rows) .")"  ;
        if(!mysql_query($sql)){
          $this->error = "error move_order_items from $from_order_id to $to_order_id ($sql), error:".mysql_error();
          return false;
        }
      }
      return true;
    }
    
    function get_order_for_user($username){
      if(!$this->init_db()) return false;
      $sql = sprintf("SELECT id FROM %s WHERE customer = '%s' AND status = 'O' order by id desc", ORDERS, $username);
      
      if ($result = mysql_query($sql)) {
        if ($row = mysql_fetch_assoc($result)) {
           return $row;
        } 
      }
      return false;
  
    }
  }
?>
