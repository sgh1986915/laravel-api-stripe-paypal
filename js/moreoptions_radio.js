$(document).ready(function(){ 
	$(".wa-moreoptions").hide();
	$('#wa-checkbox-moreOption').click(function() {
		$(".wa-moreoptions").slideToggle();
	});
});