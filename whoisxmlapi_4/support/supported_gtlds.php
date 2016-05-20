<?php
	function file_get_contents_merge($files){
		$res=array();
			
		foreach($files as $file){
			
			$lines=file($file);
			foreach ($lines as $line) {
				$line=trim($line);
				if($line)$res[]=$line;
			}
				
		}
		return array_unique($res);
		
		
	}
	 $tlds = file_get_contents_merge(array(__DIR__ ."/supported_cgtlds.txt",
           				__DIR__."/supported_ngtlds.txt"));
?>
<html>
    
    <head>
        <meta http-equiv="content-type" content="text/html; charset=UTF-8">
        <META NAME="DESCRIPTION" CONTENT="Supported gTLDs">

        <title>Supported gTLDs</title>
      
     
    </head>
<body> 
<div style="width:100%">
<div style="margin:auto;width:800px">
              <h1>
                  Supported gTLDs </h1>
              <p>
                  Whois API supports the following <?php echo count($tlds);?> gTLDs 
              </p>
           <?php 
           			echo implode ('<br/>', $tlds);
           		?>
           
           	</div>
     
     
           	</div>
</body>
</html>
