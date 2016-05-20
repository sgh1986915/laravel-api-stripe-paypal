<?php
  $contents = file_get_contents("http://localhost:8080//whoisserver/WhoisService?domainName=google.com&cmd=GET_DN_AVAILABILITY&username=root&password=changeme&outputFormat=JSON");
 // echo $contents;
  $res=json_decode($contents);
  $domainInfo = $res->DomainInfo;
  if($domainInfo){
    echo "Domain name: " . print_r($domainInfo->domainName,1) ."<br/>";
    echo "Domain Availability: " .print_r($domainInfo->domainAvailability,1) ."<br/>";

    //print_r($domainInfo);
  }
?>