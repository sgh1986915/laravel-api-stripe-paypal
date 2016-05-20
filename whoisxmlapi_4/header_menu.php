    <ul> 
      <li><a href="<?php echo $app_root?>/" id="top_home" class="ignore_jssm">Home</a></li>
    	
      
 <li><a href="<?php echo $app_root?>/whois-api-doc.php" id="top_doc" rel="history" class="ignore_jssm"> API Products</a></li>
<li><a href="<?php echo $app_root?>/reverse-whois.php" id="top_reverse_whois" class="ignore_jssm" title="Search by registrant name, email, address, phone number, or any piece of text in a whois record">Reverse Whois</a></li>
      
<li><a href="<?php echo build_ssl_url("$app_root/newly-registered-domains.php");?>" id="top_domain_name_data" class="ignore_jssm">Newly Registered Domains</a></li>
<li><a href="<?php echo $app_root?>/reverse-ip.php" id="top_reverse_ip" class="ignore_jssm" title="Find domains hosted on a given IP address.">Reverse IP</a></li>
 
 
<li><a target ="_blank" href="<?php echo $app_root=='/whoisxmlapi'? "$app_root/whois-lookup" : "http://whois.whoisxmlapi.com"?>" id="top_domain_name_data" class="ignore_jssm">Whois Lookup</a></li>


	 
      <!--<li><a href="<?php echo $app_root?>/whois-api-support.php" id="top_support" class="ignore_jssm">Support</a></li>-->
      <li><a href="<?php echo $app_root?>/whois-api-contact.php" id="top_contactus" rel="history" class="ignore_jssm">Contact Us</a></li>
      <?php if(isset($_SESSION['myuser'])){?>
	     <li><a href="<?php echo $app_root?>/myaccount.php" id="top_myaccount" rel="history" class="ignore_jssm">My Account</a></li>
	     <li class="last"><a id="logout_link"  href="<?php echo $app_root?>/logout.php?returnto=<?php echo $_SERVER['REQUEST_URI'];?>" class="ignore_jssm"> Logout</a></li>
      <?php 
        //print_r($_SERVER);
       }
      ?>
    </ul>