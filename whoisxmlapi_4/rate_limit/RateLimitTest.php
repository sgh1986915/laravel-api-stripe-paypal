<?php require __DIR__ . "/RateLimit.php";
class RateLimitTest{  
  public static function test($username){
    
    $type="reverse_whois";
	
    self::printLine("Rate Policy is ". RateLimit::getRateLimitPolicy($type, $username));
  
    RateLimitTest::testCheckRateLimit($type,$username);
    
    RateLimit::incrUsage($type, $username);
    
    RateLimitTest::testCheckRateLimit($type,$username);
    sleep(5);
    self::printLine("After 5 seconds");
    RateLimit::incrUsage($type, $username, 6);
    RateLimitTest::testCheckRateLimit($type,$username);
    sleep(6);
    self::printLine("After 11 seconds");
    RateLimitTest::testCheckRateLimit($type,$username);
    sleep(10);
    self::printLine("After 21 seconds");
    RateLimitTest::testCheckRateLimit($type,$username);

  }

  protected static function testCheckRateLimit($type, $username){
    self::printLine("usage is now " . RateLimit::getUsage($type,$username));
    if(RateLimit::checkRateLimit($type, $username)){
      self::printLine("within rate limit");
    }
    else{
      self::printLine("exceeded rate limit");
    }
  }
  public static function printLine($s){
  	echo "$s\n<br/>";
  }
}
$username=$_REQUEST['username'];
if(!$username){
	echo "must specify a username";
	exit;
}
RateLimitTest::test($username);
//$rate=RateLimit::getRateLimit("reverse_whois", $username);
//print_r($rate);
?>
