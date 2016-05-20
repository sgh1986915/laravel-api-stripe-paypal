<?php
require_once __DIR__ ."/../monitor/Monitor.php";
require_once __DIR__. "/../brand-alert-api/brand_alert.php";

/**
 * Created by PhpStorm.
 * User: Owner
 * Date: 6/16/14
 * Time: 4:22 PM
 */

class BrandMonitor extends Monitor {
    public function getMonitorResult($options){
        $res = BrandAlert::getDomainsAsArray($options);
        return $res;
    }


}

?>