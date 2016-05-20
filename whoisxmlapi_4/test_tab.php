<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>tabs demo</title>
    <link rel="stylesheet" href="//code.jquery.com/ui/1.10.4/themes/smoothness/jquery-ui.css">
    <script src="//code.jquery.com/jquery-1.10.2.js"></script>
    <script src="//code.jquery.com/ui/1.10.4/jquery-ui.js"></script>
</head>
<body>

<div id="tabs">
    <ul>
        <li><a href="#fragment-1"><span>One</span></a></li>
        <li><a href="#fragment-2"><span>Two</span></a></li>
        <li><a href="#fragment-3"><span>Three</span></a></li>
    </ul>
    <div id="fragment-1">
        <p>First tab is active by default:</p>
        <pre><code>$( "#tabs" ).tabs(); </code></pre>
    </div>
    <div id="fragment-2">
        Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam erat volutpat.
        Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam erat volutpat.
    </div>
    <div id="fragment-3">
        Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam erat volutpat.
        Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam erat volutpat.
        Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam erat volutpat.
    </div>
</div>
<div id="tabs2" >


    <ul>
        <?php
        $sample_codes=array("php"=>"code/php/reverse_ip_api_sample_code.txt",
            "java"=>"code/java/ReverseIPAPISample.java");

       foreach($sample_codes as $sample_code=>$file){

           echo "<li><a href=\"#$sample_code\" class=\"ignore_jssm\" ><span>$sample_code</span></a></li>";

           echo "</li>";
       }?>
        </ul>
        <?php
       foreach($sample_codes as $sample_code=>$file){
           echo "<div id=\"$sample_code\">";
           echo "<p>xvcxcv</p>";
          echo "</div>";

       }
        ?>



</div>
<script>
$( "#tabs" ).tabs();
$( "#tabs2" ).tabs();
</script>

</body>
</html>