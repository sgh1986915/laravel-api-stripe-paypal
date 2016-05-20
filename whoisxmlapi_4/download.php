<?php
// this is a relative path from this file to the 
// directory where the download files are stored.



// next we'll build an array of commonly used content types
$mime_types=array();
$mime_types['ai']    ='application/postscript';
$mime_types['asx']   ='video/x-ms-asf';
$mime_types['au']    ='audio/basic';
$mime_types['avi']   ='video/x-msvideo';
$mime_types['bmp']   ='image/bmp';
$mime_types['css']   ='text/css';
$mime_types['csv']   ='application/vnd.ms-excel';
$mime_types['doc']   ='application/msword';
$mime_types['eps']   ='application/postscript';
$mime_types['exe']   ='application/octet-stream';
$mime_types['gif']   ='image/gif';
$mime_types['htm']   ='text/html';
$mime_types['html']  ='text/html';
$mime_types['ico']   ='image/x-icon';
$mime_types['jpe']   ='image/jpeg';
$mime_types['jpeg']  ='image/jpeg';
$mime_types['jpg']   ='image/jpeg';
$mime_types['js']    ='application/x-javascript';
$mime_types['mid']   ='audio/mid';
$mime_types['mov']   ='video/quicktime';
$mime_types['mp3']   ='audio/mpeg';
$mime_types['mpeg']  ='video/mpeg';
$mime_types['mpg']   ='video/mpeg';
$mime_types['pdf']   ='application/pdf';
$mime_types['pps']   ='application/vnd.ms-powerpoint';
$mime_types['ppt']   ='application/vnd.ms-powerpoint';
$mime_types['ps']    ='application/postscript';
$mime_types['pub']   ='application/x-mspublisher';
$mime_types['qt']    ='video/quicktime';
$mime_types['rtf']   ='application/rtf';
$mime_types['sql']   ='text/plain';
$mime_types['svg']   ='image/svg+xml';
$mime_types['swf']   ='application/x-shockwave-flash';
$mime_types['tif']   ='image/tiff';
$mime_types['tiff']  ='image/tiff';
$mime_types['txt']   ='text/plain';
$mime_types['wav']   ='audio/x-wav';
$mime_types['wmf']   ='application/x-msmetafile';
$mime_types['xls']   ='application/vnd.ms-excel';
$mime_types['zip']   ='application/zip';

// did we get a parameter telling us what file to download?
if(!$_GET['file']){
   // if not, create an error message
   $error='No file specified to download';
}else{
   // otherwise, get the file name and its extension
   $file=$_GET['file'];
   $ext=strtolower(substr(strrchr($file,'.'),1));
}
// did we get the extension and is it in our array of content types?
if($ext && array_key_exists($ext,$mime_types)){
   // if so, grab the content type
   $mime=$mime_types[$ext];
}else{
   // otherwise, create an error for that
   $error=$error?$error:"Invalid MIME type";
}

// if we didn't get any errors above
if(!$error){
   // if the file exists
   if(file_exists("$file")){
      // and the file is readable
      if(is_readable("$file")){
         // get the file size
         $size=filesize("$file");
         // open the file for reading
         if($fp=@fopen("$file",'r')){
            // send the headers
            header("Content-type: $mime");
            header("Content-Length: $size");
            header("Content-Disposition: attachment; filename=\"$file\"");
            // send the file content
            fpassthru($fp);
            // close the file
            fclose($fp);
            // and quit
            exit;
         }
      }else{ // file is not readable
         $error='Cannot read file';
      }
   }else{  // the file does not exist
      $error='File not found';
   }
}
if($error){
	echo $error;
}
?>