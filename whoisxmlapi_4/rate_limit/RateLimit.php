<?php require_once __DIR__."/RateLimitSettings.php";
class RateLimit{
  protected static $cache;
  
  
  public static function getRateLimitPolicy($type, $user=false){
	$p=self::getRateLimitProperty($type,$user);
    return "maximum of " . $p['rate_limit'] . " queries within " . $p['limit_duration'] . " seconds";
  }
  protected static function getRateLimitProperty($type, $user){
  	if ($user){ 
    	$settings=RateLimitSettings::$user_rate_limit[$type];
    	
    	if($settings && $settings[$user]){
    		$p = $settings[$user];		
    	}
    }
  
    if(!$p){
    	$p = RateLimitSettings::$default_rate_limit[$type];
    }
    return $p;
  }
  public static function getRateLimit($type, $user){
  	$p=self::getRateLimitProperty($type,$user);
  	return $p['rate_limit'];
  }  
  protected static function initCache(){
    if(!self::$cache){ 
      self::$cache = new Redis();
      try{
	if(!self::$cache->connect("127.0.0.1", 6379)){
	  self::$cache=false;
	  error_log("Failed to connect to redis server");

	}
      }catch (Exception $e){
	self::$cache = false;
	error_log("Failed to connect to redis server ".$e->getMessage());

      }
    }

    return self::$cache;
  }
  public static function checkRateLimit($type, $username){
    if(!self::initCache())return true;
    $rateLimit = self::getRateLimit($type,$username);
  
    $cacheKey=self::getCacheKey($type, $username);
    $usage=self::$cache->get($cacheKey);
    //echo "usage is $usage key is $cacheKey username is $username";
    if($usage>=$rateLimit){
      return false;
    }

    return true;
  }
  public static function getRateLimitDuration($type, $username){
  	$p = self::getRateLimitProperty($type,$username);
  	return $p['limit_duration'];
  }
  public static function incrUsage($type, $username, $increase=1){
    if(!self::initCache())return false;
    $limitDuration = self::getRateLimitDuration($type,$username);
    $cacheKey=self::getCacheKey($type,$username);
    $usage=self::$cache->get($cacheKey);
    if(!$usage){
      //echo "set cacheKey $cacheKey to 0 with duration $limitDuration";
      self::$cache->set($cacheKey,0);
      self::expiresCache($cacheKey, $limitDuration);
    }
    self::$cache->incrBy($cacheKey,$increase);
  }
  public static function getUsage($type, $username){
    if(!self::initCache())return false;
    $cacheKey=self::getCacheKey($type,$username);
    return self::$cache->get($cacheKey);
  }
  protected static function getCacheKey($type, $username){
  
    return "$type"."_usage_$username";
  }
  protected static function expiresCache($cacheKey, $duration){
    if(!self::initCache())return false;
    self::$cache->setTimeout($cacheKey,$duration);
  }

}
//RateLimit::test();


?>
