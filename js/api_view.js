$(function(){

	$(document).on('click','.wa-modifyApiKey',function(){
		var parentElem = $(this).closest('tr');
		parentElem.find('.wa-description').removeClass('wa-resetInput').attr('contenteditable','true');
		parentElem.find('.wa-lbl-ApiKeyStatus').hide();
		parentElem.find('.wa-ApiKeyStatus').show();
		parentElem.find('.wa-updateApiKey').show();
		parentElem.find('.wa-cancelApiKey').show();
		$(this).hide();
	});

	$(document).on('click','.wa-cancelApiKey',function(){
		var parentElem = $(this).closest('tr');
		var currStatus = parentElem.find('.wa-ApiKeyStatus').attr('currstatus');
		var currStatusLbl = (currStatus == '1') ? 'Active' : 'Inactive';
		var currDesc = parentElem.find('.wa-description').attr('currdescription');
		parentElem.find('.wa-lbl-ApiKeyStatus').html(currStatusLbl).show();
		parentElem.find('.wa-ApiKeyStatus').val(currStatus).hide();
		parentElem.find('.wa-description').addClass('wa-resetInput').removeAttr('contenteditable').text(currDesc);
		parentElem.find('.wa-modifyApiKey').show();
		parentElem.find('.wa-updateApiKey').hide();
		$(this).hide();
	});

	$(document).on('click','.wa-updateApiKey',function(){
		var parentElem = $(this).closest('tr');
		var ApiKeyID = parentElem.attr('apikeyid');
		var is_active = parentElem.find('.wa-ApiKeyStatus').val();
		var Description = $.trim(parentElem.find('.wa-description').text());
		var param = {ApiKeyID:ApiKeyID,Description:Description,is_active:is_active};
		$.ajax({
			url: BASE_URL+'api/update.php',
			method: 'POST',
			dataType: 'JSON',
			data: param,
			success: function (res) {
				if(typeof (res) != 'undefined') {
					showPopUp('Update API Key','API Key modified successfully.');
					parentElem.find('.wa-ApiKeyStatus').attr('currstatus',is_active);
					parentElem.find('.wa-lbl-ApiKeyStatus').html(is_active);
					parentElem.find('.wa-description').attr('currdescription',Description);
					parentElem.find('.wa-lbl-ApiKeyStatus')
				}
				parentElem.find('.wa-cancelApiKey').click();
			}
		});
	});

	$(document).on('click','.wa-deleteApiKey',function(){
		var thisElem =  $(this);
		var ApiKeyID = thisElem.closest('tr').attr('apikeyid');
		var deleteConfirm = confirm('Are you sure you want to delete this API Key?');
		if(deleteConfirm) {
			var param = {ApiKeyID:ApiKeyID};
			$.ajax({
				url: BASE_URL+'api/delete.php',
				method: 'POST',
				dataType: 'JSON',
				data: param,
				success: function (res) {
					if(typeof (res) != 'undefined'  && res == 1) {
						thisElem.closest('tr').find('td').addClass('wa-deleting').parent().fadeOut(2000,function(){
							$(this).remove();
							if($('#wa-table-ak').find('tbody').find('tr').length == 0) {
								$('#wa-tab-content').html('<div class="alert alert-info alert-block">No API Keys Found.</div><div style="margin:10px;"><button class="btn btn-default wa-btn-orange wa-btn-createapikey" id="wa-btn-createapikey">Create New Keys</button></div>');
							}
						});
					} else {
						showPopUp('Delete API Key','Something went wrong. Try again later.');
					}
				}
			});
		}
	});

	$(document).on('click','#wa-btn-cancel-apikey',function(){
		$('#wa-tab-api-key-management').click();
	});

});