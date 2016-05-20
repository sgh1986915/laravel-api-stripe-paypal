function doRequest(param) {
	$.ajax({
		url: param.url || '',
		type: param.type || 'GET',
		dataType: param.dataType || 'JSON',
		data: param.data || {},
		success: param.success
	});

}