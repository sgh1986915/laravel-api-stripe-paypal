$(function(){
	$(document).on('click','.wa-tab-li-menu-rw-lookup',function(){
		var rel = $(this).attr('rel');
		$('.wa-tab-li-menu-rw-lookup').removeClass('active');
		$(this).addClass('active');
		$('#wa-xs-tab-active-menu').html($(this).text());
		$('.wa-content-rw-lookup').hide();
		$('#'+rel).show();
	});
});