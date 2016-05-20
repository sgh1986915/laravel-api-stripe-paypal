jssm.settings.blankurl = '../blank.html';
jssm.settings.basetitle = 'JSSM Test';
jssm.settings.titleseparator = ' : ';

jssm.functions.pageload = function (hash) {
	if (hash) {
		jssm.functions.load(hash);
	} else {
		$('.wrapper').fadeIn(500);
	}
}

jssm.functions.beforeload = function (hash) {

}

jssm.functions.load = function (hash) {
	$.get('/'+jssm.getCurrentPath()+hash, function(response) {

		/* Process the response, light edition. */

		/* Set page title based upon the response. */
		var regextitle = new RegExp('<title>([^<]*)<\/title>');
		var matches = regextitle.exec(response);
		jssm.setTitle(jssm.buildTitle(matches[1]));

		/* Get the new content. */
		var inside = response.substring(response.indexOf('<body>') + 6, response.indexOf('</body>'));

		/* Fade in time. */
		$('.wrapper').queue(function () { 
			$(this).html($(inside).filter('div.wrapper').html());
			$('a', this).jssm('click');
			$('form', this).jssm('submit');
			$(this).fadeIn(500);
			$(this).dequeue();
		});
//
//		/* Add CSS to the page based upon the response. */
//		$('link', response).each(function(i) {
//			var css = document.createElement("link");
//			css.setAttribute('rel', 'stylesheet');
//			css.setAttribute('type', 'text/css');
//			css.setAttribute('href', $(this).attr('href'));
//			document.getElementsByTagName('head')[0].appendChild(css);
//		});
//
//		/* Add page content based upon the response. */
//		$('#wrapper').html($('body', response).text()).fadeIn(500);
//
//		/* Add scripts to the page based upon the response. */
//		$('script', response).each(function(i) {
//			$.globalEval($(this).text());
//		});

	});

}

jssm.functions.afterload = function (hash) {

}

jssm.functions.beforeunload = function (hash) {

}

jssm.functions.unload = function (hash) {
	$('.wrapper').queue(function () { 
		$(this).fadeOut(500);
		$(this).dequeue();
	});
}

jssm.functions.afterunload = function (hash) {

}