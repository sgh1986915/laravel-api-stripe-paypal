var ajaxTs = {}; // unique timestamp for each request

$(function() {
	$('.wa-api-res-type').change(function() {
		$('.wa-home-radio-innerCircle').hide();
		$(this).parent().find('.wa-home-radio-innerCircle').show();
		//$('.wa-api-res-type:checked').val()
	});

	/* Carousel */
	$('.carousel').carousel({'interval':false,'wrap': true});

	/* Global Ajax Setup */
	$.ajaxSetup({
		beforeSend: function(jqXHR, settings) {
			$('#wa-loader,#wa-overlay').show();
			if(typeof ajaxTs[settings.url] == 'undefined') {
				ajaxTs[settings.url] = new Date().getTime();
			}
			var currLoader = $('.wa-loader').first().clone().addClass('wa-req-'+ajaxTs[this.url]).show();
			var currOverlay = $('.wa-overlay').first().clone().addClass('wa-req-'+ajaxTs[this.url]).show();
			if(!$('.wa-req-'+ajaxTs[this.url]).length){
				$('body').append(currLoader).append(currOverlay);
			}
		},
		complete: function() {
			$('.wa-req-'+ajaxTs[this.url]).remove();
		}
	});

	$(document).ajaxError(function(event, jqxhr, settings, exception) {
		$('.wa-req-'+ajaxTs[this.url]).remove();
		if ( jqxhr.status== 401 ) {
			window.location.reload();
		}
	});

	$(document).on('click','.wa-overlay',function(){
		$('.wa-overlay,.wa-loader').hide();
	});

	/* Hide session msg after 5 sec if not error */
	if(!$('#wa-session-message').hasClass('alert-danger')) {
		setTimeout(function() {
			$('#wa-session-message').fadeOut('slow',function() {
				$(this).find('.close').click();
			});
		},5000);
	}

	/* Prevent form submission of lookup's */
	$('#whoisform').submit(function(e){
		e.preventDefault();
	});
	/* ------ */

	/* Whois Lookup Search */
	$('#wa-search-whoislookup').keypress(function(e) {
		if(e.which == 13) {
			$('#wa-search-icon-whoislookup').click();
		}
	});

	/* Reverse Lookup Search */
	$('#wa-search-reversewhoislookup').keypress(function(e) {
		if(e.which == 13) {
			$('#wa-search-icon-reverselookup').click();
		}
	});

	/* Search Icon Click */
	$('#wa-search-icon-whoislookup').click(function(e) {
		e.preventDefault();
		var domainName = $.trim($('#wa-search-whoislookup').val());
		if(domainName == '') {
			showPopUp('Whois Lookup Error','Please enter a domain name or ip address.');
		} else {
			domainName = domainName.replace(/ /g,'');
			var outputFormat = $('.wa-api-res-type:checked').val();
			var url = BASE_URL+'whoislookup.php?domainName='+domainName+'&outputFormat='+outputFormat;
			doRequest({url:url,dataType:'HTML',success:lookupSuccess});

			/* Update URL */
			changeUrlParam({'domainName': domainName,'outputFormat': outputFormat});
		}
	});

	$('#wa-search-icon-reverselookup').click(function(e) {
		e.preventDefault();
		var searchVal = $.trim($('#wa-search-reversewhoislookup').val());
		if(searchVal == '') {
			showPopUp('Reverse Whois Lookup Error','Please enter a domain name or ip address.');
		} else {
			if($('#wa-checkbox-moreOption').is(":checked")) {
				$('#wa-checkbox-moreOption').click();
			}
			var search_type = ($('#wa-checkbox-historic-records').is(":checked")) ? 2 : 1;
			var include1 = $.trim($('#wa-input-type-include-1').val());
			var include2 = $.trim($('#wa-input-type-include-2').val());
			var include3 = $.trim($('#wa-input-type-include-3').val());
			var exclude1 = $.trim($('#wa-input-type-exclude-1').val());
			var exclude2 = $.trim($('#wa-input-type-exclude-2').val());
			var exclude3 = $.trim($('#wa-input-type-exclude-3').val());
			var url = BASE_URL+'reverse-whois-lookup.php?term1='+searchVal+'&search_type='+search_type+'&term2='+include1+'&term3='+include2+'&term4='+include3+'&exclude_term1='+exclude1+'&exclude_term2='+exclude2+'&exclude_term3='+exclude3+'';
			doRequest({url:url,dataType:'HTML',success:lookupSuccess});
			/* Update URL */
			changeUrlParam({'search_type': search_type,'term1': searchVal,'term2': include1,'term3': include2,'term4': include3,'exclude1': exclude1,'exclude2': exclude2,'exclude3': exclude3});
		}
	});

	/* Override default form submission */
	/*$("#whoisform").submit(function(e) {
		e.preventDefault();
		if($('#wa-search-icon-whoislookup').length) {
			$('#wa-search-icon-whoislookup').click();
		} else if($('#wa-search-icon-reverselookup').length) {
			$('#wa-search-icon-reverselookup').click();
		}
	});*/

	/* menu bar click fucntion of account management on mobile screen */
	$('.wa-navbar-header-management').click(function() {
		$(".wa-navbar-collpase-management").collapse('toggle');
	});

	/* menu bar click fucntion of lookup on mobile screen */
	$('.wa-navbar-header-lookup').click(function() {
		$(".wa-navbar-collapse-lookup").collapse('toggle');
	});

	/* newsletter subscription */
	 $('#wa-dontWorry').keypress(function(e) {
		if(e.which == 13) {
			$('#wa-btn-newaccnt-footer').click();
		}
	});

	$('#wa-btn-newaccnt-footer').click(function(){
		var newsletterElem = $('#wa-dontWorry');
		newsletterElem.parent().removeClass('has-error');
		newsletterElem.parent().find('.wa-input-error').text('');
		var email = $.trim(newsletterElem.val());
		if(email != '') {
			var url = BASE_URL+'subscribe.php';
			doRequest({url:url,type:'POST',dataType:'JSON',data:{email:email},success:newsletterSuccess});
		} else {
			newsletterElem.parent().addClass('has-error');
		}
	});

	$('#wa-btn-view-my-rw-report').click(function(){
		var url = BASE_URL+'reverse-whois-lookup.php';
		doRequest({url:url,dataType:'HTML',success:viewMyReportSuccess});
	});

	/* Popup events */
	$('#wa-popup-btn-close,#wa-popup-btn-ok').click(function(){
		$('#wa-popup,#wa-overlay').hide();
	});

	/* Load whois lookup from URL */
	whoisLookupFromURL();

	/* Load reverse whois lookup from URL */
	reverseWhoisLookupFromURL();

	/* Prevent closing of login dropdown on click of itself */
	$('.wa-dropdown-form').click(function(e){
		e.stopImmediatePropagation();
	});

	/* Prevent closing of my account dropdown on click of itself */
	$('.wa-dropdown-menu-client').click(function(e){
		e.stopImmediatePropagation();
	});

});

function lookupSuccess(res) {
	$('#wa-loader,#wa-overlay').hide();
	$('#wa-page-content').html(res);
	$('.wa-page-title-content-bg').hide();

	/* Get User state after lookup's  */
	getUserStateInfo();
}

/* Get User state */
function getUserStateInfo() {
	var url = BASE_URL+'whoisxmlapi_4/user_stats.php';
	doRequest({url:url,dataType:'TEXT',success:getUserStateSuccess});
}

function getUserStateSuccess(res) {
	$('#wa-user-stats').html($.trim(res));
}

function newsletterSuccess(res) {
	var newsletterElem = $('#wa-dontWorry');
	if(typeof (res.message) != 'undefined') {
		newsletterElem.parent().find('.wa-input-error').text(res.message).fadeIn();
	}
	if(typeof (res.status) == 'undefined' || !res.status) {
		newsletterElem.parent().addClass('has-error');
	} else {
		newsletterElem.parent().addClass('has-success');
		setTimeout(function(){
			newsletterElem.parent().find('.wa-input-error').fadeOut('slow',function(){
				$(this).text('');
				newsletterElem.parent().removeClass('has-success');
				newsletterElem.val('');
			});
		},5000);
	}
}

function viewMyReportSuccess(res){
	$('#wa-loader,#wa-overlay').hide();
	$('#wa-page-content').html(res);
	$('#rw_stats').hide();
	$('.wa-tab-li-menu-rw-lookup[rel="wa-my-report-rw-lookup"]').click();
	$('.wa-page-title-content-bg').hide();
}

function showPopUp(header,message) {
	$('#wa-header-popup').text(header);
	$('#wa-msg-popup').text(message);
	$('#wa-popup,#wa-overlay').show();
}