<?php
ini_set('auto_detect_line_endings',true);

/**
 * Created by PhpStorm.
 * User: Owner
 * Date: 2/3/14
 * Time: 8:10 AM
 */
require_once __DIR__."/DomainUtil.php";
require_once __DIR__."/UploadHandler.php";
require_once __DIR__."/PunyCode.php";
use True\Punycode;
class CustomUploadHandler extends UploadHandler {
    public static $DEFAULT_MAX_LINES=1000000;
    public function getMaxLines(){

        if($this->options['max_lines']){

            return $this->options['max_lines'];
        }
        return self::$DEFAULT_MAX_LINES;
    }
    public function get_total_num_lines_uploaded(){

    }
    public function get_user_id() {
        @session_start();
        return session_id();

    }
    protected function get_file_object($file_name) {
        $obj=parent::get_file_object($file_name);
        if($obj){
            $obj->numLines=self::count_file_lines( $this->get_upload_path($file_name));
        }

        return $obj;
    }
    protected function validate($uploaded_file, $file, $error, $index) {
        if(!parent::validate($uploaded_file,$file,$error,$index)){
            return false;
        }
        return $this->validate_file_content($uploaded_file, $file, $error, $index);

    }
    protected function count_file_lines($file){
        $handle = fopen($file, "r");
        if ($handle) {
            $line_no=0;
            while (($line = fgets($handle)) !== false) {
                $line=trim($line);
                if(empty($line))continue;
                $line_no++;


            }
        }

        return $line_no;
    }
    protected function validate_file_content($uploaded_file, $file, $error, $index){

        $handle = fopen($uploaded_file, "r");
        if ($handle) {
            $line_no=0;
            $Punycode = new Punycode();
            while (($line = fgets($handle)) !== false) {
                // process the line read.
                $line=trim($line);
                if(empty($line))continue;
                $line_no++;
                $line= $Punycode->encode($line);
                
                if(!DomainUtil::isValidDomainNameOrIP_NoLenCheck($line)){
                
                	
                    $file->error="invalid domain name on line $line_no : $line";
                    return false;
                }

            }
        } else {
            $file->error="unable to read file $uploaded_file";
            return false;
        }
        $n=$this->count_all_file_lines() + $line_no;
        if($n>$this->getMaxLines()){
            $file->error="This file contains $line_no lines, the total number of lines in your files will exceed the maximum of ".$this->getMaxLines();
            return false;
        }
        
        return true;


    }
    public function count_all_file_lines(){
        $dir=$this->get_upload_path();
        $count = 0;
	if (!is_dir($dir)) {
		mkdir($dir, 0755, true);
	}
        if ($handle = opendir($dir)) {

            while (false !== ($entry = readdir($handle))) {
               if ($entry != "." && $entry != "..") {
                    $file= "$dir/$entry";
                    $count+=$this->count_file_lines($file);
                }
            }
            closedir($handle);
        }
        return $count;
    }
}
