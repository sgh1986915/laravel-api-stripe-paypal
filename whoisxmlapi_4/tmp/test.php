<?php
 $url="http://www.whoisxmlapi.com/accountServices.php?outputFormat=json&servicetype=accountbalance&username=root&password=PASSWORD";
 $s=file_get_contents($url);
 print_r ($s);
 ?>
 