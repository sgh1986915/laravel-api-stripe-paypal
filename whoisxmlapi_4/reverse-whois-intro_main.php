<?php require_once "users/users.conf";?>
<p class="rightTop"></p>
<h1>Reverse WHOIS Search</h1>
 
<p class="rightTxt1">
Reverse Whois (Registrant Search) lets you perform the most comprehensive wild card search on all whois records.
</p>

<p class="rightTxt1">
Reverse whois Lookup allows you to find every domain name ever owned by a specific company or person. You just need to enter one or more unique identifiers such as the person's or the company's name, phone number, or email address, and you can find all the domain names they ever owned.  

<br/>Type in your term above to make a quick search.</p>


<div class="rightTxt2">
	<span>Key Features</span> 
	
  <ul>
    <li class="hili">
     Reverse whois Lookup allows searching by a unique identifier such as registrant name, email, address, etc. It can be any piece of text in a whois record.
    </li>
    <li>
      Advanced search allows to include and exclude multiple search terms
    </li>
  
    <li>
      Search for current as well as historic whois records
    </li>
    <li>
      Access results in online form and other formats (CSV, PDF)
    </li>
    <li>
      Resulting whois records highlight the search terms
    </li>
  </ul>

</div>
<div id="rw_video" style="margin:10px">
<!--
<iframe alt="Reverse Whois Demo Video" width="100%" height="360" src="//www.youtube.com/embed/RBEnA3mk0q4?feature=player_detailpage" frameborder="0" allowfullscreen></iframe>
-->
</div>

<div>

 <a href="images/sample_whois.gif" rel="rw_example1" class="ignore_jssm"> <img src="images/sample_whois.gif"/></a>
  <a href="images/reverse_whois_example1.gif" rel="rw_example1" class="ignore_jssm" style="display:hidden"></a>
 	 <a href="images/reverse_whois_example2.gif" rel="rw_example1" class="ignore_jssm" style="display:hidden"></a>
</div>
<br/>
<div class="rightTxt2">
	<span>When Reverse whois Lookup is helpful:</span> 
	<ul>
		<li>
			t is used by brand agents for protection of intellectual property. They can find instances of potential trademark infringements before costs of enforcement grow.	</li>
		<li>
			A useful tool for domain investors, it helps in learning about the competition, identifying domains with investment potential, and locating potential buyers.		</li>
		
		<li>
			Webmasters use it for identifying new business and partnership opportunities, and locating potential buyers.
		</li>

	</ul>

</div>
<!--<p class="rightPic"></p>-->
<p class="rightBottom"></p>
<br class="spacer" />
<script type="text/javascript">
      var tag = document.createElement('script');

      tag.src = "https://www.youtube.com/iframe_api";
      var firstScriptTag = document.getElementsByTagName('script')[0];
      firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);

      // 3. This function creates an <iframe> (and YouTube player)
      //    after the API code downloads.
      var player;
      function onYouTubeIframeAPIReady() {
        player = new YT.Player('rw_video', {
          height: '360',
          width: '95%',
          playerVars: {rel: 0},
          videoId: 'RBEnA3mk0q4'/*,
          events: {
            'onReady': onPlayerReady,
            'onStateChange': onPlayerStateChange
          }*/
        });
      }
      
</script>

<script type="text/javascript">
$(document).ready(function(){
	
			$("a[rel=rw_example1]").colorbox();
});

</script>