<?php require_once "httputil.php";
require_once "setup.php";
   my_session_start();
   global $WHOIS_LOGOUT_URL;
   if(isset($_SESSION['myuser'])){
   		unset($_SESSION['myuser']);
	
		//$url = $WHOIS_LOGOUT_URL;
		//$content = file_get_contents($url);
   }
   remove_order_from_session();
   
  // echo "Return to is ".get_return_to_url();
    //echo urldecode(get_return_to_url());
  //print_r($_REQUEST);
  Header("Location: ". urldecode(get_return_to_url()));

  function remove_order_from_session(){
    unset($_SESSION['order_id']);
    unset($_SESSION['cart']);
  }
?>
