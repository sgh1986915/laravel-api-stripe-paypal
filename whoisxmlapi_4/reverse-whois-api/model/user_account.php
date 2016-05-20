<?php 
if(isset($V2))require_once dirname(__FILE__) . "/../../reverse-whois-api-v2/config.php";
  else if(isset($V1)){ 
  	require_once dirname(__FILE__) . "/../../reverse-whois-api-v1/config.php";
  }
else require_once dirname(__FILE__) . "/../../reverse-whois-api/config.php";
	require_once __DIR__ . "/../manager/DBManager.php";

	class WUserAccount{
		private $username;
		private $data;
		
		function __construct($username) {
			$this->username=$username;
			
		}
		function getDataField($name){
			return $this->data[$name];	
		}
		function loadFromDB(){
			$error_code = false;
			$error_msg = false;
			try{
				$dbh = DBManager::getRW_WhoisAPI_DBConnection();
			
				if(!$dbh){
					$error_code = 1;
					$error_msg = "Unable to load user account from db due to database problem";
					return array($error_code, $error_msg);	
				}
				$st = $dbh->prepare("select * from user_account where username = :username");
				$st->bindParam(':username', $this->username, PDO::PARAM_STR);
				
				$st->execute();
				$result = $st->fetch();
				
				if(!$result){
					$error_code = 2;
					$error_msg="Not user account ".$this->username . " is found in the database.";	
				}
				else{
					$this->data = array_merge($result);
				}
			}catch(PDOException $e){
					$error_code = 1;
					$error_msg = "Unable to validate user due to database problem: ".$e->getMessage();
			}
			return array($error_code, $error_msg);						
		}
		function saveRWCreditToDB(){
			return $this->saveToDB(array('reverse_whois_balance', 'reverse_whois_monthly_balance'));
		}
		function saveToDB($props){
			$error_code = false;
			$error_msg = false;
			try{
				$dbh = DBManager::getRW_WhoisAPI_DBConnection();
			
				if(!$dbh){
					$error_code = 1;
					$error_msg = "Unable to save user account to db due to database problem";
					return array($error_code, $error_msg);	
				}
				$str = "update user_account set ";
				$i=0;
				$n=count($props);
				
				foreach($props as $key){
					$str .= "$key = :$key";
					if($i<$n-1)$str .=", ";
					$i++;
				}
			
				$str .=" where username = :username";
				$st = $dbh->prepare($str);
				
				$st->bindParam(':username', $this->username, PDO::PARAM_STR);
				foreach($props as $key){
					$st->bindParam(":$key", $this->data[$key]);
				}
				
				$result = $st->execute();
			
				if(!$result){
					
					$error_code = 2;
					$error_msg="Failed to save user account $username to the database: ".print_r($st->errorInfo(),1);	
					
					//echo $error_msg;
				}
				
			}catch(PDOException $e){
					$error_code = 1;
					$error_msg = "Unable to save user account to db due to database problem: ".$e->getMessage();
			}
			
			return array($error_code, $error_msg);				
		}
		function deductRWCredit($credit){
			$error_code = false;
			$error_msg = false;
			if($this->data['reverse_whois_balance'] > 0 ){
				$this->data['reverse_whois_balance']-=$credit;
				$this->data['reverse_whois_balance'] = max($this->data['reverse_whois_balance'], 0);
			}
			else if($this->data['reverse_whois_monthly_balance'] > 0 ){
				$this->data['reverse_whois_monthly_balance']-=$credit;
				$this->data['reverse_whois_monthly_balance'] = max($this->data['reverse_whois_monthly_balance'], 0);
			}			
			
			return array($error_code, $error_msg);			
			
		}
	}
?>