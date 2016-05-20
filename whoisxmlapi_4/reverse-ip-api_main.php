<style>
    .reverseip_sample_code {
        float:left;
        width:98%;
        font-size: medium;
    }



</style>

<p class="rightTop"></p>
<h1>Reverse IP API Guide</h1>
<div class="rightTxt2">
    <h2>How to make a webservice call to Reverse IP API?</h2>
    <b>http://www.whoisxmlapi.com/api/reverseip/$input?outputFormat=XML&username=xxxxx&password=xxxxx</b>
    <br/>
    <br/>


    <table style="width:95%" cellspacing="1" cellpadding="7" border="0" align="center">
        <tr>

            <td align="left" style="background-color:#C0C0C0">Parameters</td>
            <td align="left" style="background-color:#C0C0C0">Values</td>

        </tr>
        <tr>
            <td align="left">input</td>
            <td align="left">either an ip address or a domain name.  If input is an ip address, it will return all the domains hosted on this ip address.
                If input is a domain name, it will find all connected domains by first finding all hosting ip addresses for the domain name and then retrieve all domains hosted all these ip addresses.
            </td>
        </tr>


        <tr>
            <td align="left">offset</td>
            <td align="left">starting index for the resulting domains.  optional.  Defaults to 0.  For example, if offset is 10, then it will only return domains starting at the 10th element. (0-based index)</td>
        </tr>
        <tr>
            <td align="left">limit</td>
            <td align="left">maximum number of domains to retrieve, starting from the offset.  optional.  Defaults to the total length of the resulting domains.  For example, if limit is 9 and offset is 0, then it will only return the first 9 elements in the resulting domains.</td>
        </tr>
        <tr>
            <td align="left">username</td>
            <td align="left">required, your account username</td>
        </tr>
        <tr>
            <td align="left">password</td>
            <td align="left">required, your account password</td>
        </tr>
    </table>
    <br/>


    You must <a href="bulk-reverse-ip-order.php" class="ignore_jssm"> purchase Reverse IP credits</a> to use Reverse IP API.


</div>
<br/>

<h3>  &nbsp;Sample Code</h3>
<div id="reverseip_sample_code_main" class="reverseip_sample_code" >

    <ul>
        <?php
        $sample_codes=array("php"=>"code/php/reverse_ip_api_sample_code.txt",
            "java"=>"code/java/ReverseIPAPISample.java");
        foreach($sample_codes as $sample_code=>$file){

            echo "<li><a href=\"#reverseip_sample_code_$sample_code\" class=\"ignore_jssm\"  >$sample_code</a></li>";

            echo "</li>";
        }
        ?>
    </ul>
    <?php
    foreach($sample_codes as $sample_code=>$file){
        $content=file(__DIR__."/". $file);
        echo "<div id=\"reverseip_sample_code_$sample_code\"><pre class=\"sample_code_$sample_code\">";
        foreach($content as $row){
            echo htmlentities($row);
        }
        echo "</pre></div>";

    }
    ?>




</div>

<script type="text/javascript">
    $(document).ready(function(){
        $('#reverseip_sample_code_main').tabs();
       $('.sample_code_php').snippet("php",{style:"whitengrey",  clipboard:"js/ZeroClipboard.swf"});
      $('.sample_code_java').snippet("java", {style:"ide-kdev",  clipboard:"js/ZeroClipboard.swf"});

    });

</script>


