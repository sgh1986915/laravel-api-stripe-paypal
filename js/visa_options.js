$(function(){
	$('input[name="pay_choice"]').change(function (event) {
		$('.wa-paymentradioshow').hide();
		$(this).parent().siblings().show();
		$('.payment-errors').hide();
	});
});