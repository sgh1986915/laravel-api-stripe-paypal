<?php
//need to copy files from ~/.ssh/* to /var/www/.ssh/
//$createAccountHost="64.150.176.240";
$createAccountHost="216.18.199.114";
$createAccountUser="root";
//$createAccountPassword="Templecity123!";
$createAccountPassword="Zhangming1980!@!!!!!";
$createAccountCmd="/home/passwords/add_zone_file_user.sh"; //modify by info
$supportNotificationEmail="topcoder1@gmail.com";
//$defaultUserPassword="G2ef5ReG";
$chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
$defaultUserPassword=substr(str_shuffle($chars),0,8);
$deleteAccountCmd="/home/passwords/remove_domain_names_data_user_in_future.sh";
$EOL="\n";
$DOMAIN_DATA_SERVER_URL="http://bestwhois.org/zone_file/"; //modify by info

?>