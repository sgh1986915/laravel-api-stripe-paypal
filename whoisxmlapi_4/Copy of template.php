<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Whois XML API</title>

<link href="themes/base/ui.all.css" rel="stylesheet" type="text/css" />
<link href="themes/base/ui.tabs.css" rel="stylesheet" type="text/css" />

<link href="style.css" rel="stylesheet" type="text/css" />
<link href="XMLDisplay.css" rel="stylesheet" type="text/css" />
 

  <script src="http://www.google.com/jsapi"></script>  
  <script type="text/javascript">  
   
     // Load jQuery  
     google.load("jquery", "1.3.2");  
   
     google.setOnLoadCallback(function() {  
         init();
     });  
          
 </script> 
  <script type="text/javascript" src="js/ui.core.js"></script>
   <script type="text/javascript" src="js/ui.tabs.js"></script>
   <script type="text/javascript" src="js/jquery.form.js"></script>
      <script type="text/javascript" src="js/jquery.highlight.js"></script>
   <script type="text/javascript" src="js/XMLDisplay.js"></script>
   <script type="text/javascript" src="js/json.js"></script>
  <script type="text/javascript">
  	function ajax_left(){
		$(".ajax_left").each(function(i){
			ajax_link($(this), $('#right'));
		});
	}
 	function init(){
		ajax_top();	  
  	 var options = { 
       // target:        '#test',   // target element(s) to be updated with server response 
        beforeSubmit:  beforeSubmit,  // pre-submit callback 
        success:       success, // post-submit callback 
 		error:  		fail
        // other available options: 
        //url:       url         // override for form's 'action' attribute 
        //type:      type        // 'get' or 'post', override for form's 'method' attribute 
        //dataType:  null        // 'xml', 'script', or 'json' (expected server response type) 
        //clearForm: true        // clear all form fields after successful submit 
        //resetForm: true        // reset the form after successful submit 
 
        // $.ajax options can be used here too, for example: 
        //timeout:   3000 
    }; 
	
   	$('#whoisform').submit(function() { 
		
        // inside event callbacks 'this' is the DOM element so we first 
        // wrap it in a jQuery object and then invoke ajaxSubmit 
        $(this).ajaxSubmit(options); 
		
        // !!! Important !!! 
        // always return false to prevent standard browser submit and page navigation 
        return false; 
    });
	
	 
}
	function ajax_top(){
		ajax_link($("#top_home"), $("#body"));

	}
	function ajax_link(link, target){
		if(link.attr('href')){
			
			link.bind('click',function(evt){
			
				var href=$(this).attr('href');
			
				sect_open(href, target,{
					mask:true,
					url_mod: '_main'
				});
				return false;
			});
		}
	}
	function sect_open(url, target, options){
		
		if(options && options.url_mod){
			url = mod_url(url, options.url_mod);
		}
		
		$.ajax({
			url: url,
			beforeSend:function(request){
				
				if(options && options.mask){
					mask(target);
				}
				return true;
			},
			success:function(data){
				target.html(data);
			},
			error: function(request, textStatus, errorThrown){
				alert(errorThrown);
			},
			complete:function(){
				if(options && options.mask)unmask(target);
			}
		});
	}
	function mod_url(url, mod){
		var index=url.lastIndexOf('.');
		if(index<=0){
			return url;
		}	
		url = url.substring(0,index) + mod + url.substring(index);
		return url;
	}
  function beforeSubmit(formData, form, options){
  	var target = $('#right');
  	mask(target);
	$('#'+form.attr('id')+' :submit').attr('disabled',true);
  }
  function mask(ct){
  	ct.addClass('loading_mask');
	
  	var m = ct.data('mask');
	if (!m) {
		m = $('<div></div>');
		ct.data('mask', m);
	}
	if(ct.index(m) < 0 )m.prependTo(ct);
	m.css({
			position: 'absolute',
			top: ct.css('top'),
			left: ct.css('left'),
			width: ct.css('width'),
			height: ct.css('height')
	}).addClass('ajax_buzy');
	m.show();
  }
  function unmask(ct){
  	var mask = ct.data('mask');
  	if(mask){
		mask.hide();
	}
  	ct.removeClass('loading_mask');
  }
  function showResponse(response, ct){
  	unmask(ct);
  	if (!$('#whois_res').length) {
		ct.empty();
		var whois_res = "<div id=\"whois_res\">\
   <ul>\
      <li><a href=\"#whois-struct-tab\">Whois Record</a></li>\
      <li><a href=\"#whois-raw-tab\">Raw text</a></li>\
   </ul>\
   <div id=\"whois-struct-tab\">\
      <p></p>\
   </div>\
   <div id=\"whois-raw-tab\">\
      <p></p>\
   </div>\
</div>";
		
		
		$(whois_res).appendTo(ct);
		$('#whois_res').tabs();
	}		
	var whois_struct_tab = $('#whois-struct-tab');
	var whois_raw_tab = $('#whois-raw-tab');
	if (jQuery.isXMLDoc(response)) {
		LoadXMLDom('whois-struct-tab', response);
		
		var rawText = $(response).find("WhoisRecord > rawText");
		
		if(rawText)rawText = rawText.text();
		whois_raw_tab.html('<div>'+rawText+'</div>');
		
	}
	else{
		whois_struct_tab.html("<div>" + response + "</div>");
		try{
			json_res = JSON.parse(response);	
		}catch(err){
			//alert(err);
		}
		if(json_res && json_res.WhoisRecord)whois_raw_tab.html('<div>'+json_res.WhoisRecord.rawText+'</div>');
	}
	
	hili(whois_struct_tab);
  }
  function hili(tab){
  	
  	$('#whois_res').tabs('select', tab.attr('id'));
	//$('#whois_res').animate({border:"3px solid blue"},200);
  }
	function success(response, statusText){
		
		complete();
		showResponse(response, $('#right'));
		
		
	}
	function fail(req, textStatus, errorThrown) {
		complete();
		var err=(req.responseText?req.responseText:req.responseXML);
		if(!err)err=(errorThrown?errorThrown:textStatus);
		
		showResponse(err, $('#right'));
	}
	function complete(){
		$('#whoisform :submit').attr('disabled',false);
	}
	
</script>
<?php 
	$test = 10;
?>
</head>
<body>
<!--header start -->
<div id="header">
<div id="header_nav">
<ul>
<li><a href="index.php" id="top_home">Home</a></li>
<li><a href="#">Documentation</a></li>
<li><a href="#">About Us</a></li>
<li><a href="#">Services</a></li>
<li><a href="#">Support</a></li>

<li><a href="#">Why Choose Us</a></li>
<li><a href="#">News</a></li>
<li><a href="#">Testimonials</a></li>
<li class="last"><a href="#">Contact Us</a></li>
</ul>
</div>
<a href="index.php"><img src="images/logo.png" alt="whois API"  border="0" class="logo" /></a>
<h1>a unified, consistent, machine-readable Whois Lookup system</h1>
<h2></h2>

<img src="images/top_icon.png" alt="Whois API"  class="icon" />
<p class="topText">
	<span class="smallTxt">
		
	</span>

	

    	<form id="whoisform" name="whoisform" action="/whoisserver/WhoisService">
        	<a name="wi">
				<strong>Whois Lookup:</strong>
			</a>
			<!--<input type="hidden" value="FILE" name="srcMode"/>-->
			<input type="text" size="40" name="domainName" value=""/>
			<input type="submit" size="40" value="Search"/>
			<input type="radio" name="outputFormat" value="xml" id="f_xml" checked/><label for="xml">XML</label>
			<input type="radio" name="outputFormat" value="json" id="f_json"/><label for="json">JSON</label>
 		</form>
   
   

	

	
</p>






<a href="#" class="readMore"></a>
</div>
<!--header end -->
<!--body start -->
<div id="body">
	<?php include "index_main.php"?>
	
</div>
<!--body end -->
<!--bodyBottom start -->
<div id="bodyBottom">
<!--news start -->
<div id="news">
<h2>Latest News</h2>
<h3>On 23rd May 2007</h3>
<p><span>euismod justo, eu pharetra elit arcu sit amet</span>quam. um ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Nam adipiscing</p>
<p><span>euismod justo, eu pharetra elit arcu sit amet</span>Donec nec urna, praesent risus phasellustincidunt eu volutpat</p>
<br class="spacer" />
</div>
<!--news end -->
<!--services start -->
<div id="service">
<h2>New Services Overview</h2>
<h3>Finantial Services</h3>
<ul>
<li><a href="#">Lorem ipsum dolor sit amet, consectetuer adipiscing elit.</a></li>
<li><a href="#">Fusce bibendum ultricies lorem.</a></li>
<li><a href="#">Nulla facilisis odio vitae neque.</a></li>
<li><a href="#">Etiam sit amet purus a eros viverra adipiscing.</a></li>
<li><a href="#">Nunc dignissim eleifend turpis</a></li>
</ul>
<br class="spacer" />
</div>
<!--services end -->
<!--member start -->
<!--member end -->

<br class="spacer" />
</div>
<!--bodyBottom end -->
<!--footer start -->
<div id="test">

</div>
<div id="footer">
<ul>
	<li><a href="index.php" id="footer_home">Home</a>|</li>
	<li><a href="#">About Us</a>|</li>
	<li><a href="#">Services</a>|</li>
	<li><a href="#">Support</a>|</li>
	<li><a href="#">Communication</a>|</li>
	<li><a href="#">Why Choose Us</a>|</li>
	<li><a href="#">News</a>|</li>
	<li><a href="#">Testimonials</a>|</li>
	<li><a href="#">Contact Us</a></li>
  </ul>
   <p class="copyright">&copy;Jet 30. All rights reserved.</p>
   <a href="#" class="subscribe">Subscribe</a>
   <a href="http://validator.w3.org/check?uri=referer" target="_blank" class="xht"></a>
	<a href="http://jigsaw.w3.org/css-validator/check/referer" target="_blank" class="cs"></a>
	<a href="index.html"><img src="images/bottom_logo.gif" alt="Jet 30" title="Jet 30" width="84" height="26" border="0" /></a>
	<p class="design">Designed By : <a href="http://www.templateworld.com">Template World</a></p></div>
<!--footer end -->



</body>
</html>
