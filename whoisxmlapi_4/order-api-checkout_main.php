
<?php

	if(isLoggedIn()){
		include "order-api-payment-member_main.php";
	}
	else{
		display_login_msg(false, true);
	}
	
?>


