<?php
    function quote($s){
    	return "'".mysql_real_escape_string($s)."'";
    }
?>