/*application specific*/
	
  function beforeWhoisSubmit(xmlHttpReq){
  	var target = $('#right');
  	mask(target);
	$('#whoisform :submit').attr('disabled',true);
  }
  function apply_history(){
  			
  			$("a:not(.ignore_jssm)", $(document)).jssm('click');
			$("form:not(.ignore_jssm)",$(document)).jssm('submit');
			
  }
	function whois_complete(){
		$('#whoisform :submit').attr('disabled',false);
	}
  function showWhoisResponse(response, ct, options){
	options = options||{};
	
	var mainTab = options.main_tab||"Whois Record";
	var secTab = options.sec_tab||"Raw Text";
  	if(isString(ct)) ct = $('#'+ct);
  	unmask(ct);
  	if (!$('#whois_res').length) {
		ct.empty();
		var whois_res = "<div id=\"whois_res\">\
   <ul>\
      <li><a href=\"#whois-struct-tab\">"+mainTab+"</a></li>\
      <li><a href=\"#whois-raw-tab\">"+secTab+"</a></li>\
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
		var rawText = '';
		if(mainTab=='Domain Info'){
			var domainName = $(response).find("DomainInfo > domainName").text();
			var av= $(response).find("DomainInfo > domainAvailability").text();
			rawText = domainName +" is "+av;
		}
		else{
			rawText = $(response).find("WhoisRecord > rawText");
		
			if (hasText(rawText)) {
			
				rawText = rawText.text();
			
			}
			else {
				rawText = $(response).find("WhoisRecord > registryData > rawText");
				if (hasText(rawText)) {
					rawText = rawText.text();
				}
			}
		}
		whois_raw_tab.html('<pre>'+rawText+'</pre>');
		
	}
	else{
		whois_struct_tab.html("<pre>" + response + "</pre>");
		var json_res=undefined;
		try{
			json_res = JSON.parse(response);	
		}catch(err){
			
		}
		var rawText='';
		if(json_res !== undefined){
			if(json_res.WhoisRecord){
				if(json_res.WhoisRecord.rawText) rawText = json_res.WhoisRecord.rawText;
				else if(json_res.WhoisRecord.registryData){
					rawText = json_res.WhoisRecord.registryData.rawText;
				}
			}
			 
		}
		whois_raw_tab.html('<pre>'+rawText+'</pre>');
	}
	if(options.target =='raw'){
		hili(whois_raw_tab);
	}
	else if (options.target =='structured'){
		hili(whois_struct_tab);
	}
  }
  function hili(tab){
  	
  	$('#whois_res').tabs('select', tab.attr('id'));
	//$('#whois_res').animate({border:"3px solid blue"},200);
  }	
/******************************************/


jssm.settings.blankurl = 'blank.html'; //wrong path will cause IE <8 to fail!!!
jssm.settings.basetitle = 'JSSM Test';
jssm.settings.titleseparator = ' : ';
jssm.settings.target_holder  ={
		'newaccount.php': 'right',
		'forgotpassword.php': 'right',
		'hosted_pricing.php': 'right',
		'order.php': 'right',
		'index.php': "body",
		'whois-api-contact.php': "right",
		'myaccount.php': "right",
		'ceo-message.php': "right",
		'whois-api-doc.php': "right",
		'whois-api-support.php': "right",
		'whois-api.php':'right',
		'whois-api-software.php':'right',
		'whois-api-client.php':'right',
		'bulk-whois-lookup.php':'right',
		'whois-database-download.php':'right',
		'registrar-whois-services.php':'right',
		'domain-availability-api.php' : 'right',
		//reverse whois
		'reverse-whois-intro.php':'right',
		'reverse-whois.php': 'body',
		'reverse-whois-search.php': 'right',
		'reverse-whois-lookup.php': 'right',
		'reverse-whois-api.php': 'right',
		'reverse-whois-lookup-v2.php': 'right',
		'reverse-whois-v2.php': 'body',
		'reverse-whois-api-v2.php': 'right',
		'reverse-whois-lookup-v1.php': 'right',
		'reverse-whois-v1.php': 'body',
		'reverse-whois-api-v1.php': 'right'
	
};

jssm.settings.returnto  ={
		'newaccount.php': 'right',
		'forgotpassword.php': 'right',
		'hosted_pricing.php': 'right',
		'order.php': 'right',
		'index.php': "body",
		'whois-api-contact.php': "right",
		'ceo-message.php': "right",
		'whois-api-doc.php': "right",
		'whois-api-support.php': "right",
		'whois-api.php':'right',
		'whois-api-software.php':'right',
		'whois-api-client.php':'right',
		'whois-database-download.php':'right',
		'registrar-whois-services.php':'right',
		'domain-availability-api.php' : 'right',
		
		//reverse whois
		'reverse-whois-intro.php':'right',
		'reverse-whois.php': 'body',
		'reverse-whois-search.php': 'right',
		'reverse-whois-lookup.php': 'right'
};
/*
jssm.settings.actions={
		'reverse-whois-lookup.php' :function(hash){
			var res = split_hash(hash,';');
			var path = res?res['path']:hash;
			var targetOptions = {};
			console.log("param data is ", res.paramdata);
			console.log('hash is ', hash);
			if(res.paramdata){
				for(var i=1;i<=4;i++){
					if(res.paramdata['term'+i]){
						console.log('set', $('#whoisform :text[name=term'+i+']'), res.paramdata['term'+i]);
						$('#whoisform :text[name=term'+i+']').attr('value',res.paramdata['term'+i]);
					}
					if(res.paramdata['exclude_term'+i])$('#whoisform :text[name=exclude_term'+i+']').attr('value',res.paramdata['exclude_term'+i]);
				}
			}
		}
}
*/

jssm.settings.load_action={

	'whoisserver/WhoisService':function(hash){
		var res = split_hash(hash,';');
		var path = res?res['path']:hash;
		var targetOptions = {};
		if(res.paramdata){
			//console.log(res.paramdata);
			if(res.paramdata.domainName)$('#whoisform :text[name=domainName]').attr('value',res.paramdata.domainName);
			if(res.paramdata.outputFormat == 'xml') $('#f_xml').attr('checked',true);
			if(res.paramdata.outputFormat == 'json') $('#f_json').attr('checked', true);
			targetOptions={target:res.paramdata.target};
			if(res.paramdata.cmd=='GET_DN_AVAILABILITY'){
				targetOptions['main_tab'] ="Domain Info";
				
			}
		}
		
		//path = ltrim(path,'/');
		//var full_path = mod_url(path,'_main');
		//if(ltrim(jssm.getCurrentPath(),'/')!='')full_path = '/'+jssm.getCurrentPath() + '/'+path;
		var full_path = '/'+path;
		var options = {
			url: full_path,
			beforeSubmit: beforeWhoisSubmit, 
			success: function(response, status){
				whois_complete();
				
				showWhoisResponse(response, 'right',targetOptions);
				sect_open('user_stats.php','user_stats');
				//apply_history();
				
			},
			error: function(req, textStatus, errorThrown) {
				whois_complete();
				var err=(req.responseText?req.responseText:req.responseXML);
				if(!err)err=(errorThrown?errorThrown:textStatus);
		
				showWhoisResponse(err, 'right');
				//apply_history();
			}
		};
		$('#whoisform').ajaxSubmit(options);
	}
	
};
/*
function loadReverseWhoisPage(hash){
	var res = split_hash(hash,';');
	var path = res?res['path']:hash;
	var targetOptions = {};
	if(res.paramdata){			
		if(res.paramdata.term)$('#whoisform :text[name=term]').attr('value',res.paramdata.term);

	}
	if(jQuery("#revWhoisGrid").length!==0){//if the grid already exists
		var searchTerm = $('#whoisform').find('[name=term]')[0].value;
		
	      jQuery("#revWhoisGrid").jqGrid('setGridParam',
	        {postData:{term:searchTerm}}
	      ).trigger('reloadGrid');
	      return;
	}
	
	path = ltrim(path,'/');
	var full_path = mod_url(path,'_main');
	if(ltrim(jssm.getCurrentPath(),'/')!='')full_path = '/'+jssm.getCurrentPath() + '/'+path;
	console.log('full path is '+full_path);
	var options = {
		url: full_path,
		beforeSubmit: beforeWhoisSubmit, 
		success: function(response, status){	
			whois_complete();
			var searchTerm = $('#whoisform').find('[name=term]')[0].value;
			
		      jQuery("#revWhoisGrid").jqGrid('setGridParam',
		        {postData:{term:searchTerm}}
		      ).trigger('reloadGrid');
		 
			
		},
		error: function(req, textStatus, errorThrown) {
			whois_complete();
			var err=(req.responseText?req.responseText:req.responseXML);
			if(!err)err=(errorThrown?errorThrown:textStatus);
	
			
			//apply_history();
		}
	};

   
      
	$('#whoisform').ajaxSubmit(options);
}
*/
function split_hash(hash, separator){
	var index = hash.indexOf("?");
	var paramdata={};
	if(index >=0){
		var param = hash.substring(index+1);
		var end  = hash.indexOf(';');
		if(end > index){
			param = hash.substring(index,end);
		}
		param = param.split("&");
		for(var i =0; i<param.length; i++){
			var keyval= param[i].split("=");
			if(!keyval)continue;
			if(keyval.length == 2){
				paramdata[keyval[0]] = $.URLDecode(keyval[1]);
				
			}
		}
	}
	var ar={
		path: index>=0? hash.substring(0,index) : hash,
		paramdata: paramdata
	};
	return ar;
	
	
}


jssm.functions.pageload = function (hash) {
	if (hash) {	
		jssm.functions.load(hash);
	} else {
		//$('#body').fadeIn(500);
		var DEFAULT_HASH = 'index.php;body';//get_file_name(window.location.pathname);//'index.php;body';
		//console.log('before '+window.location.pathname+", after:"+DEFAULT_HASH);
		jssm.functions.load(DEFAULT_HASH);
		
	}
}

jssm.functions.beforeload = function (hash) {
	var res = split_hash(hash, ';');
	var target = res['path'] ? jssm.settings.target_holder[res['path']] : false;
	if (target) {
		mask(get(target));
	}
}
function ltrim(s){
	while(s.indexOf('/')==0){
		s=s.substring(1);
	}
	return s;
}

function combine_path(paths){
	var s='';
	if(paths.length>0)s=paths[0];
	for(var i=0;i<paths.length-1;i++){
		
		if(/\/$/.test(paths[i]) || paths[i+1].indexOf('/')===0) s +=paths[i+1];
		else s+='/'+paths[i+1];
			
	}
	return s;
}
jssm.functions.load = function (hash) {
	var res = split_hash(hash,';');
	var path = res?res['path']:hash;
	path=ltrim(path,'/');

	if(jssm.settings.load_action[path]){
		
		jssm.settings.load_action[path].apply(this,[hash]);
		return;
	}

	//var holder = res?res['holder']:false;	
	var holder = jssm.settings.target_holder[path]?jssm.settings.target_holder[path]:false;

	if(!holder || holder == undefined)return;
	
	
	//use .ajax
	//console.log('ajax to '+('/'+jssm.getCurrentPath()+mod_url(path,'_main')));
	var full_path = mod_url(path,'_main');
	var original_path = jssm.getCurrentPath();
	if(ltrim(original_path,'/')!=''){
		full_path = combine_path(new Array('/',jssm.getCurrentPath() , full_path),'/');
	}
	
	$.get(full_path, res['paramdata'], function(response) {

	
		/* Process the response, light edition. */
		
		

		/* Set page title based upon the response. */
		var regextitle = new RegExp('<title>([^<]*)<\/title>');
		var matches = regextitle.exec(response);
		if(matches)jssm.setTitle(jssm.buildTitle(matches[1]));

		/* Get the new content. */
		var inside = response;//response.substring(response.indexOf('<body>') + 6, response.indexOf('</body>'));
		
		/* Fade in time. */
	
		get(holder).queue(function () { 
			//$(this).html($(inside).filter('div.wrapper').html());
			
			//$(this).html($(inside).html());
			$(this).html(inside);
			
			$("a:not(.ignore_jssm)", $(this)).jssm('click');
			$("form:not(.ignore_jssm)", $(this)).jssm('submit');
		
			$(this).dequeue();
		
			if(jssm.settings.returnto[path]){
				
				var encoded_hash = hash;//encodeURIComponent(hash);
			
				$('#login_returnto').val(encoded_hash);
				$('#logout_link').attr('href', 'logout.php?returnto='+encoded_hash);
			}
			
		});
//
//		/* Add CSS to the page based upon the response. */
//		$('link', response).each(function(i) {
//			var css = document.createElement("link");
//			css.setAttribute('rel', 'stylesheet');
//			css.setAttribute('type', 'text/css');
//			css.setAttribute('href', $(this).attr('href'));
//			document.getElementsByTagName('head')[0].appendChild(css);
//		});
//
//		/* Add page content based upon the response. */
//		$('#wrapper').html($('body', response).text()).fadeIn(500);
//
//		/* Add scripts to the page based upon the response. */
//		$('script', response).each(function(i) {
//			$.globalEval($(this).text());
//		});

	});

}

jssm.functions.afterload = function (hash) {
	var res = split_hash(hash,';');
	var target = res['path'] ? jssm.settings.target_holder[res['path']] : false;
	if(target)unmask(get(target));
}

jssm.functions.beforeunload = function (hash) {

}

jssm.functions.unload = function (hash) {
	$('.wrapper').queue(function () { 
		$(this).fadeOut(500);
		$(this).dequeue();
	});
}

jssm.functions.afterunload = function (hash) {

}