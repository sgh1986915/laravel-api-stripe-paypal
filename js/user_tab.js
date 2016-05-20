$(function(){
	$('.wa-tab-li-menu').click(function(){
		var rel = $(this).attr('rel');
		$('.wa-tab-li-menu').removeClass('active');
		$(this).addClass('active');
		$('#wa-xs-tab-active-menu').html($(this).text());
		$.ajax({
			url: BASE_URL+rel+'.php',
			success: function(res) {
				$('#wa-tab-content').html(res);
			}
		});
	});

	/* API create button click */
	$(document).on('click','#wa-btn-createapikey',function() {
		$.ajax({
			url: BASE_URL+'api/create.php',
			success: function(res) {
				$('#wa-tab-content').html(res);
			}
		});
	});

	/* Edit Profile button click */
	$(document).on('click','#wa-btn-edit-profile',function() {
		$.ajax({
			url: BASE_URL+'user/edit.php',
			success: function(res) {
				$('#wa-tab-content').html(res);
			}
		});
	});

	/* Showing previous state on refresh */
	var locationHash = window.location.href.split('#');
	locationHash = (typeof (locationHash[1]) != 'undefined') ? locationHash[1] : 'invoices' ;
	$('.wa-tab-li-menu').find('a[href="#'+locationHash+'"]').parent().click();

});