<?php 
class RateLimitSettings{
	public static $default_rate_limit=array('reverse_whois'=>array('rate_limit'=>10,'limit_duration'=>10));
	public static $user_rate_limit=array('reverse_whois'=>array(
												'root'=>array('rate_limit'=>5,'limit_duration'=>10),
												'pushpress'=>array('rate_limit'=>5,'limit_duration'=>10),
												'bboivin'=>array('rate_limit'=>100,'limit_duration'=>10)
												)
										);
	
}
?>