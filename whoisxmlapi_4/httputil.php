<?php
   // modified
	require_once __DIR__ ."/CONFIG.php";
   function my_session_start(){
   		if(!session_id())
		    session_start();
         sync_laravel_session(); // modified
   }
   function get_return_to_url(){
     return isset($_REQUEST['returnto']) ? $_REQUEST['returnto']: $_SERVER['REQUEST_URI'];//'index.php';
   }
   function build_ssl_url($link){
   		global $SSL;
   		if(!$SSL){
   			return $link;
   		}
   		$link = ltrim($link,"/");
   		return "https://www.whoisxmlapi.com/$link";
   }

   // modified
   /* Sync laravel session with core PHP session */
   function sync_laravel_session() {
      if(!empty($_SESSION['laravel_user'])) {
         if(empty($_SESSION['myuser']) || !is_object($_SESSION['myuser'])) {
            $_SESSION['myuser'] = new User1($_SESSION['laravel_user']['username'],$_SESSION['laravel_user']);
         }
         $temp_user = $_SESSION['myuser'];
         foreach ($_SESSION['laravel_user'] as $key => $value) {
            /*if($key == 'plain_password') {
               $key = 'password';
            }*/
            $temp_user->$key = $value;
         }
         $_SESSION['myuser'] = $temp_user;
      }
   }

?>
