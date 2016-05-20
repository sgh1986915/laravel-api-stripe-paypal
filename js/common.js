var PriceUtil = function() {};
PriceUtil.compute_items_total_price = function(items, field_name) {
	if (!field_name) field_name = 'price';
	var total = 0;
	for (var i = 0; i < items.length; i++) {
		total += parseFloat($(items[i]).attr(field_name));
	}
	return total;
};
PriceUtil.compute_custom_wdb_items_total_price = function(items) {
	var total = PriceUtil.compute_items_total_price(items, false);
	var discount = (items.length > 1 ? 0.2 : 0);
	return total * (1 - discount);
};
PriceUtil.compute_cctld_wdb_items_total_price = function(items, type) {
	var total = PriceUtil.compute_items_total_price(items, type + "_price");
	var discount = (items.length > 1 ? Math.min(0.5, 0.1 * items.length) : 0);
	return total * (1 - discount);
};


function getParameterByName(name) {
	name = name.replace(/[\[]/, "\\[").replace(/[\]]/, "\\]");
	var regex = new RegExp("[\\?&]" + name + "=([^&#]*)"),
	results = regex.exec(location.search);
	return results === null ? "" : decodeURIComponent(results[1].replace(/\+/g, " "));
}


/* Update URL without page refresh */
var lastHistoryState = {};
function changeUrlParam(param, value) {
	try {
		var currentURL = newURL = window.location.href+'&';
		var newState = (typeof window.history != 'undefined' && typeof window.history.state != 'undefined' && window.history.state != null && typeof window.history.state.page != 'undefined') ? window.history.state.page : {};
		var currURL = window.location.href;
		var currState = '';
		if(typeof param == 'object') {
			$.each(param,function(key,val){
				var change = new RegExp('('+key+')=(.*?)&', 'g');
				newURL = newURL.replace(change, '$1='+val+'&');
				if (getParameterByName(key) == '') {
					if (currURL.indexOf("?") !== -1 || currState != ''){
						currState += '&' + key + '=' + val;
					} else {
						currState += '?' + key + '=' + val;
					}
				}
				newState[key] = val;
			});
			currState =  newURL.slice(0, - 1) + currState;
		} else {
			var change = new RegExp('('+param+')=(.*?)&', 'g');
			newURL = currentURL.replace(change, '$1='+value+'&');
			newState[param] = value;
		}
		/*if(typeof window.history != 'undefined' && typeof window.history.state != 'undefined' && window.history.state != null && typeof window.history.state.page != 'undefined' && JSON.stringify(lastHistoryState) === JSON.stringify(newState)) {
			window.history.replaceState({page: newState}, '', currState);
		} else {
			window.history.pushState({page: newState}, '', currState);
		}*/
		window.history.replaceState({page: newState}, '', currState);
		lastHistoryState = newState;
	} catch (e) {
		console.log(e);
	}
}

/*$(window).bind('popstate', function(event) {
	if(typeof window.history.state != 'undefined' && window.history.state != null){
		whoisLookupFromURL();
	} else {
		window.location.reload();
	}
});*/

function whoisLookupFromURL(url) {
	var domainName = getParameterByName('domainName');
	var outputFormat = getParameterByName('outputFormat');
	if(domainName != '' && outputFormat != '') {
		$('#wa-search-whoislookup').val(domainName);
		$('#wa-lbl-XMl').click();
		if(outputFormat == 'json'){
			$('#wa-lbl-JSON').click();
		}
		$('#wa-search-icon-whoislookup').click();
	}
}

function reverseWhoisLookupFromURL(url) {
	if(window.location.href.indexOf('reverse-whois.php') > -1 || window.location.href.indexOf('reverse-whois-lookup.php') > -1) {
		var search_type = getParameterByName('search_type');
		var term1 = getParameterByName('term1');
		var term2 = getParameterByName('term2');
		var term3 = getParameterByName('term3');
		var term4 = getParameterByName('term4');
		var exclude_term1 = getParameterByName('exclude_term1');
		var exclude_term2 = getParameterByName('exclude_term2');
		var exclude_term3 = getParameterByName('exclude_term3');
		if(term1 != '') {
			$('#wa-search-reversewhoislookup').val(term1);
			if(search_type == 2) {
				$('#wa-checkbox-historic-records').prop('checked',true);
			} else {
				$('#wa-checkbox-historic-records').prop('checked',false);
			}
			$('#wa-input-type-include-1').val(term2);
			$('#wa-input-type-include-2').val(term3);
			$('#wa-input-type-include-3').val(term4);
			$('#wa-input-type-exclude-1').val(exclude_term1);
			$('#wa-input-type-exclude-2').val(exclude_term2);
			$('#wa-input-type-exclude-3').val(exclude_term3);

			$('#wa-search-icon-reverselookup').click();
		}
	}
}

function formatWhoisResponse(response,outputFormat){
	var rawText = '';
	var mainTab = "Whois Record";
	if(outputFormat == 'xml') {
		if (!jQuery.isXMLDoc(response)) {
			response = toXml(response);
		}
		LoadXMLDom('wa-tab-whois-record', response);
		if (mainTab == 'Domain Info') {
			var domainName = $(response).find("DomainInfo > domainName").text();
			var av = $(response).find("DomainInfo > domainAvailability").text();
			rawText = domainName + " is " + av;
		} else {
			var errorMsg = $(response).find("ErrorMessage > msg")
			if (hasText(errorMsg)) {
				rawText = errorMsg.text();
			} else {
				var rawTextTemp = $(response).find("WhoisRecord > rawText");
				if (hasText(rawTextTemp)) {
					rawText = rawTextTemp.text();
				} else {
					rawTextTemp = $(response).find("WhoisRecord > registryData > rawText");
					if (hasText(rawTextTemp)) {
						rawText = rawTextTemp.text();
					}
				}
			}
		}
	} else {
		// var JSONRes = JSON.stringify(JSON.parse(response), undefined, '\t');
		var JSONRes = JSON.stringify(JSON.parse(response), undefined, 3);
		JSONRes = JSONRes.replace(/\n/g, '<br/>');
		$('#wa-tab-whois-record').html("<pre>" + JSONRes + "</pre>");
		var json_res = undefined;
		try {
			json_res = JSON.parse(response);
		} catch (err) {}
		if (json_res !== undefined) {
			if(typeof(json_res.ErrorMessage) != 'undefined' && typeof(json_res.ErrorMessage.msg) != 'undefined') {
				rawText = json_res.ErrorMessage.msg;
			} else if (json_res.WhoisRecord) {
				if (json_res.WhoisRecord.rawText) rawText = json_res.WhoisRecord.rawText;
				else if (json_res.WhoisRecord.registryData) {
					rawText = json_res.WhoisRecord.registryData.rawText;
				}
			}
		}
	}
	rawText = rawText.replace(/NEW_LINE/g,'<br>');
	$('#wa-tab-raw-text').html('<pre>' + rawText + '</pre>');
	$('.wa-navbar-header-lookup').click(function() {
		$(".wa-navbar-collapse-lookup").collapse('toggle');
	});
}