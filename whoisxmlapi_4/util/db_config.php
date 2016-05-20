<?php
class DBConfig{
        public static $WhoisAPIUserAccountDatabaseID = 1;
        public static $WhoisAPIUserAccountDatabase = array(
            'host'=>'216.55.137.165',
        	
             'username'=>'writer_dev',
             'password'=>'RQgTQxGzzTeA',
            'dbname'=>'whoisapi'
        );
        public static $databases;
        public static function init(){
            self::$databases=array(
                self::$WhoisAPIUserAccountDatabaseID => self::$WhoisAPIUserAccountDatabase
            );
        }
        public static function getDBInfo($id){
            if(isset(self::$databases[$id])){
                return self::$databases[$id];
            }
            return false;
        }
}
DBConfig::init();