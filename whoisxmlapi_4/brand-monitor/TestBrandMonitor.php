<?php
require_once __DIR__."/BrandMonitor.php";

$monitor=new BrandMonitor();
$monitor->monitor(
    array(
    'email_from'=>'support@whoisxmlapi.com',
    'email_to'=>'topcoder1@gmail.com',
    'email_subject'=>'your daily domains',
    'search_terms'=>array('include'=>array("hugedomains")),
        'start'=>0,
        'limit'=>10000
    )
);

?>