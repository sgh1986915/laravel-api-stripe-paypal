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
	 $tlds = file_get_contents_merge(array(__DIR__ ."/all_supported_tlds.txt",
           				__DIR__."/supported_ngtlds.txt"));
?>
<html>
    
    <head>
        <meta http-equiv="content-type" content="text/html; charset=UTF-8">
        <META NAME="DESCRIPTION" CONTENT="Supported TLDs">

        <title>Supported TLDs</title>
      
     
    </head>
<body> 
<div style="width:100%">
<div style="margin:auto;width:800px">
              <h1>
                  Supported TLDs (gTLDs and ccTLDs)</h1>
              <p>
                  Whois API supports the following <?php echo count($tlds);?> TLDs 
              </p>
           <?php 
           			echo implode ('<br/>', $tlds);
           		?>
           
           	</div>
     
     
           	</div>
</body>
</html>
