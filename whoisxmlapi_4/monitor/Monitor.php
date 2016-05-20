<?php
require_once __DIR__. "/../email/Email.php";
/**
 * Created by PhpStorm.
 * User: Owner
 * Date: 6/16/14
 * Time: 4:22 PM
 */

abstract class Monitor {
    function __construct() {

    }
    public abstract function getMonitorResult($options);
    public function monitor($options){
        $email=$options['email_to'];
        $email_from=$options['email_from'];
        $email_subject=$options['email_subject'];
	$max_file_size=$options['max_file_size'];

        //print_r($options);
        $res = $this->getMonitorResult($options);
        //echo "res is ";
        //print_r($res);
	$attachments=array();
        $cur_attachment=false;
	$counter=0;
	if(count($res) > 0){
            $email_body="Please see attached domains";
            
            
	    foreach($res as $row){
	      if(!$cur_attachment || ($counter>0 && ($counter%max_file_size)==0 )) {
		$cur_attachment = tempnam("/tmp", "domains_");
		$attachments[]=$cur_attachment;
	      }
              file_put_contents($cur_attachment, "$row\n", FILE_APPEND);
            }
   
	    $counter++;
        }
        else{
            $email_body="there are no domains matching your search term in the latest dataset";
        }
        try{

            $emailer=new Email();
            $emailer->from=$email_from;

            $emailer->send_mail($email,$email_subject,$email_body,null, 'text/plain', $attachments);

        }catch(Exception $exp){
            $error .= "<br>Failed to send email to ". $email .': '. $exp->getMessage();
            echo $error;
        }


    }
}

?>