 <?php 
$domain = "google.com";
$url = "http://www.whoisxmlapi.com/whoisserver/WhoisService?domainName=$domain&userName=tom3987&password=elephant";
 
// create your curl handler
$ch = curl_init($url);
// set your options
@curl_setopt($ch, CURLOPT_MUTE, 1);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0); //ssl stuff
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:  application/x-www-form-urlencoded'));
curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
 
// your return response
$output = curl_exec($ch);
 
// close the curl handler
curl_close($ch);

?>