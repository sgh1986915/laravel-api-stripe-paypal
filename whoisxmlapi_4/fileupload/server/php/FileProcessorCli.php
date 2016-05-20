<?php require_once __DIR__ . "/FileProcessor.php";

if(count($argv)<=1){
  return;
}
$fileDir=$argv[1];
$userId=$argv[2];
$p=new FileProcessor();
$p->process_cmd_line($fileDir,$userId);

?>