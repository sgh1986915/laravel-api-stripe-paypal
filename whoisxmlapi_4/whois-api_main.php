<?php 

require_once "users/users.conf";
require_once __DIR__ . "/httputil.php";
?>


<p class="rightTop"></p>



<h1>About Whois API - Hosted Whois Webservice</h1> 

<p class="rightTxt1">
Whois API Hosted Webservice returns well-parsed whois fields to your application in popular formats(XML&JSON) per http request.
	Leave all the hard work to us, you need not worry about the query limit & restrictions imposed by the whois registrars.
	<a id="newact2" href="newaccount.php"  class="ignore_jssm"  title="Register Your Free Developer Account">Register your free developer account</a> now to get your first <b>500 whois lookups</b> for free.
	See <a id="hosted_pricing" href="hosted_pricing.php"  class="ignore_jssm" title="Pricing Chart">pricing chart</a> for advanced offerings or <a href="<?php echo build_ssl_url('order_paypal.php#pay_as_you_go')?>"  id="order" class="ignore_jssm new_bold" title="Order Now"> Order now.</a> 
	
	
</p>

<div class="rightTxt2">

	<span>Possible usages of whois search:</span> 
	<ul>
		<li>
			Tracking domain registrations
		</li>
		<li>
			Checking domain name availability
		</li>

		
		<li>
			Advanced Whois web pages
		</li>		
		<li>
			Detecting (credit card)fraud
		</li>
		<li>
			Investigating spam, fraud, and other such online activities
		</li>
		<li>
			Researching Internet data
		</li>			
		<li>
			Locating users geographically
		</li>		
		
		<li>
			There is no end to the possibilities.
		</li>						
	</ul>	
	

</div>
<div class="rightTxt2">
	<span>Features:</span> 
	<ul>
		<li>
		   The whois service digs into the whois registry referral chains until the correct whois registrars for the most complete whois data are found. 
			
		</li>
		<li>
		Our smart mechanism neutralizes the query limits imposed by whois registrars.
			
		</li>
		
		<li>
			Robust online whois parser system parses an array of freeform whois data into well-structured fields (XML and JSON), the formats that can be read by your application.
			<ul>
				<li>
				The parse system is designed to be very fault tolerant. It would parse out the name, organization and other such details from a freeform human-filled contact address.  Try the whois lookup API demo above.

				</li>
				<li>
					Smart Parsing support for all whois registrars, constantly tested and improved
				</li>
				<li>
					Header and footer texts are stripped for a clean display of the whois data
				</li>
			</ul>
		</li>
		
		<li>
			Parser API has been developed to work over basic HTTP. This helps avoid firewall-related problems of accessing whois servers on port 43.
		</li>
		<li>
			Returns an indication about the availability of a domain
		</li>
		<li>
			The whois tool returns registry dates (created date, updated date, and expiry date) in the original format as well as generalized format.
		</li>
	</ul>

</div>
<!--<p class="rightPic"></p>-->
<p class="rightBottom"></p>
<br class="spacer" />
