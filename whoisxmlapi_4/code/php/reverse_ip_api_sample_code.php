<?php
// Fill in your details //
//$username = "YOUR_USERNAME";
//$password = "YOUR_PASSWORD";
$username="root";
$password="PASSWORD";
$input = "google.com";
$format = "JSON"; //or XML

$url = "http://www.whoisxmlapi.com/api/reverseip/$input?username=$username&password=$password&outputFormat=$format";
//$url="http://www.whoisxmlapi.com/whoisserver/WhoisService?domainName=google.com&cmd=GET_DN_AVAILABILITY&username=$username&password=$password&outputFormat=JSON";

echo "querying $url<br/>";
if($format=='JSON'){
  // Get and convert the json string into an object for further use
  $result = json_decode(file_get_contents($url));
  print_r($result);
}
?>