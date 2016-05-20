<?php 
require_once __DIR__ . "/../users/users.conf";	

	require_once __DIR__ . "/../manager/DBManager.php";

	class WUserAccount{
		private $username;
		private $data;
		private $user_account_table_index="";
		
		function __construct($username, $user_account_table_index="") {
			$this->username=$username;
			$this->user_account_table_index=$user_account_table_index;
		}
		function getDataField($name){
			return $this->data[$name];	
		}
		function loadFromDB(){
			$error_code = false;
			$error_msg = false;
			try{
				$dbh = DBManager::getWhoisAPIUserDBConnection();
			
				if(!$dbh){
					$error_code = 1;
					$error_msg = "Unable to load user account from db due to database problem";
					return array($error_code, $error_msg);	
				}
				$st = $dbh->prepare("select * from user_account" . $this->user_account_table_index . " where username = :username");
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
		
		function saveToDB($props){
			$error_code = false;
			$error_msg = false;
			try{
				$dbh = DBManager::getWhoisAPIUserDBConnection();
			
				if(!$dbh){
					$error_code = 1;
					$error_msg = "Unable to save user account to db due to database problem";
					return array($error_code, $error_msg);	
				}
				$str = "update user_account " . $this->user_account_table_index . " set ";
				$i=0;
				$n=count($props);
				foreach($props as $key){
					$str .= "$key = :$key";
					if($i<$n && $n>1)$str .=", ";
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
					
				}
				
			}catch(PDOException $e){
					$error_code = 1;
					$error_msg = "Unable to save user account to db due to database problem: ".$e->getMessage();
			}
			
			return array($error_code, $error_msg);				
		}
		
	}
?>