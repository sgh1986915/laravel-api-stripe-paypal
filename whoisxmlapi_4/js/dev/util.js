function get_file_name(url){
	return url.replace(/^.*\//, '');


}
function build_url(url, params){
  for(var key in params){
    url+="&"+key+"="+params[key];
  }
  return url;
}
function getParameterByName(url, name )
{
  name = name.replace(/[\[]/,"\\\[").replace(/[\]]/,"\\\]");
  var regexS = "[\\?&]"+name+"=([^&#]*)";
  var regex = new RegExp( regexS );
  var results = regex.exec( url);
  if( results == null )
    return "";
  else
    return decodeURIComponent(results[1].replace(/\+/g, " "));
}
function hasText(el){
	return el && isString(el.text()) && el.text().length > 0;
	
}
function get(element){
		if(isString(element))element = $('#'+element);
		return element;
}
function isString(x){
	return typeof(x) == 'string';
}
  function mask(ct){
  	if(isString(ct)) ct = $('#'+ct);
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
  	if(isString(ct)) ct = $('#'+ct);
  	var mask = ct.data('mask');
  	if(mask){
		mask.hide();
	}
  	ct.removeClass('loading_mask');
  }
  	function mod_url(url, mod){
		var index=url.lastIndexOf('.');
		if(index<=0){
			return url;
		}	
		url = url.substring(0,index) + mod + url.substring(index);
		return url;
	}
	function sect_open(url, target, options){
		
		if(options && options.url_mod){
			url = mod_url(url, options.url_mod);
		}
		
		$.ajax({
			url: url,
			beforeSend:function(request){
				
				if(options && options.mask){
					
					mask(get(target));//mod
				}
				return true;
			},
			success:function(data){
				
				get(target).html(data);
			},
			error: function(request, textStatus, errorThrown){
				alert(errorThrown);
			},
			complete:function(){
				if(options && options.mask)unmask(get(target));
			}
		});
	}
	