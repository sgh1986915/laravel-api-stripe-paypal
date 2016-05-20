<?php
require_once __DIR__."/RegistrantMonitor.php";

$monitor=new RegistrantMonitor();
$monitor->monitor(
    array(
    'email_from'=>'support@whoisxmlapi.com',
    'email_to'=>'topcoder1@gmail.com',
    'email_subject'=>'your daily domains',
    'days_back'=>1,
    'search_terms'=>array('include'=>array("hugedomains")),
        'start'=>0,
        'limit'=>10000
    )
);

?>