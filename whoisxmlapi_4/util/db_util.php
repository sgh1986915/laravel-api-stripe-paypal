<?php require_once __DIR__ ."/db_config.php";
class DBUtil{
	public static $WHOISAPI_USER_ACCOUNT_DB=1;
	
	public static function executeQuery($s, $params=false, $param_types=false, $db_id, &$err){
		$con = self::getDBConnection($db_id, $err);	
		if($con === false){
			return false;
		}
		
		$statement = $con->prepare($s);
		
		if ($statement === FALSE) {
    		die ("Mysql Error: " . $con->error);
		}
		else if($params && count($params)>0){
			self::bindStatementParams($statement,$params,$param_types);
		}
		$statement->execute();
		
		$data=self::fetch($statement);
		
		return $data;
	}
	public static function executeUpdate($s, $params=false, $param_types=false, $db_id, &$err){
		$con = self::getDBConnection($db_id, $err);	
		if($con === false){
			return false;
		}
		
		$statement = $con->prepare($s);
		
		if ($statement === FALSE) {
    		die ("Mysql Error: " . $con->error);
		}
		else if($params && count($params)>0){
			self::bindStatementParams($statement,$params,$param_types);
		}
		return $statement->execute();
		
		
	}	
	protected static function bindStatementParams($statement, $params, $param_types){
		array_unshift($params, $param_types);
		
		call_user_func_array(array($statement, 'bind_param'), $params);
	}
	public static function getDBConnection($db_id, &$err){
		$dbInfo = DBConfig::getDBInfo($db_id);
		$con=false;
		if($dbInfo){
			$con = new mysqli($dbInfo['host'], $dbInfo['username'], $dbInfo['password'], $dbInfo['dbname']);
		}
		if($con===false){
			$err="Failed to fetch database connection information for $db_id";
			return false;
		}
		else if($con->connect_errno){
			$err = "Connection failed: ". $con->connect_error;
			return false;
		}
		
		return $con;
	}
	
public static function fetch($result)
{    
    $array = array();
    
    if($result instanceof mysqli_stmt)
    {
        $result->store_result();
        
        $variables = array();
        $data = array();
        $meta = $result->result_metadata();
        
        while($field = $meta->fetch_field())
            $variables[] = &$data[$field->name]; // pass by reference
        
        call_user_func_array(array($result, 'bind_result'), $variables);
        
        $i=0;
        while($result->fetch())
        {
            $array[$i] = array();
            foreach($data as $k=>$v)
                $array[$i][$k] = $v;
            $i++;
            
            // don't know why, but when I tried $array[] = $data, I got the same one result in all rows
        }
    }
    elseif($result instanceof mysqli_result)
    {
        while($row = $result->fetch_assoc())
            $array[] = $row;
    }
    
    return $array;
}

}