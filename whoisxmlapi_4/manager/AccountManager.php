<?php require_once __DIR__ . "/../models/user_account.php";
	class AccountManager{

		public static function insufficientUserCredit( $username, $user_table_index, $db_field){
			$userAccount = new WUserAccount($username, $user_table_index);
			$ret = $userAccount->loadFromDB();
			
			if($ret[0]) return $ret;
			
			if($userAccount->getDataField($db_field)<=0){
				return array(-1,'Insufficient credit');
			}
			return array(0,0);
		}
		
		//deduct directly from db for atomicity
		function deductUserAccountCredit($username, $user_table_index, $db_field, $credit){
			$error_code = false;
			$error_msg = false;
			try{
				$dbh = DBManager::getWhoisAPIUserDBConnection();
			
				if(!$dbh){
					$error_code = 1;
					$error_msg = "Unable to save user account to db due to database problem";
					return array($error_code, $error_msg);	
				}
				$str = "update user_account $user_table_index set $db_field=$db_field-$credit";
				
				$str .=" where username = :username";
				$st = $dbh->prepare($str);
				$st->bindParam(':username', $username, PDO::PARAM_STR);
				
				
				$result = $st->execute();
			
				if(!$result){
					
					$error_code = 2;
					$error_msg="Failed to deduct user account credit $username  due to db error: ".print_r($st->errorInfo(),1);	
					
				}
				
			}catch(PDOException $e){
					$error_code = 1;
					$error_msg="Failed to deduct user account credit $username  due to db error: ".print_r($st->errorInfo(),1);	
			}
			
			return array($error_code, $error_msg);				
		}
	}

?>
