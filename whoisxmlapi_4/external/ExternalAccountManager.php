<?php 
set_include_path(get_include_path() . PATH_SEPARATOR . __DIR__ . '/../../phpseclib');
require_once __DIR__ . "/../util/common_init.php";
require_once __DIR__ . "/config.php";
require_once __DIR__ . "/../util/string_util.php";
require_once __DIR__ . '/../email/Email.php';
require_once 'Net/SSH2.php';
	class ExternalAccountManager{

		public static function createAccount($username, $password, $account_type){
		    global $AccountManagerConfig;
            $t=$AccountManagerConfig[$account_type];
            $createAccountCmd=$t['createAccountCmd'];
            $createAccountHost=$t['createAccountHost'];
            $createAccountUser=$t['createAccountUser'];
            $createAccountPassword=$t['createAccountPassword'];

			$output=array();
			$return_val=0;
			$ssh = new Net_SSH2($createAccountHost);
			if (!$ssh->login($createAccountUser,$createAccountPassword)){
			  return false;
			}
			
			echo $ssh->exec("$createAccountCmd $username $password");
			return true;
		}
        public static function createWhoisAPIBulkClientAccount($username, $password){
            return self::createAccount($username, $password, 'whoisapi_bulkclient');
        }

    }
?>