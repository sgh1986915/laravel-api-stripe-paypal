<?php
	$BUSINESS_NAME ="Whois XML API";
	$TEST_MODE=0;
	//testing using 4242424242424242 
	//$STRIP_API_PUBLIC_KEY ="pk_Bo9UVWn1xjt5AhrE2tpTCkB2WN8m4";	
	//$STRIP_API_SECRET_KEY ="XY4fxHkKX9Qyn5dzRSx2p59KxYMVAhaP";
	
	//production
	$STRIP_API_PUBLIC_KEY_1 ="pk_WcX2cQdbWunp0DSNAb1PbMCfEpFaY";	//topcoder1@gmail.com
	$STRIP_API_SECRET_KEY_1 ="AOb4DALG80s2IvYH7F5x79oMIr5Ffj3O";
	$STRIP_API_PUBLIC_KEY_2 ="pk_gzpaFzSu4LVYzxFSHOITHgcEpIT7J";	//support@whoisxmlapi.com
	$STRIP_API_SECRET_KEY_2 ="DUxzrjZcEDDSOL3MmeX9QFKDBRdMP8VL";
	$STRIP_API_PUBLIC_KEY_3 ="pk_live_kmvlUNbNlG5YwH2HLytwkiHK";	//support@mookal.com
	$STRIP_API_SECRET_KEY_3 ="sk_live_zsDw7nCyuK9kAbSGRzD8GsGK"; 
	$STRIP_API_PUBLIC_KEY_4 ="pk_live_1uMz2su23Wd4L3GC6z4XLCch";	//support@domainwhoisdatabase.com
	$STRIP_API_SECRET_KEY_4 ="sk_live_lS2B0SEttTCxi0BeKBudx0CH";
	$STRIP_API_PUBLIC_KEY_5 ="pk_live_cc3hwy0vvAZuquxlneNtFdLy";	//support@whoiswebservice.net
	$STRIP_API_SECRET_KEY_5 ="sk_live_FJ9Bufy1cBqI7ffZx57uAw3X";
	
		
	$STRIP_API_CURRENT_PUBLIC_KEY = $STRIP_API_PUBLIC_KEY_4;
	$STRIP_API_CURRENT_SECRET_KEY = $STRIP_API_SECRET_KEY_4;
							   
	if($TEST_MODE){
		$STRIP_API_PUBLIC_KEY_1 ="pk_Bo9UVWn1xjt5AhrE2tpTCkB2WN8m4";	//topcoder1@gmail.com
		$STRIP_API_SECRET_KEY_1 ="XY4fxHkKX9Qyn5dzRSx2p59KxYMVAhaP";
		$STRIP_API_PUBLIC_KEY_2 ="pk_M8AzGAAh82GAOl1W3ayWykQa5bEfu";	//support@whoisxmlapi.com
		$STRIP_API_SECRET_KEY_2 ="xt1cB6LECUbfOl24MJgJr6LqLxxqn483";
		$STRIP_API_PUBLIC_KEY_3 ="pk_test_KbsDqx8QnAmTIW6hmaQ6QTZH";	//support@mookal.com
		$STRIP_API_SECRET_KEY_3 ="sk_test_sqViNUsshirMGvt31cKuSiCY"; 
		$STRIP_API_PUBLIC_KEY_4 ="pk_test_a5lT6DPaHMQaQgthq6fbixpQ";	//support@domainwhoisdatabase.com
		$STRIP_API_SECRET_KEY_4 ="sk_test_yOKVw8QPAoSlqbp6qLAmzdDI";
		$STRIP_API_PUBLIC_KEY_5 ="pk_test_hLOfM7ZMTHfg66061rrhhvNs";	//support@whoiswebservice.net
		$STRIP_API_SECRET_KEY_5 ="sk_test_YoNxWwBwf6YukMJyssZ2hAyR"; 
	
		$STRIP_API_CURRENT_PUBLIC_KEY = ${"STRIP_API_PUBLIC_KEY_$TEST_MODE"};
		$STRIP_API_CURRENT_SECRET_KEY = ${"STRIP_API_SECRET_KEY_$TEST_MODE"};
	
	}
	
	$STRIP_API_SECRET_KEYS=array(
		$STRIP_API_SECRET_KEY_1,
		$STRIP_API_SECRET_KEY_2,
		$STRIP_API_SECRET_KEY_3,
		$STRIP_API_SECRET_KEY_4,
		$STRIP_API_SECRET_KEY_5
	);
	//echo "pub key is $STRIP_API_CURRENT_PUBLIC_KEY";
	
	
    $PAYPAL_EMAIL = "support@whoisxmlapi.com";
   	$CURRENT_PAYMENT_EMAILS = array('support@whoisxmlapi.co',"sales@whoisxmlapi.com", "dev@whoisxmlapi.com", 'support@whoisxmlapi.com'); //putback later
 //   $CURRENT_PAYMENT_EMAILS = array('support@whoisxmlapi.com');
    $ALL_PAYPAL_EMAILS = array_merge($CURRENT_PAYMENT_EMAILS, array("support@whoisxmlapi.net",  "support@whoisxmlapi.com", "wo_shi_ni_ba_ba@yahoo.com"));
    
    function getRandomPaymentEmail(){
    	global $CURRENT_PAYMENT_EMAILS;
    	$t = array_rand($CURRENT_PAYMENT_EMAILS);
    	
    	return $CURRENT_PAYMENT_EMAILS[$t];
    	
    }
    
    function getStripePrivateKey($publicKey=false){
    	global $STRIP_API_CURRENT_PUBLIC_KEY, $STRIP_API_PUBLIC_KEY_1, $STRIP_API_PUBLIC_KEY_2,$STRIP_API_PUBLIC_KEY_3,$STRIP_API_PUBLIC_KEY_4, $STRIP_API_PUBLIC_KEY_5,$STRIP_API_CURRENT_SECRET_KEY, $STRIP_API_SECRET_KEY_1, $STRIP_API_SECRET_KEY_2, $STRIP_API_SECRET_KEY_3, $STRIP_API_SECRET_KEY_4, $STRIP_API_SECRET_KEY_5;
    	$match=array(
    		$STRIP_API_PUBLIC_KEY_1=>$STRIP_API_SECRET_KEY_1,
    		$STRIP_API_PUBLIC_KEY_2=>$STRIP_API_SECRET_KEY_2,
    		$STRIP_API_PUBLIC_KEY_3=>$STRIP_API_SECRET_KEY_3,
    		$STRIP_API_PUBLIC_KEY_4=>$STRIP_API_SECRET_KEY_4,
    		$STRIP_API_PUBLIC_KEY_5=>$STRIP_API_SECRET_KEY_5
    	);
    	if(!$publicKey)return $STRIP_API_CURRENT_SECRET_KEY;
    	return $match[$publicKey];
    }
    
?>
