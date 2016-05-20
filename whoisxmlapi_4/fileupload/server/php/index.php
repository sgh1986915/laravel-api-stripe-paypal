<?php
/*
 * jQuery File Upload Plugin PHP Example 5.14
 * https://github.com/blueimp/jQuery-File-Upload
 *
 * Copyright 2010, Sebastian Tschan
 * https://blueimp.net
 *
 * Licensed under the MIT license:
 * http://www.opensource.org/licenses/MIT
 */

error_reporting(E_ALL | E_STRICT);
require('CustomUploadHandler.php');
@session_start();
if(isset($_REQUEST['userEmail'])){
    $_SESSION['email']=$_REQUEST['userEmail'];
}
if(isset($_REQUEST['maxLines'])){
    $_SESSION['maxLines']=$_REQUEST['maxLines'];
}
$params= array('user_dirs' => true,
    'upload_dir'=>'/home/tmp/',
    'download_via_php'=> 1,
    'accept_file_types' => '/.csv|.txt/i',
    'max_file_size'=>524288000
 );
if($_SESSION['maxLines'])$params['max_lines']=$_SESSION['maxLines'];
$upload_handler = new CustomUploadHandler(
   $params
);

$_SESSION['upload_handler']=$upload_handler;