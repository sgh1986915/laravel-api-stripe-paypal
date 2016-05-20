<?php
class DBConfig{
        public static $WhoisAPIUserAccountDatabaseID = 1;
        public static $WhoisAPIUserAccountDatabase = array(
            'host'=>'@WhoisAPIUserAccountDatabaseHost@',
            'username'=>'@WhoisAPIUserAccountDatabaseUsername@',
            'password'=>'@WhoisAPIUserAccountDatabasePassword@',
            'dbname'=>'@WhoisAPIUserAccountDatabaseName@'
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