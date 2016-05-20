<?php require('CustomUploadHandler.php');
require('FileProcessor.php');
require_once(dirname(__FILE__) . '/../../../email/Email.php');
@session_start();
$upload_handler = $_SESSION['upload_handler'];
$response=array();
$error=false;
if(!$upload_handler){
    $error="No files have been uploaded";
    echo json_encode(array('error'=>$error));
    return;
}

$uploadPath= $upload_handler->get_upload_path();
$userId=$upload_handler->get_user_id();
$fileProcessor = new FileProcessor();
$output=array();
$ret=0;
$email=$_SESSION['email'];
$num_lines_uploaded=$upload_handler->count_all_file_lines() ;
if($num_lines_uploaded<=0){
    $error="No files have been uploaded to $uploadPath";
    echo json_encode(array('error'=>$error));
    return;
}
if($num_lines_uploaded>$upload_handler->getMaxLines()){
    $error="Total number of domains uploaded ($num_lines_uploaded) exceeds the maximum allowed (".$upload_handler->getMaxLines().")";
    echo json_encode(array('error'=>$error));
    return;
}

emailFileProcessingStarted($email,$userId);


$fileProcessor->process_async($uploadPath,$userId, $output, $ret);

echo json_encode($response);



?>


<?php
function emailFileProcessingStarted($email,$userId){
    $emailer=new Email;
    $emailer->from="support@whoisxmlapi.com";
    $subject="bulk whois lookup processing started";
    $body="email=$email, userId=$userId";
    $emailer->send_mail('topcoder1@gmail.com',$subject,$body,null);
}
?>