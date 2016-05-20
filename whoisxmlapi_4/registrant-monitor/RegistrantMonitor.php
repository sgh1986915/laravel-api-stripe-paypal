<?php
require_once __DIR__ ."/../monitor/Monitor.php";
require_once __DIR__. "/../registrant-alert-api/registrant_alert.php";
require_once(__DIR__ ."/../reverse-whois-api/report.php");

/**
 * Created by PhpStorm.
 * User: Owner
 * Date: 6/16/14
 * Time: 4:22 PM
 */

class RegistrantMonitor extends Monitor {
    public function getMonitorResult($options){
        $res = Report::get_current_whois_records($options,true);
        if($res){
            $res=$res->domains;
        }
        return $res;
    }


}

?>