$(function(){

	$(document).on('click','#wa-update-profile',function(e) {
		e.preventDefault();
		$('.form-group').removeClass('has-error');
		$('.help-block').remove();

		var currPass = $.trim($('#wa-update-profile-password').val());
		var newPass = $.trim($('#wa-update-profile-newpassword').val());
		var rePass = $.trim($('#wa-update-profile-re-pass').val());

		if(currPass == '' && newPass == '' && rePass == '') {
			submitForm();
		} else {
			var hasError = false;
			if(currPass == '') {
				hasError = true;
				addErrorMsg('wa-update-profile-password','Please enter current password.');
			}
			if(currPass != '' && currPass.length < 6) {
				hasError = true;
				addErrorMsg('wa-update-profile-password','The password must be at least 6 characters.');
			}
			if(newPass == '') {
				hasError = true;
				addErrorMsg('wa-update-profile-newpassword','Please enter new password. ');
			}
			if(newPass != '' && newPass.length < 6) {
				hasError = true;
				addErrorMsg('wa-update-profile-newpassword','The password must be at least 6 characters.');
			}
			if(rePass == '') {
				hasError = true;
				addErrorMsg('wa-update-profile-re-pass','Please enter Re-Enter new password.');
			}
			if(rePass != newPass) {
				hasError = true;
				addErrorMsg('wa-update-profile-newpassword','Password & Re-Enter password does not match.');
				addErrorMsg('wa-update-profile-re-pass','Password & Re-Enter password does not match.');
			}
			if(hasError) {
				addErrorClass();
			} else {
				submitForm();
			}
		}
	});

	addErrorClass();

	function addErrorClass() {
		$('#wa-tab-content').find('.wa-input-error').each(function(){
			$(this).parent().addClass('has-error');
		})
	}

	function addErrorMsg(elemId,errorMsg) {
		var parantElem = $('#'+elemId).parent().addClass('has-error');
		parantElem.find('.help-block').remove();
		parantElem.append('<div class="help-block wa-input-error">'+errorMsg+'</div>');
	}

	function submitForm() {
		var firstname = $.trim($('#wa-update-profile-firstname').val());
		var lastname = $.trim($('#wa-update-profile-lastname').val());
		var username = $.trim($('#wa-update-profile-username').val());
		var email = $.trim($('#wa-update-profile-email').val());
		var organization = $.trim($('#wa-update-profile-organization').val());
		var password = $.trim($('#wa-update-profile-password').val());
		var newpassword = $.trim($('#wa-update-profile-newpassword').val());
		$.ajax({
			url: BASE_URL+'user/edit.php',
			type:'POST',
			dataType: 'JSON',
			data:{firstname:firstname,lastname:lastname,username:username,email:email,organization:organization,password:password,newpassword:newpassword},
			success: function(res) {
				if(typeof (res) != 'undefined') {
					if(typeof (res.status) != 'undefined' && res.status == 1) {
						/*if(typeof (res.message) != 'undefined') {
							alert(res.message);
						}*/
						window.location.reload();
					} else {
						/*if(typeof (res.message) != 'undefined') {
							alert(res.message);
						}*/
						$.each(res,function(key,val){
							addErrorMsg('wa-update-profile-'+key,val);
						});
					}
				}
			}
		});
	}

});