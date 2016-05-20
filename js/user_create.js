$(function(){

	$(document).on('click','#wa-update-profile',function(e) {
		e.preventDefault();
		$('.form-group').removeClass('has-error');
		$('.help-block').remove();

		var password = $.trim($('#wa-registration-password').val());
		var repassword = $.trim($('#wa-registration-re-password').val());

		var hasError = false;
		if(password == '') {
			hasError = true;
			addErrorMsg('wa-registration-password','Please enter current password.');
		}
		if(password != '' && password.length < 6) {
			hasError = true;
			addErrorMsg('wa-registration-password','The password must be at least 6 characters.');
		}
		if(repassword == '') {
			hasError = true;
			addErrorMsg('wa-registration-re-password','Please enter Re-Enter new password.');
		}
		if(repassword != password) {
			hasError = true;
			addErrorMsg('wa-registration-password','Password & Re-Enter password does not match.');
			addErrorMsg('wa-registration-re-password','Password & Re-Enter password does not match.');
		}
		if(hasError) {
			addErrorClass();
		} else {
			submitForm();
		}
	});

	// addErrorClass();

	function addErrorClass() {
		$('.wa-input-error').each(function(){
			$(this).parent().addClass('has-error');
		})
	}

	function addErrorMsg(elemId,errorMsg) {
		var parantElem = $('#'+elemId).parent().addClass('has-error');
		parantElem.find('.help-block').remove();
		parantElem.append('<div class="help-block wa-input-error">'+errorMsg+'</div>');
	}

});