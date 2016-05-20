<?php require_once __DIR__ . "/../model/user_account.php";
	class AccountManager{
		public static function deductRWUserCredit($username, $credit){
			$userAccount = new WUserAccount($username);
			$ret = array(0, 0);
			$ret = $userAccount->loadFromDB();
			if($ret[0]) return $ret;
			$userAccount->deductRWCredit($credit);
			
			return $userAccount->saveRWCreditToDB();
			
		}
		public static function insufficientRWUserCredit($username, $credits){
			$userAccount = new WUserAccount($username);
			$ret = $userAccount->loadFromDB();
			if($ret[0]) return $ret;
			
			if($userAccount->getDataField('reverse_whois_balance')<$credits && $userAccount->getDataField('reverse_whois_monthly_balance')<$credits){
				return array(-1,'Insufficient reverse whois credit');
			}
			return array(0,0);
			
			
			
		}
	}

?>
