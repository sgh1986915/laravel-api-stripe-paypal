<?php
/**
 * Created by PhpStorm.
 * User: Owner
 * Date: 1/30/14
 * Time: 4:52 AM
 */

class DomainUtil {
    public static function isValidDomainNameOrIP($input){
        return self::isValidDomainNameExpression($input) || self::isValidIP($input);

    }
    public static function isValidDomainNameOrIP_NoLenCheck($input){
        return self::isValidDomainNameExpression_NoLenCheck($input) || self::isValidIP($input);

    }    
    public static function isValidDomainNameExpression_NoLenCheck($input){
        if(preg_match('/^[a-zA-Z0-9-_]{1,256}(\.[a-zA-Z0-9-_]{1,}){1,}(\/)*$/', $input)){
            return true;
        }
        return false;
    }    
    public static function isValidDomainNameExpression($input){
        if(preg_match('/^[a-zA-Z0-9-_]{1,61}(\.[a-zA-Z0-9-_]{1,}){1,}(\/)*$/', $input)){
            return true;
        }
        return false;
    }
    public static function isValidIP($input){
        return filter_var($input,FILTER_VALIDATE_IP);
    }
}


