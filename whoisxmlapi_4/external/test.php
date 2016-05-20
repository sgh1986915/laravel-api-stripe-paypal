<?php require_once __DIR__ . "/ExternalAccountManager.php";
require_once __DIR__ . "/../payment/payment_util.php";
function testWhoisBulkClientCreateAccount(){
    emailOrderProcessed("topcoder1@gmail.com", array('order_type'=>'whois_api_client_license'));
}
testWhoisBulkClientCreateAccount();
?>