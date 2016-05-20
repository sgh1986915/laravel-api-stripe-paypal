<?php
/**
 * Warning: does not run on windows localhost, needs to upload to unix server
 */


//require_once __DIR__ . '/../email/Email.php';


require_once __DIR__ ."/config.php";

class FileProcessor {
    public $error="";
    public function getError(){
      return $this->error;
    }
    public function process($params){
        $files=$params['files'];
        $user_id=$params['user_id'];

        $res=$this->moveFilesToDest($params);
	if(!$res)return false;
	if(!$this->startProcessing($res))return false;
	sleep(2);
	$this->startMonitoring($res);

	return true;

    }
    public function process_cmd_line($files_dir, $user_id){
      $files=array();
      if ($handle = opendir($files_dir)) {
	while (false !== ($entry = readdir($handle))) {
	  if ($entry != "." && $entry != "..") {
            $files[]="$files_dir/$entry";
	  }
	}
	closedir($handle);
      }
      if(count($files)<=0){
	$this->error="there are no files in $files_dir";
	return false;
      }
      return $this->process(array('files'=>$files,'user_id'=>$user_id));

    }
    public function process_async($files_dir, $user_id, $output, $ret){
      $cmd= "php " . __DIR__ ."/FileProcessorCli.php $files_dir $user_id > /dev/null &";
      //echo $cmd;
      return exec($cmd, $output, $ret);
      
    }
    private function startMonitoring($params){
      $connection=$params['connection'];
      $root_path=$params['root_path'];
      $module_name=$params['module_name'];
      $monitor_cmd="$root_path/monitor/std_monitor_tld_collection.sh $module_name";
      $output=array();
      $ret=0;
      return exec($cmd, $output, $ret);

    }
    private function startProcessing($params){
      global $fileProcessingRunCmd, $cleanInputFileCmd, $loadInputDomainsCmd;
      $connection=$params['connection'];
      $root_path=$params['root_path'];
      $module_name=$params['module_name'];
      

      $fileProcessingRunCmd="$root_path/$fileProcessingRunCmd";
      $cleanInputFileCmd="$root_path/$cleanInputFileCmd";
      $domain_names_file=trim("$root_path/input/domain_names");
      $cleanupInputCmd="$cleanInputFileCmd $root_path/input/domain_names_in $domain_names_file";
      $loadDomainsCmd="$root_path/$loadInputDomainsCmd $domain_names_file $module_name";

      echo "$cleanupInputCmd<br/>";
      $stdOut="";
      $stdErr="";
      if(!ssh2_exec_blocking($connection,$cleanupInputCmd, $stdOut, $stdErr)){
 	$this->error="Failed to cleanup domain names from $root_path/input/domain_names_in.  $stdErr";
	//echo $this->error;
	return false;
      }
      echo "$loadDomainsCmd<br/>";
      if(!ssh2_exec_blocking($connection,$loadDomainsCmd, $stdOut, $stdErr)){
	$this->error="Failed to load domain names from $domain_names_file into $module_name.  $stdErr";
        return false;
      }
 	 echo "$fileProcessingRunCmd $module_name<br/>";
      if(!ssh2_exec_blocking($connection, "$fileProcessingRunCmd $module_name")){
      	$this->error="Failed to start processing";
        return false;
      }
      return $stream;
    }
    private function moveFilesToDest($params){

      global $fileProcessorHost, $fileProcessorUser, $fileProcessorPassword, $fileProcessorInitCmd, $modulePrefix;
        $files=$params['files'];
        $user_id=$params['user_id'];
        $connection=ssh2_connect($fileProcessorHost,22);
        if (!$connection || !ssh2_auth_password($connection, $fileProcessorUser,$fileProcessorPassword)){
	  $this->error="unable to connect to host $fileProcessorHost";
            return false;
        }
	$stdOut="";
	$stdErr="";
	echo "$fileProcessorInitCmd $user_id<br/>";
	if(!ssh2_exec_blocking($connection, "$fileProcessorInitCmd w_$user_id", $stdOut, $stdErr)){
	   $this->error="unable to create module $user_id.  $stdErr";
	   return false;
	}


	if (count($files)<=0){
	  $this->error="no file to transfer";
	  return false;
	}
	$module_file_path=$stdOut;
	//echo "module_file_path is ".$module_file_path;
	$module_file_path=trim($module_file_path);

	
	
	$remote_dir="$module_file_path/input/domain_names_in/";
	$error="";
	if(!ssh2_scp_send_files($connection, $files, $remote_dir, $error)){
	  $this->error=$error;
	  return false;
	}
	
	$root_path="$module_file_path";
	$module_name=substr(trim(basename($root_path), ' \\'), strlen($modulePrefix));
	
	return array( 'connection' => $connection,
		      'root_path' => $root_path,
		      'module_name'=>$module_name
		      );

    }
}

function ssh2_exec_blocking($connection, $cmd, &$stdOutput, &$stdErr){
  $stream=ssh2_exec($connection, $cmd);
  if(!$stream)return false;
  $errorStream = ssh2_fetch_stream($stream, SSH2_STREAM_STDERR);

  // Enable blocking for both streams
  stream_set_blocking($errorStream, true);
  stream_set_blocking($stream, true);


  $stdOutput=stream_get_contents($stream);
  $stdErr=stream_get_contents($errorStream);
  echo "stdOutput is $stdOutput";
  // Close the streams        
  fclose($errorStream);
  fclose($stream); 
  if(!empty($stdErr))return false;
  return true;

}
function ssh2_scp_send_files($connection, $files, $dest_dir, &$error){
  foreach($files as $file){
    $basefilename=basename($file);
    //echo "send file from ".$file. " to ". "$dest_dir/$basefilename";             
    if(!ssh2_scp_send($connection, $file, "$dest_dir/$basefilename")){
      $error="Failed to send file from $file to $dest_dir";
      return false;
    }

  }
  return true;
}

