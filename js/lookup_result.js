$(function(){
	$(document).on('click','.wa-tab-li-menu-whoislookup',function(){
		var rel = $(this).attr('rel');
		$('.wa-tab-li-menu-whoislookup').removeClass('active');
		$(this).addClass('active');
		$('#wa-xs-tab-active-menu').html($(this).text());
		$('.wa-content-lookup').hide();
		$('#'+rel).show();
	});
});